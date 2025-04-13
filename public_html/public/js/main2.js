/**
 * StageFinder - Script principal modernisé
 * Version: 2.0
 * 
 * Ce fichier contient toutes les fonctionnalités JavaScript modernisées
 * pour accompagner la refonte CSS.
 */

// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des fonctionnalités essentielles
    initializeCore();
    
    // Initialisation des composants UI
    initializeUIComponents();
    
    // Initialisation des fonctionnalités spécifiques
    initializeForms();
    initializeMessaging();
    initializeNotifications();
    initializeAnimations();
        
    // Optimisations pour mobiles
    initializeMobileOptimizations();
});

/**
 * Initialisation des fonctionnalités de base
 */
function initializeCore() {
    console.log('StageFinder v2.0 - Initialisation...');
    
    // Activer les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover',
            boundary: document.body,
            container: 'body'
        });
    });
    
    // Activer les popovers Bootstrap
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(popoverTriggerEl => {
        new bootstrap.Popover(popoverTriggerEl, {
            container: 'body',
            html: true
        });
    });
    
    // Gestionnaire pour les messages flash
    setupFlashMessages();
    
    // Mémoriser l'état des accordéons
    setupAccordionMemory();
}

/**
 * Initialisation des composants UI
 */
function initializeUIComponents() {
    // Toggle du hamburger menu
    const hamburgers = document.querySelectorAll('.hamburger');
    hamburgers.forEach(hamburger => {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('is-active');
        });
    });
    
    // Setup des toggles de vue (grille/liste)
    setupViewToggles();
    
    // Système de notation par étoiles
    setupStarRating();
    
    // Boutons wishlist (favoris)
    setupWishlistButtons();
    
    // Initialiser les selects améliorés
    initializeCustomSelects();
}

/**
 * Initialisation des formulaires et validations
 */
function initializeForms() {
    // Setup de la validation des formulaires
    setupFormValidation();
    
    // Toggles de visibilité des mots de passe
    setupPasswordToggles();
    
    // Validation des fichiers
    setupFileValidation();
    
    // Indicateur de force du mot de passe
    initPasswordStrengthMeter();
    
    // Auto-resize des textareas
    initTextareaAutoResize();
}

/**
 * Configuration des messages flash auto-hide
 */
function setupFlashMessages() {
    const flashMessages = document.querySelectorAll('.alert-dismissible');
    
    flashMessages.forEach(message => {
        // Ajouter une animation d'entrée
        message.classList.add('animate-fadeIn');
        
        // Auto-fermeture après 5 secondes
        setTimeout(() => {
            const closeButton = message.querySelector('.btn-close');
            if (closeButton) {
                // Ajouter une animation de sortie avant la fermeture
                message.classList.add('animate-fadeOut');
                
                // Fermer après l'animation
                setTimeout(() => {
                    closeButton.click();
                }, 300);
            }
        }, 5000);
    });
}

/**
 * Configuration des sauvegardes d'état des accordéons
 */
function setupAccordionMemory() {
    const accordions = document.querySelectorAll('.accordion');
    
    accordions.forEach(accordion => {
        const id = accordion.id;
        if (id) {
            // Charger l'état sauvegardé
            const openItems = JSON.parse(localStorage.getItem(`accordion_${id}`)) || [];
            
            openItems.forEach(itemId => {
                const item = document.getElementById(itemId);
                if (item) {
                    const collapse = new bootstrap.Collapse(item, {
                        toggle: false
                    });
                    collapse.show();
                }
            });
            
            // Sauvegarder l'état lors des changements
            accordion.addEventListener('shown.bs.collapse', function(e) {
                const itemId = e.target.id;
                let openItems = JSON.parse(localStorage.getItem(`accordion_${id}`)) || [];
                if (!openItems.includes(itemId)) {
                    openItems.push(itemId);
                    localStorage.setItem(`accordion_${id}`, JSON.stringify(openItems));
                }
            });
            
            accordion.addEventListener('hidden.bs.collapse', function(e) {
                const itemId = e.target.id;
                let openItems = JSON.parse(localStorage.getItem(`accordion_${id}`)) || [];
                openItems = openItems.filter(id => id !== itemId);
                localStorage.setItem(`accordion_${id}`, JSON.stringify(openItems));
            });
        }
    });
}

/**
 * Configuration des toggles de vue (grille vs liste)
 */
function setupViewToggles() {
    // Fonction pour mettre en place le toggle de vue pour une section spécifique
    const setupViewToggle = (buttonPrefix, viewPrefix, storageKey) => {
        const gridViewBtn = document.getElementById(`${buttonPrefix}-grid`);
        const listViewBtn = document.getElementById(`${buttonPrefix}-list`);
        const gridView = document.getElementById(`${viewPrefix}-grid`);
        const listView = document.getElementById(`${viewPrefix}-list`);
        
        if (gridViewBtn && listViewBtn && gridView && listView) {
            // Charger la préférence depuis localStorage
            const viewPreference = localStorage.getItem(storageKey);
            if (viewPreference === 'list') {
                gridView.classList.add('d-none');
                listView.classList.remove('d-none');
                gridViewBtn.classList.remove('active');
                listViewBtn.classList.add('active');
            }
            
            // Gestionnaires d'événements pour les boutons
            gridViewBtn.addEventListener('click', function() {
                // Animations pour une transition fluide
                listView.classList.add('animate-fadeOut');
                
                setTimeout(() => {
                    gridView.classList.remove('d-none');
                    listView.classList.add('d-none');
                    
                    listViewBtn.classList.remove('active');
                    gridViewBtn.classList.add('active');
                    
                    gridView.classList.add('animate-fadeIn');
                    
                    localStorage.setItem(storageKey, 'grid');
                }, 200);
            });
            
            listViewBtn.addEventListener('click', function() {
                // Animations pour une transition fluide
                gridView.classList.add('animate-fadeOut');
                
                setTimeout(() => {
                    gridView.classList.add('d-none');
                    listView.classList.remove('d-none');
                    
                    gridViewBtn.classList.remove('active');
                    listViewBtn.classList.add('active');
                    
                    listView.classList.add('animate-fadeIn');
                    
                    localStorage.setItem(storageKey, 'list');
                }, 200);
            });
        }
    };
    
    // Configuration des différents toggles de vue
    setupViewToggle('viewBtn', 'view', 'internshipsViewPreference');
    setupViewToggle('companyViewBtn', 'companyView', 'companiesViewPreference');
    setupViewToggle('applicationViewBtn', 'applicationView', 'applicationsViewPreference');
}

/**
 * StageFinder - Script principal modernisé (Suite)
 * Version: 2.0
 */

/**
 * Configuration du système de notation par étoiles (suite)
 */
function setupStarRating() {
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingValue = document.querySelector('.rating-value');
    const starLabels = document.querySelectorAll('.star-label');
    
    if (ratingInputs.length && starLabels.length) {
        // Initialiser l'affichage des étoiles
        ratingInputs.forEach(input => {
            if (input.checked) {
                updateStars(input.value);
            }
            
            input.addEventListener('change', function() {
                if (ratingValue) {
                    // Animation du changement de valeur
                    ratingValue.classList.add('animate-pulse');
                    setTimeout(() => {
                        ratingValue.textContent = this.value + '/5';
                        ratingValue.classList.remove('animate-pulse');
                    }, 300);
                }
                updateStars(this.value);
            });
        });
    }
    
    // Fonction pour mettre à jour l'affichage des étoiles
    function updateStars(value) {
        starLabels.forEach((label, index) => {
            const star = label.querySelector('i');
            if (index < value) {
                star.classList.remove('far');
                star.classList.add('fas');
                label.classList.add('animate-pulse');
                setTimeout(() => {
                    label.classList.remove('animate-pulse');
                }, 300 * (index + 1));
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });
    }
}

/**
 * Configuration des toggles de visibilité pour les mots de passe
 */
function setupPasswordToggles() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    if (toggleButtons.length) {
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    
                    // Message temporaire d'aide
                    const helpText = document.createElement('small');
                    helpText.className = 'text-muted password-visibility-notice mt-1 animate-fadeIn';
                    helpText.textContent = 'Mot de passe visible - n\'oubliez pas de masquer avant de quitter';
                    input.parentNode.insertAdjacentElement('afterend', helpText);
                    
                    // Supprimer le message après 3 secondes
                    setTimeout(() => {
                        if (helpText) {
                            helpText.classList.add('animate-fadeOut');
                            setTimeout(() => helpText.remove(), 300);
                        }
                    }, 3000);
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    
                    // Supprimer tout message d'aide existant
                    const helpText = input.parentNode.parentNode.querySelector('.password-visibility-notice');
                    if (helpText) {
                        helpText.classList.add('animate-fadeOut');
                        setTimeout(() => helpText.remove(), 300);
                    }
                }
            });
        });
    }
}

/**
 * Configuration de la validation des formulaires
 */
function setupFormValidation() {
    const forms = document.querySelectorAll('form[data-validate="true"]');
    
    if (forms.length) {
        forms.forEach(form => {
            // Ajouter l'attribut novalidate
            form.setAttribute('novalidate', '');
            
            // Validation de correspondance des mots de passe
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');
            
            if (password && confirmPassword) {
                confirmPassword.addEventListener('input', function() {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas.');
                        
                        // Ajouter une classe d'animation pour signaler l'erreur
                        confirmPassword.classList.add('invalid-highlight');
                        setTimeout(() => {
                            confirmPassword.classList.remove('invalid-highlight');
                        }, 500);
                    } else {
                        confirmPassword.setCustomValidity('');
                        
                        // Ajouter une classe d'animation pour signaler la validation
                        confirmPassword.classList.add('valid-highlight');
                        setTimeout(() => {
                            confirmPassword.classList.remove('valid-highlight');
                        }, 500);
                    }
                });
            }
            
            // Validation de plage de dates
            const startDate = form.querySelector('input[name="start_date"]');
            const endDate = form.querySelector('input[name="end_date"]');
            
            if (startDate && endDate) {
                const validateDates = function() {
                    if (startDate.value && endDate.value) {
                        const start = new Date(startDate.value);
                        const end = new Date(endDate.value);
                        
                        if (start > end) {
                            endDate.setCustomValidity('La date de fin doit être postérieure à la date de début.');
                            
                            // Mettre en évidence l'erreur
                            endDate.classList.add('invalid-highlight');
                            setTimeout(() => {
                                endDate.classList.remove('invalid-highlight');
                            }, 500);
                        } else {
                            endDate.setCustomValidity('');
                            
                            // Ajouter une confirmation visuelle
                            [startDate, endDate].forEach(el => {
                                el.classList.add('valid-highlight');
                                setTimeout(() => {
                                    el.classList.remove('valid-highlight');
                                }, 500);
                            });
                        }
                    }
                };
                
                startDate.addEventListener('change', validateDates);
                endDate.addEventListener('change', validateDates);
            }
            
            // Événement de soumission
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Trouver le premier champ invalide et le mettre en évidence
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        // Animation d'erreur
                        firstInvalid.classList.add('invalid-highlight');
                        setTimeout(() => {
                            firstInvalid.classList.remove('invalid-highlight');
                        }, 500);
                    }
                } else {
                    // Montrer un indicateur de chargement pour le formulaire
                    const submitButton = form.querySelector('[type="submit"]');
                    if (submitButton && !form.hasAttribute('data-no-loading')) {
                        submitButton.innerHTML = `
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="ms-2">En cours...</span>
                        `;
                        submitButton.disabled = true;
                    }
                }
                
                form.classList.add('was-validated');
            });
            
            // Validation en temps réel des champs
            setupLiveValidation(form);
        });
    }
}

/**
 * Configuration de la validation en temps réel des champs
 */
function setupLiveValidation(form) {
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        // Validation quand l'utilisateur quitte le champ
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Validation en temps réel pour certains types de champs
        input.addEventListener('input', function() {
            // Pour les champs spécifiques qui nécessitent une validation immédiate
            if (this.type === 'email' || this.hasAttribute('pattern') || this.hasAttribute('minlength')) {
                validateField(this);
            }
            
            // Si c'est un champ requis qui était invalide
            if (this.hasAttribute('required') && this.classList.contains('is-invalid') && this.value.trim()) {
                validateField(this);
            }
        });
    });
}

/**
 * Validation d'un champ individuel
 */
function validateField(field) {
    // Vérifier si le champ est vide
    if (field.hasAttribute('required') && !field.value.trim()) {
        addError(field, 'Ce champ est requis');
        return false;
    }
    
    // Vérifier la longueur minimale
    if (field.hasAttribute('minlength') && field.value.length < parseInt(field.getAttribute('minlength'))) {
        const minLength = field.getAttribute('minlength');
        addError(field, `Ce champ doit contenir au moins ${minLength} caractères`);
        return false;
    }
    
    // Vérifier la longueur maximale
    if (field.hasAttribute('maxlength') && field.value.length > parseInt(field.getAttribute('maxlength'))) {
        const maxLength = field.getAttribute('maxlength');
        addError(field, `Ce champ ne doit pas dépasser ${maxLength} caractères`);
        return false;
    }
    
    // Vérifier les patterns
    if (field.hasAttribute('pattern') && field.value) {
        const pattern = new RegExp(field.getAttribute('pattern'));
        if (!pattern.test(field.value)) {
            addError(field, field.getAttribute('data-error-pattern') || 'Format invalide');
            return false;
        }
    }
    
    // Validation spécifique pour les emails
    if (field.type === 'email' && field.value) {
        const emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!emailRegex.test(field.value)) {
            addError(field, 'Veuillez entrer une adresse email valide');
            return false;
        }
    }
    
    // Le champ est valide
    removeError(field);
    return true;
}

/**
 * Ajoute un message d'erreur à un champ
 * @param {HTMLElement} field - Le champ concerné
 * @param {string} message - Le message d'erreur
 */
function addError(field, message) {
    // Supprimer d'abord les messages d'erreur existants
    removeError(field);
    
    // Ajouter la classe d'invalidité
    field.classList.add('is-invalid');
    
    // Créer un élément pour le message d'erreur
    const errorElement = document.createElement('div');
    errorElement.className = 'invalid-feedback';
    errorElement.textContent = message;
    
    // Ajouter le message après le champ
    field.parentNode.appendChild(errorElement);
  }
  
  /**
   * Supprime les messages d'erreur d'un champ
   * @param {HTMLElement} field - Le champ concerné
   */
  function removeError(field) {
    // Supprimer la classe d'invalidité
    field.classList.remove('is-invalid');
    
    // Supprimer tous les messages d'erreur existants
    const errorElements = field.parentNode.querySelectorAll('.invalid-feedback');
    errorElements.forEach(element => element.remove());
  }

/**
 * Initialisation de l'indicateur de force du mot de passe
 */
function initPasswordStrengthMeter() {
    const passwordFields = document.querySelectorAll('input[type="password"][data-strength]');
    
    passwordFields.forEach(field => {
        const strengthId = field.getAttribute('data-strength');
        const strengthIndicator = document.getElementById(strengthId);
        
        if (strengthIndicator) {
            field.addEventListener('input', () => {
                const result = calculatePasswordStrength(field.value);
                updatePasswordStrengthUI(strengthIndicator, result);
            });
        }
    });
}

/**
 * Calcule la force d'un mot de passe
 */
function calculatePasswordStrength(password) {
    let score = 0;
    let feedback = [];
    
    // Mot de passe vide
    if (!password) {
        return { score: 0, feedback: ['Veuillez entrer un mot de passe'] };
    }
    
    // Vérification de la longueur
    if (password.length < 8) {
        feedback.push('Trop court (minimum 8 caractères)');
    } else {
        score += 25;
    }
    
    // Vérification de la complexité
    if (/[A-Z]/.test(password)) { 
        score += 25; 
    } else {
        feedback.push('Ajoutez des lettres majuscules');
    }
    
    if (/[a-z]/.test(password)) { 
        score += 15; 
    } else {
        feedback.push('Ajoutez des lettres minuscules');
    }
    
    if (/[0-9]/.test(password)) { 
        score += 15; 
    } else {
        feedback.push('Ajoutez des chiffres');
    }
    
    if (/[^A-Za-z0-9]/.test(password)) { 
        score += 20; 
    } else {
        feedback.push('Ajoutez des caractères spéciaux');
    }
    
    // Limiter le score à 100
    score = Math.min(100, score);
    
    // Message global basé sur le score
    let message = '';
    if (score < 25) {
        message = 'Très faible';
    } else if (score < 50) {
        message = 'Faible';
    } else if (score < 75) {
        message = 'Moyen';
    } else {
        message = 'Fort';
    }
    
    return {
        score: score,
        message: message,
        feedback: feedback
    };
}

/**
 * Met à jour l'interface de l'indicateur de force du mot de passe
 */
function updatePasswordStrengthUI(indicator, result) {
    // Mettre à jour la barre de progression
    const progressBar = indicator.querySelector('.progress-bar');
    if (progressBar) {
        // Animation fluide de la barre
        progressBar.style.transition = 'width 0.5s ease, background-color 0.5s ease';
        progressBar.style.width = `${result.score}%`;
        
        // Mise à jour de la couleur
        progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');
        
        if (result.score < 25) {
            progressBar.classList.add('bg-danger');
        } else if (result.score < 50) {
            progressBar.classList.add('bg-warning');
        } else if (result.score < 75) {
            progressBar.classList.add('bg-info');
        } else {
            progressBar.classList.add('bg-success');
        }
    }
    
    // Mettre à jour le texte
    const textElement = indicator.querySelector('.strength-text');
    if (textElement) {
        let textClass = 'text-danger';
        
        if (result.score < 25) {
            textClass = 'text-danger';
        } else if (result.score < 50) {
            textClass = 'text-warning';
        } else if (result.score < 75) {
            textClass = 'text-info';
        } else {
            textClass = 'text-success';
        }
        
        textElement.textContent = result.message;
        textElement.className = `strength-text ${textClass} animate-fadeIn`;
    }
    
    // Mettre à jour les suggestions
    const feedbackElement = indicator.querySelector('.strength-feedback');
    if (feedbackElement && result.feedback.length > 0) {
        feedbackElement.innerHTML = result.feedback.map(item => `<li>${item}</li>`).join('');
        feedbackElement.classList.add('animate-fadeIn');
    } else if (feedbackElement) {
        feedbackElement.innerHTML = '';
    }
}

/**
 * Configuration de la validation des fichiers
 */
function setupFileValidation() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        const maxSize = input.getAttribute('data-max-size');
        const allowedExtensions = input.getAttribute('data-extensions');
        
        if (maxSize || allowedExtensions) {
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    // Validation de la taille
                    if (maxSize) {
                        const fileSize = this.files[0].size / 1024 / 1024; // Taille en Mo
                        if (fileSize > parseFloat(maxSize)) {
                            addError(this, `Le fichier est trop volumineux. Taille maximale autorisée: ${maxSize} Mo.`);
                            this.value = ''; // Réinitialiser l'input
                            return;
                        }
                    }
                    
                    // Validation de l'extension
                    if (allowedExtensions) {
                        const extensions = allowedExtensions.split(',').map(ext => ext.trim());
                        const fileName = this.files[0].name;
                        const fileExt = fileName.split('.').pop().toLowerCase();
                        
                        if (!extensions.includes(fileExt)) {
                            addError(this, `Extension non autorisée. Extensions acceptées: ${allowedExtensions}`);
                            this.value = ''; // Réinitialiser l'input
                            return;
                        }
                    }
                    
                    // Le fichier est valide - mettre à jour l'UI
                    removeError(this);
                    
                    // Afficher le nom du fichier sélectionné
                    const fileNameDisplay = this.closest('.form-file')?.querySelector('.form-file-text');
                    if (fileNameDisplay) {
                        fileNameDisplay.textContent = this.files[0].name;
                        
                        // Animation pour indiquer la sélection
                        fileNameDisplay.classList.add('animate-fadeIn');
                        setTimeout(() => {
                            fileNameDisplay.classList.remove('animate-fadeIn');
                        }, 500);
                    }
                }
            });
        }
    });
    
    // Gestion du CV existant vs nouveau CV
    const useExistingCvCheckbox = document.getElementById('use_existing_cv');
    const newCvUpload = document.getElementById('new_cv_upload');
    const updateProfileCvCheckbox = document.getElementById('update_profile_cv');
    
    if (useExistingCvCheckbox && newCvUpload) {
        useExistingCvCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Animation de transition
                newCvUpload.style.opacity = '1';
                newCvUpload.style.transition = 'opacity 0.3s ease, height 0.3s ease, margin 0.3s ease';
                
                setTimeout(() => {
                    newCvUpload.style.opacity = '0';
                    newCvUpload.style.height = '0';
                    newCvUpload.style.margin = '0';
                    
                    setTimeout(() => {
                        newCvUpload.classList.add('d-none');
                        
                        // Désactiver l'input
                        const cvInput = document.getElementById('cv');
                        if (cvInput) {
                            cvInput.removeAttribute('required');
                        }
                        
                        // Activer la case à cocher pour mettre à jour le profil
                        if (updateProfileCvCheckbox) {
                            updateProfileCvCheckbox.disabled = false;
                        }
                    }, 300);
                }, 10);
            } else {
                // Animation de transition
                newCvUpload.classList.remove('d-none');
                newCvUpload.style.opacity = '0';
                newCvUpload.style.height = '0';
                
                setTimeout(() => {
                    newCvUpload.style.opacity = '1';
                    newCvUpload.style.height = '';
                    newCvUpload.style.margin = '';
                    
                    // Activer l'input
                    const cvInput = document.getElementById('cv');
                    if (cvInput) {
                        cvInput.setAttribute('required', 'required');
                    }
                    
                    // Désactiver la case à cocher pour mettre à jour le profil
                    if (updateProfileCvCheckbox) {
                        updateProfileCvCheckbox.checked = true;
                        updateProfileCvCheckbox.disabled = true;
                    }
                }, 10);
            }
        });
    }
}

/**
 * StageFinder - Script principal modernisé (Partie 3)
 * Version: 2.0
 */

/**
 * Auto-redimensionnement des zones de texte
 */
function initTextareaAutoResize() {
    const textareas = document.querySelectorAll('textarea[data-auto-resize]');
    
    textareas.forEach(textarea => {
        // Fonction pour redimensionner
        const resize = () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        };
        
        // Appliquer au chargement
        resize();
        
        // Redimensionner à chaque saisie
        textarea.addEventListener('input', resize);
        
        // Redimensionner lors du focus
        textarea.addEventListener('focus', resize);
    });
}

/**
 * Configuration des selects personnalisés
 */
function initializeCustomSelects() {
    const customSelects = document.querySelectorAll('.custom-select');
    
    customSelects.forEach(select => {
        // Créer un wrapper div
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper position-relative';
        
        // Insérer le wrapper
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        
        // Créer le faux select
        const fakeSelect = document.createElement('div');
        fakeSelect.className = 'form-control d-flex align-items-center justify-content-between cursor-pointer';
        fakeSelect.innerHTML = `
            <span class="custom-select-value">${select.options[select.selectedIndex]?.textContent || 'Sélectionner'}</span>
            <i class="fas fa-chevron-down ms-2 text-muted"></i>
        `;
        wrapper.appendChild(fakeSelect);
        
        // Créer la liste déroulante
        const dropdown = document.createElement('div');
        dropdown.className = 'custom-select-dropdown position-absolute w-100 bg-white rounded shadow-lg d-none animate-fadeIn';
        dropdown.style.zIndex = '1000';
        wrapper.appendChild(dropdown);
        
        // Ajouter les options
        Array.from(select.options).forEach((option, index) => {
            const item = document.createElement('div');
            item.className = 'custom-select-item p-2 cursor-pointer';
            if (index === select.selectedIndex) {
                item.classList.add('active', 'bg-light');
            }
            
            item.textContent = option.textContent;
            
            item.addEventListener('click', () => {
                // Mettre à jour le select original
                select.selectedIndex = index;
                
                // Déclencher l'événement change
                const event = new Event('change', { bubbles: true });
                select.dispatchEvent(event);
                
                // Mettre à jour l'affichage
                fakeSelect.querySelector('.custom-select-value').textContent = option.textContent;
                
                // Mettre à jour l'item actif
                dropdown.querySelectorAll('.custom-select-item').forEach((el, i) => {
                    if (i === index) {
                        el.classList.add('active', 'bg-light');
                    } else {
                        el.classList.remove('active', 'bg-light');
                    }
                });
                
                // Fermer le dropdown
                dropdown.classList.add('animate-fadeOut');
                setTimeout(() => {
                    dropdown.classList.remove('animate-fadeOut');
                    dropdown.classList.add('d-none');
                }, 300);
            });
            
            item.addEventListener('mouseenter', () => {
                item.classList.add('bg-light');
            });
            
            item.addEventListener('mouseleave', () => {
                if (index !== select.selectedIndex) {
                    item.classList.remove('bg-light');
                }
            });
            
            dropdown.appendChild(item);
        });
        
        // Toggle du dropdown au clic
        fakeSelect.addEventListener('click', (e) => {
            e.stopPropagation();
            
            if (dropdown.classList.contains('d-none')) {
                // Fermer tous les autres dropdowns
                document.querySelectorAll('.custom-select-dropdown:not(.d-none)').forEach(el => {
                    el.classList.add('animate-fadeOut');
                    setTimeout(() => {
                        el.classList.remove('animate-fadeOut');
                        el.classList.add('d-none');
                    }, 300);
                });
                
                // Ouvrir ce dropdown
                dropdown.classList.remove('d-none');
                
                // Animer la flèche
                fakeSelect.querySelector('i').style.transform = 'rotate(180deg)';
            } else {
                // Fermer ce dropdown
                dropdown.classList.add('animate-fadeOut');
                setTimeout(() => {
                    dropdown.classList.remove('animate-fadeOut');
                    dropdown.classList.add('d-none');
                    
                    // Réinitialiser la flèche
                    fakeSelect.querySelector('i').style.transform = 'rotate(0)';
                }, 300);
            }
        });
        
        // Fermer au clic en dehors
        document.addEventListener('click', () => {
            if (!dropdown.classList.contains('d-none')) {
                dropdown.classList.add('animate-fadeOut');
                setTimeout(() => {
                    dropdown.classList.remove('animate-fadeOut');
                    dropdown.classList.add('d-none');
                    
                    // Réinitialiser la flèche
                    fakeSelect.querySelector('i').style.transform = 'rotate(0)';
                }, 300);
            }
        });
        
        // Mise à jour visuelle lorsque le select change
        select.addEventListener('change', () => {
            fakeSelect.querySelector('.custom-select-value').textContent = 
                select.options[select.selectedIndex]?.textContent || 'Sélectionner';
        });
    });
}

/**
 * StageFinder - Script principal modernisé (Fonctionnalités avancées)
 * Version: 2.0
 */

/**
 * Mettre à jour le compteur de wishlist dans la navigation
 * @param {number} count - Nombre d'éléments dans la wishlist
 */
function updateWishlistCount(count) {
    const wishlistCountBadge = document.querySelector('.wishlist-count');
    if (wishlistCountBadge) {
        // Animer le changement
        wishlistCountBadge.classList.add('animate-pulse');
        
        setTimeout(() => {
            wishlistCountBadge.textContent = count;
            
            if (count > 0) {
                wishlistCountBadge.classList.remove('d-none');
            } else {
                wishlistCountBadge.classList.add('d-none');
            }
            
            wishlistCountBadge.classList.remove('animate-pulse');
        }, 300);
    }
}

/**
 * Afficher une notification toast
 * @param {string} type - Type de toast ('success', 'error', 'info', 'warning')
 * @param {string} message - Message à afficher
 * @param {number} duration - Durée d'affichage en ms (défaut: 5000)
 */
function showToast(type, message, duration = 5000) {
    // Convertir le type "error" en "danger" pour correspondre aux classes Bootstrap
    const bsType = type === 'error' ? 'danger' : type;
    
    // Créer le conteneur de toast s'il n'existe pas
    let toastContainer = document.querySelector('.toast-container');
    
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    // Créer l'élément toast
    const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white bg-${bsType} border-0 animate-fadeIn`;
    toastEl.id = toastId;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    
    // Déterminer l'icône en fonction du type
    let icon = 'info-circle';
    if (type === 'success') icon = 'check-circle';
    if (type === 'error' || type === 'danger') icon = 'exclamation-circle';
    if (type === 'warning') icon = 'exclamation-triangle';
    
    // Définir le contenu du toast
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${icon} me-2"></i>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
    `;
    
    // Ajouter au conteneur
    toastContainer.appendChild(toastEl);
    
    // Créer et afficher le toast avec Bootstrap
    const toast = new bootstrap.Toast(toastEl, {
        animation: true,
        autohide: true,
        delay: duration
    });
    
    toast.show();
    
    // Ajouter une animation de sortie avant la suppression
    toastEl.addEventListener('hidden.bs.toast', function() {
        toastEl.classList.add('animate-fadeOut');
        setTimeout(() => {
            toastEl.remove();
            
            // Supprimer le conteneur s'il est vide
            if (toastContainer.children.length === 0) {
                toastContainer.remove();
            }
        }, 300);
    });
    
    // Retourner l'ID pour permettre la manipulation externe
    return toastId;
}

/**
 * Initialisation du système de messagerie en temps réel
 */
function initializeMessaging() {
    // Vérifier si nous sommes sur une page de messagerie
    const messagesContainer = document.getElementById('messages-container');
    if (!messagesContainer) return;
    
    // Faire défiler jusqu'au dernier message
    scrollToBottom(messagesContainer);
    
    // Récupérer les données importantes
    const lastMessage = messagesContainer.querySelector('.message:last-child');
    let lastMessageId = 0;
    
    // Récupérer le dernier ID de message de façon sécurisée
    if (lastMessage && lastMessage.hasAttribute('data-id')) {
        lastMessageId = parseInt(lastMessage.getAttribute('data-id'));
    } else {
        // Fallback: récupérer depuis la variable JavaScript
        const lastMessageScript = document.querySelector('script[data-last-message-id]');
        if (lastMessageScript) {
            lastMessageId = parseInt(lastMessageScript.getAttribute('data-last-message-id'));
        }
    }
    
    // Récupérer l'ID de la conversation
    const conversationIdInput = document.getElementById('conversation_id');
    if (!conversationIdInput) return;
    
    const conversationId = parseInt(conversationIdInput.value);
    
    // Variable globale pour suivre le dernier ID de message
    window.currentLastMessageId = lastMessageId;
    
    // Démarrer le polling des nouveaux messages
    const messageCheckInterval = setInterval(function() {
        checkNewMessages(conversationId, window.currentLastMessageId);
    }, 5000);
    
    // Soumettre le formulaire en AJAX
    setupMessageForm(conversationId);
    
    // Ajouter l'auto-redimensionnement au textarea
    const messageTextarea = document.getElementById('message-content');
    if (messageTextarea) {
        // Fonction pour redimensionner
        const resize = () => {
            messageTextarea.style.height = 'auto';
            const newHeight = Math.min(Math.max(60, messageTextarea.scrollHeight), 200);
            messageTextarea.style.height = newHeight + 'px';
        };
        
        // Appliquer au chargement
        resize();
        
        // Redimensionner à chaque saisie
        messageTextarea.addEventListener('input', resize);
        
        // Gérer l'envoi avec Entrée (Shift+Entrée pour nouvelle ligne)
        messageTextarea.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const form = document.getElementById('send-message-form');
                if (form) {
                    // Vérifier que le message n'est pas vide
                    if (this.value.trim()) {
                        form.dispatchEvent(new Event('submit', { bubbles: true }));
                    } else {
                        // Animation d'erreur si vide
                        this.classList.add('invalid-highlight');
                        setTimeout(() => {
                            this.classList.remove('invalid-highlight');
                        }, 500);
                    }
                }
            }
        });
    }
    
    // Ajouter une zone de statut d'écriture
    if (messagesContainer) {
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'typing-indicator d-none animate-fadeIn';
        typingIndicator.innerHTML = `
            <small class="text-muted"><i class="fas fa-pencil-alt me-1"></i> Quelqu'un est en train d'écrire</small>
            <span></span><span></span><span></span>
        `;
        messagesContainer.appendChild(typingIndicator);
        
        // Simuler le statut d'écriture (dans un vrai cas, cela viendrait du serveur)
        if (messageTextarea) {
            let typingTimeout;
            messageTextarea.addEventListener('input', function() {
                if (this.value.trim()) {
                    // Envoyer le statut "en train d'écrire" au serveur (simulation)
                    console.log('Utilisateur en train d\'écrire...');
                    
                    // Réinitialiser le timeout à chaque saisie
                    clearTimeout(typingTimeout);
                    typingTimeout = setTimeout(() => {
                        // Envoyer le statut "a cessé d'écrire" au serveur (simulation)
                        console.log('Utilisateur a cessé d\'écrire');
                    }, 2000);
                }
            });
        }
    }
}

/**
 * Configurer le formulaire d'envoi de message
 * @param {number} conversationId - ID de la conversation
 */
function setupMessageForm(conversationId) {
    const messageForm = document.getElementById('send-message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageTextarea = document.getElementById('message-content');
            if (!messageTextarea.value.trim()) {
                return; // Ne pas envoyer si vide
            }
            
            // Désactiver le bouton d'envoi pendant le traitement
            const submitButton = this.querySelector('[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="ms-2">Envoi...</span>
                `;
            }
            
            const formData = new FormData(this);
            
            fetch('?page=message&action=send-ajax', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Vider le champ de message
                    messageTextarea.value = '';
                    messageTextarea.style.height = '60px'; // Réinitialiser la hauteur
                    
                    // Vérifier immédiatement les nouveaux messages
                    checkNewMessages(conversationId, window.currentLastMessageId);
                } else {
                    console.error('Erreur lors de l\'envoi du message:', data.message);
                    showToast('error', data.message || 'Erreur lors de l\'envoi du message');
                }
                
                // Réactiver le bouton d'envoi
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = `
                        <i class="fas fa-paper-plane"></i>
                        <span class="ms-2">Envoyer</span>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('error', 'Une erreur est survenue lors de l\'envoi du message');
                
                // Réactiver le bouton d'envoi
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = `
                        <i class="fas fa-paper-plane"></i>
                        <span class="ms-2">Envoyer</span>
                    `;
                }
            });
        });
    }
}

/**
 * Vérifier s'il y a de nouveaux messages
 * @param {number} conversationId - ID de la conversation
 * @param {number} lastId - ID du dernier message reçu
 */
function checkNewMessages(conversationId, lastId) {
    if (!conversationId) return;
    
    // URL pour vérifier les nouveaux messages
    const url = `?page=message&action=check-new&conversation_id=${conversationId}&last_id=${lastId}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages && data.messages.length > 0) {
                // Masquer l'indicateur de saisie si présent
                const typingIndicator = document.querySelector('.typing-indicator');
                if (typingIndicator && !typingIndicator.classList.contains('d-none')) {
                    typingIndicator.classList.add('animate-fadeOut');
                    setTimeout(() => {
                        typingIndicator.classList.remove('animate-fadeOut');
                        typingIndicator.classList.add('d-none');
                    }, 300);
                }
                
                // Ajouter les nouveaux messages
                appendNewMessages(data.messages);
                
                // Mettre à jour l'ID du dernier message
                if (data.messages.length > 0) {
                    window.currentLastMessageId = data.messages[data.messages.length - 1].id;
                }
                
                // Faire défiler vers le bas
                const messagesContainer = document.getElementById('messages-container');
                if (messagesContainer) {
                    scrollToBottom(messagesContainer);
                }
                
                // Notification sonore (si activée)
                playMessageSound();
            }
            
            // Gérer le statut "en train d'écrire" (simulation)
            if (data.typing) {
                const typingIndicator = document.querySelector('.typing-indicator');
                if (typingIndicator && typingIndicator.classList.contains('d-none')) {
                    typingIndicator.classList.remove('d-none');
                }
            } else {
                const typingIndicator = document.querySelector('.typing-indicator');
                if (typingIndicator && !typingIndicator.classList.contains('d-none')) {
                    typingIndicator.classList.add('animate-fadeOut');
                    setTimeout(() => {
                        typingIndicator.classList.remove('animate-fadeOut');
                        typingIndicator.classList.add('d-none');
                    }, 300);
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification des nouveaux messages:', error);
        });
}

/**
 * Ajouter de nouveaux messages à la conversation
 * @param {Array} messages - Liste des nouveaux messages
 */
function appendNewMessages(messages) {
    const container = document.getElementById('messages-container');
    const currentUserId = getCurrentUserId();
    
    messages.forEach(message => {
        const isCurrentUser = parseInt(message.sender_id) === currentUserId;
        const messageElement = document.createElement('div');
        messageElement.className = `message mb-3 message-wrapper ${isCurrentUser ? 'message-sent' : 'message-received'} animate-fadeIn`;
        messageElement.setAttribute('data-id', message.id);
        
        messageElement.innerHTML = `
            <div class="message-bubble ${isCurrentUser ? 'message-sent' : 'message-received'}">
                <div class="message-content">
                    ${formatMessageContent(message.content)}
                </div>
                <div class="message-info mt-1">
                    <small class="${isCurrentUser ? 'text-white-50' : 'text-muted'}">
                        ${formatDate(message.sent_at || message.created_at)}
                    </small>
                </div>
            </div>
        `;
        
        container.appendChild(messageElement);
        
        // Animation d'entrée
        setTimeout(() => {
            messageElement.classList.add('message-visible');
        }, 100);
    });
}

/**
 * Formater le contenu d'un message
 * @param {string} content - Contenu du message
 * @returns {string} - Contenu formaté avec liens cliquables et emojis
 */
function formatMessageContent(content) {
    if (!content) return '';
    
    // Échapper le HTML
    let formattedContent = content
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    
    // Convertir les sauts de ligne en balises <br>
    formattedContent = formattedContent.replace(/\n/g, '<br>');
    
    // Rendre les liens cliquables
    formattedContent = formattedContent.replace(
        /(https?:\/\/[^\s]+)/g, 
        '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-decoration-underline">$1</a>'
    );
    
    // Support basique des emojis
    const emojiMap = {
        ':)': '😊', 
        ':D': '😃', 
        ':(': '😞', 
        ';)': '😉',
        ':p': '😛',
        '<3': '❤️'
    };
    
    for (const [shortcut, emoji] of Object.entries(emojiMap)) {
        formattedContent = formattedContent.replace(
            new RegExp(escapeRegExp(shortcut), 'g'), 
            emoji
        );
    }
    
    return formattedContent;
}

/**
 * Récupérer l'ID de l'utilisateur actuel
 * Recherche dans plusieurs sources possibles
 * @returns {number|null} - ID de l'utilisateur ou null si non trouvé
 */
function getCurrentUserId() {
    // Option 1: Attribut data-user-id dans le body
    if (document.body.hasAttribute('data-user-id')) {
        return parseInt(document.body.getAttribute('data-user-id'));
    }
    
    // Option 2: Variable JavaScript globale
    if (typeof USER_ID !== 'undefined') {
        return parseInt(USER_ID);
    }
    
    // Option 3: Element caché dans la page
    const userIdInput = document.querySelector('input[name="user_id"], input#user_id');
    if (userIdInput) {
        return parseInt(userIdInput.value);
    }
    
    // Option 4: Meta tag
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (userIdMeta) {
        return parseInt(userIdMeta.getAttribute('content'));
    }
    
    return null; // Aucun ID utilisateur trouvé
}

/**
 * Formater une date pour l'affichage
 * @param {string} dateString - Date au format ISO
 * @returns {string} - Date formatée
 */
function formatDate(dateString) {
    if (!dateString) return '';
    
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '';
        
        // Vérifier si c'est aujourd'hui
        const today = new Date();
        const isToday = date.getDate() === today.getDate() &&
                      date.getMonth() === today.getMonth() &&
                      date.getFullYear() === today.getFullYear();
        
        if (isToday) {
            // Format: Aujourd'hui, HH:MM
            return `Aujourd'hui, ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
        } else {
            // Format: JJ/MM/AAAA HH:MM
            return `${date.toLocaleDateString()} ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
        }
    } catch (error) {
        console.error('Erreur de formatage de date:', error);
        return '';
    }
}

/**
 * Échapper les caractères spéciaux pour les expressions régulières
 * @param {string} string - Chaîne à échapper
 * @returns {string} - Chaîne échappée
 */
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

/**
 * Faire défiler jusqu'au dernier message
 * @param {HTMLElement} container - Conteneur de messages
 * @param {boolean} smooth - Utiliser un défilement fluide
 */
function scrollToBottom(container, smooth = true) {
    if (!container) return;
    
    // Vérifier si l'utilisateur était déjà en bas
    const wasAtBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 50;
    
    // Si l'utilisateur était en bas ou si c'est forcé, faire défiler
    if (wasAtBottom) {
        setTimeout(() => {
            container.scrollTo({
                top: container.scrollHeight,
                behavior: smooth ? 'smooth' : 'auto'
            });
        }, 100);
    }
}

/**
 * Jouer un son de notification de message
 * Ne joue que si l'utilisateur a activé les sons
 */
function playMessageSound() {
    // Vérifier si l'utilisateur a activé les sons
    const soundsEnabled = localStorage.getItem('message_sounds_enabled') !== 'false';
    
    if (soundsEnabled) {
        // Créer ou récupérer l'élément audio
        let audioElement = document.getElementById('message-sound');
        
        if (!audioElement) {
            audioElement = document.createElement('audio');
            audioElement.id = 'message-sound';
            audioElement.src = '/assets/sounds/message.mp3'; // Ajuster le chemin selon votre structure
            audioElement.volume = 0.5;
            document.body.appendChild(audioElement);
        }
        
        // Jouer le son
        audioElement.currentTime = 0;
        audioElement.play().catch(error => {
            console.warn('Impossible de jouer le son de notification:', error);
        });
    }
}

/**
 * Initialisation du système de notifications
 */
function initializeNotifications() {
    
    const notificationDropdown = document.querySelector('.notification-dropdown');
    const notificationLink = document.querySelector('.notification-link');
    const notificationBadge = document.querySelector('.notification-badge');
    
    if (notificationLink && notificationDropdown) {
        // Marquer les notifications comme lues lors de l'ouverture du dropdown
        notificationLink.addEventListener('click', function(e) {
            if (notificationBadge && !notificationBadge.classList.contains('d-none')) {
                // Requête AJAX pour marquer comme lu
                fetch('/?page=ajax&action=markAllNotificationsAsRead', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Animation pour masquer le badge
                        notificationBadge.classList.add('animate-fadeOut');
                        setTimeout(() => {
                            notificationBadge.classList.add('d-none');
                            notificationBadge.classList.remove('animate-fadeOut');
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du marquage des notifications comme lues:', error);
                });
            }
        });
        
        // Charger les notifications via AJAX
        loadNotifications();
        
        // Rafraîchir les notifications toutes les 2 minutes
        setInterval(loadNotifications, 2 * 60 * 1000);
    }
}

/**
 * Charge les notifications via AJAX
 */
function loadNotifications() {
    const userId = document.body.dataset.userId;
    
    // Si l'utilisateur n'est pas connecté, on ne fait rien
    if (!userId || userId === "0") return;
    
    const notificationLink = document.querySelector('.notification-link');
    if (!notificationLink) return;
    
    // Effectuer la requête AJAX - CORRECTION DE L'URL
    fetch('/dev_site/?page=ajax&action=loadNotifications', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            console.error('Erreur:', data.error);
            return;
        }
        
        // Mettre à jour la liste des notifications dans le dropdown
        const notificationsList = document.querySelector('.notifications-list');
        const notificationBadge = document.querySelector('.notification-badge');
        
        if (notificationsList && data.notifications) {
            // Vider la liste actuelle
            notificationsList.innerHTML = '';
            
            // Mettre à jour avec les nouvelles notifications
            updateNotificationsList(data, notificationsList, notificationBadge);
        }
        
        // Mise à jour du badge - Vérifier que notificationBadge existe
        if (notificationBadge && data.unread !== undefined) {
            if (data.unread > 0) {
                notificationBadge.textContent = data.unread > 9 ? '9+' : data.unread;
                notificationBadge.classList.remove('d-none');
            } else {
                notificationBadge.classList.add('d-none');
            }
        }
    })
    .catch(error => {
        console.error('Erreur de chargement des notifications:', error);
    });
}

/**
 * Mettre à jour la liste des notifications
 * @param {Object} data - Données des notifications
 * @param {HTMLElement} notificationsList - Élément DOM de la liste
 * @param {HTMLElement} notificationBadge - Badge de notification (peut être null)
 */
function updateNotificationsList(data, notificationsList, notificationBadge) {
    if (data.notifications && data.notifications.length > 0) {
        // Ajouter les notifications avec animation
        data.notifications.forEach((notification, index) => {
            const notificationItem = document.createElement('a');
            notificationItem.className = `dropdown-item notification-item ${notification.read ? '' : 'unread'} animate-fadeIn`;
            notificationItem.style.animationDelay = `${index * 0.1}s`;
            notificationItem.href = notification.url || '#';
            
            const content = `
                <div class="d-flex align-items-center">
                    <div class="notification-icon ${notification.icon_class || ''}">
                        <i class="fas fa-${notification.icon || 'bell'}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-text">${notification.message}</div>
                        <div class="notification-time small text-muted">${notification.time_ago || ''}</div>
                    </div>
                </div>
            `;
            
            notificationItem.innerHTML = content;
            notificationsList.appendChild(notificationItem);
        });
        
        // Mettre à jour le compteur si le badge existe
        if (notificationBadge) {
            const unreadCount = data.notifications.filter(n => !n.read).length;
            
            if (unreadCount > 0) {
                notificationBadge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                notificationBadge.classList.remove('d-none');
                
                // Animation du badge
                notificationBadge.classList.add('animate-pulse');
                setTimeout(() => {
                    notificationBadge.classList.remove('animate-pulse');
                }, 1000);
            } else {
                notificationBadge.classList.add('d-none');
            }
        }
    } else {
        // Aucune notification
        const emptyItem = document.createElement('div');
        emptyItem.className = 'dropdown-item text-center text-muted py-3 empty-notification animate-fadeIn';
        emptyItem.textContent = 'Aucune notification';
        notificationsList.appendChild(emptyItem);
        
        // Masquer le badge s'il existe
        if (notificationBadge) {
            notificationBadge.classList.add('d-none');
        }
    }
}

/**
 * Initialisation des animations au défilement
 */
function initializeAnimations() {
    // Animation des éléments au scroll
    setupScrollAnimations();
    
    // Animation des listes avec effet séquentiel
    setupStaggeredLists();
    
    // Animation de la navigation au scroll
    setupNavbarScroll();
    
    // Animation des effets parallaxe
    setupParallaxEffects();
    
    // Animation des compteurs
    setupCounterAnimations();
}

/**
 * Configuration des animations au défilement
 */
function setupScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    // Observer pour détecter quand un élément entre dans la vue
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                // Désinscrire l'élément une fois animé
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1, // Déclencher quand 10% de l'élément est visible
        rootMargin: '0px 0px -50px 0px' // Déclencher un peu avant que l'élément soit complètement visible
    });
    
    // Observer chaque élément animé
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

/**
 * Configuration des animations de listes avec effet séquentiel
 */
function setupStaggeredLists() {
    const staggeredLists = document.querySelectorAll('.staggered-list');
    
    // Observer pour détecter quand une liste entre dans la vue
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                // Désinscrire la liste une fois animée
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Observer chaque liste
    staggeredLists.forEach(list => {
        observer.observe(list);
    });
}


/**
 * Configuration de l'animation de la barre de navigation au défilement
 */
function setupNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        // Ajouter la classe au chargement si déjà scrollé
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        }
        
        // Ajouter/supprimer la classe au scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
}

/**
 * Configuration des effets parallaxe
 */
function setupParallaxEffects() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    if (parallaxElements.length > 0) {
        // Vérifier si l'appareil est mobile (désactiver pour performances)
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (!isMobile) {
            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset;
                
                parallaxElements.forEach(element => {
                    const speed = element.getAttribute('data-parallax-speed') || 0.5;
                    const offset = scrollTop * speed;
                    element.style.transform = `translateY(${offset}px)`;
                });
            });
        } else {
            // Désactiver l'effet sur mobile en supprimant la classe
            parallaxElements.forEach(element => {
                element.classList.remove('parallax');
            });
        }
    }
}

/**
 * Configuration des animations de compteurs
 */
function setupCounterAnimations() {
    const counters = document.querySelectorAll('.counter');
    
    if (counters.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-count'));
                    const duration = parseInt(counter.getAttribute('data-duration')) || 2000;
                    let start = 0;
                    const step = timestamp => {
                        if (!start) start = timestamp;
                        const progress = Math.min((timestamp - start) / duration, 1);
                        const current = Math.floor(progress * target);
                        counter.textContent = current.toLocaleString();
                        if (progress < 1) {
                            window.requestAnimationFrame(step);
                        } else {
                            counter.textContent = target.toLocaleString();
                        }
                    };
                    window.requestAnimationFrame(step);
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    }
}


/**
 * Initialisation des optimisations pour les appareils mobiles
 */
function initializeMobileOptimizations() {
    // Détecter si l'appareil est mobile
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    if (isMobile) {
        // Ajouter une classe au body
        document.body.classList.add('is-mobile-device');
        
        // Optimiser le menu mobile
        setupMobileMenu();
        
        // Convertir les tableaux en cartes pour mobile
        setupResponsiveTables();
        
        // Améliorer les interactions tactiles
        enhanceTouchInteractions();
    }
    
    // Gérer le sidebar du dashboard sur mobile
    setupMobileDashboardSidebar();
}

/**
 * Configuration du menu pour appareils mobiles
 */
function setupMobileMenu() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        // Empêcher la propagation des clics dans le menu
        navbarCollapse.addEventListener('click', e => {
            // Ne fermer que si on clique sur un lien
            if (e.target.classList.contains('nav-link')) {
                // Animation de fermeture
                navbarCollapse.classList.remove('show');
                
                // Réinitialiser l'icône du hamburger
                const hamburger = document.querySelector('.hamburger');
                if (hamburger) {
                    hamburger.classList.remove('is-active');
                }
            }
        });
        
        // Animer l'ouverture/fermeture du menu
        navbarToggler.addEventListener('click', () => {
            // Ajouter une animation de transition
            if (navbarCollapse.classList.contains('show')) {
                navbarCollapse.classList.add('animate-fadeOut');
                setTimeout(() => {
                    navbarCollapse.classList.remove('animate-fadeOut');
                }, 300);
            } else {
                navbarCollapse.classList.add('animate-fadeIn');
                setTimeout(() => {
                    navbarCollapse.classList.remove('animate-fadeIn');
                }, 300);
            }
        });
    }
}

/**
 * Configuration des tableaux responsifs
 * Transforme les tableaux en cartes sur mobile
 */
function setupResponsiveTables() {
    const tables = document.querySelectorAll('.table-responsive-card');
    
    tables.forEach(table => {
        // Si ce n'est pas déjà un tableau responsive card
        if (table.tagName === 'TABLE') {
            const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent.trim());
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                
                cells.forEach((cell, index) => {
                    // Ajouter l'attribut data-label pour l'affichage mobile
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index]);
                    }
                });
            });
        }
    });
}

/**
 * Amélioration des interactions tactiles sur mobile
 */
function enhanceTouchInteractions() {
    // Augmenter la taille des zones cliquables
    const interactiveElements = document.querySelectorAll('a, button, .nav-link, .dropdown-item');
    
    interactiveElements.forEach(element => {
        // Ne modifier que si c'est un petit élément
        if (element.offsetHeight < 44) {
            element.style.minHeight = '44px';
            element.style.display = 'flex';
            element.style.alignItems = 'center';
        }
    });
    
    // Ajouter un feedback visuel tactile
    document.body.addEventListener('touchstart', e => {
        if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || 
            e.target.classList.contains('nav-link') || 
            e.target.classList.contains('dropdown-item')) {
            
            e.target.classList.add('touch-active');
        }
    }, { passive: true });
    
    document.body.addEventListener('touchend', e => {
        const activeElements = document.querySelectorAll('.touch-active');
        activeElements.forEach(element => {
            element.classList.remove('touch-active');
        });
    }, { passive: true });
}

/**
 * Configuration du sidebar du dashboard pour mobile
 */
function setupMobileDashboardSidebar() {
    const sidebar = document.querySelector('.dashboard-sidebar');
    const content = document.querySelector('.dashboard-content');
    const toggleButton = document.querySelector('.sidebar-toggle');
    
    if (sidebar && content) {
        // Créer un overlay pour la fermeture sur mobile
        const overlay = document.createElement('div');
        overlay.className = 'dashboard-sidebar-overlay';
        document.body.appendChild(overlay);
        
        // Fonction pour gérer le toggle du sidebar
        const toggleSidebar = () => {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
            
            // Gérer l'overlay sur mobile
            if (window.innerWidth < 992) {
                if (sidebar.classList.contains('collapsed')) {
                    overlay.classList.remove('active');
                } else {
                    overlay.classList.add('active');
                }
            }
        };
        
        // Ajouter l'écouteur d'événement au bouton de toggle
        if (toggleButton) {
            toggleButton.addEventListener('click', toggleSidebar);
        }
        
        // Fermer le sidebar au clic sur l'overlay
        overlay.addEventListener('click', () => {
            if (!sidebar.classList.contains('collapsed')) {
                toggleSidebar();
            }
        });
        
        // Fermer automatiquement le sidebar sur les petits écrans au chargement
        if (window.innerWidth < 992 && !sidebar.classList.contains('collapsed')) {
            setTimeout(() => {
                toggleSidebar();
            }, 300);
        }
        
        // Gérer le redimensionnement de la fenêtre
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                overlay.classList.remove('active');
            } else if (!sidebar.classList.contains('collapsed')) {
                overlay.classList.add('active');
            }
        });
    }
}


/**
 * Setup les boutons de la wishlists
 * @returns {void}
 */
function setupWishlistButtons() {
    const wishlistButtons = document.querySelectorAll('.wishlist-button');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', () => {
            const itemId = button.getAttribute('data-item-id');
            const action = button.classList.contains('added') ? 'remove' : 'add';
            
            fetch(`/wishlist/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ item_id: itemId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.toggle('added');
                    button.textContent = button.classList.contains('added') ? 'Retirer de la wishlist' : 'Ajouter à la wishlist';
                    
                    // Afficher une notification
                    showToast(
                        data.success ? 'success' : 'error',
                        data.message,
                        2000
                    );
                }
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour de la wishlist:', error);
            });
        });
    });
}

/**
 * Afficher une notification toast
 * @param {string} type - Type de notification (success, error, info)
 * @param {string} message - Message à afficher
 * @param {number} duration - Durée d'affichage en millisecondes
 */
function showToast(type, message, duration) {
    const toastContainer = document.querySelector('.toast-container');
    
    if (!toastContainer) {
        const newToastContainer = document.createElement('div');
        newToastContainer.className = 'toast-container';
        document.body.appendChild(newToastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    document.querySelector('.toast-container').appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, duration);
}


// Exporter les fonctions pour les tests ou l'utilisation externe
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        setupNavbarScroll,
        setupParallaxEffects,
        setupCounterAnimations,
        initializeMobileOptimizations,
        setupMobileMenu,
        setupResponsiveTables,
        enhanceTouchInteractions,
        setupMobileDashboardSidebar
    };
}