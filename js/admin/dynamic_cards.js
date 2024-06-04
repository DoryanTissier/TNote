document.addEventListener('DOMContentLoaded', (event) => {
    const cardContainer = document.getElementById('card-container');
    
    // Supposons que fetchCardsData() est une fonction qui récupère les données de la base de données.
    // Cette fonction peut être une requête API ou une fonction qui retourne un tableau d'objets.
    function fetchCardsData() {
        return [
            { id: 'td1', name: 'TD1' },
            { id: 'td2', name: 'TD2' },
            // Ajoutez ici d'autres objets pour chaque TD
        ];
    }

    function createCard(tdData) {
        // Création de la carte
        const card = document.createElement('div');
        card.className = 'card';
        card.setAttribute('data-popup-id', `${tdData.id}-popup`);
        
        // Texte de la carte
        const cardText = document.createElement('div');
        cardText.className = 'card-text';
        cardText.innerHTML = `<p>${tdData.name}</p>`;
        
        // Boutons de la carte
        const cardIcons = document.createElement('div');
        cardIcons.className = 'card-icons';
        const cardBtn = document.createElement('button');
        cardBtn.className = 'card-btn';
        cardBtn.setAttribute('data-popup-id', `aj-${tdData.id}-popup`);
        cardBtn.innerHTML = '<b>ajouter</b>';
        
        cardIcons.appendChild(cardBtn);
        card.appendChild(cardText);
        card.appendChild(cardIcons);
        
        // Ajout de la carte au conteneur
        cardContainer.appendChild(card);

        // Création des popups associés
        createPopup(tdData.id, tdData.name);
        createPopup(`aj-${tdData.id}`, `ajouter ${tdData.name}`);
    }

    function createPopup(id, text) {
        const popup = document.createElement('div');
        popup.id = `${id}-popup`;
        popup.className = 'popup';

        const popupContent = document.createElement('div');
        popupContent.className = 'popup-content';
        
        const closeBtn = document.createElement('span');
        closeBtn.className = 'popup-close';
        closeBtn.innerHTML = '&times;';
        
        const img = document.createElement('img');
        img.src = '../../source/img/logo/logo-nom.png';
        img.width = 200;
        img.alt = '';

        const p = document.createElement('p');
        p.textContent = text;

        popupContent.appendChild(closeBtn);
        popupContent.appendChild(img);
        popupContent.appendChild(p);
        popup.appendChild(popupContent);

        document.body.appendChild(popup);
    }

    // Récupération des données et génération des cartes
    const cardsData = fetchCardsData();
    cardsData.forEach(tdData => {
        createCard(tdData);
    });

    // Code de gestion des popups (déjà fourni dans card.js)
    const { openPopup, closePopup } = (() => {
        function openPopup(popupId) {
            const popup = document.getElementById(popupId);
            if (popup) {
                popup.style.display = 'block';
                setTimeout(() => {
                    popup.classList.add('open');
                }, 10); // Permet de déclencher la transition
            }
        }

        function closePopup(popup) {
            popup.classList.remove('open');
            setTimeout(() => {
                popup.style.display = 'none';
            }, 300); // Durée de la transition définie dans CSS
        }

        return { openPopup, closePopup };
    })();

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
