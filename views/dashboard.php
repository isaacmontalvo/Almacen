<?php
// Preparar datos para gráficos
$locationLabels = [];
$locationValues = [];
foreach ($locationData as $item) {
    $locationLabels[] = $item['ubicacion'];
    $locationValues[] = $item['total'];
}

$monthLabels = [];
$monthValues = [];
$monthNames = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

foreach ($monthlyMovements as $item) {
    $monthLabels[] = $monthNames[$item['mes']] . ' ' . $item['anio'];
    $monthValues[] = $item['total_cantidad'];
}

$expLabels = [];
$expValues = [];
$expColors = [
    'Expirado' => 'rgb(220, 53, 69)',
    'Menos de 30 días' => 'rgb(255, 193, 7)',
    'Entre 30 y 90 días' => 'rgb(13, 110, 253)',
    'Más de 90 días' => 'rgb(25, 135, 84)'
];

foreach ($expirationData as $item) {
    $expLabels[] = $item['categoria'];
    $expValues[] = $item['total'];
}
?>

<div class="space-y-8">
    <h1 class="text-3xl font-bold">Dashboard</h1>
    
    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 hover-effect">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Productos</p>
                    <p class="text-2xl font-semibold"><?php echo $stats['total_productos']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 hover-effect">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Por Expirar</p>
                    <p class="text-2xl font-semibold"><?php echo $stats['productos_por_expirar']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 hover-effect">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Items</p>
                    <p class="text-2xl font-semibold"><?php echo $stats['total_items']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 hover-effect">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Movimientos del Mes</p>
                    <p class="text-2xl font-semibold"><?php echo $stats['movimientos_mes']; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Inventario por Ubicación -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Inventario por Ubicación</h2>
            <div class="h-80">
                <canvas id="locationChart"></canvas>
            </div>
        </div>
        
        <!-- Gráfico de Movimientos Mensuales -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Movimientos Mensuales</h2>
            <div class="h-80">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
        
        <!-- Gráfico de Estado de Expiración -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Estado de Expiración</h2>
            <div class="h-80">
                <canvas id="expirationChart"></canvas>
            </div>
        </div>
        
        <!-- Gráfico de Entradas vs Salidas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Entradas vs Salidas</h2>
            <div class="h-80">
                <canvas id="inOutChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Gráfico de Inventario por Ubicación
        const locationCtx = document.getElementById("locationChart").getContext("2d");
        new Chart(locationCtx, {
            type: "bar",
            data: {
                labels: <?php echo json_encode($locationLabels); ?>,
                datasets: [{
                    label: "Cantidad de Items",
                    data: <?php echo json_encode($locationValues); ?>,
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Gráfico de Movimientos Mensuales
        const monthlyCtx = document.getElementById("monthlyChart").getContext("2d");
        new Chart(monthlyCtx, {
            type: "line",
            data: {
                labels: <?php echo json_encode($monthLabels); ?>,
                datasets: [{
                    label: "Cantidad de Items",
                    data: <?php echo json_encode($monthValues); ?>,
                    fill: false,
                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Gráfico de Estado de Expiración
        const expirationCtx = document.getElementById("expirationChart").getContext("2d");
        new Chart(expirationCtx, {
            type: "pie",
            data: {
                labels: <?php echo json_encode($expLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($expValues); ?>,
                    backgroundColor: [
                        "rgba(220, 53, 69, 0.6)",
                        "rgba(255, 193, 7, 0.6)",
                        "rgba(13, 110, 253, 0.6)",
                        "rgba(25, 135, 84, 0.6)"
                    ],
                    borderColor: [
                        "rgba(220, 53, 69, 1)",
                        "rgba(255, 193, 7, 1)",
                        "rgba(13, 110, 253, 1)",
                        "rgba(25, 135, 84, 1)"
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // Gráfico de Entradas vs Salidas (datos simulados)
        const inOutCtx = document.getElementById("inOutChart").getContext("2d");
        new Chart(inOutCtx, {
            type: "bar",
            data: {
                labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun"],
                datasets: [
                    {
                        label: "Entradas",
                        data: [65, 59, 80, 81, 56, 55],
                        backgroundColor: "rgba(54, 162, 235, 0.6)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1
                    },
                    {
                        label: "Salidas",
                        data: [28, 48, 40, 19, 86, 27],
                        backgroundColor: "rgba(255, 99, 132, 0.6)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>