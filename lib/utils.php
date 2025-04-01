<?php
/**
 * Funciones de utilidad para el sistema
 */

/**
 * Obtiene los filtros de la solicitud HTTP
 * 
 * @return array Filtros aplicados
 */
function getFiltersFromRequest() {
    $filters = [];
    
    // Filtros para productos
    if (isset($_GET['part_number']) && !empty($_GET['part_number'])) {
        $filters['part_number'] = $_GET['part_number'];
    }
    
    if (isset($_GET['descripcion']) && !empty($_GET['descripcion'])) {
        $filters['descripcion'] = $_GET['descripcion'];
    }
    
    if (isset($_GET['lote']) && !empty($_GET['lote'])) {
        $filters['lote'] = $_GET['lote'];
    }
    
    if (isset($_GET['ubicacion']) && !empty($_GET['ubicacion'])) {
        $filters['ubicacion'] = $_GET['ubicacion'];
    }
    
    if (isset($_GET['cantidad_min']) && !empty($_GET['cantidad_min'])) {
        $filters['cantidad_min'] = (int)$_GET['cantidad_min'];
    }
    
    if (isset($_GET['cantidad_max']) && !empty($_GET['cantidad_max'])) {
        $filters['cantidad_max'] = (int)$_GET['cantidad_max'];
    }
    
    if (isset($_GET['fecha_entrada_desde']) && !empty($_GET['fecha_entrada_desde'])) {
        $filters['fecha_entrada_desde'] = $_GET['fecha_entrada_desde'];
    }
    
    if (isset($_GET['fecha_entrada_hasta']) && !empty($_GET['fecha_entrada_hasta'])) {
        $filters['fecha_entrada_hasta'] = $_GET['fecha_entrada_hasta'];
    }
    
    if (isset($_GET['fecha_exp_desde']) && !empty($_GET['fecha_exp_desde'])) {
        $filters['fecha_exp_desde'] = $_GET['fecha_exp_desde'];
    }
    
    if (isset($_GET['fecha_exp_hasta']) && !empty($_GET['fecha_exp_hasta'])) {
        $filters['fecha_exp_hasta'] = $_GET['fecha_exp_hasta'];
    }
    
    if (isset($_GET['dias_restantes']) && !empty($_GET['dias_restantes'])) {
        $filters['dias_restantes'] = (int)$_GET['dias_restantes'];
    }
    
    // Filtros para historial
    if (isset($_GET['fecha_desde']) && !empty($_GET['fecha_desde'])) {
        $filters['fecha_desde'] = $_GET['fecha_desde'];
    }
    
    if (isset($_GET['fecha_hasta']) && !empty($_GET['fecha_hasta'])) {
        $filters['fecha_hasta'] = $_GET['fecha_hasta'];
    }
    
    if (isset($_GET['ot']) && !empty($_GET['ot'])) {
        $filters['ot'] = $_GET['ot'];
    }
    
    if (isset($_GET['matricula']) && !empty($_GET['matricula'])) {
        $filters['matricula'] = $_GET['matricula'];
    }
    
    if (isset($_GET['order_by']) && !empty($_GET['order_by'])) {
        $filters['order_by'] = $_GET['order_by'];
    }
    
    if (isset($_GET['order_dir']) && !empty($_GET['order_dir'])) {
        $filters['order_dir'] = $_GET['order_dir'];
    }
    
    // Incluir la página actual en los filtros
    if (isset($_GET['page'])) {
        $filters['page'] = (int)$_GET['page'];
    }
    
    return $filters;
}

/**
 * Genera HTML para la paginación
 * 
 * @param int $currentPage Página actual
 * @param int $totalPages Total de páginas
 * @param array $filters Filtros aplicados
 * @return string HTML de la paginación
 */
function generatePagination($currentPage, $totalPages, $filters = []) {
    // Incluir el componente de paginación
    require_once BASE_PATH . '/views/components/pagination.php';
    return renderPagination($currentPage, $totalPages, $filters);
}

/**
 * Genera un código de barras único para un producto
 * 
 * @param string $partNumber Número de parte
 * @param string $lote Número de lote
 * @return string Código de barras generado
 */
function generateBarcodeString($partNumber, $lote) {
    // Limpiar caracteres no alfanuméricos
    $partNumber = preg_replace('/[^a-zA-Z0-9]/', '', $partNumber);
    $lote = preg_replace('/[^a-zA-Z0-9]/', '', $lote);
    
    // Generar código único
    $unique = substr(md5(uniqid()), 0, 6);
    
    // Combinar para formar el código de barras
    $barcode = strtoupper(substr($partNumber, 0, 6) . substr($lote, 0, 4) . $unique);
    
    return $barcode;
}

/**
 * Calcula los días restantes hasta la fecha de expiración
 * 
 * @param string $fechaExp Fecha de expiración (YYYY-MM-DD)
 * @return int Días restantes (negativo si ya expiró)
 */
function calcularDiasRestantes($fechaExp) {
    if (empty($fechaExp)) {
        return 999; // Valor alto para productos sin fecha de expiración
    }
    
    $hoy = new DateTime();
    $expiracion = new DateTime($fechaExp);
    $diferencia = $hoy->diff($expiracion);
    
    // Si la fecha de expiración ya pasó, el valor será negativo
    return $diferencia->invert ? -$diferencia->days : $diferencia->days;
}

/**
 * Formatea una fecha para mostrar en la interfaz
 * 
 * @param string $fecha Fecha en formato YYYY-MM-DD
 * @return string Fecha formateada (DD/MM/YYYY)
 */
function formatearFecha($fecha) {
    if (empty($fecha)) {
        return '';
    }
    
    $datetime = new DateTime($fecha);
    return $datetime->format('d/m/Y');
}

/**
 * Sanitiza una cadena para evitar inyección SQL
 * 
 * @param string $str Cadena a sanitizar
 * @return string Cadena sanitizada
 */
function sanitizeString($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

/**
 * Valida si una fecha tiene formato correcto (YYYY-MM-DD)
 * 
 * @param string $fecha Fecha a validar
 * @return bool True si la fecha es válida
 */
function validarFecha($fecha) {
    if (empty($fecha)) {
        return true;
    }
    
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    return $d && $d->format('Y-m-d') === $fecha;
}

function getProductos($filters = [], $paginate = true) {
    global $pdo;
    
    if ($paginate) {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $products = getAllProducts($pdo, $page, $limit, $filters);
    } else {
        $products = getAllProducts($pdo, 1, PHP_INT_MAX, $filters);
    }
    
    $totalPages = ceil($products['total'] / ($limit ?? 10));
    
    return [
        'products' => $products['products'],
        'total_pages' => $totalPages,
        'current_page' => $page ?? 1,
        'total_records' => $products['total'],
        'limit' => $limit ?? 10
    ];
}

/**
 * Obtiene estadísticas generales del inventario
 */
function getStats() {
    global $pdo;
    
    $stats = [
        'total_productos' => 0,
        'productos_por_expirar' => 0,
        'total_items' => 0,
        'movimientos_mes' => 0
    ];
    
    // Total de productos y cantidad
    $sql = "SELECT COUNT(*) as total_productos, SUM(cantidad) as total_items FROM productos";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['total_productos'] = $result['total_productos'];
    $stats['total_items'] = $result['total_items'];
    
    // Productos próximos a expirar (30 días)
    $sql = "SELECT COUNT(*) as total FROM productos WHERE fecha_exp IS NOT NULL AND fecha_exp <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
    $stmt = $pdo->query($sql);
    $stats['productos_por_expirar'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Movimientos del mes actual
    $sql = "SELECT COUNT(*) as total FROM movimientos WHERE MONTH(fecha_salida) = MONTH(CURDATE()) AND YEAR(fecha_salida) = YEAR(CURDATE())";
    $stmt = $pdo->query($sql);
    $stats['movimientos_mes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    return $stats;
}

function getProductByBarcode($codigo_barra) {
    global $pdo;
    return getProductoByBarcode($codigo_barra);
}