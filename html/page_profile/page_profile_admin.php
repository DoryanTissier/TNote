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
    <link rel="stylesheet" href="../../css/profil/admin_page_profile.css">
</head>
<body>
    <div class="header">
      <div class="dlogo">
          <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
      </div>
      <div class="navbar">
          <a href="../prof/acceuil_prof.php" class="value" style="text-decoration: none;">Note</a>
          <a class="value" >Profile</a>
          <a href="../../PHP/Page_login/logout.php" class="value" style="text-decoration: none;">Déconnexion</a>
      </div>
    </div>
    
<!-- Formulaire -->
    <div class="content">
    <div class="text">Formulaire de profil</div>
    <form class="pos" action="edit_mdp.php" method="post">

        <div class="field">
            <span class="span">Nom</span>
            <input type="text" id="nom" name="nom" class="input" placeholder=" " required value="<?= $profil['nom'] ?>" readonly>
        </div>

        <div class="field">
            <span class="span">Prénom</span>
            <input type="text" id="prenom" name="prenom" class="input" placeholder=" " required value="<?= $profil['prenom'] ?>" readonly>
        </div>

        <div class="field">
            <span class="span">Identifiant</span>
            <input type="text" id="identifiant" name="identifiant" class="input" placeholder=" " required value="<?= $profil['identifiant'] ?>" readonly>
        </div>

        <div class="field">
            <span class="span">Nouveau mot de passe</span>
            <input type="password" id="password" name="password" class="input" placeholder=" " required>
        </div>

        <!-- Bouton modifier -->
        <button type="submit" formaction="edit_mdp.php">Modifier<div class="arrow-wrapper"><div class="arrow"></div></div></button>
    </form>
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