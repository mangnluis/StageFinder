/**
 * Script principal du site StageFinder
 * Gère les fonctionnalités JavaScript communes à toutes les pages
 */

document.addEventListener('DOMContentLoaded', function() {
  // ===== GESTION DES ALERTES =====
  // Auto-masquer les alertes après 5 secondes
  const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
  alerts.forEach(function(alert) {
    setTimeout(function() {
      if (alert) {
        // Ajouter une classe de fondu avant de masquer
        alert.classList.add('fade');
        
        // Attendre la fin de l'animation puis masquer
        setTimeout(function() {
          alert.style.display = 'none';
        }, 500);
      }
    }, 5000);
  });
  
  // ===== CONFIRMATION AVANT SUPPRESSION =====
  // Afficher une confirmation pour toutes les actions de suppression
  const deleteLinks = document.querySelectorAll('a[href*="action=delete"], a[href*="action=remove"]');
  deleteLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
      if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });
  
  // ===== ANIMATION DES CARTES =====
  // Ajouter des effets de survol sur les cartes
  const cards = document.querySelectorAll('.card');
  cards.forEach(function(card) {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-5px)';
      this.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.1)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.08)';
    });
  });
  
  // ===== INITIALISATION DES TOOLTIPS =====
  // Si vous utilisez Bootstrap, initialiser les tooltips
  if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(function(tooltip) {
      new bootstrap.Tooltip(tooltip);
    });
  }
  
  // ===== TOGGLE VUES GRILLE/LISTE =====
  initViewToggle();
  
  // ===== VALIDATION DES FORMULAIRES AVEC ONGLETS =====
  initTabFormValidation();
  
  // ===== VALIDATION DE DATES =====
  initDateValidation();
  
  // ===== AUTO-REMPLISSAGE D'ADRESSE =====
  initLocationAutofill();
});

// JavaScript pour le toggle entre vue grille et liste
function initViewToggle() {
const gridViewBtn = document.getElementById('gridViewBtn');
const listViewBtn = document.getElementById('listViewBtn');
const gridView = document.getElementById('gridView');
const listView = document.getElementById('listView');

if (gridViewBtn && listViewBtn && gridView && listView) {
  // Charger la préférence de vue depuis localStorage
  const viewPreference = localStorage.getItem('internshipsViewPreference');
  if (viewPreference === 'list') {
      gridView.style.display = 'none';
      listView.style.display = 'block';
      gridViewBtn.classList.remove('active');
      listViewBtn.classList.add('active');
  }
  
  // Gestionnaires d'événements pour les boutons
  gridViewBtn.addEventListener('click', function() {
      gridView.style.display = 'flex';
      listView.style.display = 'none';
      listViewBtn.classList.remove('active');
      gridViewBtn.classList.add('active');
      localStorage.setItem('internshipsViewPreference', 'grid');
  });
  
  listViewBtn.addEventListener('click', function() {
      gridView.style.display = 'none';
      listView.style.display = 'block';
      gridViewBtn.classList.remove('active');
      listViewBtn.classList.add('active');
      localStorage.setItem('internshipsViewPreference', 'list');
  });
}
}

// Validation des formulaires avec onglets
function initTabFormValidation() {
const formWithTabs = document.querySelector('form[data-validate="true"][id="companyForm"]');

if (formWithTabs) {
  formWithTabs.addEventListener('submit', function(event) {
    let isValid = true;
    const invalidTabs = [];
    
    // Vérifier tous les champs requis, même dans les onglets cachés
    const requiredFields = formWithTabs.querySelectorAll('[required]');
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        field.classList.add('is-invalid');
        isValid = false;
        
        // Trouver l'onglet parent
        const tabPane = field.closest('.tab-pane');
        if (tabPane && !invalidTabs.includes(tabPane.id)) {
          invalidTabs.push(tabPane.id);
        }
      } else {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
      }
    });
    
    if (!isValid) {
      event.preventDefault();
      
      // Marquer visuellement les onglets contenant des erreurs
      invalidTabs.forEach(tabId => {
        const tabButton = document.querySelector(`[data-bs-target="#${tabId}"]`);
        if (tabButton) {
          tabButton.classList.add('text-danger');
          // Ajouter un indicateur visuel
          if (!tabButton.querySelector('.error-indicator')) {
            const indicator = document.createElement('span');
            indicator.className = 'error-indicator ms-1 badge rounded-pill bg-danger';
            indicator.textContent = '!';
            tabButton.appendChild(indicator);
          }
        }
      });
      
      // Afficher une alerte
      alert('Veuillez remplir tous les champs obligatoires.');
      
      // Activer le premier onglet contenant une erreur
      if (invalidTabs.length > 0) {
        const firstInvalidTabButton = document.querySelector(`[data-bs-target="#${invalidTabs[0]}"]`);
        if (firstInvalidTabButton) {
          const tab = new bootstrap.Tab(firstInvalidTabButton);
          tab.show();
        }
      }
    }
  });
  
  // Nettoyer les indicateurs d'erreur quand l'utilisateur corrige
  const allInputs = formWithTabs.querySelectorAll('input, select, textarea');
  allInputs.forEach(input => {
    input.addEventListener('input', function() {
      if (this.hasAttribute('required')) {
        if (this.value.trim()) {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
          
          // Vérifier si tous les champs requis de l'onglet sont valides
          const tabPane = this.closest('.tab-pane');
          if (tabPane) {
            const invalidFields = tabPane.querySelectorAll('.is-invalid[required]');
            if (invalidFields.length === 0) {
              // Supprimer l'indicateur d'erreur de l'onglet
              const tabButton = document.querySelector(`[data-bs-target="#${tabPane.id}"]`);
              if (tabButton) {
                tabButton.classList.remove('text-danger');
                const indicator = tabButton.querySelector('.error-indicator');
                if (indicator) {
                  indicator.remove();
                }
              }
            }
          }
        }
      }
    });
  });
}
}

// Validation des dates (pour stage)
function initDateValidation() {
const startDateInput = document.getElementById('start_date');
const endDateInput = document.getElementById('end_date');

if (startDateInput && endDateInput) {
  endDateInput.addEventListener('change', function() {
    if (startDateInput.value && endDateInput.value) {
      const startDate = new Date(startDateInput.value);
      const endDate = new Date(endDateInput.value);
      
      if (endDate < startDate) {
        endDateInput.setCustomValidity('La date de fin doit être postérieure à la date de début');
      } else {
        endDateInput.setCustomValidity('');
      }
    }
  });
  
  startDateInput.addEventListener('change', function() {
    if (endDateInput.value) {
      endDateInput.dispatchEvent(new Event('change'));
    }
  });
}
}

// Auto-remplissage d'adresse en fonction de l'entreprise
function initLocationAutofill() {
const companySelect = document.getElementById('company_id');
const locationInput = document.getElementById('location');

if (companySelect && locationInput) {
  companySelect.addEventListener('change', function() {
    const companyId = this.value;
    if (companyId && !locationInput.value) {
      fetch(`/api/companies/${companyId}/location`)
        .then(response => response.json())
        .then(data => {
          if (data.location) {
            locationInput.value = data.location;
          }
        })
        .catch(error => console.error('Erreur lors de la récupération de l\'adresse:', error));
    }
  });
}
}