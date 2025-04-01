<?php
// Modal de Filtros de Historial
?>
<div id="filtrosHistorialModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Filtros de Historial</h2>
            <button onclick="closeAllModals()" class="closeModal text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="/historial" method="GET" class="space-y-4">
            <!-- Fechas -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Desde</label>
                    <input type="date" name="fecha_desde" value="<?php echo $_GET['fecha_desde'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" value="<?php echo $_GET['fecha_hasta'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- OT y Matrícula -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">OT</label>
                    <input type="text" name="ot" value="<?php echo $_GET['ot'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Matrícula</label>
                    <input type="text" name="matricula" value="<?php echo $_GET['matricula'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- Part Number y Lote -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number</label>
                    <input type="text" name="part_number" value="<?php echo $_GET['part_number'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lote</label>
                    <input type="text" name="lote" value="<?php echo $_GET['lote'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- Ubicación -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" name="ubicacion" value="<?php echo $_GET['ubicacion'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <!-- Botones -->
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAllModals()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openFiltrosHistorialModal() {
    openModal('filtrosHistorialModal');
}
</script> 