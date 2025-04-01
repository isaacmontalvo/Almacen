<!-- Modales -->
<div id="salidaModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Registrar Salida</h2>
            <button class="closeModal text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="salidaForm" action="/update-quantity" method="POST" class="space-y-4">
            <input type="hidden" id="salidaProductId" name="product_id">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Part Number:</p>
                    <p id="salidaPartNumber" class="text-sm text-gray-900 font-semibold"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Descripción:</p>
                    <p id="salidaDescripcion" class="text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Cantidad Disponible:</p>
                    <p id="salidaCantidad" class="text-sm text-gray-900 font-semibold"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Ubicación:</p>
                    <p id="salidaUbicacion" class="text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Cantidad a Retirar</label>
                <input type="number" name="quantity" id="salidaCantidadRetirar" required min="1"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">OT</label>
                <input type="text" name="ot" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Matrícula</label>
                <input type="text" name="matricula" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" class="closeModal bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Registrar Salida
                </button>
            </div>
        </form>
    </div>
</div>

<div id="detalleModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Detalles del Producto</h2>
            <button class="closeModal text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Part Number:</p>
                    <p id="detallePartNumber" class="text-sm text-gray-900 font-semibold"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Lote:</p>
                    <p id="detalleLote" class="text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-700">Descripción:</p>
                <p id="detalleDescripcion" class="text-sm text-gray-900"></p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Cantidad:</p>
                    <p id="detalleCantidad" class="text-sm text-gray-900 font-semibold"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Ubicación:</p>
                    <p id="detalleUbicacion" class="text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Fecha de Entrada:</p>
                    <p id="detalleFechaEntrada" class="text-sm text-gray-900"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Fecha de Expiración:</p>
                    <p id="detalleFechaExp" class="text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-700">Inspección de Recibo:</p>
                <p id="detalleInspeccion" class="text-sm text-gray-900"></p>
            </div>
            
            <div class="text-center mt-4">
                <img id="detalleBarcode" src="/placeholder.svg" alt="Código de Barras" class="h-16 mx-auto">
                <p id="detalleCodigo" class="text-xs text-gray-500 mt-1"></p>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" class="closeModal bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Cerrar
                </button>
                <a id="detallePdfLink" href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-block">
                    Descargar PDF
                </a>
                <a id="detalleBarcodeLink" href="#" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg inline-block">
                    Solo Código de Barras
                </a>
            </div>
        </div>
    </div>
</div>

<div id="filtrosModal" class="modal modal-lg">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Filtros Avanzados</h2>
            <button class="closeModal text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="filtrosForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number</label>
                    <input type="text" name="part_number" id="filtroPartNumber" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <input type="text" name="descripcion" id="filtroDescripcion"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lote</label>
                    <input type="text" name="lote" id="filtroLote"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- Resto del formulario de filtros -->
            
            <div class="flex justify-between pt-4">
                <div>
                    <button type="button" id="limpiarFiltros" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                        Limpiar Filtros
                    </button>
                </div>
                <div class="flex space-x-3">
                    <button type="button" class="closeModal bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Otros modales según sea necesario -->