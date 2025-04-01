<?php
$products = $result['products'] ?? [];
$totalPages = $result['total_pages'] ?? 1;
$currentPage = $result['current_page'] ?? 1;
$totalRecords = $result['total_records'] ?? 0;
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Inventario</h1>
        <div class="flex space-x-2">
            <button onclick="openFiltrosModal()" class="hover-effect bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filtros
            </button>
            <button onclick="openPdfOptionsModal()" class="hover-effect bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                PDF
            </button>
            <a href="/create" class="hover-effect bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Producto
            </a>
        </div>
    </div>

    <?php
    // Mostrar filtros activos
    $activeFilters = [];
    foreach ($filters as $key => $value) {
        if ($key !== 'order_by' && $key !== 'order_dir') {
            $label = str_replace('_', ' ', $key);
            $label = ucfirst($label);
            $activeFilters[] = "$label: $value";
        }
    }
    
    if (!empty($activeFilters)) {
        echo '
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Filtros activos: ' . implode(', ', $activeFilters) . '
                        <a href="/" class="font-medium underline text-blue-600 hover:text-blue-800">Limpiar</a>
                    </p>
                </div>
            </div>
        </div>';
    }
    ?>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código de Barras</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lote</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días Rest.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No se encontraron productos
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): 
                            $diasRestantes = $product['dias_restantes'] ?? 999;
                            $rowClass = $diasRestantes < 30 ? 'bg-red-50' : '';
                            $diasClass = $diasRestantes < 30 ? 'text-red-600 font-bold' : 'text-gray-900';
                            $diasText = $diasRestantes === 999 ? 'N/A' : $diasRestantes;
                            
                            // Agregar clase para productos con cantidad 0
                            if ($product['cantidad'] <= 0) {
                                $rowClass .= ' opacity-50';
                            }
                        ?>
                            <tr class="hover-effect <?php echo $rowClass; ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <img src="<?php echo BARCODE_URL.'/'.$product['codigo_barra'].'.png'; ?>" alt="Código de Barras" class="h-10">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($product['part_number']); ?>
                                    <?php if ($product['cantidad'] <= 0): ?>
                                        <span class="ml-2 text-xs text-red-600 font-semibold">(Sin Stock)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars(substr($product['descripcion'], 0, 50)).(strlen($product['descripcion']) > 50 ? '...' : ''); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($product['lote']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($product['cantidad']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($product['ubicacion']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo $diasClass; ?>">
                                    <?php echo $diasText; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button onclick="openAccionesModal(<?php echo htmlspecialchars(json_encode($product)); ?>)" 
                                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-1 px-3 rounded inline-flex items-center">
                                        <span>Acciones</span>
                                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Paginación
    if ($totalPages > 1) {
        echo generatePagination($currentPage, $totalPages, $filters);
    }
    ?>
</div>

<script>
    // Funciones específicas para esta página
    function openSalidaModal(id, partNumber, descripcion, cantidad, ubicacion) {
        document.getElementById('salidaProductId').value = id;
        document.getElementById('salidaPartNumber').textContent = partNumber;
        document.getElementById('salidaDescripcion').textContent = descripcion;
        document.getElementById('salidaCantidad').textContent = cantidad;
        document.getElementById('salidaUbicacion').textContent = ubicacion;
        document.getElementById('salidaCantidadRetirar').max = cantidad;
        document.getElementById('salidaCantidadRetirar').value = 1;
        
        openModal('salidaModal');
    }
    
    function openDetalleModal(id, partNumber, descripcion, lote, cantidad, ubicacion, fechaEntrada, fechaExp, inspeccion, barcode) {
        document.getElementById('detallePartNumber').textContent = partNumber;
        document.getElementById('detalleDescripcion').textContent = descripcion;
        document.getElementById('detalleLote').textContent = lote;
        document.getElementById('detalleCantidad').textContent = cantidad;
        document.getElementById('detalleUbicacion').textContent = ubicacion;
        document.getElementById('detalleFechaEntrada').textContent = fechaEntrada;
        document.getElementById('detalleFechaExp').textContent = fechaExp;
        document.getElementById('detalleInspeccion').textContent = inspeccion;
        document.getElementById('detalleBarcode').src = '/barcodes/' + barcode + '.png';
        document.getElementById('detalleCodigo').textContent = barcode;
        document.getElementById('detallePdfLink').href = '/product-pdf/' + id;
        document.getElementById('detalleBarcodeLink').href = '/barcode-pdf/' + id;
        
        openModal('detalleModal');
    }
    
    function openPdfOptionsModal() {
        // Actualizar los enlaces con los filtros actuales
        const urlParams = new URLSearchParams(window.location.search);
        const filterParams = urlParams.toString();
        
        const allProductsPdfLink = document.getElementById('allProductsPdfLink');
        const allBarcodesPdfLink = document.getElementById('allBarcodesPdfLink');
        
        allProductsPdfLink.href = '/all-products-pdf' + (filterParams ? '?' + filterParams : '');
        allBarcodesPdfLink.href = '/all-barcodes-pdf' + (filterParams ? '?' + filterParams : '');
        
        openModal('pdfOptionsModal');
    }
</script>

<!-- Incluir modales -->
<?php require_once BASE_PATH . '/views/components/filtros.php'; ?>
<?php require_once BASE_PATH . '/views/components/pdf-options.php'; ?>
<?php require_once BASE_PATH . '/views/components/acciones-modal.php'; ?>