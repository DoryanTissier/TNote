<?php
session_start();
include 'db_connect.php';

$error = null;

// Vérifie si l'utilisateur est déjà connecté, auquel cas redirige-le vers la page correspondante au profil
if (isset($_SESSION['profile_type'])) {
    switch ($_SESSION['profile_type']) {
        case 'etudiant':
            header("Location: ../../html/etudiant/acceuil_etudiant.php");
            exit();
        case 'professeur':
            header("Location: ../../html/prof/acceuil_prof.php");
            exit();
        case 'admin':
            header("Location: ../../html/admin/page_admin.php");
            exit();
    }
}

// Vérifier si un profil a été choisi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['type-p'])) {
        $error = "Veuillez sélectionner un type de profil.";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $profile_type = $_POST['type-p'];

        // Vérifier le type de profil et la table associée
        switch ($profile_type) {
            case 'etudiant':
                $table = 'Profil_etudiant';
                $id_column = 'ID_Etudiants';
                break;
            case 'professeur':
                $table = 'Profil_prof';
                $id_column = 'ID_prof';
                break;
            case 'admin':
                $table = 'Profil_admin';
                $id_column = 'ID_Admin';
                break;
            default:
                $table = null;
        }

        if ($table) {
            // Extraction du nom et du prénom depuis l'identifiant
            $name_parts = explode('.', $username);
            if (count($name_parts) != 2 || empty($name_parts[0]) || empty($name_parts[1])) {
                $error = "Format d'identifiant invalide. Utilisez le format 'nom.prenom'.";
            } else {
                $nom = $name_parts[0];
                $prenom = $name_parts[1];

                // Préparation de la requête SQL
                $sql = "SELECT $id_column, mot_de_Passe FROM $table WHERE nom = :nom AND prenom = :prenom";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['nom' => $nom, 'prenom' => $prenom]);

                // Vérification du mot de passe
                $user = $stmt->fetch();

                if ($user) {
                    if ($password === $user['mot_de_Passe']) {
                        // Stocker les informations de session
                        $_SESSION['user_id'] = $user[$id_column];
                        $_SESSION['profile_type'] = $profile_type;

                        // Redirection vers la page appropriée
                        switch ($profile_type) {
                            case 'etudiant':
                                header("Location: ../../html/etudiant/acceuil_etudiant.php");
                                break;
                            case 'professeur':
                                header("Location: ../../html/prof/acceuil_prof.php");
                                break;
                            case 'admin':
                                header("Location: ../../html/admin/page_admin.php");
                                break;
                        }
                        exit();
                    } else {
                        $error = "Mot de passe incorrect.";
                    }
                } else {
                    $error = "Identifiant ou mot de passe incorrect.";
                }
            }
        } else {
            $error = "Type de profil invalide.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../css/connexion/connexion.css">
</head>
<body>
    <div class="fp" id="connex">
        <div class="formulaire-co">
            <div class="dlogo">
                <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
            </div>
            <form class="pos" method="post">
                <div class="type-p">
                    <label class="choix">
                        Choix du profil :
                    </label>
                    <div class="profile-images">
                        <label>
                            <input type="radio" name="type-p" value="etudiant">
                            <img class="choix-p" src="../../source/img/profile/e1.png" alt="Étudiant">
                            <span class="message">Étudiant(e)</span>
                        </label>
                        <label>
                            <input type="radio" name="type-p" value="professeur">
                            <img class="choix-p" src="../../source/img/profile/p1.png" alt="Professeur">
                            <span class="message">Professeur(e)</span>
                        </label>
                        <label>
                            <input type="radio" name="type-p" value="admin">
                            <img class="choix-p" src="../../source/img/profile/admin.png" alt="admin">
                            <span class="message">Admin</span>
                        </label>
                    </div>
                </div>
                <div class="id-mdp">
                    <input type="text" id="identifiant" name="username" placeholder=" " required>
                    <span>
                        Identifiant
                    </span>
                </div>
                <div class="id-mdp">
                    <input type="password" id="mdp" name="password" placeholder=" " required>
                    <span>
                        Mot de passe
                    </span>
                </div>
                <button type="submit">
                    Connexion
                    <div class="arrow-wrapper">
                        <div class="arrow"></div>
                    </div>
                </button>
            </form>
        </div>
        <?php if (isset($error)): ?>
                    <div class="notification show" id="errorNotification">
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                        <span class="close-btn" onclick="closeNotification()">&times;</span>
                    </div>
        <?php endif; ?>
    <!-- DEBUT rectangle 2 -->
    <div class="block-logo">
        <img src="../../source/img/logo/uge.png" alt="logo universite gustave eiffel" height="50px">
        <img src="../../source/img/logo/x.png" alt="" height="50px">
        <img src="../../source/img/logo/logo-nom.png" alt="logo Tnote" height="50px">
    </div>
    <!-- FIN rectangle 2 -->
    </div>

    <script src="../../js/connexion/connexion.js"></script>
</body>
</html>