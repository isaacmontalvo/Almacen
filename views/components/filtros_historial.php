<div id="filtrosHistorialModal" class="modal modal-lg">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Filtros de Historial</h2>
            <button class="closeModal text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="filtrosHistorialForm" action="/historial" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number</label>
                    <input type="text" name="part_number" id="filtroHistorialPartNumber" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lote</label>
                    <input type="text" name="lote" id="filtroHistorialLote"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">OT</label>
                    <input type="text" name="ot" id="filtroHistorialOT"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Matrícula</label>
                    <input type="text" name="matricula" id="filtroHistorialMatricula"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                    <input type="text" name="ubicacion" id="filtroHistorialUbicacion"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="cantidad_min" id="filtroHistorialCantidadMin" min="0" placeholder="Mínimo"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <input type="number" name="cantidad_max" id="filtroHistorialCantidadMax" min="0" placeholder="Máximo"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Salida Desde</label>
                    <input type="date" name="fecha_salida_desde" id="filtroHistorialFechaSalidaDesde"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Salida Hasta</label>
                    <input type="date" name="fecha_salida_hasta" id="filtroHistorialFechaSalidaHasta"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ordenar Por</label>
                    <div class="grid grid-cols-2 gap-2">
                        <select name="order_by" id="filtroHistorialOrderBy"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="fecha_salida">Fecha Salida</option>
                            <option value="part_number">Part Number</option>
                            <option value="lote">Lote</option>
                            <option value="ot">OT</option>
                            <option value="matricula">Matrícula</option>
                            <option value="cantidad">Cantidad</option>
                            <option value="ubicacion">Ubicación</option>
                        </select>
                        <select name="order_dir" id="filtroHistorialOrderDir"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="DESC">Descendente</option>
                            <option value="ASC">Ascendente</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between pt-4">
                <div>
                    <button type="button" id="limpiarFiltrosHistorial" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                        Limpiar Filtros
                    </button>
                </div>
                <div class="flex space-x-3">
                    <button type="button" class="closeModal bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" onclick="return validateFilterForm(this.form)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>