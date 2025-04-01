<?php
function getAllProducts($pdo, $page = 1, $limit = 10, $filters = []) {
    $offset = ($page - 1) * $limit;
    
    $whereClause = [];
    $params = [];
    
    if (!empty($filters['part_number'])) {
        $whereClause[] = "part_number LIKE :part_number";
        $params['part_number'] = "%{$filters['part_number']}%";
    }
    
    if (!empty($filters['descripcion'])) {
        $whereClause[] = "descripcion LIKE :descripcion";
        $params['descripcion'] = "%{$filters['descripcion']}%";
    }
    
    if (!empty($filters['lote'])) {
        $whereClause[] = "lote LIKE :lote";
        $params['lote'] = "%{$filters['lote']}%";
    }
    
    if (!empty($filters['ubicacion'])) {
        $whereClause[] = "ubicacion LIKE :ubicacion";
        $params['ubicacion'] = "%{$filters['ubicacion']}%";
    }
    
    if (isset($filters['cantidad_min']) && $filters['cantidad_min'] !== '') {
        $whereClause[] = "cantidad >= :cantidad_min";
        $params['cantidad_min'] = $filters['cantidad_min'];
    }
    
    if (isset($filters['cantidad_max']) && $filters['cantidad_max'] !== '') {
        $whereClause[] = "cantidad <= :cantidad_max";
        $params['cantidad_max'] = $filters['cantidad_max'];
    }
    
    if (!empty($filters['fecha_entrada_desde'])) {
        $whereClause[] = "fecha_entrada >= :fecha_entrada_desde";
        $params['fecha_entrada_desde'] = $filters['fecha_entrada_desde'];
    }
    
    if (!empty($filters['fecha_entrada_hasta'])) {
        $whereClause[] = "fecha_entrada <= :fecha_entrada_hasta";
        $params['fecha_entrada_hasta'] = $filters['fecha_entrada_hasta'];
    }
    
    if (!empty($filters['fecha_exp_desde'])) {
        $whereClause[] = "fecha_exp >= :fecha_exp_desde";
        $params['fecha_exp_desde'] = $filters['fecha_exp_desde'];
    }
    
    if (!empty($filters['fecha_exp_hasta'])) {
        $whereClause[] = "fecha_exp <= :fecha_exp_hasta";
        $params['fecha_exp_hasta'] = $filters['fecha_exp_hasta'];
    }
    
    $where = !empty($whereClause) ? "WHERE " . implode(" AND ", $whereClause) : "";
    
    // Obtener el total de registros
    $countQuery = "SELECT COUNT(*) as total FROM productos $where";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute($params);
    $total = $stmt->fetch()['total'];
    
    // Obtener los productos con cálculo de días restantes
    $query = "SELECT 
                p.*,
                CASE 
                    WHEN p.fecha_exp IS NULL THEN 999
                    ELSE DATEDIFF(p.fecha_exp, CURDATE())
                END as dias_restantes
              FROM productos p 
              $where 
              ORDER BY p.fecha_entrada DESC 
              LIMIT :limit OFFSET :offset";
              
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    return [
        'products' => $products,
        'total' => $total
    ];
}

function getProductById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT *, DATEDIFF(fecha_exp, CURDATE()) as dias_restantes FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createProduct($pdo, $data) {
    // If expiration checkbox is not checked, set fecha_exp to NULL
    if (!isset($data['has_expiration']) || $data['has_expiration'] != '1') {
        $data['fecha_exp'] = null;
    }
    
    // Asegurar que inspeccion_recibo esté definido
    if (!isset($data['inspeccion_recibo'])) {
        $data['inspeccion_recibo'] = null;
    }
    
    $sql = "INSERT INTO productos (
        part_number, descripcion, lote, cantidad, 
        fecha_entrada, fecha_exp, inspeccion_recibo, 
        ubicacion, codigo_barra
    ) VALUES (
        :part_number, :descripcion, :lote, :cantidad,
        :fecha_entrada, :fecha_exp, :inspeccion_recibo,
        :ubicacion, :codigo_barra
    )";
    
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':part_number' => $data['part_number'],
            ':descripcion' => $data['descripcion'],
            ':lote' => $data['lote'],
            ':cantidad' => $data['cantidad'],
            ':fecha_entrada' => $data['fecha_entrada'],
            ':fecha_exp' => $data['fecha_exp'],
            ':inspeccion_recibo' => $data['inspeccion_recibo'],
            ':ubicacion' => $data['ubicacion'],
            ':codigo_barra' => $data['codigo_barra']
        ]);
    } catch (PDOException $e) {
        error_log("Error al crear producto: " . $e->getMessage());
        return false;
    }
}

function updateProductQuantity($pdo, $id, $quantity, $ot, $matricula) {
    $pdo->beginTransaction();
    try {
        // Verificar stock disponible
        $stmt = $pdo->prepare("SELECT p.*, DATEDIFF(p.fecha_exp, CURDATE()) as dias_restantes FROM productos p WHERE p.id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch();
        
        if (!$producto) {
            throw new Exception("Producto no encontrado");
        }
        
        if ($producto['cantidad'] < $quantity) {
            throw new Exception("Stock insuficiente");
        }

        // Actualizar cantidad
        $stmt = $pdo->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");
        $stmt->execute([$quantity, $id]);

        // Registrar movimiento
        $stmt = $pdo->prepare("INSERT INTO movimientos (
            producto_id, tipo, cantidad, ot, matricula, lote, 
            fecha_exp, ubicacion, part_number, descripcion, fecha_salida
        ) VALUES (
            ?, 'salida', ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )");
        $stmt->execute([
            $id, $quantity, $ot, $matricula, $producto['lote'], 
            $producto['fecha_exp'], $producto['ubicacion'], 
            $producto['part_number'], $producto['descripcion']
        ]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Funciones para estadísticas y gráficos
function getInventoryStats($pdo) {
    $stats = [];
    
    // Total de productos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM productos");
    $stats['total_productos'] = $stmt->fetch()['total'];
    
    // Productos por expirar (menos de 30 días)
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM productos WHERE DATEDIFF(fecha_exp, CURDATE()) < 30");
    $stats['productos_por_expirar'] = $stmt->fetch()['total'];
    
    // Valor total del inventario
    $stmt = $pdo->query("SELECT SUM(cantidad) as total FROM productos");
    $stats['total_items'] = $stmt->fetch()['total'] ?: 0;
    
    // Movimientos del mes actual
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM movimientos WHERE MONTH(fecha_salida) = MONTH(CURDATE()) AND YEAR(fecha_salida) = YEAR(CURDATE())");
    $stats['movimientos_mes'] = $stmt->fetch()['total'];
    
    return $stats;
}

function getInventoryByLocation($pdo) {
    $stmt = $pdo->query("SELECT ubicacion, SUM(cantidad) as total FROM productos GROUP BY ubicacion ORDER BY total DESC");
    return $stmt->fetchAll();
}

function getExpirationData($pdo) {
    $stmt = $pdo->query("
        SELECT 
            CASE 
                WHEN DATEDIFF(fecha_exp, CURDATE()) < 0 THEN 'Expirado'
                WHEN DATEDIFF(fecha_exp, CURDATE()) < 30 THEN 'Menos de 30 días'
                WHEN DATEDIFF(fecha_exp, CURDATE()) < 90 THEN 'Entre 30 y 90 días'
                ELSE 'Más de 90 días'
            END as categoria,
            COUNT(*) as total
        FROM 
            productos
        GROUP BY 
            categoria
        ORDER BY 
            CASE 
                WHEN categoria = 'Expirado' THEN 1
                WHEN categoria = 'Menos de 30 días' THEN 2
                WHEN categoria = 'Entre 30 y 90 días' THEN 3
                ELSE 4
            END
    ");
    return $stmt->fetchAll();
}

function createProducto($data) {
    global $pdo;
    try {
        // Validar datos requeridos
        $required_fields = ['part_number', 'descripcion', 'lote', 'cantidad', 'fecha_entrada', 'ubicacion'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return ['error' => "El campo $field es requerido"];
            }
        }
        
        // Generar código de barras único
        $data['codigo_barra'] = generateUniqueBarcode($pdo);
        
        // Crear el producto
        if (createProduct($pdo, $data)) {
            return ['success' => true, 'codigo_barra' => $data['codigo_barra']];
        } else {
            return ['error' => 'Error al crear el producto'];
        }
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

function getProductoByBarcode($barcode) {
    global $pdo;
    
    $query = "SELECT *, DATEDIFF(fecha_exp, CURDATE()) as dias_restantes FROM productos WHERE codigo_barra = :barcode";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['barcode' => $barcode]);
    return $stmt->fetch();
}
?>