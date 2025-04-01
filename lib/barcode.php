<?php
use Picqer\Barcode\BarcodeGeneratorPNG;

function generateUniqueBarcode($pdo) {
    do {
        // Generar un código único
        $code = uniqid() . substr(md5(uniqid(mt_rand(), true)), 0, 8);
        
        // Verificar si el código ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE codigo_barra = ?");
        $stmt->execute([$code]);
        $exists = $stmt->fetchColumn() > 0;
    } while ($exists);
    
    // Asegurar que el directorio existe
    if (!file_exists(BARCODE_PATH)) {
        if (!mkdir(BARCODE_PATH, 0755, true)) {
            throw new Exception("No se pudo crear el directorio de códigos de barras.");
        }
    }
    
    // Asegurar permisos de escritura
    if (!is_writable(BARCODE_PATH)) {
        chmod(BARCODE_PATH, 0755);
    }
    
    // Generar el código de barras
    $generator = new BarcodeGeneratorPNG();
    $barcodeFile = BARCODE_PATH . "/{$code}.png";
    $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
    
    if (file_put_contents($barcodeFile, $barcode) === false) {
        throw new Exception("No se pudo guardar el código de barras.");
    }
    
    return $code;
}
?>