<?php
class Helpers {
  // Fonction pour générer la pagination
  public static function renderPagination($currentPage, $totalPages, $baseUrl, $additionalParams = []) {
    if ($totalPages <= 1) {
      return '';
    }
    
    $params = $additionalParams;
    $html = '<nav aria-label="Pagination"><ul class="pagination">';
    
    // Bouton Précédent
    if ($currentPage > 1) {
      $params['p'] = $currentPage - 1;
      $prevUrl = $baseUrl . '?' . http_build_query($params);
      $html .= '<li class="page-item"><a class="page-link" href="' . $prevUrl . '">&laquo;</a></li>';
    } else {
      $html .= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
    }
    
    // Pages
    for ($i = 1; $i <= $totalPages; $i++) {
      $params['p'] = $i;
      $pageUrl = $baseUrl . '?' . http_build_query($params);
      
      if ($i == $currentPage) {
        $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
      } else {
        $html .= '<li class="page-item"><a class="page-link" href="' . $pageUrl . '">' . $i . '</a></li>';
      }
    }
    
    // Bouton Suivant
    if ($currentPage < $totalPages) {
      $params['p'] = $currentPage + 1;
      $nextUrl = $baseUrl . '?' . http_build_query($params);
      $html .= '<li class="page-item"><a class="page-link" href="' . $nextUrl . '">&raquo;</a></li>';
    } else {
      $html .= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
    }
    
    $html .= '</ul></nav>';
    return $html;
  }
}