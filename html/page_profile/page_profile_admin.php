<?php
session_start();
include '../../PHP/Page_login/db_connect.php';

// Vérifie si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['user_id']) || $_SESSION['profile_type'] !== 'admin') {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté ou n'est pas admin
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}

$id_admin = $_SESSION['user_id'];

// Requête pour récupérer toutes les informations du profil de l'admin
$sql_profil = "SELECT nom, prenom, mot_de_passe, CONCAT(nom, '.', prenom) AS identifiant FROM Profil_admin WHERE ID_Admin = ?";
$stmt_profil = $pdo->prepare($sql_profil);
$stmt_profil->execute([$id_admin]);
$profil = $stmt_profil->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de profil</title>
    <link rel="stylesheet" href="../../css/admin/admin_header.css">
    <link rel="stylesheet" href="../../css/admin/admin_aj_td_prof_form.css">
</head>
<body>
<div class="body-aj-td">

        <div class="header">
            <div class="dlogo">
                <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
            </div>
            <div class="navbar">
                <a href="../admin/page_admin.php" class="value" style="text-decoration: none;">Note</a>
                <a class="value" >Profile</a>
                <a href="../../PHP/Page_login/logout.php" class="value" style="text-decoration: none;">Déconnexion</a>
            </div>
        </div>

            <!-- Formulaire -->
        <div class="block-modif-admin">
            <h1>PROFIL - ADMIN</h1>
            <form class="form-aj-td" action="edit_mdp.php" method="post">
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="nom" name="nom" required value="<?= $profil['nom'] ?>" readonly>
                    <span>Nom</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="prenom" name="prenom" required value="<?= $profil['prenom'] ?>" readonly>
                    <span>Prénom</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="identifiant" name="identifiant" required value="<?= $profil['identifiant'] ?>" readonly>
                    <span>Identifiant</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="password" id="password" name="password" required>
                    <span>Nouveau mot de passe</span>
                </div>
                <div class="btn-effacer-ajouter">
                    <button type="submit" class="aj-td-btn-ajouter"><b>MODIFIER</b></button>
                </div>
            </form>
        </div>
</div>  
    <script>
        // Vérifie si le paramètre GET 'error' est présent dans l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');

        if (error === 'same_password') {
            // Affiche une notification popup rouge
            alert('Le nouveau mot de passe est identique à l\'ancien !');
        }
    </script>
</body>
</html>
