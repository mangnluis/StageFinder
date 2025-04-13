<?php
class InternshipController {
    private $internshipModel;
    private $companyModel;
    private $wishlistModel;
    
    public function __construct() {
        $this->internshipModel = new InternshipModel();
        $this->companyModel = new CompanyModel();
        $this->wishlistModel = new WishlistModel();
    }
    
    public function index() {
        // Récupérer les paramètres GET pour la pagination et les filtres
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $search = $_GET['search'] ?? '';
        $skillId = isset($_GET['skill']) ? (int)$_GET['skill'] : 0;
        
        /* pas encore sur de */
        $filterLocation = isset($_GET['location']) ? (int)$_GET['location'] : 0;
        $filterDateStart = isset($_GET['date_start']) ? $_GET['date_start'] : '';

        $sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;


        //ancien version
        // Définir la limite et l'offset pour la pagination
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        // Récupérer les offres filtrées
        
        
        $hasApplied = false;
        $isInWishlist = false;
        // Get numbers of applications by internship
        
        //marche pas mais jaimerais bien fixer les filtres
        $internships = $this->internshipModel->getFilteredInternships(
            $search, $skillId, $filterLocation, $filterDateStart, $sortOption, $currentPage
        );
        

        
        $internships = $this->internshipModel->getFilteredInternships($search, $skillId, $limit, $offset);

        foreach ($internships as $key => $internship) {
            // Récupérer le nombre de candidatures pour chaque offre
            $internships[$key]['applications_count'] = $this->internshipModel->countApplications($internship['id']);
            // Vérifier si ce stage est dans la wishlist de l'utilisateur
            $internships[$key]['is_in_wishlist'] = $this->wishlistModel->isInWishlist(
                Auth::getUserId(), 
                $internship['id']
            );

            $internships[$key]['has_applied'] = $this->internshipModel->hasApplied(
                Auth::getUserId(), 
                $internship['id']
            );

            // Récupérer la ville de l'offre de stage
            $internships[$key]['location'] = $this->internshipModel->getLocationInternshipById($internship['id']);
        }

        // Calculer le nombre total d'offres pour la pagination
        $totalItems = $this->internshipModel->countFiltered($search, $skillId);
        $totalPages = ceil($totalItems / $limit);
        
        // Récupérer toutes les compétences pour le filtre
        $skills = $this->internshipModel->getAllSkills();
        

        // Récupérer toutes les entreprises pour le filtre
        $locations = $this->companyModel->getCities();

        view('internship/list', [
            'pageTitle' => 'Offres de stage',
            'internships' => $internships,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'search' => $search,
            'skillId' => $skillId,
            'skills' => $skills,
            'locations' => $locations
            //'hasApplied' => $hasApplied,
            //'isInWishlist' => $isInWishlist
        ]);
    }
    
    public function view($id) {
        $internship = $this->internshipModel->getById($id);
        
        if (!$internship) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Récupérer les informations de l'entreprise
        $company = $this->companyModel->getById($internship['company_id']);
        $companyRating = $this->companyModel->getAverageRating($internship['company_id']);
        $companyRatingCount = $this->companyModel->getRatingCount($internship['company_id']);

        // Récupérer les compétences de l'offre
        $skills = $this->internshipModel->getSkills($id);
        
        // Vérifier si l'étudiant a déjà postulé
        $hasApplied = false;
        $isInWishlist = false;
        
        if (Auth::isStudent()) {
            $hasApplied = $this->internshipModel->hasApplied(Auth::getUserId(), $id);
            $isInWishlist = $this->internshipModel->isInWishlist(Auth::getUserId(), $id);
        }
        
        // Récupérer le nombre de candidatures
        $applicationsCount = $this->internshipModel->countApplications($id);
        
        // Récupérer les candidatures (pour les admins et pilotes)
        $applications = [];
        if (Auth::isAdmin() || Auth::isPilot()) {
            $applications = $this->internshipModel->getApplications($id);
        }
        
        // Récupérer des offres similaires
        $db = Database::getInstance();
        $similarInternships = $db->fetchAll(
            "SELECT DISTINCT i.id, i.title, c.name as company_name 
             FROM internships i 
             JOIN companies c ON i.company_id = c.id 
             LEFT JOIN internship_skills is1 ON i.id = is1.internship_id 
             LEFT JOIN internship_skills is2 ON is1.skill_id = is2.skill_id 
             WHERE (is2.internship_id = ? OR i.company_id = ?) 
             AND i.id != ? 
             LIMIT 5",
            [$id, $internship['company_id'], $id]
        );
        
        view('internship/view', [
            'pageTitle' => $internship['title'],
            'internship' => $internship,
            'company' => $company,
            'companyRating' => $companyRating,
            'companyRatingCount' => $companyRatingCount,
            'skills' => $skills,
            'similarInternships' => $similarInternships,
            'applications' => $applications,
            'applicationsCount' => $applicationsCount,
            'hasApplied' => $hasApplied,
            'isInWishlist' => $isInWishlist

        ]);
    }
    
    public function create() {
        // Seuls les administrateurs et pilotes peuvent créer des offres
        Auth::requirePilotOrAdmin();
        
        // Récupérer toutes les entreprises pour le select
        $companies = $this->companyModel->getAll(1, 1000);
        
        // Récupérer toutes les compétences pour les checkboxes
        $skills = $this->internshipModel->getAllSkills();
        $locations = $this->companyModel->getCities();

        $error = '';
        
        // Traiter le formulaire de création
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $companyId = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
            $compensation = isset($_POST['compensation']) ? (float)$_POST['compensation'] : 0;
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            $skillIds = $_POST['skills'] ?? [];
            $responsibilities = $_POST['responsibilities'] ?? '';
            $requirements = $_POST['requirements'] ?? '';
            
            // Valider les entrées
            if (empty($title) || empty($description) || $companyId <= 0) {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } else {
                // Créer l'offre
                $internshipData = [
                    'title' => $title,
                    'description' => $description,
                    'company_id' => $companyId,
                    'compensation' => $compensation,
                    'start_date' => !empty($startDate) ? $startDate : null,
                    'end_date' => !empty($endDate) ? $endDate : null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'responsibilities' => $responsibilities,
                    'requirements' => $requirements,
                    
                    'skills' => $skillIds
                ];
                
                $internshipId = $this->internshipModel->create($internshipData);
                
                flash('success', 'L\'offre de stage a été créée avec succès.');
                redirect('/?page=internships&action=view&id=' . $internshipId);
                exit;
            }
        }
        
        view('internship/create', [
            'pageTitle' => 'Ajouter une offre de stage',
            'companies' => $companies,
            'skills' => $skills,
            'locations' => $locations,
            'error' => $error
        ]);
    }
    
    public function edit($id) {
        // Seuls les administrateurs et pilotes peuvent modifier des offres
        Auth::requirePilotOrAdmin();
        
        $internship = $this->internshipModel->getById($id);
        
        if (!$internship) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Récupérer toutes les entreprises pour le select
        $companies = $this->companyModel->getAll(1, 1000);
        
        // Récupérer toutes les compétences pour les checkboxes
        $allSkills = $this->internshipModel->getAllSkills();
        
        // Récupérer les compétences sélectionnées
        $selectedSkills = $this->internshipModel->getSkills($id);
        $selectedSkillIds = array_map(function($skill) {
            return $skill['id'];
        }, $selectedSkills);
        
        // Récupérer les villes pour le select
        $cities = $this->companyModel->getCities();
        // Pas besoin de convertir en array d'IDs, on veut garder la structure complète
        
        $error = '';
        
        // Traiter le formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $companyId = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
            $compensation = isset($_POST['compensation']) ? (float)$_POST['compensation'] : 0;
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            $skillIds = $_POST['skills'] ?? [];
            $locationId = isset($_POST['location_id']) ? (int)$_POST['location_id'] : null;
            $responsibilities = $_POST['responsibilities'] ?? '';
            $requirements = $_POST['requirements'] ?? '';
            $type = $_POST['type'] ?? 'full-time';
            
            // Valider les entrées
            if (empty($title) || empty($description) || $companyId <= 0) {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } else {
                // Mettre à jour l'offre
                $internshipData = [
                    'title' => $title,
                    'description' => $description,
                    'company_id' => $companyId,
                    'compensation' => $compensation,
                    'start_date' => !empty($startDate) ? $startDate : null,
                    'end_date' => !empty($endDate) ? $endDate : null,
                    'location_id' => $locationId,
                    'responsibilities' => $responsibilities,
                    'requirements' => $requirements,
                    'skills' => $skillIds
                ];
                
                $this->internshipModel->update($id, $internshipData);
                
                flash('success', 'L\'offre de stage a été mise à jour avec succès.');
                redirect('/?page=internships&action=view&id=' . $id);
                exit;
            }
        }
        
        view('internship/edit', [
            'pageTitle' => 'Modifier l\'offre de stage',
            'internship' => $internship,
            'companies' => $companies,
            'allSkills' => $allSkills,
            'selectedSkillIds' => $selectedSkillIds,
            'cities' => $cities,  // Passer les villes complètes, pas juste les IDs
            'error' => $error
        ]);
    }
    
    public function delete($id) {
        // Seuls les administrateurs peuvent supprimer des offres
        Auth::requireAdmin();
        
        $internship = $this->internshipModel->getById($id);
        $company = $this->companyModel->getById($internship['company_id']);
        if (!$internship) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Confirmation de suppression
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $this->internshipModel->delete($id);
            
            flash('success', 'L\'offre de stage a été supprimée avec succès.');
            redirect('/?page=internships');
            exit;
        }
        
        view('internship/delete', [
            'pageTitle' => 'Supprimer l\'offre de stage',
            'internship' => $internship,
            'company' => $company,
            'error' => ''
        ]);
    }
    
    public function apply($id) {
        // Seuls les étudiants peuvent postuler
        Auth::requireLogin();
        
        if (!Auth::isStudent() && !Auth::isAdmin()) {
            flash('danger', 'Seuls les étudiants peuvent postuler aux offres de stage.');
            redirect('/?page=internships&action=view&id=' . $id);
            exit;
        }
        
        $internship = $this->internshipModel->getById($id);
        
        if (!$internship) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Vérifier si l'étudiant a déjà postulé
        if ($this->internshipModel->hasApplied(Auth::getUserId(), $id)) {
            flash('danger', 'Vous avez déjà postulé à cette offre.');
            redirect('/?page=internships&action=view&id=' . $id);
            exit;
        }
        
        // Récupérer les compétences de l'offre
        $skills = $this->internshipModel->getSkills($id);
        
        $error = '';
        
        // Traiter le formulaire de candidature
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motivationLetter = $_POST['motivation_letter'] ?? '';
            
            // Valider les entrées
            if (empty($motivationLetter)) {
                $error = 'Veuillez rédiger une lettre de motivation.';
            } else {
                // Gérer le téléversement du CV
                $cvFilename = null;
                
                if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = UPLOADS_PATH . '/cv/';
                    
                    // Créer le dossier s'il n'existe pas
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    // Générer un nom de fichier unique
                    $extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
                    $cvFilename = 'cv_' . Auth::getUserId() . '_' . time() . '.' . $extension;
                    $destination = $uploadDir . $cvFilename;
                    
                    // Déplacer le fichier téléversé
                    if (!move_uploaded_file($_FILES['cv']['tmp_name'], $destination)) {
                        $error = 'Erreur lors du téléversement du CV.';
                    }
                }
                
                if (empty($error)) {
                    // Créer la candidature
                    $applicationData = [
                        'student_id' => Auth::getUserId(),
                        'internship_id' => $id,
                        'motivation_letter' => $motivationLetter,
                        'cv_filename' => $cvFilename,
                        'status' => 'pending',
                        'applied_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $this->internshipModel->apply($applicationData);
                    
                    flash('success', 'Votre candidature a été envoyée avec succès.');
                    redirect('/?page=internships&action=view&id=' . $id);
                    exit;
                }
            }
        }
        
        view('internship/apply', [
            'pageTitle' => 'Postuler à l\'offre',
            'internship' => $internship,
            'skills' => $skills,
            'error' => $error
        ]);
    }
    
    public function addToWishlist($id) {
        // Seuls les étudiants peuvent ajouter aux favoris
        Auth::requireLogin();
        
        if (!Auth::isStudent()) {
            flash('danger', 'Seuls les étudiants peuvent ajouter des offres à leur wishlist.');
            redirect('/?page=internships&action=view&id=' . $id);
            exit;
        }
        
        // Vérifier si l'offre existe
        $internship = $this->internshipModel->getById($id);
        
        if (!$internship) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Vérifier si l'offre est déjà dans la wishlist
        if ($this->internshipModel->isInWishlist(Auth::getUserId(), $id)) {
            flash('info', 'Cette offre est déjà dans votre wishlist.');
        } else {
            // Ajouter l'offre à la wishlist
            $this->internshipModel->addToWishlist(Auth::getUserId(), $id);
            flash('success', 'L\'offre a été ajoutée à votre wishlist.');
        }
        
        // Redirection vers la page précédente ou la page de détail de l'offre
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('/?page=internships&action=view&id=' . $id);
        }
        exit;
    }
    
    public function removeFromWishlist($id) {
        // Seuls les étudiants peuvent retirer des favoris
        Auth::requireLogin();
        
        if (!Auth::isStudent()) {
            flash('danger', 'Seuls les étudiants peuvent gérer leur wishlist.');
            redirect('/?page=internships&action=view&id=' . $id);
            exit;
        }
        
        // Retirer l'offre de la wishlist
        $this->internshipModel->removeFromWishlist(Auth::getUserId(), $id);
        
        flash('success', 'L\'offre a été retirée de votre wishlist.');
        
        // Redirection vers la page précédente ou la wishlist
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('/?page=wishlist');
        }
        exit;
    }
}