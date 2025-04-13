<?php
class CompanyController {
    private $companyModel;
    private $ratingModel;
    private $internshipModel;
    

    public function __construct() {
        $this->companyModel = new CompanyModel();
        $this->ratingModel = new RatingModel();
        $this->internshipModel = new InternshipModel();
    }
    
    public function index() {
        // Récupération des paramètres de recherche
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $search = $_GET['search'] ?? '';
        $industryId = isset($_GET['industry']) ? (int)$_GET['industry'] : 0;
        $locationId = isset($_GET['location']) ? (int)$_GET['location'] : 0;
        $sort = $_GET['sort'] ?? 'name_asc';
        
        // Création du tableau de filtres
        $filters = [
            'search' => $search,
            'industry' => $industryId,
            'location' => $locationId,
            'sort' => $sort
        ];
        
        // Récupération des données filtrées
        $companies = $this->companyModel->getEnrichedCompanies($page, $filters);
        
        // Récupération du nombre total pour la pagination
        $totalCompanies = $this->companyModel->countFiltered($filters);
        $totalPages = ceil($totalCompanies / ITEMS_PER_PAGE);
        
        // Récupération des données pour les filtres
        $industries = $this->companyModel->getIndustries();
        $locations = $this->companyModel->getCities();
        
        // Préparation des noms pour les filtres actifs
        $industryName = $industryId ? $this->companyModel->getIndustryName($industryId) : '';
        $locationName = $locationId ? $this->companyModel->getLocationName($locationId) : '';
        
        // Passage des données à la vue
        view('company/list', [
            'pageTitle' => 'Entreprises',
            'companies' => $companies,
            'totalPages' => $totalPages,
            'page' => $page,
            'search' => $search,
            'industryId' => $industryId,
            'locationId' => $locationId,
            'sort' => $sort,
            'industries' => $industries,
            'locations' => $locations,
            'industryName' => $industryName,
            'locationName' => $locationName
        ]);
    }
    
    public function view($id) {
        $company = $this->companyModel->getById($id);
        
        if (!$company) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Récupérer les offres de cette entreprise
        $internships = $this->companyModel->getInternships($id);
        
        // Récupérer les évaluations
        $ratings = $this->ratingModel->getCompanyRatings($id);
        $averageRating = $this->ratingModel->getCompanyAverageRating($id);
        
        // Vérifier si l'étudiant connecté a déjà évalué cette entreprise
        $userRating = null;
        if (Auth::isStudent()) {
            $userRating = $this->ratingModel->getStudentRating(Auth::getUserId(), $id);
        }
        
        view('company/view', [
            'pageTitle' => $company['name'],
            'company' => $company,
            'internships' => $internships,
            'ratings' => $ratings,
            'averageRating' => $averageRating,
            'userRating' => $userRating
            
        ]);
    }
    
    public function create() {
        // Seuls les administrateurs et pilotes peuvent créer des entreprises
        Auth::requirePilotOrAdmin();
        
        $error = '';
        
        // Charger les secteurs d'activité depuis la BDD
        $industries = $this->companyModel->getIndustries();
        // Charger les villes depuis la BDD
        $cities = $this->companyModel->getCities();

        // Traiter le formulaire de création
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $contactEmail = $_POST['contact_email'] ?? '';
            $contactPhone = $_POST['contact_phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $city = $_POST['city'] ?? '';
            $website = $_POST['website'] ?? '';
            $additional_info = $_POST['additional_info'] ?? '';
            

            // Gérer l'upload du logo si présent
            $logo_filename = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/company_logos/';
                // Créer le dossier s'il n'existe pas
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_info = pathinfo($_FILES['logo']['name']);
                $extension = strtolower($file_info['extension']);
                
                // Vérifier l'extension
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($extension, $allowed_extensions)) {
                    $new_filename = uniqid() . '.' . $extension;
                    $destination = $upload_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                        $logo_filename = $new_filename;
                    }
                }
            }

            // Vérifier si la ville existe dans la BDD
            $city_id = null;
            if (!empty($city)) {
                $cityData = $this->companyModel->getCityByName($city);
                if ($cityData) {
                    $city_id = $cityData['id'];
                } else {
                    // Si la ville n'existe pas, on peut choisir de l'ajouter ou de ne pas l'associer
                    // Ici, on choisit de ne pas l'associer
                    $error = 'La ville spécifiée n\'existe pas.';
                }
            }
            // Vérifier si le secteur d'activité existe dans la BDD
            $industryId = null;
            if (!empty($industry)) {
                $industryData = $this->companyModel->getindustryByName($industry);
                if ($industryData) {
                    $industry_id = $industryData['id'];
                } else {
                    // Si la ville n'existe pas, on peut choisir de l'ajouter ou de ne pas l'associer
                    // Ici, on choisit de ne pas l'associer
                    $error = 'La ville spécifiée n\'existe pas.';
                }
            }


            // Valider les entrées
            if (empty($name)) {
                $error = 'Le nom de l\'entreprise est requis.';
            } else {
                // Créer l'entreprise
                $companyData = [
                    'name' => $name,
                    'description' => $description,
                    'industry_id' => $industryId,
                    'contact_email' => $contactEmail,
                    'contact_phone' => $contactPhone,
                    'address' => $address,
                    'city_id' => $city_id,
                    'website' => $website,
                    'logo_filename' => $logo_filename,
                    'additional_info' => $additional_info,
                    'created_at' => date('Y-m-d H:i:s')
        
                ];
                
                $companyId = $this->companyModel->create($companyData);
                
                flash('success', 'L\'entreprise a été créée avec succès.');
                redirect('/?page=companies&action=view&id=' . $companyId);
                exit;
            }
        }
        
        view('company/create', [
            'pageTitle' => 'Ajouter une entreprise',
            'industries' => $industries,
            'cities' => $cities,
            'error' => $error
        ]);
    }
    
    public function edit($id) {
        // Seuls les administrateurs et pilotes peuvent modifier des entreprises
        Auth::requirePilotOrAdmin();
        
        $company = $this->companyModel->getById($id);
        $industries = $this->companyModel->getIndustries();
        if (!$company) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        $error = '';
        
        // Traiter le formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $contactEmail = $_POST['contact_email'] ?? '';
            $contactPhone = $_POST['contact_phone'] ?? '';
            
            // Valider les entrées
            if (empty($name)) {
                $error = 'Le nom de l\'entreprise est requis.';
            } else {
                // Mettre à jour l'entreprise
                $companyData = [
                    'name' => $name,
                    'description' => $description,
                    'contact_email' => $contactEmail,
                    'contact_phone' => $contactPhone,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $this->companyModel->update($id, $companyData);
                
                flash('success', 'L\'entreprise a été mise à jour avec succès.');
                redirect('/?page=companies&action=view&id=' . $id);
                exit;
            }
        }
        
        view('company/edit', [
            'pageTitle' => 'Modifier l\'entreprise',
            'company' => $company,
            'industries' => $industries,
            'error' => $error
        ]);
    }
    
    public function delete($id) {
        // Seuls les administrateurs peuvent supprimer des entreprises
        Auth::requireAdmin();
        
        $company = $this->companyModel->getById($id);
        
        if (!$company) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Confirmation de suppression
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $this->companyModel->delete($id);
            
            flash('success', 'L\'entreprise a été supprimée avec succès.');
            redirect('/?page=companies');
            exit;
        }
        
        view('company/delete', [
            'pageTitle' => 'Supprimer l\'entreprise',
            'company' => $company
        ]);
    }
    
    public function rate($id) {
        // Seuls les étudiants peuvent évaluer les entreprises
        Auth::requireLogin();
        
        if (!Auth::isStudent()) {
            flash('danger', 'Seuls les étudiants peuvent évaluer les entreprises.');
            redirect('/?page=companies&action=view&id=' . $id);
            exit;
        }
        
        $company = $this->companyModel->getById($id);
        
        if (!$company) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Vérifier si l'étudiant a déjà évalué cette entreprise
        $userRating = $this->ratingModel->getStudentRating(Auth::getUserId(), $id);
        
        $error = '';
        
        // Traiter le formulaire d'évaluation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
            $comment = $_POST['comment'] ?? '';
            
            // Valider les entrées
            if ($rating < 1 || $rating > 5) {
                $error = 'La note doit être comprise entre 1 et 5.';
            } else {
                // Enregistrer l'évaluation
                $ratingData = [
                    'company_id' => $id,
                    'student_id' => Auth::getUserId(),
                    'rating' => $rating,
                    'comment' => $comment,
                    'rated_at' => date('Y-m-d H:i:s')
                ];
                
                $this->ratingModel->rateCompany($ratingData);
                
                flash('success', 'Votre évaluation a été enregistrée avec succès.');
                redirect('/?page=companies&action=view&id=' . $id);
                exit;
            }
        }
        
        view('company/rate', [
            'pageTitle' => 'Évaluer l\'entreprise',
            'company' => $company,
            'userRating' => $userRating,
            'error' => $error
        ]);
    }
    
    public function deleteRating($companyId) {
        // Vérifier que l'utilisateur est connecté
        Auth::requireLogin();
        
        // Récupérer l'évaluation
        $userRating = $this->ratingModel->getStudentRating(Auth::getUserId(), $companyId);
        
        if (!$userRating) {
            flash('danger', 'Évaluation introuvable.');
            redirect('/?page=companies&action=view&id=' . $companyId);
            exit;
        }
        
        // Vérifier que l'évaluation appartient à l'utilisateur ou que l'utilisateur est admin
        if ($userRating['student_id'] !== Auth::getUserId() && !Auth::isAdmin()) {
            flash('danger', 'Vous n\'êtes pas autorisé à supprimer cette évaluation.');
            redirect('/?page=companies&action=view&id=' . $companyId);
            exit;
        }
        
        // Confirmation de suppression
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $this->ratingModel->deleteRating(Auth::getUserId(), $companyId);
            
            flash('success', 'L\'évaluation a été supprimée avec succès.');
            redirect('/?page=companies&action=view&id=' . $companyId);
            exit;
        }
        
        $company = $this->companyModel->getById($companyId);
        
        view('company/delete_rating', [
            'pageTitle' => 'Supprimer l\'évaluation',
            'company' => $company,
            'rating' => $userRating
        ]);
    }
}