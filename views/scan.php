<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Escanear y Registrar Salida</h1>
    
    <?php if (isset($success)): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700"><?php echo $success; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?php echo $error; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Escanear Código de Barras</h2>
            <p class="text-gray-600 mt-1">Escanee el código de barras del producto o ingréselo manualmente</p>
        </div>
        
        <div class="p-6">
            <form id="scanForm" method="POST" class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="barcode" class="block text-sm font-medium text-gray-700">Código de Barras</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" name="barcode" id="barcode" 
                                   class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300"
                                   placeholder="Escanee o ingrese el código de barras">
                        </div>
                    </div>
                    <div class="pt-5">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($product): ?>
        <?php
        // Determine if the product is about to expire
        $expirationClass = '';
        $expirationWarning = '';
        
        if ($product['dias_restantes'] < 0) {
            $expirationClass = 'bg-red-50';
            $expirationWarning = '<div class="text-red-600 font-bold mt-2">¡PRODUCTO EXPIRADO!</div>';
        } elseif ($product['dias_restantes'] < 30) {
            $expirationClass = 'bg-yellow-50';
            $expirationWarning = '<div class="text-yellow-600 font-bold mt-2">¡Producto próximo a expirar! (' . $product['dias_restantes'] . ' días restantes)</div>';
        }
        ?>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 <?php echo $expirationClass; ?>">
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Información del Producto</h2>
                <p class="text-gray-600 mt-1">Detalles del producto escaneado</p>
                <?php echo $expirationWarning; ?>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Part Number</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($product['part_number']); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Lote</h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($product['lote']); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Descripción</h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($product['descripcion']); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Ubicación</h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($product['ubicacion']); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Cantidad Disponible</h3>
                        <p class="mt-1 text-lg font-bold text-gray-900"><?php echo htmlspecialchars($product['cantidad']); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Fecha de Expiración</h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($product['fecha_exp']); ?></p>
                    </div>
                </div>

                <div class="mt-6">
                    <img src="<?php echo BARCODE_URL.'/'.$product['codigo_barra'].'.png'; ?>" alt="Código de Barras" class="mx-auto">
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900">Registrar Salida</h3>
                    <form method="POST" class="mt-4 space-y-4">
                        <input type="hidden" name="action" value="register_output">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        
                        <div>
                            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad a Retirar</label>
                            <div class="mt-1">
                                <input type="number" name="cantidad" id="cantidad" required min="1" max="<?php echo $product['cantidad']; ?>"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       placeholder="Ingrese la cantidad">
                                <p class="mt-1 text-sm text-gray-500">Máximo disponible: <?php echo $product['cantidad']; ?></p>
                            </div>
                        </div>
                        
                        <div>
                            <label for="ot" class="block text-sm font-medium text-gray-700">OT (Orden de Trabajo)</label>
                            <div class="mt-1">
                                <input type="text" name="ot" id="ot" required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       placeholder="Ingrese la OT">
                            </div>
                        </div>
                        
                        <div>
                            <label for="matricula" class="block text-sm font-medium text-gray-700">Matrícula</label>
                            <div class="mt-1">
                                <input type="text" name="matricula" id="matricula" required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       placeholder="Ingrese la matrícula">
                            </div>
                        </div>

                        <div>
                            <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <div class="mt-1">
                                <textarea name="observaciones" id="observaciones" rows="3"
                                          class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                          placeholder="Ingrese observaciones adicionales"></textarea>
                            </div>
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Registrar Salida
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex justify-end">
        <a href="/" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Volver al Inventario
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Validar cantidad antes de enviar formulario de salida de productos
        const outputForm = document.querySelector("form[action='register_output']");
        if (outputForm) {
            outputForm.addEventListener("submit", function(e) {
                const quantityInput = document.getElementById("cantidad");
                const maxQuantity = parseInt(quantityInput.getAttribute("max"));
                const quantity = parseInt(quantityInput.value);

                // Validación de la cantidad ingresada
                if (isNaN(quantity) || quantity <= 0) {
                    alert("Por favor, ingrese una cantidad válida mayor a cero.");
                    e.preventDefault();
                    return false;
                }

                if (quantity > maxQuantity) {
                    alert("La cantidad a retirar no puede ser mayor que la cantidad disponible (" + maxQuantity + ").");
                    e.preventDefault();
                    return false;
                }

                return true;
            });
        }
    });
</script>

<!-- Incluir modal de registro de salida -->
<?php require_once BASE_PATH . '/views/components/registro-salida-modal.php'; ?>