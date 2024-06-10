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
$sql_td = "SELECT numero_TD FROM Groupe GROUP BY numero_TD";
$stmt_td = $pdo->query($sql_td);

$tds = [];
if ($stmt_td->rowCount() > 0) {
    while ($row = $stmt_td->fetch()) {
        $tds[] = $row;
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
        foreach ($tds as $td) {
            echo "
            <div class='card' data-popup-id='td-popup' data-td-number='{$td['numero_TD']}'>
                <div class='card-text' id='card-text'>
                    <p>TD{$td['numero_TD']}</p>
                </div>
                <div class='card-icons'>
                    <button class='card-btn'><b>ajouter</b></button>
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
        const detailButtons = document.querySelectorAll('[data-popup-id="detail_prof"]');

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

        const closeButtons = document.querySelectorAll('.popup-close');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                button.closest('.popup').style.display = 'none';
            });
        });

        window.onclick = function(event) {
            if (event.target.classList.contains('popup')) {
                event.target.style.display = 'none';
            }
        };

        const message = document.querySelector('.message');
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
</script>
</body>
</html>
<?php $pdo = null; ?>
