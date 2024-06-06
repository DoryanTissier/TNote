class Popup {
    constructor() {
        this.addGlobalEventListeners();
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

        // Ouvrir la popup en cliquant sur le texte de la carte ou sur le bouton
        document.addEventListener('click', (event) => {
            const target = event.target.closest('.card-text, .card-btn');
            if (target) {
                const popupId = target.dataset.popupId;
                if (popupId) {
                    this.openPopup(popupId);
                }
            }
        });
    }

    openPopup(popupId) {
        const popup = document.getElementById(popupId);
        if (popup) {
            popup.style.display = 'block';
            setTimeout(() => popup.classList.add('open'), 10);
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