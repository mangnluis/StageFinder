<?php
/**
 * Classe utilitaire pour gérer la pagination
 */
class Pagination {
    /**
     * Nombre total d'éléments
     * @var int
     */
    private $totalItems;
    
    /**
     * Nombre d'éléments par page
     * @var int
     */
    private $itemsPerPage;
    
    /**
     * Page courante
     * @var int
     */
    private $currentPage;
    
    /**
     * Nombre total de pages
     * @var int
     */
    private $totalPages;
    
    /**
     * URL de base pour les liens de pagination
     * @var string
     */
    private $baseUrl;
    
    /**
     * Nombre de liens de page à afficher (avant et après la page courante)
     * @var int
     */
    private $pageRange = 2;
    
    /**
     * Paramètres supplémentaires pour l'URL
     * @var array
     */
    private $params = [];
    
    /**
     * Constructeur
     * 
     * @param int $totalItems Nombre total d'éléments
     * @param int $itemsPerPage Nombre d'éléments par page
     * @param int $currentPage Page courante
     * @param string $baseUrl URL de base pour les liens de pagination
     * @param array $params Paramètres supplémentaires pour l'URL
     */
    public function __construct($totalItems, $itemsPerPage, $currentPage, $baseUrl, $params = []) {
        $this->totalItems = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = max(1, $currentPage);
        $this->baseUrl = $baseUrl;
        $this->params = $params;
        $this->totalPages = ceil($totalItems / $itemsPerPage);
    }
    
    /**
     * Définir le nombre de liens de page à afficher (avant et après la page courante)
     * 
     * @param int $range Nombre de liens de page
     * @return Pagination
     */
    public function setPageRange($range) {
        $this->pageRange = max(1, $range);
        return $this;
    }
    
    /**
     * Obtenir le nombre total d'éléments
     * 
     * @return int Nombre total d'éléments
     */
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
     * Obtenir le nombre d'éléments par page
     * 
     * @return int Nombre d'éléments par page
     */
    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }
    
    /**
     * Obtenir la page courante
     * 
     * @return int Page courante
     */
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
     * Obtenir le nombre total de pages
     * 
     * @return int Nombre total de pages
     */
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
     * Vérifier si une page précédente existe
     * 
     * @return bool True si une page précédente existe, false sinon
     */
    public function hasPreviousPage() {
        return $this->currentPage > 1;
    }
    
    /**
     * Vérifier si une page suivante existe
     * 
     * @return bool True si une page suivante existe, false sinon
     */
    public function hasNextPage() {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
     * Obtenir le numéro de la page précédente
     * 
     * @return int Numéro de la page précédente
     */
    public function getPreviousPage() {
        return max(1, $this->currentPage - 1);
    }
    
    /**
     * Obtenir le numéro de la page suivante
     * 
     * @return int Numéro de la page suivante
     */
    public function getNextPage() {
        return min($this->totalPages, $this->currentPage + 1);
    }
    
    /**
     * Obtenir l'offset pour la requête SQL LIMIT
     * 
     * @return int Offset pour la requête SQL
     */
    public function getOffset() {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }
    
    /**
     * Construire l'URL pour une page donnée
     * 
     * @param int $page Numéro de page
     * @return string URL pour cette page
     */
    public function getPageUrl($page) {
        $params = $this->params;
        $params['p'] = $page;
        
        $queryString = http_build_query($params);
        
        return $this->baseUrl . ($queryString ? '?' . $queryString : '');
    }
    
    /**
     * Obtenir la liste des pages à afficher
     * 
     * @return array Liste des numéros de page à afficher
     */
    public function getPages() {
        $pages = [];
        
        // Calculer la plage de pages à afficher
        $startPage = max(1, $this->currentPage - $this->pageRange);
        $endPage = min($this->totalPages, $this->currentPage + $this->pageRange);
        
        // Toujours afficher la première page
        if ($startPage > 1) {
            $pages[] = 1;
            if ($startPage > 2) {
                $pages[] = '...';
            }
        }
        
        // Afficher les pages dans la plage
        for ($i = $startPage; $i <= $endPage; $i++) {
            $pages[] = $i;
        }
        
        // Toujours afficher la dernière page
        if ($endPage < $this->totalPages) {
            if ($endPage < $this->totalPages - 1) {
                $pages[] = '...';
            }
            $pages[] = $this->totalPages;
        }
        
        return $pages;
    }
    
    /**
     * Générer le HTML de la pagination
     * 
     * @return string HTML de la pagination
     */
    public function render() {
        if ($this->totalPages <= 1) {
            return '';
        }
        
        $html = '<nav aria-label="Pagination">';
        $html .= '<ul class="pagination">';
        
        // Lien "Précédent"
        if ($this->hasPreviousPage()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getPageUrl($this->getPreviousPage()) . '" aria-label="Précédent">';
            $html .= '<span aria-hidden="true">&laquo;</span>';
            $html .= '</a>';
            $html .= '</li>';
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<span class="page-link" aria-hidden="true">&laquo;</span>';
            $html .= '</li>';
        }
        
        // Liens des pages
        foreach ($this->getPages() as $page) {
            if ($page === '...') {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            } else {
                $html .= '<li class="page-item' . ($page == $this->currentPage ? ' active' : '') . '">';
                $html .= '<a class="page-link" href="' . $this->getPageUrl($page) . '">' . $page . '</a>';
                $html .= '</li>';
            }
        }
        
        // Lien "Suivant"
        if ($this->hasNextPage()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getPageUrl($this->getNextPage()) . '" aria-label="Suivant">';
            $html .= '<span aria-hidden="true">&raquo;</span>';
            $html .= '</a>';
            $html .= '</li>';
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<span class="page-link" aria-hidden="true">&raquo;</span>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $html;
    }
}