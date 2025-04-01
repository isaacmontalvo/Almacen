<?php
/**
 * Punto de entrada principal de la aplicación
 * Sistema de Gestión de Inventario
 */

// Definir constantes
define('BASE_PATH', __DIR__);
define('BARCODE_PATH', BASE_PATH . '/barcodes');
define('BARCODE_URL', '/barcodes');
define('PDF_OUTPUT_PATH', BASE_PATH . '/pdf_output');

// Cargar configuración
require_once BASE_PATH . '/config/database.php';

// Cargar utilidades
require_once BASE_PATH . '/lib/utils.php';
require_once BASE_PATH . '/lib/barcode.php';
require_once BASE_PATH . '/lib/pdf_generator.php';

// Cargar modelos
require_once BASE_PATH . '/models/producto.php';
require_once BASE_PATH . '/models/movimiento.php';

// Crear directorios necesarios si no existen
if (!file_exists(BARCODE_PATH)) {
    mkdir(BARCODE_PATH, 0755, true);
}

if (!file_exists(PDF_OUTPUT_PATH)) {
    mkdir(PDF_OUTPUT_PATH, 0755, true);
}

// Iniciar sesión
session_start();

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Procesar la ruta
switch ($path) {
    case '':
    case 'index.php':
        // Página principal - Listado de productos
        $filters = getFiltersFromRequest();
        $result = getProductos($filters);
        $content = 'home';
        
        // Cargar la vista
        require_once BASE_PATH . '/views/layouts/main.php';
        break;
        
    case 'create':
        // Página de creación de producto
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar formulario de creación
            $result = createProducto($_POST);
            
            if (isset($result['error'])) {
                // Redirigir con error
                header('Location: /create?error=' . urlencode($result['error']));
                exit;
            } else {
                // Redirigir al listado
                header('Location: /?success=Producto creado correctamente');
                exit;
            }
        }
        
        // Cargar la vista de creación
        $content = 'producto_create';
        require_once BASE_PATH . '/views/layouts/main.php';
        break;
        
    case 'scan':
        // Página de escaneo de código de barras
        $product = null;
        $error = null;
        $success = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['barcode']) && !empty($_POST['barcode'])) {
                // Buscar producto por código de barras
                $product = getProductoByBarcode($_POST['barcode']);
                
                if (!$product) {
                    $error = "No se encontró ningún producto con el código de barras: " . htmlspecialchars($_POST['barcode']);
                }
            } elseif (isset($_POST['action']) && $_POST['action'] === 'register_output') {
                // Registrar salida de producto
                $result = registerSalida($_POST);
                
                if (isset($result['error'])) {
                    $error = $result['error'];
                    // Volver a cargar el producto
                    $product = getProductById($pdo, $_POST['product_id']);
                } else {
                    $success = "Salida registrada correctamente";
                    $product = null; // Limpiar producto para escanear otro
                }
            }
        }
        
        // Cargar la vista de escaneo
        $content = 'scan';
        require_once BASE_PATH . '/views/layouts/main.php';
        break;
        
    case 'historial':
    case (preg_match('/^historial(\?.*)?$/', $path) ? true : false):
        // Página de historial de movimientos
        $filters = getFiltersFromRequest();
        $result = getMovimientos($pdo, $filters['page'] ?? 1, 10, $filters);
        
        // Cargar la vista de historial
        $content = 'historial';
        require_once BASE_PATH . '/views/layouts/main.php';
        break;

    case (preg_match('/^api\/historial(\?.*)?$/', $path) ? true : false):
        // API endpoint para el historial
        header('Content-Type: application/json');
        $filters = getFiltersFromRequest();
        $result = getMovimientos($pdo, $filters['page'] ?? 1, 10, $filters);
        echo json_encode($result);
        exit;
        break;
        
    case 'dashboard':
        // Página de dashboard
        $stats = getStats();
        $locationData = getInventoryByLocation($pdo);
        $monthlyMovements = getMonthlyMovements($pdo);
        $expirationData = getExpirationData($pdo);
        
        // Cargar la vista de dashboard
        $content = 'dashboard';
        require_once BASE_PATH . '/views/layouts/main.php';
        break;
        
    // Rutas para generación de PDF
    case (preg_match('/^product-pdf\/(\d+)$/', $path, $matches) ? true : false):
        $productId = $matches[1];
        $product = getProductById($pdo, $productId);
        
        if (!$product) {
            header('HTTP/1.0 404 Not Found');
            $content = '404';
            require_once BASE_PATH . '/views/layouts/main.php';
            break;
        }
        
        $pdfPath = generateProductPDF($product);
        
        // Descargar el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="producto_' . $productId . '.pdf"');
        readfile($pdfPath);
        exit;
        
    case (preg_match('/^barcode-pdf\/(\d+)$/', $path, $matches) ? true : false):
        $productId = $matches[1];
        $product = getProductById($pdo, $productId);
        
        if (!$product) {
            header('HTTP/1.0 404 Not Found');
            $content = '404';
            require_once BASE_PATH . '/views/layouts/main.php';
            break;
        }
        
        $pdfPath = generateBarcodePDF($product);
        
        // Descargar el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="barcode_' . $productId . '.pdf"');
        readfile($pdfPath);
        exit;
        
    case 'all-products-pdf':
        $filters = getFiltersFromRequest();
        $result = getProductos($filters, false); // Sin paginación
        $products = $result['products'];
        
        $pdfPath = generateAllProductsPDF($products, $filters);
        
        // Descargar el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="inventario_completo.pdf"');
        readfile($pdfPath);
        exit;
        
    case 'all-barcodes-pdf':
        $filters = getFiltersFromRequest();
        $result = getProductos($filters, false); // Sin paginación
        $products = $result['products'];
        
        $pdfPath = generateAllBarcodesPDF($products);
        
        // Descargar el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="codigos_barras.pdf"');
        readfile($pdfPath);
        exit;
        
    case 'historial-pdf':
        $filters = getFiltersFromRequest();
        $result = getMovimientos($pdo, 1, 1000, $filters);
        $movimientos = $result['movimientos'];
        
        $pdfPath = generateHistorialPDF($movimientos, $filters);
        
        // Descargar el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="historial_salidas.pdf"');
        readfile($pdfPath);
        exit;
        
    case 'get_product':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $product = getProductById($pdo, $id);
            if ($product) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Producto no encontrado']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
        }
        exit;
        
    case 'product-pdf':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $product = getProductById($pdo, $id);
            if ($product) {
                generateProductPDF($product);
            }
        }
        break;

    case 'registrar-salida':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? null;
            $cantidad = $_POST['cantidad'] ?? 0;
            $observaciones = $_POST['observaciones'] ?? '';
            $ot = $_POST['ot'] ?? '';
            $matricula = $_POST['matricula'] ?? '';
            
            if ($product_id && $cantidad > 0 && !empty($ot) && !empty($matricula)) {
                $product = getProductById($pdo, $product_id);
                if ($product && $product['cantidad'] >= $cantidad) {
                    // Verificar si existe la columna observaciones
                    $stmt = $pdo->query("SHOW COLUMNS FROM movimientos LIKE 'observaciones'");
                    if ($stmt->rowCount() == 0) {
                        // Agregar la columna observaciones si no existe
                        $pdo->exec("ALTER TABLE movimientos ADD COLUMN observaciones TEXT");
                    }
                    
                    // Registrar la salida
                    $stmt = $pdo->prepare("INSERT INTO movimientos (part_number, lote, cantidad, tipo, fecha_salida, observaciones, ot, matricula) VALUES (?, ?, ?, 'salida', NOW(), ?, ?, ?)");
                    $stmt->execute([
                        $product['part_number'],
                        $product['lote'],
                        $cantidad,
                        $observaciones,
                        $ot,
                        $matricula
                    ]);
                    
                    // Actualizar la cantidad del producto
                    $stmt = $pdo->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");
                    $stmt->execute([$cantidad, $product_id]);
                    
                    header('Location: /?success=Salida registrada correctamente');
                } else {
                    header('Location: /?error=Stock insuficiente');
                }
            } else {
                header('Location: /?error=Datos incompletos');
            }
        }
        break;

    case 'delete-product':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $product_id = $data['product_id'] ?? null;
            
            if ($product_id) {
                try {
                    // Verificar si el producto existe y tiene cantidad 0
                    $stmt = $pdo->prepare("SELECT cantidad FROM productos WHERE id = ?");
                    $stmt->execute([$product_id]);
                    $product = $stmt->fetch();
                    
                    if ($product) {
                        if ($product['cantidad'] > 0) {
                            echo json_encode(['success' => false, 'error' => 'No se puede eliminar un producto con cantidad mayor a 0']);
                            exit;
                        }
                        
                        // Eliminar el producto
                        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
                        $stmt->execute([$product_id]);
                        
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'ID de producto no proporcionado']);
            }
            exit;
        }
        break;

    case 'barcode-pdf':
        // ... existing code ...
        break;
        
    case '/historial-pdf':
        require_once BASE_PATH . '/controllers/historial_controller.php';
        $filters = $_GET;
        $movimientos = getMovimientos($pdo, 1, 1000, $filters);
        generateHistorialPDF($movimientos['movimientos'], $filters);
        break;

    case (preg_match('/^api\/movimientos-producto\/(\d+)$/', $path, $matches) ? true : false):
        $productId = $matches[1];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $result = getMovimientosByProductId($pdo, $productId, $page);
        header('Content-Type: application/json');
        echo json_encode($result);
        break;
        
    // Ruta para buscar movimientos para devolución
    case (preg_match('/^api\/movimientos-devolucion$/', $path) ? true : false):
        $busqueda = $_GET['busqueda'] ?? '';
        $result = getMovimientosDevolucion($pdo, $busqueda);
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
        
    // Ruta para registrar devolución
    case '/registrar-devolucion' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $result = registrarDevolucion(
            $pdo,
            $data['movimiento_id'],
            $data['cantidad'],
            $data['observaciones'] ?? ''
        );
        
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
        
    case 'api/devolucion':
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['cantidad']) || empty($data['matricula'])) {
            http_response_code(400);
            echo json_encode(['message' => 'La cantidad y la matrícula son obligatorias']);
            exit;
        }
        
        // Buscar el producto por OT o código de barras
        $product = null;
        if (!empty($data['ot'])) {
            $product = getProductByOT($data['ot']);
        } elseif (!empty($data['codigo_barra'])) {
            $product = getProductoByBarcode($data['codigo_barra']);
        }
        
        if (!$product) {
            http_response_code(404);
            echo json_encode(['message' => 'No se encontró el producto']);
            exit;
        }
        
        // Registrar la devolución
        $result = registrarDevolucion($product['id'], $data['cantidad'], $data['matricula']);
        
        if ($result) {
            echo json_encode(['message' => 'Devolución registrada correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al registrar la devolución']);
        }
        exit;
        
    default:
        // Página no encontrada
        header('HTTP/1.0 404 Not Found');
        $content = '404';
        require_once BASE_PATH . '/views/layouts/main.php';
        break;
}