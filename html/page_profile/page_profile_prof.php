<?php
session_start();
include '../../PHP/Page_login/db_connect.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['profile_type'] !== 'professeur') {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}

$id_prof = $_SESSION['user_id'];

// Requête pour récupérer les SAE liés au professeur connecté
$sql_sae = "SELECT SAE.nom_SAE FROM SAE INNER JOIN Liaison_SAE_prof ON SAE.nom_SAE = Liaison_SAE_prof.nom_SAE WHERE Liaison_SAE_prof.id_prof = ?";
$stmt_sae = $pdo->prepare($sql_sae);
$stmt_sae->execute([$id_prof]);
$sae_list = $stmt_sae->fetchAll(PDO::FETCH_COLUMN);

// Requête pour récupérer toutes les informations du profil du professeur
$sql_profil = "SELECT Profil_prof.nom, Profil_prof.prenom, GROUP_CONCAT(Ressources.nom) AS matieres, CONCAT(Profil_prof.nom, '.', Profil_prof.prenom) AS identifiant, Profil_prof.mot_de_passe 
                FROM Profil_prof 
                INNER JOIN Liaison_ressources_prof ON Profil_prof.ID_prof = Liaison_ressources_prof.id_prof 
                INNER JOIN Ressources ON Liaison_ressources_prof.num_ressource = Ressources.num_ressource 
                WHERE Profil_prof.ID_prof = ?";
$stmt_profil = $pdo->prepare($sql_profil);
$stmt_profil->execute([$id_prof]);
$profil = $stmt_profil->fetch(PDO::FETCH_ASSOC);

// Convertir la liste de matières en un tableau
$matieres_list = explode(',', $profil['matieres']);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de profil</title>
    <link rel="stylesheet" href="../../css/profil/prof_page_profile.css">
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

        <!-- Liste des matières -->
        <div class="field">
            <span class="span">Matière(s)</span>
            <ul>
                <?php foreach ($matieres_list as $matiere) : ?>
                    <li><?= $matiere ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Liste des SAEs -->
        <div class="field">
            <span class="span">SAE</span>
            <ul>
                <?php foreach ($sae_list as $sae) : ?>
                    <li><?= $sae ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="field">
            <span class="span">Identifiant</span>
            <input type="text" id="identifiant" name="identifiant" class="input" placeholder=" " required value="<?= $profil['identifiant'] ?>" readonly>
        </div>

        <div class="field">
            <span class="span">Mot de passe</span>
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