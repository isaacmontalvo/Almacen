<?php
// Modal de Acciones del Producto
?>
<div id="accionesModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Acciones del Producto</h2>
            <button onclick="closeAllModals()" class="closeModal text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number:</label>
                    <p id="acciones_part_number" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción:</label>
                    <p id="acciones_descripcion" class="mt-1 text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad:</label>
                    <p id="acciones_cantidad" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ubicación:</label>
                    <p id="acciones_ubicacion" class="mt-1 text-sm text-gray-900"></p>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4 mt-4">
                <div class="grid grid-cols-2 gap-4">
                    <button onclick="openDetallesProductoModal(currentProduct)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Ver Detalles
                    </button>
                    
                    <button onclick="openRegistroSalidaModal(currentProduct)" id="btnRegistrarSalida" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        Registrar Salida
                    </button>
                    
                    <a id="acciones_pdf_link" href="#" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Descargar PDF
                    </a>
                    
                    <a id="acciones_barcode_link" href="#" target="_blank" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Solo Código de Barras
                    </a>
                </div>

                <div class="mt-4">
                    <button onclick="confirmarEliminarProducto(currentProduct)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center w-full">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar Producto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentProduct = null;

function openAccionesModal(product) {
    currentProduct = product; // Guardar el producto actual
    document.getElementById('acciones_part_number').textContent = product.part_number;
    document.getElementById('acciones_descripcion').textContent = product.descripcion;
    document.getElementById('acciones_cantidad').textContent = product.cantidad;
    document.getElementById('acciones_ubicacion').textContent = product.ubicacion;
    
    // Deshabilitar botón de registrar salida si la cantidad es 0
    const btnRegistrarSalida = document.getElementById('btnRegistrarSalida');
    if (product.cantidad <= 0) {
        btnRegistrarSalida.disabled = true;
        btnRegistrarSalida.classList.add('opacity-50', 'cursor-not-allowed');
        btnRegistrarSalida.classList.remove('hover:bg-green-600');
    } else {
        btnRegistrarSalida.disabled = false;
        btnRegistrarSalida.classList.remove('opacity-50', 'cursor-not-allowed');
        btnRegistrarSalida.classList.add('hover:bg-green-600');
    }
    
    // Actualizar enlaces
    document.getElementById('acciones_pdf_link').href = '/product-pdf/' + product.id;
    document.getElementById('acciones_barcode_link').href = '/barcode-pdf/' + product.id;
    
    openModal('accionesModal');
}

function openRegistroSalidaModal(product) {
    // Verificar que el producto sea un objeto válido
    if (!product || typeof product !== 'object') {
        console.error('Error: Producto no válido');
        return;
    }
    
    // Llenar los campos del modal con los datos del producto
    const productId = document.getElementById('product_id');
    const partNumber = document.getElementById('part_number');
    const descripcion = document.getElementById('descripcion');
    const cantidadDisponible = document.getElementById('cantidad_disponible');
    const ubicacion = document.getElementById('ubicacion');
    
    if (productId) productId.value = product.id || '';
    if (partNumber) partNumber.textContent = product.part_number || '';
    if (descripcion) descripcion.textContent = product.descripcion || '';
    if (cantidadDisponible) cantidadDisponible.textContent = product.cantidad || '0';
    if (ubicacion) ubicacion.textContent = product.ubicacion || '';
    
    // Limpiar los campos de OT, matrícula y observaciones
    const ot = document.getElementById('ot');
    const matricula = document.getElementById('matricula');
    const observaciones = document.getElementById('observaciones');
    
    if (ot) ot.value = '';
    if (matricula) matricula.value = '';
    if (observaciones) observaciones.value = '';
    
    // Cerrar el modal de acciones
    closeAllModals();
    
    // Abrir el modal de registro de salida
    openModal('registroSalidaModal');
}

function confirmarEliminarProducto(product) {
    if (confirm('¿Está seguro que desea eliminar este producto? Esta acción no se puede deshacer.')) {
        fetch('/delete-product', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: product.id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Producto eliminado correctamente');
                window.location.reload();
            } else {
                alert(data.error || 'Error al eliminar el producto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el producto');
        });
    }
}
</script> 