<!-- Modal de Registro de Devolución -->
<div id="registroDevolucionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Devolución</h3>
            <div class="mt-2">
                <form id="devolucionForm" class="space-y-4">
                    <div>
                        <label for="ot_devolucion" class="block text-sm font-medium text-gray-700">OT</label>
                        <input type="text" id="ot_devolucion" name="ot" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="codigo_barra_devolucion" class="block text-sm font-medium text-gray-700">Código de Barras</label>
                        <input type="text" id="codigo_barra_devolucion" name="codigo_barra" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="cantidad_devolucion" class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" id="cantidad_devolucion" name="cantidad" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="matricula_devolucion" class="block text-sm font-medium text-gray-700">Matrícula</label>
                        <input type="text" id="matricula_devolucion" name="matricula" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="cerrarModalDevolucion()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function abrirModalDevolucion() {
    document.getElementById('registroDevolucionModal').classList.remove('hidden');
}

function cerrarModalDevolucion() {
    document.getElementById('registroDevolucionModal').classList.add('hidden');
    document.getElementById('devolucionForm').reset();
}

document.getElementById('devolucionForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        ot: formData.get('ot'),
        codigo_barra: formData.get('codigo_barra'),
        cantidad: formData.get('cantidad'),
        matricula: formData.get('matricula')
    };
    
    try {
        const response = await fetch('/api/devolucion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            alert('Devolución registrada correctamente');
            cerrarModalDevolucion();
            // Recargar la tabla de productos
            window.location.reload();
        } else {
            const error = await response.json();
            alert(error.message || 'Error al registrar la devolución');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al registrar la devolución');
    }
});
</script> 