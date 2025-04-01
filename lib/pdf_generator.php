<?php
require_once BASE_PATH . '/vendor/autoload.php';

// Define modern color palette
define('COLOR_PRIMARY', array(79, 70, 229)); // Indigo-600
define('COLOR_SECONDARY', array(107, 114, 128)); // Gray-500
define('COLOR_ACCENT', array(236, 72, 153)); // Pink-500
define('COLOR_BACKGROUND', array(249, 250, 251)); // Gray-50
define('COLOR_BORDER', array(229, 231, 235)); // Gray-200
define('COLOR_SUCCESS', array(16, 185, 129)); // Emerald-500
define('COLOR_WARNING', array(245, 158, 11)); // Amber-500
define('COLOR_DANGER', array(239, 68, 68)); // Red-500
define('COLOR_TEXT', array(31, 41, 55)); // Gray-800
define('COLOR_TEXT_LIGHT', array(107, 114, 128)); // Gray-500

// Helper function to create consistent section headers
function addSectionHeader($pdf, $title, $y = null) {
    if ($y !== null) {
        $pdf->SetY($y);
    }
    
    $pdf->SetFillColor(...COLOR_PRIMARY);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->RoundedRect(15, $pdf->GetY(), 180, 12, 4, '1111', 'DF');
    $pdf->Cell(0, 12, $title, 0, 1, 'C', false);
    $pdf->SetTextColor(...COLOR_TEXT);
    $pdf->Ln(5);
    
    return $pdf->GetY();
}

// Helper function to create data cards
function addDataCard($pdf, $data, $x, $y, $width, $height) {
    // Card background with shadow effect
    $pdf->SetFillColor(...COLOR_BACKGROUND);
    $pdf->SetDrawColor(...COLOR_BORDER);
    $pdf->SetLineWidth(0.3);
    
    // Shadow effect (light gray rectangle slightly offset)
    $pdf->SetFillColor(220, 220, 220);
    $pdf->RoundedRect($x + 2, $y + 2, $width, $height, 6, '1111', 'F');
    
    // Actual card
    $pdf->SetFillColor(...COLOR_BACKGROUND);
    $pdf->RoundedRect($x, $y, $width, $height, 6, '1111', 'DF');
    
    $startY = $y + 5;
    foreach ($data as $key => $value) {
        $pdf->SetXY($x + 5, $startY);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(...COLOR_PRIMARY);
        $pdf->Cell(60, 10, $key . ':', 0, 0);
        
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(...COLOR_TEXT);
        $pdf->Cell(0, 10, $value, 0, 1);
        
        $startY += 12;
    }
}

function generateProductPDF($product) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Sistema de Inventario');
    $pdf->SetAuthor('Sistema de Inventario');
    $pdf->SetTitle('Producto: ' . $product['part_number']);
    
    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Set margins
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(TRUE, 25);
    
    // Add page
    $pdf->AddPage();
    
    // Set default font
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(...COLOR_TEXT);
    
    // Add logo and company info in a modern header
    $headerY = 15;
    if (file_exists(BASE_PATH . '/assets/img/logo.png')) {
        $pdf->Image(BASE_PATH . '/assets/img/logo.png', 15, $headerY, 40);
        $pdf->SetXY(60, $headerY);
    } else {
        $pdf->SetXY(15, $headerY);
    }
    
    // Company info
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(...COLOR_PRIMARY);
    $pdf->Cell(0, 10, 'Sistema de Inventario', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(...COLOR_TEXT_LIGHT);
    $pdf->Cell(0, 5, 'Detalles del Producto', 0, 1);
    $pdf->Ln(10);
    
    // Main title with accent bar
    $pdf->SetDrawColor(...COLOR_ACCENT);
    $pdf->SetLineWidth(1);
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(5);
    
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetTextColor(...COLOR_PRIMARY);
    $pdf->Cell(0, 15, 'Ficha de Producto', 0, 1, 'C');
    $pdf->Ln(5);
    
    // Product information in two columns with modern cards
    $leftX = 15;
    $rightX = 105;
    $cardWidth = 85;
    $cardHeight = 80;
    $cardY = $pdf->GetY();
    
    // Left column data
    $data1 = [
        'Part Number' => $product['part_number'],
        'Descripción' => $product['descripcion'],
        'Lote' => $product['lote'],
        'Cantidad' => $product['cantidad'],
        'Ubicación' => $product['ubicacion']
    ];
    
    // Right column data
    $data2 = [
        'Fecha de Entrada' => date('d/m/Y', strtotime($product['fecha_entrada'])),
        'Fecha de Expiración' => $product['fecha_exp'] ? date('d/m/Y', strtotime($product['fecha_exp'])) : 'N/A',
        'Días Restantes' => $product['dias_restantes'] ?? 'N/A',
        'Inspección de Recibo' => $product['inspeccion_recibo'] ? 'Sí' : 'No'
    ];
    
    // Add data cards
    addDataCard($pdf, $data1, $leftX, $cardY, $cardWidth, $cardHeight);
    addDataCard($pdf, $data2, $rightX, $cardY, $cardWidth, $cardHeight);
    
    // Move position after cards
    $pdf->SetY($cardY + $cardHeight + 15);
    
    // Barcode section
    if (!empty($product['codigo_barra'])) {
        $barcodeFile = BARCODE_PATH . '/' . $product['codigo_barra'] . '.png';
        if (file_exists($barcodeFile)) {
            // Check if we need a new page
            if ($pdf->GetY() > 200) {
                $pdf->AddPage();
            }
            
            // Add section header
            addSectionHeader($pdf, 'Código de Barras');
            
            // Barcode container with shadow effect
            $barcodeY = $pdf->GetY();
            
            // Shadow effect
            $pdf->SetFillColor(220, 220, 220);
            $pdf->RoundedRect(17, $barcodeY + 2, 176, 80, 6, '1111', 'F');
            
            // Actual container
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetDrawColor(...COLOR_BORDER);
            $pdf->RoundedRect(15, $barcodeY, 176, 80, 6, '1111', 'DF');
            
            // Barcode centered
            $pdf->Image($barcodeFile, 15, $barcodeY + 5, 176, 60, '', '', '', false, 300, 'C');
            
            // Barcode number
            $pdf->SetY($barcodeY + 65);
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(...COLOR_TEXT);
            $pdf->Cell(0, 10, $product['codigo_barra'], 0, 1, 'C');
        }
    }
    
    // Footer with gradient
    $pdf->SetY(-40);
    
    // Gradient line
    for ($i = 0; $i < 180; $i++) {
        $r = intval(COLOR_PRIMARY[0] + ($i/180) * (COLOR_ACCENT[0] - COLOR_PRIMARY[0]));
        $g = intval(COLOR_PRIMARY[1] + ($i/180) * (COLOR_ACCENT[1] - COLOR_PRIMARY[1]));
        $b = intval(COLOR_PRIMARY[2] + ($i/180) * (COLOR_ACCENT[2] - COLOR_PRIMARY[2]));
        
        $pdf->SetDrawColor($r, $g, $b);
        $pdf->Line(15 + $i, $pdf->GetY(), 15 + $i, $pdf->GetY());
    }
    
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'I', 9);
    $pdf->SetTextColor(...COLOR_TEXT_LIGHT);
    $pdf->Cell(0, 10, 'Generado el ' . date('d/m/Y H:i:s'), 0, 1, 'C');
    
    // QR code with product ID in corner
    $style = [
        'border' => false,
        'vpadding' => 'auto',
        'hpadding' => 'auto',
        'fgcolor' => [0, 0, 0],
        'bgcolor' => false,
        'module_width' => 1,
        'module_height' => 1
    ];
    
    // Generate QR code with product ID
    $pdf->write2DBarcode('PROD:' . $product['id'], 'QRCODE,L', 170, 240, 25, 25, $style);
    
    // Generate unique filename
    $filename = 'producto_' . $product['id'] . '_' . uniqid() . '.pdf';
    $filepath = PDF_OUTPUT_PATH . '/' . $filename;
    
    // Save PDF
    $pdf->Output($filepath, 'F');
    
    return $filepath;
}

function generateAllProductsPDF($products, $filters = []) {
    // Create new PDF document - Use landscape for tables with many columns
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Sistema de Inventario');
    $pdf->SetAuthor('Sistema de Inventario');
    $pdf->SetTitle('Inventario Completo');
    
    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Set margins - reduced for landscape mode
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 20);
    
    // Add page
    $pdf->AddPage();
    
    // Set default font
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(...COLOR_TEXT);
    
    // Add logo and company info in a modern header
    $headerY = 10;
    if (file_exists(BASE_PATH . '/assets/img/logo.png')) {
        $pdf->Image(BASE_PATH . '/assets/img/logo.png', 10, $headerY, 30);
        $pdf->SetXY(45, $headerY);
    } else {
        $pdf->SetXY(10, $headerY);
    }
    
    // Company info
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(...COLOR_PRIMARY);
    $pdf->Cell(0, 10, 'Sistema de Inventario', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(...COLOR_TEXT_LIGHT);
    $pdf->Cell(0, 5, 'Inventario Completo', 0, 1);
    $pdf->Ln(5);
    
    // Main title with accent bar
    $pdf->SetDrawColor(...COLOR_ACCENT);
    $pdf->SetLineWidth(1);
    $pdf->Line(10, $pdf->GetY(), 287, $pdf->GetY());
    $pdf->Ln(5);
    
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->SetTextColor(...COLOR_PRIMARY);
    $pdf->Cell(0, 12, 'Inventario Completo', 0, 1, 'C');
    
    // Show applied filters if any
    if (!empty($filters)) {
        $pdf->Ln(2);
        $filterY = $pdf->GetY();
        
        // Shadow
        $pdf->SetFillColor(220, 220, 220);
        $pdf->RoundedRect(12, $filterY + 2, 275, 20, 6, '1111', 'F');
        
        // Filter box
        $pdf->SetFillColor(252, 252, 252);
        $pdf->SetDrawColor(...COLOR_BORDER);
        $pdf->RoundedRect(10, $filterY, 275, 20, 6, '1111', 'DF');
        
        $pdf->SetXY(15, $filterY + 3);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(...COLOR_PRIMARY);
        $pdf->Cell(50, 6, 'Filtros Aplicados:', 0, 0);
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(...COLOR_TEXT);
        
        $filterText = '';
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $filterText .= ucfirst($key) . ': ' . $value . '   |   ';
            }
        }
        $filterText = rtrim($filterText, '   |   ');
        $pdf->Cell(0, 6, $filterText, 0, 1);
    }
    
    $pdf->Ln(8);
    
    // Optimized table layout for landscape mode
    // Calculate optimal column widths based on available space
    $pageWidth = 277; // Landscape A4 width minus margins
    
    // Define columns and their relative weights
    $columns = [
        'Part Number' => 3,
        'Descripción' => 5,
        'Lote' => 2,
        'Cantidad' => 1.5,
        'Ubicación' => 2,
        'Fecha Entrada' => 2.5,
        'Fecha Exp.' => 2.5,
        'Días' => 1.5
    ];
    
    // Calculate total weight
    $totalWeight = array_sum($columns);
    
    // Calculate actual widths
    $widths = [];
    foreach ($columns as $column => $weight) {
        $widths[$column] = ($weight / $totalWeight) * $pageWidth;
    }
    
    // Table headers with modern styling
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(...COLOR_PRIMARY);
    $pdf->SetTextColor(255, 255, 255);
    
    // Draw header with rounded corners
    $pdf->RoundedRect(10, $pdf->GetY(), $pageWidth, 10, 4, '1111', 'DF');
    
    $x = 10;
    foreach ($columns as $column => $weight) {
        $width = $widths[$column];
        $pdf->SetXY($x, $pdf->GetY());
        $pdf->Cell($width, 10, $column, 0, 0, 'C');
        $x += $width;
    }
    $pdf->Ln(10);
    
    // Table data with zebra striping and rounded corners for each row
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(...COLOR_TEXT);
    
    $row = 0;
    $rowHeight = 8; // Reduced row height for more compact table
    
    foreach ($products as $product) {
        // Check if we need a new page
        if ($pdf->GetY() > 180) { // Adjusted for landscape
            $pdf->AddPage();
            
            // Redraw headers on new page
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(...COLOR_PRIMARY);
            $pdf->SetTextColor(255, 255, 255);
            
            $pdf->RoundedRect(10, $pdf->GetY(), $pageWidth, 10, 4, '1111', 'DF');
            
            $x = 10;
            foreach ($columns as $column => $weight) {
                $width = $widths[$column];
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell($width, 10, $column, 0, 0, 'C');
                $x += $width;
            }
            $pdf->Ln(10);
            
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(...COLOR_TEXT);
        }
        
        // Zebra striping
        if ($row % 2 == 0) {
            $pdf->SetFillColor(...COLOR_BACKGROUND);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }
        
        // Row with subtle rounded corners
        $rowY = $pdf->GetY();
        $pdf->RoundedRect(10, $rowY, $pageWidth, $rowHeight, 2, '1111', 'DF');
        
        // Data cells - using array keys for clarity
        $x = 10;
        
        // Part Number
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Part Number'], $rowHeight, $product['part_number'], 0, 0, 'L');
        $x += $widths['Part Number'];
        
        // Descripción - handle long text with ellipsis
        $desc = $product['descripcion'];
        if (strlen($desc) > 40) {
            $desc = substr($desc, 0, 37) . '...';
        }
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Descripción'], $rowHeight, $desc, 0, 0, 'L');
        $x += $widths['Descripción'];
        
        // Lote
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Lote'], $rowHeight, $product['lote'], 0, 0, 'C');
        $x += $widths['Lote'];
        
        // Cantidad
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Cantidad'], $rowHeight, $product['cantidad'], 0, 0, 'C');
        $x += $widths['Cantidad'];
        
        // Ubicación
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Ubicación'], $rowHeight, $product['ubicacion'], 0, 0, 'C');
        $x += $widths['Ubicación'];
        
        // Fecha Entrada
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Fecha Entrada'], $rowHeight, date('d/m/Y', strtotime($product['fecha_entrada'])), 0, 0, 'C');
        $x += $widths['Fecha Entrada'];
        
        // Fecha Exp.
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Fecha Exp.'], $rowHeight, $product['fecha_exp'] ? date('d/m/Y', strtotime($product['fecha_exp'])) : 'N/A', 0, 0, 'C');
        $x += $widths['Fecha Exp.'];
        
        // Días
        $pdf->SetXY($x, $rowY);
        
        // Color-code days remaining
        if (isset($product['dias_restantes'])) {
            if ($product['dias_restantes'] <= 30) {
                $pdf->SetTextColor(...COLOR_DANGER);
            } elseif ($product['dias_restantes'] <= 90) {
                $pdf->SetTextColor(...COLOR_WARNING);
            } else {
                $pdf->SetTextColor(...COLOR_SUCCESS);
            }
            $pdf->Cell($widths['Días'], $rowHeight, $product['dias_restantes'], 0, 0, 'C');
            $pdf->SetTextColor(...COLOR_TEXT);
        } else {
            $pdf->Cell($widths['Días'], $rowHeight, 'N/A', 0, 0, 'C');
        }
        
        $pdf->Ln($rowHeight);
        $row++;
    }
    
    // Footer with gradient
    $pdf->SetY(-30);
    
    // Gradient line
    for ($i = 0; $i < $pageWidth; $i++) {
        $r = intval(COLOR_PRIMARY[0] + ($i/$pageWidth) * (COLOR_ACCENT[0] - COLOR_PRIMARY[0]));
        $g = intval(COLOR_PRIMARY[1] + ($i/$pageWidth) * (COLOR_ACCENT[1] - COLOR_PRIMARY[1]));
        $b = intval(COLOR_PRIMARY[2] + ($i/$pageWidth) * (COLOR_ACCENT[2] - COLOR_PRIMARY[2]));
        
        $pdf->SetDrawColor($r, $g, $b);
        $pdf->Line(10 + $i, $pdf->GetY(), 10 + $i, $pdf->GetY());
    }
    
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'I', 9);
    $pdf->SetTextColor(...COLOR_TEXT_LIGHT);
    $pdf->Cell(0, 10, 'Generado el ' . date('d/m/Y H:i:s') . ' | Total de productos: ' . count($products), 0, 1, 'C');
    
    // Generate unique filename
    $filename = 'inventario_completo_' . uniqid() . '.pdf';
    $filepath = PDF_OUTPUT_PATH . '/' . $filename;
    
    // Save PDF
    $pdf->Output($filepath, 'F');
    
    return $filepath;
}

function generateHistorialPDF($movimientos, $filters = []) {
    // Create new PDF document - Use landscape for tables with many columns
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Sistema de Inventario');
    $pdf->SetAuthor('Sistema de Inventario');
    $pdf->SetTitle('Historial de Movimientos');
    
    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Set margins - reduced for landscape mode
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 20);
    
    // Add page
    $pdf->AddPage();
    
    // Set default font
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(...COLOR_TEXT);
    
    // Add logo and company info in a modern header
    $headerY = 10;
    if (file_exists(BASE_PATH . '/assets/img/logo.png')) {
        $pdf->Image(BASE_PATH . '/assets/img/logo.png', 10, $headerY, 30);
        $pdf->SetXY(45, $headerY);
    } else {
        $pdf->SetXY(10, $headerY);
    }
    
    // Company info
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(...COLOR_PRIMARY);
    $pdf->Cell(0, 10, 'Sistema de Inventario', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(...COLOR_TEXT_LIGHT);
    $pdf->Cell(0, 5, 'Historial de Movimientos', 0, 1);
    $pdf->Ln(5);
    
    // Main title with accent bar
    $pdf->SetDrawColor(...COLOR_ACCENT);
    $pdf->SetLineWidth(1);
    $pdf->Line(10, $pdf->GetY(), 287, $pdf->GetY());
    $pdf->Ln(5);
    
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->SetTextColor(...COLOR_PRIMARY);
    $pdf->Cell(0, 12, 'Historial de Movimientos', 0, 1, 'C');
    
    // Show applied filters if any
    if (!empty($filters)) {
        $pdf->Ln(2);
        $filterY = $pdf->GetY();
        
        // Shadow
        $pdf->SetFillColor(220, 220, 220);
        $pdf->RoundedRect(12, $filterY + 2, 275, 20, 6, '1111', 'F');
        
        // Filter box
        $pdf->SetFillColor(252, 252, 252);
        $pdf->SetDrawColor(...COLOR_BORDER);
        $pdf->RoundedRect(10, $filterY, 275, 20, 6, '1111', 'DF');
        
        $pdf->SetXY(15, $filterY + 3);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(...COLOR_PRIMARY);
        $pdf->Cell(50, 6, 'Filtros Aplicados:', 0, 0);
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(...COLOR_TEXT);
        
        $filterText = '';
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $filterText .= ucfirst($key) . ': ' . $value . '   |   ';
            }
        }
        $filterText = rtrim($filterText, '   |   ');
        $pdf->Cell(0, 6, $filterText, 0, 1);
    }
    
    $pdf->Ln(8);
    
    // Optimized table layout for landscape mode
    // Calculate optimal column widths based on available space
    $pageWidth = 277; // Landscape A4 width minus margins
    
    // Define columns and their relative weights
    $columns = [
        'Fecha' => 2.5,
        'Part Number' => 3,
        'Descripción' => 5,
        'Lote' => 2,
        'Cantidad' => 1.5,
        'OT' => 2,
        'Matrícula' => 2
    ];
    
    // Calculate total weight
    $totalWeight = array_sum($columns);
    
    // Calculate actual widths
    $widths = [];
    foreach ($columns as $column => $weight) {
        $widths[$column] = ($weight / $totalWeight) * $pageWidth;
    }
    
    // Table headers with modern styling
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(...COLOR_PRIMARY);
    $pdf->SetTextColor(255, 255, 255);
    
    // Draw header with rounded corners
    $pdf->RoundedRect(10, $pdf->GetY(), $pageWidth, 10, 4, '1111', 'DF');
    
    $x = 10;
    foreach ($columns as $column => $weight) {
        $width = $widths[$column];
        $pdf->SetXY($x, $pdf->GetY());
        $pdf->Cell($width, 10, $column, 0, 0, 'C');
        $x += $width;
    }
    $pdf->Ln(10);
    
    // Table data with zebra striping and rounded corners for each row
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(...COLOR_TEXT);
    
    $row = 0;
    $rowHeight = 8; // Reduced row height for more compact table
    
    foreach ($movimientos as $movimiento) {
        // Check if we need a new page
        if ($pdf->GetY() > 180) { // Adjusted for landscape
            $pdf->AddPage();
            
            // Redraw headers on new page
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(...COLOR_PRIMARY);
            $pdf->SetTextColor(255, 255, 255);
            
            $pdf->RoundedRect(10, $pdf->GetY(), $pageWidth, 10, 4, '1111', 'DF');
            
            $x = 10;
            foreach ($columns as $column => $weight) {
                $width = $widths[$column];
                $pdf->SetXY($x, $pdf->GetY());
                $pdf->Cell($width, 10, $column, 0, 0, 'C');
                $x += $width;
            }
            $pdf->Ln(10);
            
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(...COLOR_TEXT);
        }
        
        // Zebra striping
        if ($row % 2 == 0) {
            $pdf->SetFillColor(...COLOR_BACKGROUND);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }
        
        // Row with subtle rounded corners
        $rowY = $pdf->GetY();
        $pdf->RoundedRect(10, $rowY, $pageWidth, $rowHeight, 2, '1111', 'DF');
        
        // Data cells - using array keys for clarity
        $x = 10;
        
        // Fecha
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Fecha'], $rowHeight, date('d/m/Y', strtotime($movimiento['fecha_salida'])), 0, 0, 'C');
        $x += $widths['Fecha'];
        
        // Part Number
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Part Number'], $rowHeight, $movimiento['part_number'], 0, 0, 'L');
        $x += $widths['Part Number'];
        
        // Descripción - handle long text with ellipsis
        $desc = $movimiento['descripcion'];
        if (strlen($desc) > 40) {
            $desc = substr($desc, 0, 37) . '...';
        }
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Descripción'], $rowHeight, $desc, 0, 0, 'L');
        $x += $widths['Descripción'];
        
        // Lote
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Lote'], $rowHeight, $movimiento['lote'], 0, 0, 'C');
        $x += $widths['Lote'];
        
        // Cantidad
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Cantidad'], $rowHeight, $movimiento['cantidad'], 0, 0, 'C');
        $x += $widths['Cantidad'];
        
        // OT
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['OT'], $rowHeight, $movimiento['ot'], 0, 0, 'C');
        $x += $widths['OT'];
        
        // Matrícula
        $pdf->SetXY($x, $rowY);
        $pdf->Cell($widths['Matrícula'], $rowHeight, $movimiento['matricula'], 0, 0, 'C');
        
        $pdf->Ln($rowHeight);
        $row++;
    }
    
    // Footer with gradient
    $pdf->SetY(-30);
    
    // Gradient line
    for ($i = 0; $i < $pageWidth; $i++) {
        $r = intval(COLOR_PRIMARY[0] + ($i/$pageWidth) * (COLOR_ACCENT[0] - COLOR_PRIMARY[0]));
        $g = intval(COLOR_PRIMARY[1] + ($i/$pageWidth) * (COLOR_ACCENT[1] - COLOR_PRIMARY[1]));
        $b = intval(COLOR_PRIMARY[2] + ($i/$pageWidth) * (COLOR_ACCENT[2] - COLOR_PRIMARY[2]));
        
        $pdf->SetDrawColor($r, $g, $b);
        $pdf->Line(10 + $i, $pdf->GetY(), 10 + $i, $pdf->GetY());
    }
    
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'I', 9);
    $pdf->SetTextColor(...COLOR_TEXT_LIGHT);
    $pdf->Cell(0, 10, 'Generado el ' . date('d/m/Y H:i:s') . ' | Total de movimientos: ' . count($movimientos), 0, 1, 'C');
    
    // Generate unique filename
    $filename = 'historial_movimientos_' . uniqid() . '.pdf';
    $filepath = PDF_OUTPUT_PATH . '/' . $filename;
    
    // Save PDF
    $pdf->Output($filepath, 'F');
    
    return $filepath;
}

function generateAllBarcodesPDF($products) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Sistema de Inventario');
    $pdf->SetAuthor('Sistema de Inventario');
    $pdf->SetTitle('Códigos de Barras');
    
    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Set margins
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 10);
    
    // Add page
    $pdf->AddPage();
    
    // Ordenar productos por part number
    usort($products, function($a, $b) {
        return strcmp($a['part_number'], $b['part_number']);
    });
    
    $currentY = 15;
    $barcodeWidth = 120;
    $startX = (210 - 20 - $barcodeWidth) / 2 + 10; // Centrado en la página
    
    foreach ($products as $product) {
        if (!empty($product['codigo_barra'])) {
            $barcodeFile = BARCODE_PATH . '/' . $product['codigo_barra'] . '.png';
            
            if (file_exists($barcodeFile)) {
                // Nueva página si es necesario
                if ($currentY > 250) {
                    $pdf->AddPage();
                    $currentY = 15;
                }
                
                // Part Number y descripción
                $pdf->SetXY($startX, $currentY);
                $pdf->SetFont('helvetica', '', 10);
                $pdf->Cell($barcodeWidth, 5, $product['part_number'], 0, 1, 'L');
                
                // Código de barras
                $pdf->Image($barcodeFile, $startX, $currentY + 8, $barcodeWidth, 25, '', '', '', false, 300, 'C');
                
                // Número de código de barras
                $pdf->SetXY($startX, $currentY + 35);
                $pdf->SetFont('helvetica', '', 10);
                $pdf->Cell($barcodeWidth, 5, $product['codigo_barra'], 0, 1, 'C');
                
                $currentY += 50;
            }
        }
    }
    
    // Generate unique filename
    $filename = 'codigos_barras_' . uniqid() . '.pdf';
    $filepath = PDF_OUTPUT_PATH . '/' . $filename;
    
    // Save PDF
    $pdf->Output($filepath, 'F');
    
    return $filepath;
}

function generateBarcodePDF($product) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Sistema de Inventario');
    $pdf->SetAuthor('Sistema de Inventario');
    $pdf->SetTitle('Código de Barras: ' . $product['part_number']);
    
    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Set margins
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 10);
    
    // Add page
    $pdf->AddPage();
    
    if (!empty($product['codigo_barra'])) {
        $barcodeFile = BARCODE_PATH . '/' . $product['codigo_barra'] . '.png';
        
        if (file_exists($barcodeFile)) {
            // Part Number y descripción
            $pdf->SetXY(10, 15);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 5, $product['part_number'], 0, 1, 'L');
            
            if (!empty($product['descripcion'])) {
                $pdf->SetFont('helvetica', '', 10);
                $pdf->Cell(0, 5, $product['descripcion'], 0, 1, 'L');
            }
            
            // Código de barras
            $pdf->Image($barcodeFile, 10, $pdf->GetY() + 5, 190, 40, '', '', '', false, 300, 'C');
            
            // Número de código de barras
            $pdf->SetY($pdf->GetY() + 45);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 5, $product['codigo_barra'], 0, 1, 'C');
        } else {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 10, 'No se encontró el archivo del código de barras', 0, 1, 'C');
        }
    } else {
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'El producto no tiene código de barras asignado', 0, 1, 'C');
    }
    
    // Generate unique filename
    $filename = 'barcode_' . $product['id'] . '_' . uniqid() . '.pdf';
    $filepath = PDF_OUTPUT_PATH . '/' . $filename;
    
    // Save PDF
    $pdf->Output($filepath, 'F');
    
    return $filepath;
}