<div id="filtrosModal" class="modal modal-lg">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Filtros de Inventario</h2>
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
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                    <input type="text" name="ubicacion" id="filtroUbicacion"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="cantidad_min" id="filtroCantidadMin" min="0" placeholder="Mínimo"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <input type="number" name="cantidad_max" id="filtroCantidadMax" min="0" placeholder="Máximo"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Días Restantes</label>
                    <input type="number" name="dias_restantes" id="filtroDiasRestantes" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Entrada</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs text-gray-500">Desde</label>
                            <input type="date" name="fecha_entrada_desde" id="filtroFechaEntradaDesde"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">Hasta</label>
                            <input type="date" name="fecha_entrada_hasta" id="filtroFechaEntradaHasta"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Expiración</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs text-gray-500">Desde</label>
                            <input type="date" name="fecha_exp_desde" id="filtroFechaExpDesde"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">Hasta</label>
                            <input type="date" name="fecha_exp_hasta" id="filtroFechaExpHasta"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ordenar Por</label>
                    <div class="grid grid-cols-2 gap-2">
                        <select name="order_by" id="filtroOrderBy"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="part_number">Part Number</option>
                            <option value="lote">Lote</option>
                            <option value="descripcion">Descripción</option>
                            <option value="cantidad">Cantidad</option>
                            <option value="ubicacion">Ubicación</option>
                            <option value="fecha_entrada">Fecha Entrada</option>
                            <option value="fecha_exp">Fecha Expiración</option>
                            <option value="dias_restantes">Días Restantes</option>
                        </select>
                        <select name="order_dir" id="filtroOrderDir"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="ASC">Ascendente</option>
                            <option value="DESC">Descendente</option>
                        </select>
                    </div>
                </div>
            </div>
            
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
                    <button type="submit" onclick="return validateFilterForm(this.form)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>