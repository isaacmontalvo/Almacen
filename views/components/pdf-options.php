<div id="pdfOptionsModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Opciones de PDF</h2>
            <button class="closeModal text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="space-y-4">
            <p class="text-gray-700">Seleccione el tipo de PDF que desea generar:</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/all-products-pdf" id="allProductsPdfLink" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-4 rounded-lg text-center">
                    <svg class="h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Inventario Completo
                </a>
                <a href="/all-barcodes" id="allBarcodesPdfLink" class="bg-green-500 hover:bg-green-600 text-white px-4 py-4 rounded-lg text-center">
                    <svg class="h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Solo Códigos de Barras
                </a>
            </div>
            
            <p class="text-gray-600 text-sm mt-4">Nota: Se aplicarán los mismos filtros que están actualmente seleccionados en la tabla.</p>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" class="closeModal bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>