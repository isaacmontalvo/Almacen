<?php
/**
 * Componente de paginación reutilizable
 * 
 * @param int $currentPage Página actual
 * @param int $totalPages Total de páginas
 * @param array $filters Filtros aplicados (opcional)
 * @return string HTML de la paginación
 */
function renderPagination($currentPage, $totalPages, $filters = []) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $html = '<div class="pagination">
        <div class="flex justify-between items-center">
            <div class="flex space-x-1">';
    
    // Botón anterior
    $prevPageUrl = '?page=' . ($currentPage - 1);
    foreach ($filters as $key => $value) {
        $prevPageUrl .= "&$key=$value";
    }
    
    $html .= '
        <a href="' . ($currentPage > 1 ? $prevPageUrl : '#') . '" class="pagination-item ' . ($currentPage <= 1 ? 'disabled' : '') . '">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>';
    
    // Números de página
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $startPage + 4);
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        $pageUrl = '?page=' . $i;
        foreach ($filters as $key => $value) {
            $pageUrl .= "&$key=$value";
        }
        
        $html .= '
        <a href="' . $pageUrl . '" class="pagination-item ' . ($i === $currentPage ? 'active' : '') . '">
            ' . $i . '
        </a>';
    }
    
    // Botón siguiente
    $nextPageUrl = '?page=' . ($currentPage + 1);
    foreach ($filters as $key => $value) {
        $nextPageUrl .= "&$key=$value";
    }
    
    $html .= '
        <a href="' . ($currentPage < $totalPages ? $nextPageUrl : '#') . '" class="pagination-item ' . ($currentPage >= $totalPages ? 'disabled' : '') . '">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
            </div>
        </div>
    </div>';
    
    return $html;
}
?>