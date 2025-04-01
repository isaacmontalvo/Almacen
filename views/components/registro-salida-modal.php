<?php
// Modal de Registro de Salida
?>
<div id="registroSalidaModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Registrar Salida</h2>
            <button onclick="closeAllModals()" class="closeModal text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="/scan" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="register_output">
            <input type="hidden" name="product_id" id="product_id">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number:</label>
                    <p id="modal_part_number" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción:</label>
                    <p id="modal_descripcion" class="mt-1 text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad Disponible:</label>
                    <p id="modal_cantidad" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ubicación:</label>
                    <p id="modal_ubicacion" class="mt-1 text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div>
                <label for="cantidad_retirar" class="block text-sm font-medium text-gray-700">Cantidad a Retirar</label>
                <input type="number" name="cantidad_retirar" id="cantidad_retirar" min="1" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="ot" class="block text-sm font-medium text-gray-700">OT</label>
                <input type="text" name="ot" id="ot" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="matricula" class="block text-sm font-medium text-gray-700">Matrícula</label>
                <input type="text" name="matricula" id="matricula" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAllModals()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Registrar Salida
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRegistroSalidaModal(product) {
    document.getElementById('product_id').value = product.id;
    document.getElementById('modal_part_number').textContent = product.part_number;
    document.getElementById('modal_descripcion').textContent = product.descripcion;
    document.getElementById('modal_cantidad').textContent = product.cantidad;
    document.getElementById('modal_ubicacion').textContent = product.ubicacion;
    document.getElementById('cantidad_retirar').max = product.cantidad;
    openModal('registroSalidaModal');
}
</script> 