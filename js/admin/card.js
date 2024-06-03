document.addEventListener('DOMContentLoaded', (event) => {
    // Fonction pour ouvrir un popup
    function openPopup(popupId) {
        const popup = document.getElementById(popupId);
        if (popup) {
            popup.style.display = 'block';
            setTimeout(() => {
                popup.classList.add('open');
            }, 10); // Permet de déclencher la transition
        }
    }

    // Fonction pour fermer un popup
    function closePopup(popup) {
        popup.classList.remove('open');
        setTimeout(() => {
            popup.style.display = 'none';
        }, 300); // Durée de la transition définie dans CSS
    }

    // Ajout des événements pour ouvrir les popups via les cartes
    document.querySelectorAll('.card').forEach(element => {
        element.addEventListener('click', (event) => {
            const popupId = event.currentTarget.getAttribute('data-popup-id');
            openPopup(popupId);
        });
    });

    // Ajout des événements pour ouvrir les popups via les boutons
    document.querySelectorAll('.card-btn').forEach(element => {
        element.addEventListener('click', (event) => {
            event.stopPropagation(); // Empêche l'événement de se propager à la carte parente
            const popupId = event.currentTarget.getAttribute('data-popup-id');
            openPopup(popupId);
        });
    });

    // Ajout des événements pour fermer les popups
    document.querySelectorAll('.popup-close').forEach(closeBtn => {
        closeBtn.addEventListener('click', (event) => {
            const popup = closeBtn.closest('.popup');
            closePopup(popup);
        });
    });

    // Fermer le popup si on clique en dehors du contenu
    window.addEventListener('click', (event) => {
        document.querySelectorAll('.popup').forEach(popup => {
            if (event.target === popup) {
                closePopup(popup);
            }
        });
    });
});

