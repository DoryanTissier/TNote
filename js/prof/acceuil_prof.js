class Popup {
    constructor() {
        this.addGlobalEventListeners();
        this.addCardEventListeners();
        this.addSelectAllEventListeners(); // Ajout de la méthode pour les checkboxes de sélection globale
    }

    addGlobalEventListeners() {
        // Fermer la popup en cliquant en dehors ou sur le bouton de fermeture
        window.addEventListener('click', (event) => {
            if (event.target.classList.contains('popup')) {
                this.closePopup(event.target);
            } else if (event.target.classList.contains('popup-close')) {
                this.closePopup(event.target.closest('.popup'));
            }
        });
    }

    addCardEventListeners() {
        // Ouvrir la popup en cliquant sur le texte de la carte ou sur le bouton
        document.addEventListener('click', (event) => {
            const target = event.target.closest('.card-text, .card-btn');
            if (target) {
                const popupId = target.dataset.popupId;
                if (popupId) {
                    this.openPopup(popupId);
                    // Ajoutez le code pour mettre à jour les menus déroulants ici
                    var resourceId = popupId.split('-').pop(); // Obtenez l'ID à partir de l'ID du popup
                    var ressourceSelect = document.querySelector('#ressources-' + resourceId);
                    var saeSelect = document.querySelector('#saes-' + resourceId);
                    if (ressourceSelect) {
                        ressourceSelect.value = resourceId;
                    }
                    if (saeSelect) {
                        saeSelect.value = resourceId;
                    }
                }
            }
        });
    }

    addSelectAllEventListeners() {
        // Sélectionner tous les étudiants par TD
        document.querySelectorAll('.select-all-td').forEach(tdCheckbox => {
            tdCheckbox.addEventListener('change', function() {
                const td = this.dataset.td;
                document.querySelectorAll(`.presence-checkbox[data-td="${td}"]`).forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });

        // Sélectionner tous les étudiants par TP
        document.querySelectorAll('.select-all-tp').forEach(tpCheckbox => {
            tpCheckbox.addEventListener('change', function() {
                const tp = this.dataset.tp;
                const td = this.dataset.td;
                document.querySelectorAll(`.presence-checkbox[data-tp="${tp}"][data-td="${td}"]`).forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });
    }

    openPopup(popupId) {
        const popup = document.getElementById(popupId);
        if (popup) {
            popup.style.display = 'block';
            setTimeout(() => popup.classList.add('open'), 10);
            this.addSelectAllEventListeners(); // Assurez-vous d'ajouter les écouteurs d'événements après l'ouverture de la popup
        }
    }

    closePopup(popup) {
        if (popup) {
            popup.classList.remove('open');
            setTimeout(() => popup.style.display = 'none', 300);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Popup();
});
