<?php
session_start();
include '../../PHP/Page_login/db_connect.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['profile_type'] !== 'professeur') {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de profile</title>
    <link rel="stylesheet" href="../../css/profil/etudiant_page_profile.css">
</head>
<body>

    <div class="header">
      <div class="dlogo">
          <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
      </div>
      <div class="navbar">
          <a class="value" >Note</a>
          <a class="value" >Profile</a>
          <a href="../../PHP/Page_login/logout.php" class="value" style="text-decoration: none;">Déconnexion</a>
      </div>
    </div>
    
    <div class="fp" id="connex">
        <div div class="formulaire-co">
            <!-- DEBUT formulaire de profil -->
            <form class="pos">

                <!-- DEBUT formulaire -->
                <div class="id-mdp">
                    <input type="text" id="nom" name="nom" placeholder=" " required>
                    <span>
                        nom
                    </span>
                </div>

                <div class="id-mdp">
                    <input type="text" id="prenom" name="premom" placeholder=" " required>
                    <span>
                        prenom
                    </span>
                </div>

                <div class="id-mdp">
                    <input type="text" id="identifiant" name="identifiant" placeholder=" " required>
                    <span>
                        identifiant
                    </span>
                </div>

                <div class="id-mdp">
                    <input type="text" id="tp" name="tp" placeholder=" " required>
                    <span>
                        TP
                    </span>
                </div>

                <div class="id-mdp">
                    <input type="text" id="td" name="td" placeholder=" " required>
                    <span>
                        TD
                    </span>
                </div>

                <div class="id-mdp">
                    <input type="text" id="annee_univ" name="annee_univ" placeholder=" " required>
                    <span>
                        annee universitaire
                    </span>
                </div>

                <div class="id-mdp">
                    <input type="" id="mdp" name="password" placeholder=" " required>
                    <span>
                        Mot de passe
                    </span>
                </div>
                <!-- FIN formulaire -->

                <!-- DEBUT button modifier -->
                <button type="submit">
                    Modifier
                    <div class="arrow-wrapper">
                        <div class="arrow"></div>

                    </div>
                </button>
                <!-- FIN button modifier -->

            </form>
            <!-- FIN formulaire de profil -->

        </div>
    </div>
    
</body>
</html>