// Funciones para manejar el escáner de códigos de barras
function initBarcodeScanner() {
    const barcodeInput = document.getElementById('barcode');
    if (!barcodeInput) return;
    
    // Enfocar automáticamente el campo de código de barras
    barcodeInput.focus();
    
    // Actualizar el estado del escáner
    const scannerStatus = document.getElementById('scannerStatus');
    if (scannerStatus) {
        scannerStatus.textContent = 'Listo para escanear';
        scannerStatus.classList.remove('bg-gray-100', 'text-gray-800');
        scannerStatus.classList.add('bg-green-100', 'text-green-800');
    }
    
    // Manejar el evento de escaneo (cuando se presiona Enter)
    barcodeInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            // Actualizar el estado del escáner
            if (scannerStatus) {
                scannerStatus.textContent = 'Escaneando...';
                scannerStatus.classList.remove('bg-green-100', 'text-green-800');
                scannerStatus.classList.add('bg-blue-100', 'text-blue-800', 'scanner-active');
            }
            
            // Enviar el formulario después de un breve retraso
            setTimeout(() => {
                document.getElementById('scanForm').submit();
            }, 300);
        }
    });
}

// Función para abrir el modal de filtros
function openFiltrosModal() {
    // Cargar valores actuales de los filtros desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    
    if (document.getElementById('filtroPartNumber')) {
        document.getElementById('filtroPartNumber').value = urlParams.get('part_number') || '';
        document.getElementById('filtroDescripcion').value = urlParams.get('descripcion') || '';
        document.getElementById('filtroLote').value = urlParams.get('lote') || '';
        document.getElementById('filtroUbicacion').value = urlParams.get('ubicacion') || '';
        document.getElementById('filtroCantidadMin').value = urlParams.get('cantidad_min') || '';
        document.getElementById('filtroCantidadMax').value = urlParams.get('cantidad_max') || '';
        document.getElementById('filtroFechaEntradaDesde').value = urlParams.get('fecha_entrada_desde') || '';
        document.getElementById('filtroFechaEntradaHasta').value = urlParams.get('fecha_entrada_hasta') || '';
        document.getElementById('filtroFechaExpDesde').value = urlParams.get('fecha_exp_desde') || '';
        document.getElementById('filtroFechaExpHasta').value = urlParams.get('fecha_exp_hasta') || '';
        document.getElementById('filtroDiasRestantes').value = urlParams.get('dias_restantes') || '';
        
        if (urlParams.get('order_by')) {
            document.getElementById('filtroOrderBy').value = urlParams.get('order_by');
        }
        
        if (urlParams.get('order_dir')) {
            document.getElementById('filtroOrderDir').value = urlParams.get('order_dir');
        }
    }
    
    openModal('filtrosModal');
}

// Función para abrir el modal de filtros de historial
function openFiltrosHistorialModal() {
    // Cargar valores actuales de los filtros desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    
    if (document.getElementById('filtroHistorialPartNumber')) {
        document.getElementById('filtroHistorialPartNumber').value = urlParams.get('part_number') || '';
        document.getElementById('filtroHistorialLote').value = urlParams.get('lote') || '';
        document.getElementById('filtroHistorialOT').value = urlParams.get('ot') || '';
        document.getElementById('filtroHistorialMatricula').value = urlParams.get('matricula') || '';
        document.getElementById('filtroHistorialUbicacion').value = urlParams.get('ubicacion') || '';
        document.getElementById('filtroHistorialCantidadMin').value = urlParams.get('cantidad_min') || '';
        document.getElementById('filtroHistorialCantidadMax').value = urlParams.get('cantidad_max') || '';
        document.getElementById('filtroHistorialFechaSalidaDesde').value = urlParams.get('fecha_salida_desde') || '';
        document.getElementById('filtroHistorialFechaSalidaHasta').value = urlParams.get('fecha_salida_hasta') || '';
        
        if (urlParams.get('order_by')) {
            document.getElementById('filtroHistorialOrderBy').value = urlParams.get('order_by');
        }
        
        if (urlParams.get('order_dir')) {
            document.getElementById('filtroHistorialOrderDir').value = urlParams.get('order_dir');
        }
    }
    
    openModal('filtrosHistorialModal');
}

// Limpiar filtros
function limpiarFiltros() {
    const form = document.getElementById('filtrosForm');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        if (input.type === 'text' || input.type === 'number' || input.type === 'date') {
            input.value = '';
        } else if (input.type === 'select-one') {
            input.selectedIndex = 0;
        }
    });
}

// Limpiar filtros de historial
function limpiarFiltrosHistorial() {
    const form = document.getElementById('filtrosHistorialForm');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        if (input.type === 'text' || input.type === 'number' || input.type === 'date') {
            input.value = '';
        } else if (input.type === 'select-one') {
            input.selectedIndex = 0;
        }
    });
}

// Validar formulario de filtros
function validateFilterForm(form) {
    // Eliminar campos vacíos para no enviarlos en la URL
    const inputs = form.querySelectorAll('input, select');
    let hasValues = false;
    
    inputs.forEach(input => {
        if (input.value.trim() === '') {
            input.disabled = true; // Deshabilitar temporalmente para que no se envíe
        } else {
            hasValues = true;
        }
    });
    
    // Re-habilitar los campos después del envío
    setTimeout(() => {
        inputs.forEach(input => {
            input.disabled = false;
        });
    }, 100);
    
    return true;
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    initBarcodeScanner();
    
    // Inicializar el toggle de fecha de expiración
    if (document.getElementById('has_expiration')) {
        toggleExpirationDate();
    }
    
    // Manejar botones de limpiar filtros
    const limpiarFiltrosBtn = document.getElementById('limpiarFiltros');
    if (limpiarFiltrosBtn) {
        limpiarFiltrosBtn.addEventListener('click', limpiarFiltros);
    }
    
    const limpiarFiltrosHistorialBtn = document.getElementById('limpiarFiltrosHistorial');
    if (limpiarFiltrosHistorialBtn) {
        limpiarFiltrosHistorialBtn.addEventListener('click', limpiarFiltrosHistorial);
    }
});