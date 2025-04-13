<?php
class WishlistController {
    private $wishlistModel;
    private $internshipModel;
    
    public function __construct() {
        // Vérifier que l'utilisateur est connecté et est un étudiant
        Auth::requireLogin();
        
        if (!Auth::isStudent()) {
            flash('danger', 'Seuls les étudiants peuvent accéder à la wishlist.');
            redirect('/?page=dashboard');
            exit;
        }
        
        $this->wishlistModel = new WishlistModel();
        $this->internshipModel = new InternshipModel();
    }
    
    // Afficher la wishlist
    public function index() {
        // Récupérer les offres de la wishlist
        $wishlist = $this->wishlistModel->getWishlist(Auth::getUserId());
        
        // Vérifier si la wishlist est vide
        $hasApplied = false;

        //has applied to internship
        foreach ($wishlist as $key => $internship) {
            $hasApplied = $this->internshipModel->hasApplied(Auth::getUserId(), $internship['internship_id']);
            $wishlist[$key]['applied'] = $hasApplied;
        }

        view('wishlist/index', [
            'pageTitle' => 'Ma wishlist',
            'wishlist' => $wishlist,
            'hasApplied' => $hasApplied,
        ]);
    }
    
    // Ajouter une offre à la wishlist
    public function add($id) {
        // Vérifier si l'offre existe
        $internship = $this->internshipModel->getById($id);
        
        if (!$internship) {
            flash('danger', 'Offre de stage introuvable.');
            redirect('/?page=internships');
            exit;
        }
        
        // Vérifier si l'offre est déjà dans la wishlist
        if ($this->wishlistModel->isInWishlist(Auth::getUserId(), $id)) {
            flash('info', 'Cette offre est déjà dans votre wishlist.');
        } else {
            // Ajouter l'offre à la wishlist
            $this->wishlistModel->add(Auth::getUserId(), $id);
            flash('success', 'L\'offre a été ajoutée à votre wishlist.');
        }
        
        // Rediriger vers la page précédente ou la page de détail de l'offre
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('/?page=internships&action=view&id=' . $id);
        }
        exit;
    }
    
    // Retirer une offre de la wishlist
    public function remove($id) {
        $studentId = Auth::getUserId() ?? null;
        $internshipId = $id;
    
        if ($studentId && $internshipId) {
            // Utilisation du modèle Wishlist pour supprimer
            $this->wishlistModel->remove($studentId, $internshipId);
            flash('success', 'L\'offre a été retirée de la wishlist.');
        } else {
            flash('danger', 'Impossible de retirer l\'offre de la wishlist.');
        }
    
        redirect('/?page=wishlist');
        exit;
    }
}