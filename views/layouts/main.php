<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/assets/css/styles.css">
    
    <!-- Estilos para modales -->
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 600px;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        @media (max-width: 640px) {
            .modal-content {
                margin: 10% auto;
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/" class="text-white font-bold text-xl">Inventario</a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="/" class="<?php echo ($content ?? '') === 'home' || empty($content ?? '') ? 'bg-gray-900' : 'hover:bg-gray-700'; ?> px-3 py-2 rounded-md text-sm font-medium">Inventario</a>
                            <a href="/scan" class="<?php echo ($content ?? '') === 'scan' ? 'bg-gray-900' : 'hover:bg-gray-700'; ?> px-3 py-2 rounded-md text-sm font-medium">Escanear</a>
                            <a href="/historial" class="<?php echo ($content ?? '') === 'historial' ? 'bg-gray-900' : 'hover:bg-gray-700'; ?> px-3 py-2 rounded-md text-sm font-medium">Historial</a>
                            <a href="/dashboard" class="<?php echo ($content ?? '') === 'dashboard' ? 'bg-gray-900' : 'hover:bg-gray-700'; ?> px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <button onclick="openModal('devolucionModal')" class="bg-green-500 hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium mr-2">
                            <svg class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Devolución
                        </button>
                        <a href="/scan" class="bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <svg class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Escanear
                        </a>
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Menú móvil -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="/" class="<?php echo ($content ?? '') === 'home' || empty($content ?? '') ? 'bg-gray-900' : 'hover:bg-gray-700'; ?> block px-3 py-2 rounded-md text-base font-medium">Inventario</a>
                <a href="/scan" class="<?php echo ($content ?? '') === 'scan' ? 'bg-gray-900' : 'hover:bg-gray-700'; ?> block px-3 py-2 rounded-md text-base font-medium">Escanear</a>
                <a href="/historial" class="<?php echo ($content ?? '') === 'historial' ? 'bg-gray-900' : 'hover:bg-gray-700'; ?> block px-3 py-2 rounded-md text-base font-medium">Historial</a>
                <a href="/dashboard" class="<?php echo ($content ?? '') === 'dashboard' ? 'bg-gray-900' : 'hover:bg-gray-700'; ?> block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
            </div>
        </div>
    </nav>
    
    <!-- Contenido principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php
        // Cargar la vista correspondiente
        if (isset($content)) {
            require_once BASE_PATH . '/views/' . $content . '.php';
        } else {
            // Vista por defecto (home)
            require_once BASE_PATH . '/views/home.php';
        }
        ?>
    </main>

    <!-- Modal de Registro de Salida -->
    <div id="registroSalidaModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Registrar Salida de Producto</h2>
                <button onclick="closeAllModals()" class="closeModal text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="/registrar-salida" method="POST" class="space-y-4">
                <input type="hidden" id="product_id" name="product_id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Part Number:</label>
                        <p id="part_number" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción:</label>
                        <p id="descripcion" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cantidad Disponible:</label>
                        <p id="cantidad_disponible" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ubicación:</label>
                        <p id="ubicacion" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                </div>
                
                <div>
                    <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad a Retirar:</label>
                    <input type="number" id="cantidad" name="cantidad" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="ot" class="block text-sm font-medium text-gray-700">Orden de Trabajo (OT):</label>
                    <input type="text" id="ot" name="ot" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="matricula" class="block text-sm font-medium text-gray-700">Matrícula del Avión:</label>
                    <input type="text" id="matricula" name="matricula" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAllModals()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Registrar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal de Detalles del Producto -->
    <div id="detallesProductoModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Detalles del Producto</h2>
                <button onclick="closeAllModals()" class="closeModal text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="space-y-6">
                <!-- Información Básica -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Información Básica</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Part Number:</label>
                            <p id="det_part_number" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción:</label>
                            <p id="det_descripcion" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ubicación:</label>
                            <p id="det_ubicacion" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock Actual:</label>
                            <p id="det_stock" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                    </div>
                </div>

                <!-- Código de Barras -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Código de Barras</h3>
                    <div class="flex justify-center">
                        <div id="det_codigo_barras" class="text-center">
                            <!-- El código de barras se insertará aquí -->
                        </div>
                    </div>
                </div>

                <!-- Historial de Movimientos -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Historial de Movimientos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">OT</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                                </tr>
                            </thead>
                            <tbody id="det_historial" class="divide-y divide-gray-200">
                                <!-- El historial se insertará aquí -->
                            </tbody>
                        </table>
                    </div>
                    <!-- Paginación -->
                    <div id="det_historial_pagination" class="mt-4 flex justify-center space-x-2">
                        <!-- Los botones de paginación se insertarán aquí -->
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button onclick="closeAllModals()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal de Devoluciones -->
    <div id="devolucionModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Registrar Devolución</h2>
                <button onclick="closeAllModals()" class="closeModal text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="devolucionForm" class="space-y-4">
                <div>
                    <label for="busqueda_devolucion" class="block text-sm font-medium text-gray-700">Buscar por Part Number:</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" id="busqueda_devolucion" name="busqueda" class="flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ingrese el Part Number">
                        <button type="button" onclick="buscarMovimientosDevolucion()" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Buscar
                        </button>
                    </div>
                </div>

                <div id="movimientos_devolucion" class="hidden">
                    <h3 class="text-lg font-semibold mb-3">Movimientos del Día</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Seleccionar</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Part Number</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">OT</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                                </tr>
                            </thead>
                            <tbody id="lista_movimientos_devolucion" class="divide-y divide-gray-200">
                                <!-- Los movimientos se insertarán aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="formulario_devolucion" class="hidden space-y-4">
                    <div>
                        <label for="cantidad_devolucion" class="block text-sm font-medium text-gray-700">Cantidad a Devolver:</label>
                        <input type="number" id="cantidad_devolucion" name="cantidad" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="observaciones_devolucion" class="block text-sm font-medium text-gray-700">Observaciones:</label>
                        <textarea id="observaciones_devolucion" name="observaciones" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAllModals()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Registrar Devolución
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="/assets/js/script.js"></script>
    <script>
        // Funciones generales para modales
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeAllModals() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.style.display = 'none';
            });
            document.body.style.overflow = 'auto';
        }
        
        // Función para abrir el modal de registro de salida
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
            
            // Abrir el modal
            openModal('registroSalidaModal');
        }
        
        // Funciones para dropdowns
        function toggleDropdown(dropdownId) {
            document.getElementById(dropdownId).classList.toggle('show');
        }
        
        // Cerrar dropdowns al hacer clic fuera
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                const dropdowns = document.getElementsByClassName('dropdown-content');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
        
        // Toggle para fecha de expiración
        function toggleExpirationDate() {
            const checkbox = document.getElementById('has_expiration');
            const dateField = document.getElementById('fecha_exp');
            
            if (checkbox && dateField) {
                dateField.disabled = !checkbox.checked;
                if (!checkbox.checked) {
                    dateField.value = '';
                }
            }
        }
        
        // Inicializar eventos
        document.addEventListener('DOMContentLoaded', function() {
            // Botón de menú móvil
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Cerrar modales
            const closeButtons = document.querySelectorAll('.closeModal');
            closeButtons.forEach(button => {
                button.addEventListener('click', closeAllModals);
            });
            
            // Cerrar modal al hacer clic fuera
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        closeAllModals();
                    }
                });
            });
        });

        // Función para abrir el modal de detalles del producto
        function openDetallesProductoModal(product) {
            if (!product || typeof product !== 'object') {
                console.error('Error: Producto no válido');
                return;
            }
            
            // Llenar información básica
            document.getElementById('det_part_number').textContent = product.part_number || '';
            document.getElementById('det_descripcion').textContent = product.descripcion || '';
            document.getElementById('det_ubicacion').textContent = product.ubicacion || '';
            document.getElementById('det_stock').textContent = product.cantidad || '0';
            
            // Mostrar código de barras
            const codigoBarrasDiv = document.getElementById('det_codigo_barras');
            if (product.codigo_barra) {
                codigoBarrasDiv.innerHTML = `
                    <img src="/barcodes/${product.codigo_barra}.png" alt="Código de Barras" class="mx-auto">
                    <p class="mt-2 text-sm text-gray-600">${product.codigo_barra}</p>
                `;
            } else {
                codigoBarrasDiv.innerHTML = '<p class="text-gray-500">No hay código de barras disponible</p>';
            }
            
            // Cargar historial de movimientos
            loadMovimientosHistorial(product.id, 1);
            
            // Abrir el modal
            openModal('detallesProductoModal');
        }

        // Función para cargar el historial de movimientos
        function loadMovimientosHistorial(productId, page) {
            console.log('Cargando página:', page); // Debug
            fetch(`/api/movimientos-producto/${productId}?page=${page}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos recibidos:', data); // Debug
                    const historialDiv = document.getElementById('det_historial');
                    const paginationDiv = document.getElementById('det_historial_pagination');
                    
                    // Mostrar movimientos
                    if (data.movimientos && data.movimientos.length > 0) {
                        historialDiv.innerHTML = data.movimientos.map(mov => `
                            <tr>
                                <td class="px-3 py-2 text-sm text-gray-900">${new Date(mov.fecha_salida).toLocaleDateString()}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">${mov.tipo || 'Salida'}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">${mov.cantidad}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">${mov.ot || '-'}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">${mov.matricula || '-'}</td>
                            </tr>
                        `).join('');
                        
                        // Mostrar paginación
                        if (data.pages > 1) {
                            let paginationHtml = '';
                            
                            // Botón anterior
                            if (data.current_page > 1) {
                                paginationHtml += `
                                    <button onclick="loadMovimientosHistorial(${productId}, ${data.current_page - 1})" 
                                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">
                                        Anterior
                                    </button>
                                `;
                            }
                            
                            // Números de página
                            for (let i = 1; i <= data.pages; i++) {
                                paginationHtml += `
                                    <button onclick="loadMovimientosHistorial(${productId}, ${i})" 
                                            class="px-3 py-1 ${i === data.current_page ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'} rounded-md text-sm">
                                        ${i}
                                    </button>
                                `;
                            }
                            
                            // Botón siguiente
                            if (data.current_page < data.pages) {
                                paginationHtml += `
                                    <button onclick="loadMovimientosHistorial(${productId}, ${data.current_page + 1})" 
                                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">
                                        Siguiente
                                    </button>
                                `;
                            }
                            
                            paginationDiv.innerHTML = paginationHtml;
                        } else {
                            paginationDiv.innerHTML = '';
                        }
                    } else {
                        historialDiv.innerHTML = `
                            <tr>
                                <td colspan="5" class="px-3 py-2 text-sm text-gray-500 text-center">
                                    No hay movimientos registrados
                                </td>
                            </tr>
                        `;
                        paginationDiv.innerHTML = '';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar el historial:', error);
                    document.getElementById('det_historial').innerHTML = `
                        <tr>
                            <td colspan="5" class="px-3 py-2 text-sm text-red-500 text-center">
                                Error al cargar el historial
                            </td>
                        </tr>
                    `;
                    document.getElementById('det_historial_pagination').innerHTML = '';
                });
        }

        // Función para buscar movimientos para devolución
        function buscarMovimientosDevolucion() {
            const busqueda = document.getElementById('busqueda_devolucion').value.trim();
            if (!busqueda) {
                alert('Por favor ingrese un Part Number');
                return;
            }

            const movimientosDiv = document.getElementById('movimientos_devolucion');
            const listaMovimientos = document.getElementById('lista_movimientos_devolucion');
            const formularioDevolucion = document.getElementById('formulario_devolucion');

            // Mostrar indicador de carga
            listaMovimientos.innerHTML = `
                <tr>
                    <td colspan="6" class="px-3 py-2 text-sm text-gray-500 text-center">
                        Buscando movimientos...
                    </td>
                </tr>
            `;
            movimientosDiv.classList.remove('hidden');
            formularioDevolucion.classList.add('hidden');

            fetch(`/api/movimientos-devolucion?busqueda=${encodeURIComponent(busqueda)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        listaMovimientos.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-3 py-2 text-sm text-red-500 text-center">
                                    ${data.error}
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    if (data.movimientos && data.movimientos.length > 0) {
                        listaMovimientos.innerHTML = data.movimientos.map(mov => `
                            <tr>
                                <td class="px-3 py-2">
                                    <input type="radio" name="movimiento_seleccionado" value="${mov.id}" 
                                           onchange="seleccionarMovimiento(${mov.id}, ${mov.cantidad})"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-900">${new Date(mov.fecha_salida).toLocaleDateString()}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">${mov.part_number}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">${mov.cantidad}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">${mov.ot || '-'}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">${mov.matricula || '-'}</td>
                            </tr>
                        `).join('');
                    } else {
                        listaMovimientos.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-3 py-2 text-sm text-gray-500 text-center">
                                    No se encontraron movimientos para devolución
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error al buscar movimientos:', error);
                    listaMovimientos.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-3 py-2 text-sm text-red-500 text-center">
                                Error al buscar movimientos. Por favor, intente nuevamente.
                            </td>
                        </tr>
                    `;
                });
        }

        // Función para seleccionar un movimiento
        function seleccionarMovimiento(movimientoId, cantidadMaxima) {
            document.getElementById('formulario_devolucion').classList.remove('hidden');
            document.getElementById('cantidad_devolucion').max = cantidadMaxima;
        }

        // Manejar el envío del formulario de devolución
        document.getElementById('devolucionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const movimientoId = document.querySelector('input[name="movimiento_seleccionado"]:checked')?.value;
            const cantidad = document.getElementById('cantidad_devolucion').value;
            const observaciones = document.getElementById('observaciones_devolucion').value;

            if (!movimientoId) {
                alert('Por favor seleccione un movimiento');
                return;
            }

            fetch('/registrar-devolucion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    movimiento_id: movimientoId,
                    cantidad: cantidad,
                    observaciones: observaciones
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Devolución registrada correctamente');
                    closeAllModals();
                    // Actualizar la vista si es necesario
                    if (typeof actualizarVista === 'function') {
                        actualizarVista();
                    }
                } else {
                    alert(data.error || 'Error al registrar la devolución');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al registrar la devolución');
            });
        });
    </script>
</body>
</html>