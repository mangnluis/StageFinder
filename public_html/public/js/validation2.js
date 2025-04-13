/**
 * Valide un champ individuel
 * @param {HTMLElement} field - Le champ à valider
 * @return {boolean} - True si le champ est valide, false sinon
 */
function validateField(field) {
    // Vérifier si le champ est vide
    if (field.hasAttribute('required') && !field.value.trim()) {
      if (!field.classList.contains('is-invalid')) {
        addError(field, 'Ce champ est requis');
      }
      return false;
    }
  
    // Vérifier la longueur minimale
    if (field.hasAttribute('minlength') && field.value.length < parseInt(field.getAttribute('minlength'))) {
      const minLength = field.getAttribute('minlength');
      if (!field.classList.contains('is-invalid')) {
        addError(field, `Ce champ doit contenir au moins ${minLength} caractères`);
      }
      return false;
    }
  
    // Vérifier la longueur maximale
    if (field.hasAttribute('maxlength') && field.value.length > parseInt(field.getAttribute('maxlength'))) {
      const maxLength = field.getAttribute('maxlength');
      if (!field.classList.contains('is-invalid')) {
        addError(field, `Ce champ ne doit pas dépasser ${maxLength} caractères`);
      }
      return false;
    }
  
    // Vérifier les patterns
    if (field.hasAttribute('pattern') && field.value) {
      const pattern = new RegExp(field.getAttribute('pattern'));
      if (!pattern.test(field.value)) {
        if (!field.classList.contains('is-invalid')) {
          addError(field, field.getAttribute('data-error-pattern') || 'Format invalide');
        }
        return false;
      }
    }
  
    // Le champ est valide
    removeError(field);
    return true;
  }
  