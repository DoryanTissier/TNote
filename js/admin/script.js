document.addEventListener('DOMContentLoaded', () => {
    const mainRows = document.querySelectorAll('.main-row');
    
    mainRows.forEach(mainRow => {
        const id = mainRow.getAttribute('data-id');
        const detailRow = document.querySelector(`.detail-row[data-id="${id}"]`);

        mainRow.addEventListener('click', () => {
            if (detailRow) {
                detailRow.classList.toggle('hidden');
                detailRow.style.display = detailRow.classList.contains('hidden') ? 'none' : 'table-row';
            }
        });

        const editButton = mainRow.querySelector('.edit');
        editButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Pour empêcher l'événement de clic de se propager
            openEditPopup(id);
        });

        const deleteButton = mainRow.querySelector('.delete');
        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Pour empêcher l'événement de clic de se propager
            const confirmation = confirm("Voulez-vous vraiment supprimer cet étudiant ?");
            if (confirmation) {
                // Envoi de la requête AJAX pour supprimer l'entrée de la base de données
                fetch('delete_student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Response:', data); // Ajoutez ceci pour déboguer
                    if (data.includes('success')) {
                        mainRow.remove();
                        if (detailRow) {
                            detailRow.remove();
                        }
                    } else {
                        alert('Erreur lors de la suppression de l\'étudiant.');
                        console.error('Error:', data); // Ajoutez ceci pour voir l'erreur complète
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            }
        });
    });

    // Fonction pour ouvrir le popup d'édition
    function openEditPopup(studentId) {
        fetch(`edit_student.php?id=${studentId}`)
            .then(response => response.text())
            .then(data => {
                const editPopup = document.createElement('div');
                editPopup.classList.add('popup');
                editPopup.innerHTML = `
                    <div class="popup-content">
                        <span class="popup-close">&times;</span>
                        <div class="popup-body">${data}</div>
                    </div>
                `;
                document.body.appendChild(editPopup);

                // Fermer le popup lorsque le bouton de fermeture est cliqué
                editPopup.querySelector('.popup-close').addEventListener('click', () => {
                    document.body.removeChild(editPopup);
                });

                // Fermer le popup lorsque l'extérieur du contenu du popup est cliqué
                window.addEventListener('click', (event) => {
                    if (event.target === editPopup) {
                        document.body.removeChild(editPopup);
                    }
                });
            })
            .catch(error => console.error('Erreur:', error));
    }
});
