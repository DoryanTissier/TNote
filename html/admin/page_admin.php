<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['profile_type'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}

include '../../PHP/Page_login/db_connect.php'; // Inclusion du fichier de connexion à la base de données
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les informations des TDs
$sql_td = "SELECT numero_TD, lettre_TP FROM Groupe GROUP BY numero_TD, lettre_TP";
$stmt_td = $pdo->query($sql_td);

$tds = [];
$tds_tp = [];
if ($stmt_td->rowCount() > 0) {
    while ($row = $stmt_td->fetch()) {
        $tds[$row['numero_TD']][] = $row['lettre_TP'];
        if (!in_array($row['numero_TD'], $tds_tp)) {
            $tds_tp[$row['numero_TD']] = [];
        }
        $tds_tp[$row['numero_TD']][] = $row['lettre_TP'];
    }
}

// Récupérer les informations des professeurs
$sql_prof = "SELECT pp.ID_prof, pp.nom, pp.prenom, r.nom AS ressource
             FROM Profil_prof pp
             LEFT JOIN Liaison_ressources_prof lrp ON pp.ID_prof = lrp.id_prof
             LEFT JOIN Ressources r ON lrp.num_ressource = r.num_ressource";
$stmt_prof = $pdo->query($sql_prof);

$professeurs = [];
if ($stmt_prof->rowCount() > 0) {
    while ($row = $stmt_prof->fetch()) {
        if (!isset($professeurs[$row['ID_prof']])) {
            $professeurs[$row['ID_prof']] = [
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'ressources' => []
            ];
        }
        if ($row['ressource']) {
            $professeurs[$row['ID_prof']]['ressources'][] = $row['ressource'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu</title>
    <link rel="stylesheet" href="../../css/admin/admin_header.css">
    <link rel="stylesheet" href="../../css/admin/admin_card.css">
    <link rel="stylesheet" href="../../css/admin/admin.css">
    <link rel="stylesheet" href="../../css/admin/admin_aj_td_prof_form.css">
    <style>
        .message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            opacity: 1;
            transition: opacity 1s ease-out;
        }
        .message.error {
            background-color: #f44336;
        }
        .message.hide {
            opacity: 0;
        }
        .popup-content iframe {
            width: 100%;
            height: 600px;
            border: none;
        }
    </style>
</head>
<body>

<?php
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    echo "<div class='message $message_type'>$message</div>";
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<!-- Menu -->
<div class="header">
    <div class="dlogo">
        <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
    </div>
    <div class="navbar">
        <button onclick="window.location.href='page_admin.php'" class="value">Note</button>
        <button onclick="window.location.href='../page_profile/page_profile_admin.php'" class="value" id="rouge">Profile</button>
        <button onclick="window.location.href='../../PHP/Page_login/logout.php'" class="value" id="rouge">Déconnexion</button>
    </div>
</div>
<!-- FIN menu -->

<div class="block">

<!-- Conteneur pour aligner les cartes des TD -->
<h1>MMI1<button class="btn_exp"><b>EXPORTER</b></button></h1>
<div class="card-container">
    <?php
    if (!empty($tds)) {
        foreach ($tds as $numero_TD => $lettres_TP) {
            echo "
            <div class='card' data-popup-id='td-popup' data-td-number='{$numero_TD}'>
                <div class='card-text' id='card-text'>
                    <p>TD{$numero_TD}</p>
                </div>
                <div class='card-icons'>
                    <button class='card-btn' data-popup-id='aj_etudiant' data-td-number='{$numero_TD}' data-tp-letters='".json_encode($lettres_TP)."'><b>ajouter</b></button>
                    <button class='card-btn' onclick='supprimerTD({$numero_TD})'><b>supprimer</b></button>
                </div>
            </div>";
        }
    } else {
        echo "<p>Aucun TD trouvé</p>";
    }
    ?>

    <!-- ajouter -->
    <div class="aj" data-popup-id="aj-td-popup">
        <img class="plus" src="../../source/img/icon/blanc/ajouter-b.png" alt="ajouter un TD">
    </div>
</div>
<br>
<!-- FIN conteneur pour aligner les cartes des TD -->

<!-- Containers des popups ajout étudiant -->
<div id="aj_etudiant" class="popup">
    <div class="popup-content">
        <span class="popup-close">&times;</span>
        <?php include 'aj_etudiant.php'; ?>
    </div>
</div>
<!-- FIN containers des popups ajout étudiant -->


<!-- Conteneur pour aligner les cartes des professeur(e)s -->
<h1>Professeur(e)s</h1>
<div class="card-container">
    <?php
    if (!empty($professeurs)) {
        foreach ($professeurs as $id_prof => $prof) {
            $ressources = implode(", ", $prof['ressources']);
            echo "
            <div class='card'>
                <div class='card-text'>
                    <p>{$ressources}<br><b class='nom'>{$prof['nom']} {$prof['prenom']}</b></p>
                </div>
                <div class='card-icons'>
                    <button class='card-btn' data-popup-id='detail_prof' data-prof-id='{$id_prof}'><b>détails</b></button>
                </div>
            </div>";
        }
    } else {
        echo "<p>Aucun professeur trouvé</p>";
    }
    ?>

    <!-- ajouter -->
    <div class="aj" data-popup-id="aj-prof-popup">
        <img class="plus" src="../../source/img/icon/blanc/ajouter-b.png" alt="ajouter un professeur">
    </div>
</div>
<!-- FIN conteneur pour aligner les cartes des professeur(e)s -->

</div>

<!-- Containers des popups TD -->
<div id="td-popup" class="popup">
    <div class="popup-content">
        <span class="popup-close">&times;</span>
        <div id="td-popup-content"></div> <!-- Conteneur pour charger dynamiquement le contenu de td1.php -->
    </div>
</div>

<!-- Containers des popups ajout TD -->
<div id="aj-td-popup" class="popup">
    <div class="popup-content">
        <span class="popup-close">&times;</span>
        <?php include 'aj_td.php'; ?>
    </div>
</div>
<!-- FIN containers des popups ajout TD -->

<!-- Containers des popups details prof -->
<div id="detail_prof" class="popup">
    <div class="popup-content">
        <span class="popup-close">&times;</span>
        <div id="popup-content-detail-prof"></div> <!-- Conteneur pour charger dynamiquement les détails du professeur -->
    </div>
</div>
<!-- Fin containers des details popups prof -->

<!-- Containers des popups ajout prof -->
<div class="popup" id="aj-prof-popup">
    <div class="popup-content">
        <span class="popup-close">&times;</span>
        <?php include 'aj_prof.php'; ?>
    </div>
</div>
<!-- FIN containers des popups ajout prof -->

<script src="../../js/admin/card.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cardButtons = document.querySelectorAll('.card');
        const addButtons = document.querySelectorAll('[data-popup-id="aj_etudiant"]');
        const detailButtons = document.querySelectorAll('[data-popup-id="detail_prof"]');
        const closeButtons = document.querySelectorAll('.popup-close');
        const message = document.querySelector('.message');

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

        addButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tdNumber = button.getAttribute('data-td-number');
                const tpLetters = JSON.parse(button.getAttribute('data-tp-letters'));
                
                document.getElementById('td').value = tdNumber;
                updateLabel('td'); // Mise à jour du label si nécessaire

                const tpSelect = document.getElementById('tp');
                tpSelect.innerHTML = ''; // Clear previous options

                tpLetters.forEach(letter => {
                    const option = document.createElement('option');
                    option.value = letter;
                    option.textContent = letter;
                    tpSelect.appendChild(option);
                });

                document.getElementById('aj_etudiant').style.display = 'block';
            });
        });

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const profId = button.getAttribute('data-prof-id');
                fetch(`edit_prof.php?id=${profId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('popup-content-detail-prof').innerHTML = data;
                        document.getElementById('detail_prof').style.display = 'block';
                    });
            });
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const popup = button.closest('.popup');
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

        if (message) {
            setTimeout(() => {
                message.classList.add('hide');
            }, 2000); // 2 seconds

            // Remove the message from the DOM after the transition ends
            message.addEventListener('transitionend', () => {
                message.remove();
            });
        }
    });

    function closePopup(popup) {
        popup.classList.remove('open');
        setTimeout(() => popup.style.display = 'none', 300);
    }

    function supprimerTD(tdNumber) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce TD et toutes les données associées ?')) {
            window.location.href = `supprimer_td.php?td_number=${tdNumber}`;
        }
    }

    function redirectToPage(tdNumber) {
        // Change the URL as needed
        window.location.href = 'aj_etudiant.php?td=' + tdNumber;
    }

    function updateLabel(id) {
        const input = document.getElementById(id);
        if (input.value.trim() !== "") {
            input.classList.add('not-empty');
        } else {
            input.classList.remove('not-empty');
        }
    }

    // Fonction pour initialiser les toggles des détails après l'insertion de contenu via AJAX
    function initializeDetailToggle() {
        const mainRows = document.querySelectorAll('.main-row');
        mainRows.forEach(row => {
            row.addEventListener('click', function() {
                const detailRow = document.querySelector(`.detail-row[data-id='${row.getAttribute('data-id')}']`);
                if (detailRow.style.display === 'none' || detailRow.style.display === '') {
                    detailRow.style.display = 'table-row';
                } else {
                    detailRow.style.display = 'none';
                }
            });
        });
    }

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
</script>
</body>
</html>
<?php $pdo = null; ?>
