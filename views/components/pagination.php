<?php
function renderPagination($currentPage, $totalPages, $filters = []) {
    if ($totalPages <= 1) {
        return '';
    }
    
    // Obtener la ruta actual
    $currentPath = $_SERVER['REQUEST_URI'];
    $basePath = strtok($currentPath, '?');
    
    $html = '<div class="flex justify-center space-x-2 mt-4">';
    
    // Botón anterior
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $queryParams = array_merge($filters, ['page' => $prevPage]);
        $html .= sprintf(
            '<a href="%s?%s" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">Anterior</a>',
            $basePath,
            http_build_query($queryParams)
        );
    }
    
    // Números de página
    for ($i = 1; $i <= $totalPages; $i++) {
        $queryParams = array_merge($filters, ['page' => $i]);
        $html .= sprintf(
            '<a href="%s?%s" class="px-3 py-1 %s rounded-md text-sm">%d</a>',
            $basePath,
            http_build_query($queryParams),
            $i === $currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300',
            $i
        );
    }
    
    // Botón siguiente
    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        $queryParams = array_merge($filters, ['page' => $nextPage]);
        $html .= sprintf(
            '<a href="%s?%s" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">Siguiente</a>',
            $basePath,
            http_build_query($queryParams)
        );
    }
    
    $html .= '</div>';
    
    return $html;
} 