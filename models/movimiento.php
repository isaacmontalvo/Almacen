<?php
function getMovimientos($pdo, $page = 1, $limit = 10, $filters = []) {
    $offset = ($page - 1) * $limit;
    
    $whereClause = [];
    $params = [];
    
    if (!empty($filters['part_number'])) {
        $whereClause[] = "m.part_number LIKE :part_number";
        $params['part_number'] = "%{$filters['part_number']}%";
    }
    
    if (!empty($filters['lote'])) {
        $whereClause[] = "m.lote LIKE :lote";
        $params['lote'] = "%{$filters['lote']}%";
    }
    
    if (!empty($filters['ot'])) {
        $whereClause[] = "m.ot LIKE :ot";
        $params['ot'] = "%{$filters['ot']}%";
    }
    
    if (!empty($filters['matricula'])) {
        $whereClause[] = "m.matricula LIKE :matricula";
        $params['matricula'] = "%{$filters['matricula']}%";
    }
    
    if (!empty($filters['ubicacion'])) {
        $whereClause[] = "m.ubicacion LIKE :ubicacion";
        $params['ubicacion'] = "%{$filters['ubicacion']}%";
    }
    
    if (isset($filters['cantidad_min']) && $filters['cantidad_min'] !== '') {
        $whereClause[] = "m.cantidad >= :cantidad_min";
        $params['cantidad_min'] = $filters['cantidad_min'];
    }
    
    if (isset($filters['cantidad_max']) && $filters['cantidad_max'] !== '') {
        $whereClause[] = "m.cantidad <= :cantidad_max";
        $params['cantidad_max'] = $filters['cantidad_max'];
    }
    
    if (!empty($filters['fecha_salida_desde'])) {
        $whereClause[] = "DATE(m.fecha_salida) >= :fecha_salida_desde";
        $params['fecha_salida_desde'] = $filters['fecha_salida_desde'];
    }
    
    if (!empty($filters['fecha_salida_hasta'])) {
        $whereClause[] = "DATE(m.fecha_salida) <= :fecha_salida_hasta";
        $params['fecha_salida_hasta'] = $filters['fecha_salida_hasta'];
    }
    
    if (!empty($filters['fecha_exp_desde'])) {
        $whereClause[] = "m.fecha_exp >= :fecha_exp_desde";
        $params['fecha_exp_desde'] = $filters['fecha_exp_desde'];
    }
    
    if (!empty($filters['fecha_exp_hasta'])) {
        $whereClause[] = "m.fecha_exp <= :fecha_exp_hasta";
        $params['fecha_exp_hasta'] = $filters['fecha_exp_hasta'];
    }
    
    $where = !empty($whereClause) ? "WHERE " . implode(" AND ", $whereClause) : "";
    
    // Consulta para obtener el total de registros
    $countQuery = "SELECT COUNT(*) as total FROM movimientos m $where";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute($params);
    $total = $stmt->fetch()['total'];
    
    // Consulta principal con JOIN a la tabla productos
    $query = "SELECT m.*, p.part_number, p.ubicacion 
              FROM movimientos m 
              LEFT JOIN productos p ON m.part_number = p.part_number 
              $where 
              ORDER BY m.fecha_salida DESC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $movimientos = $stmt->fetchAll();
    
    return [
        'movimientos' => $movimientos,
        'total' => $total,
        'pages' => ceil($total / $limit),
        'current_page' => $page
    ];
}

function getMonthlyMovements($pdo) {
    $stmt = $pdo->query("
        SELECT 
            MONTH(fecha_salida) as mes, 
            YEAR(fecha_salida) as anio, 
            COUNT(*) as total_movimientos,
            SUM(cantidad) as total_cantidad
        FROM 
            movimientos 
        WHERE 
            fecha_salida >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY 
            YEAR(fecha_salida), MONTH(fecha_salida)
        ORDER BY 
            anio, mes
    ");
    return $stmt->fetchAll();
}

function getMovimientosByProductId($pdo, $productId, $page = 1, $limit = 5) {
    try {
        // Primero obtener el part_number del producto
        $stmt = $pdo->prepare("SELECT part_number FROM productos WHERE id = :product_id");
        $stmt->execute(['product_id' => $productId]);
        $product = $stmt->fetch();
        
        if (!$product) {
            return [
                'movimientos' => [],
                'total' => 0,
                'pages' => 0,
                'current_page' => 1
            ];
        }
        
        // Obtener el total de movimientos
        $countQuery = "SELECT COUNT(*) as total FROM movimientos WHERE part_number = :part_number";
        $stmt = $pdo->prepare($countQuery);
        $stmt->execute(['part_number' => $product['part_number']]);
        $total = $stmt->fetch()['total'];
        
        // Calcular el offset
        $offset = ($page - 1) * $limit;
        
        // Obtener los movimientos paginados
        $query = "SELECT m.*, p.part_number, p.ubicacion 
                  FROM movimientos m 
                  LEFT JOIN productos p ON m.part_number = p.part_number 
                  WHERE m.part_number = :part_number 
                  ORDER BY m.fecha_salida DESC 
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':part_number', $product['part_number']);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Asegurarse de que el tipo de movimiento esté definido
        foreach ($movimientos as &$mov) {
            if (!isset($mov['tipo'])) {
                $mov['tipo'] = 'Salida';
            }
        }
        
        return [
            'movimientos' => $movimientos,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    } catch (PDOException $e) {
        error_log("Error en getMovimientosByProductId: " . $e->getMessage());
        return [
            'movimientos' => [],
            'total' => 0,
            'pages' => 0,
            'current_page' => 1,
            'error' => 'Error al obtener los movimientos'
        ];
    }
}

function registerSalida($data) {
    global $pdo;
    
    try {
        // Validar campos requeridos
        if (!isset($data['product_id']) || !isset($data['cantidad']) || !isset($data['ot']) || !isset($data['matricula'])) {
            throw new Exception('Faltan campos requeridos para registrar la salida');
        }

        // Validar que la cantidad sea un número positivo
        if (!is_numeric($data['cantidad']) || $data['cantidad'] <= 0) {
            throw new Exception('La cantidad debe ser un número positivo');
        }

        // Obtener el producto antes de iniciar la transacción
        $product = getProductById($pdo, $data['product_id']);
        if (!$product) {
            throw new Exception('Producto no encontrado');
        }
        
        // Verificar cantidad disponible
        if ($product['cantidad'] < $data['cantidad']) {
            throw new Exception('No hay suficiente cantidad disponible');
        }

        // Iniciar la transacción
        $pdo->beginTransaction();
        
        try {
            // Registrar el movimiento
            $stmt = $pdo->prepare("INSERT INTO movimientos (producto_id, part_number, lote, cantidad, tipo, fecha_salida, observaciones, ot, matricula) VALUES (?, ?, ?, ?, 'salida', NOW(), ?, ?, ?)");
            $stmt->execute([
                $data['product_id'],
                $product['part_number'],
                $product['lote'],
                $data['cantidad'],
                $data['observaciones'] ?? '',
                $data['ot'],
                $data['matricula']
            ]);
            
            // Actualizar la cantidad del producto
            $stmt = $pdo->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");
            $stmt->execute([$data['cantidad'], $data['product_id']]);
            
            // Si todo salió bien, confirmar la transacción
            $pdo->commit();
            return ['success' => true];
            
        } catch (Exception $e) {
            // Si hay un error durante la transacción, hacer rollback
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
        
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

function getMovimientosDevolucion($pdo, $busqueda) {
    try {
        if (empty($busqueda)) {
            return ['error' => 'El Part Number es requerido'];
        }

        // Buscar movimientos por Part Number de los últimos 7 días
        $query = "SELECT m.*, p.part_number, p.ubicacion, p.descripcion, p.cantidad as stock_actual 
                  FROM movimientos m 
                  LEFT JOIN productos p ON m.part_number = p.part_number 
                  WHERE m.fecha_salida >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                  AND m.part_number LIKE :busqueda
                  AND m.tipo = 'salida'
                  ORDER BY m.fecha_salida DESC";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['busqueda' => "%{$busqueda}%"]);
        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($movimientos)) {
            return ['error' => 'No se encontraron movimientos para este Part Number en los últimos 7 días'];
        }

        // Asegurar que todos los campos necesarios estén presentes
        foreach ($movimientos as &$mov) {
            $mov['cantidad'] = $mov['cantidad'] ?? 0;
            $mov['ot'] = $mov['ot'] ?? '';
            $mov['matricula'] = $mov['matricula'] ?? '';
            $mov['descripcion'] = $mov['descripcion'] ?? '';
            $mov['stock_actual'] = $mov['stock_actual'] ?? 0;
        }

        return ['movimientos' => $movimientos];
    } catch (PDOException $e) {
        error_log("Error en getMovimientosDevolucion: " . $e->getMessage());
        return ['error' => 'Error al buscar los movimientos'];
    }
}

function registrarDevolucion($pdo, $movimientoId, $cantidad, $observaciones) {
    try {
        $pdo->beginTransaction();
        
        // Obtener el movimiento original
        $stmt = $pdo->prepare("SELECT m.*, p.id as producto_id 
                              FROM movimientos m 
                              LEFT JOIN productos p ON m.part_number = p.part_number 
                              WHERE m.id = :movimiento_id");
        $stmt->execute(['movimiento_id' => $movimientoId]);
        $movimiento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$movimiento) {
            throw new Exception('Movimiento no encontrado');
        }
        
        if ($cantidad > $movimiento['cantidad']) {
            throw new Exception('La cantidad a devolver no puede ser mayor a la cantidad original');
        }
        
        // Registrar el movimiento de devolución
        $stmt = $pdo->prepare("INSERT INTO movimientos (
            producto_id, part_number, lote, cantidad, tipo, 
            fecha_salida, observaciones, ot, matricula
        ) VALUES (
            :producto_id, :part_number, :lote, :cantidad, 'devolucion',
            NOW(), :observaciones, :ot, :matricula
        )");
        
        $stmt->execute([
            'producto_id' => $movimiento['producto_id'],
            'part_number' => $movimiento['part_number'],
            'lote' => $movimiento['lote'],
            'cantidad' => $cantidad,
            'observaciones' => $observaciones,
            'ot' => $movimiento['ot'],
            'matricula' => $movimiento['matricula']
        ]);
        
        // Actualizar el stock del producto
        $stmt = $pdo->prepare("UPDATE productos SET cantidad = cantidad + :cantidad WHERE id = :producto_id");
        $stmt->execute([
            'cantidad' => $cantidad,
            'producto_id' => $movimiento['producto_id']
        ]);
        
        $pdo->commit();
        return ['success' => true];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error en registrarDevolucion: " . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}
?>