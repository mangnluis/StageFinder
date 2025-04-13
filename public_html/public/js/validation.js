/**
 * Système de validation de formulaires pour StageFinder
 * Fonctionnalités avancées de validation côté client
 */

document.addEventListener('DOMContentLoaded', () => {
  const forms = document.querySelectorAll('form[data-validate="true"]');
  
  forms.forEach(form => {
      // Add novalidate attribute to all forms with data-validate
      form.setAttribute('novalidate', '');
      
      form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
          }
          
          // Check for password confirmation fields
          const password = form.querySelector('input[name="password"]');
          const passwordConfirm = form.querySelector('input[name="password_confirm"]');
          
          if (password && passwordConfirm && password.value !== passwordConfirm.value) {
              event.preventDefault();
              event.stopPropagation();
              
              // Create or update password confirm error message
              let errorMessage = passwordConfirm.nextElementSibling;
              if (!errorMessage || !errorMessage.classList.contains('invalid-feedback')) {
                  errorMessage = document.createElement('div');
                  errorMessage.classList.add('invalid-feedback');
                  passwordConfirm.parentNode.appendChild(errorMessage);
              }
              
              errorMessage.textContent = 'Les mots de passe ne correspondent pas';
              passwordConfirm.classList.add('is-invalid');
          }
          
          // Check for date validation (internship form)
          if (form.id === 'internshipForm') {
              const startDate = form.querySelector('#start_date');
              const endDate = form.querySelector('#end_date');
              
              if (startDate && endDate && startDate.value && endDate.value) {
                  const start = new Date(startDate.value);
                  const end = new Date(endDate.value);
                  
                  if (end < start) {
                      event.preventDefault();
                      event.stopPropagation();
                      
                      // Create error message
                      let errorMessage = endDate.nextElementSibling;
                      if (!errorMessage || !errorMessage.classList.contains('invalid-feedback')) {
                          errorMessage = document.createElement('div');
                          errorMessage.classList.add('invalid-feedback');
                          endDate.parentNode.appendChild(errorMessage);
                      }
                      
                      errorMessage.textContent = 'La date de fin doit être postérieure à la date de début';
                      endDate.classList.add('is-invalid');
                  }
              }
          }
          
          form.classList.add('was-validated');
      }, false);
      
      // Live validation for required fields
      const requiredFields = form.querySelectorAll('[required]');
      requiredFields.forEach(field => {
          field.addEventListener('blur', () => {
              if (!field.validity.valid) {
                  field.classList.add('is-invalid');
              } else {
                  field.classList.remove('is-invalid');
                  field.classList.add('is-valid');
              }
          });
          
          field.addEventListener('input', () => {
              if (field.validity.valid) {
                  field.classList.remove('is-invalid');
                  field.classList.add('is-valid');
              }
          });
      });
      
      // Validate email fields
      const emailFields = form.querySelectorAll('input[type="email"]');
      emailFields.forEach(field => {
          field.addEventListener('blur', () => {
              if (field.value && !validateEmail(field.value)) {
                  field.classList.add('is-invalid');
                  let errorMessage = field.nextElementSibling;
                  if (!errorMessage || !errorMessage.classList.contains('invalid-feedback')) {
                      errorMessage = document.createElement('div');
                      errorMessage.classList.add('invalid-feedback');
                      field.parentNode.appendChild(errorMessage);
                  }
                  errorMessage.textContent = 'Veuillez entrer une adresse email valide';
              }
          });
      });
      
      // Password strength check
      const passwordFields = form.querySelectorAll('input[type="password"]:not([name="password_confirm"])');
      passwordFields.forEach(field => {
          // Create password strength indicator if data-strength attribute exists
          const strengthIndicator = field.getAttribute('data-strength');
          if (strengthIndicator) {
              const indicatorElement = document.getElementById(strengthIndicator);
              if (indicatorElement) {
                  field.addEventListener('input', () => {
                      const strength = checkPasswordStrength(field.value);
                      updatePasswordStrengthIndicator(indicatorElement, strength);
                  });
              }
          }
      });
      
      // Input masks for specific field types
      const phoneFields = form.querySelectorAll('input[type="tel"]');
      phoneFields.forEach(field => {
          field.addEventListener('input', function() {
              // Format phone numbers (French format)
              let value = this.value.replace(/\D/g, ''); // Remove non-digit characters
              if (value.length > 0) {
                  // Format as XX XX XX XX XX
                  value = value.match(/.{1,2}/g).join(' ');
              }
              this.value = value.substring(0, 14); // Limit to 10 digits + spaces
          });
      });
      
      // Number input validation (prevent negative values)
      const numberFields = form.querySelectorAll('input[type="number"]');
      numberFields.forEach(field => {
          const min = field.getAttribute('min');
          if (min !== null) {
              field.addEventListener('input', function() {
                  if (parseFloat(this.value) < parseFloat(min)) {
                      this.value = min;
                  }
              });
          }
      });
  });
  
  // File size validation
  const fileInputs = document.querySelectorAll('input[type="file"]');
  fileInputs.forEach(input => {
      const maxSize = input.getAttribute('data-max-size');
      if (maxSize) {
          input.addEventListener('change', function() {
              if (this.files && this.files[0]) {
                  const fileSize = this.files[0].size / 1024 / 1024; // Size in MB
                  if (fileSize > parseFloat(maxSize)) {
                      alert(`Le fichier est trop volumineux. Taille maximale autorisée: ${maxSize} Mo.`);
                      this.value = ''; // Reset the input
                  }
              }
          });
      }
  });
});

 /**
   * Valide une adresse email
   * @param {string} email - L'adresse email à valider
   * @return {boolean} - True si l'email est valide, false sinon
   */
 function validateEmail(email) {
    // Vérification basique que l'email contient @ et au moins un point après le @
    if (!email) return false;
    
    const hasAtSign = email.indexOf('@') > 0;
    const hasDotAfterAt = email.indexOf('.', email.indexOf('@')) > email.indexOf('@');
    
    if (!hasAtSign || !hasDotAfterAt) {
      return false;
    }
    
    // Utilisation d'une regex simplifiée pour vérifier le format général
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
  }

// Password strength checker
function checkPasswordStrength(password) {
  let strength = 0;
  
  // Length check
  if (password.length >= 8) strength += 25;
  
  // Complexity checks
  if (password.match(/[a-z]+/)) strength += 25;
  if (password.match(/[A-Z]+/)) strength += 25;
  if (password.match(/[0-9]+/)) strength += 25;
  if (password.match(/[^a-zA-Z0-9]+/)) strength += 25;
  
  return Math.min(100, strength);
}

// Update password strength UI
function updatePasswordStrengthIndicator(element, strength) {
  // Update progress bar
  const progressBar = element.querySelector('.progress-bar');
  if (progressBar) {
      progressBar.style.width = `${strength}%`;
      
      // Update color based on strength
      progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');
      
      if (strength < 25) {
          progressBar.classList.add('bg-danger');
      } else if (strength < 50) {
          progressBar.classList.add('bg-warning');
      } else if (strength < 75) {
          progressBar.classList.add('bg-info');
      } else {
          progressBar.classList.add('bg-success');
      }
  }
  
  // Update text if it exists
  const textElement = element.querySelector('.strength-text');
  if (textElement) {
      let text = 'Très faible';
      let textClass = 'text-danger';
      
      if (strength < 25) {
          text = 'Très faible';
          textClass = 'text-danger';
      } else if (strength < 50) {
          text = 'Faible';
          textClass = 'text-warning';
      } else if (strength < 75) {
          text = 'Moyen';
          textClass = 'text-info';
      } else {
          text = 'Fort';
          textClass = 'text-success';
      }
      
      textElement.textContent = text;
      textElement.className = `strength-text ${textClass}`;
  }
}

// Fonction spécifique pour la validation des offres de stage
function validateInternshipForm() {
    const form = document.getElementById('internshipForm');
    if (!form) return;
    
    // Validation personnalisée pour la description
    const descriptionField = document.getElementById('description');
    if (descriptionField) {
        descriptionField.addEventListener('blur', function() {
            if (this.value.length < 50) {
                this.classList.add('is-invalid');
                let errorMessage = this.nextElementSibling;
                if (!errorMessage || !errorMessage.classList.contains('invalid-feedback')) {
                    errorMessage = document.createElement('div');
                    errorMessage.classList.add('invalid-feedback');
                    this.parentNode.appendChild(errorMessage);
                }
                errorMessage.textContent = 'La description doit contenir au moins 50 caractères.';
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }
    
    // S'assurer qu'au moins une compétence est sélectionnée
    form.addEventListener('submit', function(event) {
        const selectedSkills = form.querySelectorAll('input[name="skills[]"]:checked');
        if (selectedSkills.length === 0) {
            event.preventDefault();
            alert('Veuillez sélectionner au moins une compétence requise.');
            
            // Mettre en évidence la section des compétences
            const skillsSection = document.querySelector('.mb-4:has(input[name="skills[]"])');
            if (skillsSection) {
                skillsSection.classList.add('border', 'border-danger', 'p-3', 'rounded');
            }
        }
    });
}

// Initialiser les validations spécifiques au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    validateInternshipForm();
});