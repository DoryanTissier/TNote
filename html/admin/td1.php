<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
$td_number = isset($_GET['td_number']) ? (int)$_GET['td_number'] : 1; // Remplacez par le numéro approprié pour chaque fichier (1, 2 ou 3)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TD <?php echo $td_number; ?> - Student Table</title>
    <link rel="stylesheet" href="../../css/admin/styles.css">
</head>
<body>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="success-message" id="success-message">
            VOUS AVEZ MODIFIÉ ID : <?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'N/A'; ?>
        </div>
    <?php endif; ?>
    <h1 class="titre_td_list">TD <?php echo $td_number; ?> - Liste des étudiants</h1>

    <table class="main-table">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>TD</th>
                <th>TP</th>
                <th>Année</th>
                <th>Moyenne</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT pe.ID_Etudiants, pe.nom, pe.prenom, g.numero_TD, g.lettre_TP, pe.annee_univ, AVG(n.note) AS moyenne
                    FROM Profil_etudiant pe
                    JOIN Groupe g ON pe.ID_groupe = g.ID_groupe
                    LEFT JOIN Note n ON pe.ID_Etudiants = n.ID_Etudiants
                    WHERE g.numero_TD = ?
                    GROUP BY pe.ID_Etudiants";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$td_number]);
            $result = $stmt->fetchAll();

            if (count($result) > 0) {
                foreach ($result as $row) {
                    $moyenne = is_null($row['moyenne']) ? 'N/A' : number_format($row['moyenne'], 2);
                    echo "<tr>
                            <td>{$row['ID_Etudiants']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['prenom']}</td>
                            <td>{$row['numero_TD']}</td>
                            <td>{$row['lettre_TP']}</td>
                            <td>{$row['annee_univ']}</td>
                            <td>{$moyenne}</td>
                            <td class='actions'>
                                <button class='edit' data-id='{$row['ID_Etudiants']}'>Edit</button>
                                <button class='delete' data-id='{$row['ID_Etudiants']}'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Aucun étudiant trouvé</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Popup pour edit_student.php -->
    <div id="edit-popup" class="popup">
        <div class="popup-content">
            <span class="popup-close">&times;</span>
            <div id="edit-popup-content"></div> <!-- Conteneur pour charger dynamiquement le contenu de edit_student.php -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.add('hide');
                }, 2000); // Ajoute la classe 'hide' après 2 secondes
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 2500); // Cache le message après la transition
            }

            // Fonction pour attacher les événements après chargement du contenu dynamique
            function attachEventHandlers() {
                // Initialiser les boutons "edit"
                document.querySelectorAll('.edit').forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation(); // Empêche le déclenchement de l'événement click sur la ligne principale
                        const studentId = this.getAttribute('data-id');
                        fetch(`edit_student.php?id=${studentId}`)
                            .then(response => response.text())
                            .then(data => {
                                const popupContent = document.getElementById('edit-popup-content');
                                if (popupContent) {
                                    popupContent.innerHTML = data;
                                    document.getElementById('edit-popup').style.display = 'flex';
                                }
                            });
                    });
                });

                // Initialiser les boutons "delete"
                document.querySelectorAll('.delete').forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation(); // Empêche le déclenchement de l'événement click sur la ligne principale
                        const studentId = this.getAttribute('data-id');
                        if (confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')) {
                            fetch(`delete_student.php?id=${studentId}`, { method: 'POST' })
                                .then(response => response.text())
                                .then(data => {
                                    if (data.trim() === 'success') {
                                        location.reload(); // Recharger la page après la suppression
                                    } else {
                                        alert('Erreur lors de la suppression : ' + data);
                                    }
                                });
                        }
                    });
                });
            }

            // Appel initial pour attacher les événements
            attachEventHandlers();

            // Attacher les événements après chargement du contenu dynamique
            const cardButtons = document.querySelectorAll('.card');
            cardButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tdNumber = button.getAttribute('data-td-number');
                    fetch(`td1.php?td_number=${tdNumber}`)
                        .then(response => response.text())
                        .then(data => {
                            const popupContent = document.getElementById('td-popup-content');
                            if (popupContent) {
                                popupContent.innerHTML = data;
                                document.getElementById('td-popup').style.display = 'block';
                                // Attacher les événements après l'insertion de contenu
                                attachEventHandlers();
                            }
                        });
                });
            });

            // Initialiser les fermetures de popup
            document.querySelectorAll('.popup-close').forEach(button => {
                button.addEventListener('click', function() {
                    const popup = this.closest('.popup');
                    closePopup(popup);
                });
            });

            window.addEventListener('click', (event) => {
                document.querySelectorAll('.popup').forEach(popup => {
                    if (event.target === popup) {
                        closePopup(popup);
                    }
                });
            });

            function closePopup(popup) {
                popup.classList.remove('open');
                setTimeout(() => popup.style.display = 'none', 300);
            }
        });
    </script>
</body>
</html>
