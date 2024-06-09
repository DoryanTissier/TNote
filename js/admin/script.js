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
            window.location.href = `edit_student.php?id=${id}`;
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
});


document.querySelectorAll('.student-row').forEach(row => {
    row.addEventListener('click', function() {
        const studentId = this.getAttribute('data-student-id');
        fetchStudentDetails(studentId);
    });
});

function fetchStudentDetails(studentId) {
    fetch(`get_student_details.php?id=${studentId}`)
        .then(response => response.json())
        .then(data => {
            // Logique pour afficher les détails de l'étudiant
            displayStudentDetails(data);
        })
        .catch(error => console.error('Erreur:', error));
}

function displayStudentDetails(data) {
    const detailsDiv = document.getElementById('student-details');
    detailsDiv.innerHTML = `
        <h3>Détails de l'étudiant</h3>
        <p>Nom: ${data.nom}</p>
        <p>Prénom: ${data.prenom}</p>
        <p>Évaluations:</p>
        <ul>
            ${data.evaluations.map(eval => `
                <li>${eval.type}: ${eval.note} (Coefficient: ${eval.coefficient})</li>
            `).join('')}
        </ul>
    `;
    detailsDiv.style.display = 'block';
}

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.stopPropagation(); // Empêche le clic de se propager
        const studentId = this.getAttribute('data-student-id');
        openEditPopup(studentId);
    });
});

function openEditPopup(studentId) {
    const popup = window.open(`edit_student.php?id=${studentId}`, 'popup', 'width=600,height=400');
    if (popup) {
        popup.focus();
    } else {
        alert('Veuillez autoriser les popups pour ce site.');
    }
}

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.stopPropagation(); // Empêche le clic de se propager
        const studentId = this.getAttribute('data-student-id');
        const confirmation = confirm("Voulez-vous vraiment supprimer cet étudiant ?");
        if (confirmation) {
            deleteStudent(studentId);
        }
    });
});

function deleteStudent(studentId) {
    fetch('delete_student.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${studentId}`
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('success')) {
            document.querySelector(`.student-row[data-student-id="${studentId}"]`).remove();
        } else {
            alert('Erreur lors de la suppression de l'étudiant.');
        }
    })
    .catch(error => console.error('Erreur:', error));
}


document.getElementById('delete-td-btn').addEventListener('click', function() {
    const confirmation = confirm("Voulez-vous vraiment supprimer ce TD ?");
    if (confirmation) {
        deleteTD();
    }
});

function deleteTD() {
    fetch('delete_td.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `td_id=1` // Vous pouvez ajuster cette valeur selon la manière dont vous identifiez le TD
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('success')) {
            alert('TD supprimé avec succès.');
            window.location.href = 'page_admin.php'; // Redirection vers la page admin
        } else {
            alert('Erreur lors de la suppression du TD.');
        }
    })
    .catch(error => console.error('Erreur:', error));
}
