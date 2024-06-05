<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $profile_type = $_POST['type-p'];

    // Vérification du type de profil et de la table associée
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
        list($nom, $prenom) = explode('.', $username);

        // Préparation de la requête SQL
        $sql = "SELECT $id_column, mot_de_Passe FROM $table WHERE nom = :nom AND prenom = :prenom";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom]);

        // Vérification du mot de passe
        $user = $stmt->fetch();

        if ($user) {

            if ($password === $user['mot_de_Passe']) {
                $_SESSION['user_id'] = $user[$id_column];
                $_SESSION['profile_type'] = $profile_type;
                // Remplacer les redirections par des phrases de confirmation
                switch ($profile_type) {
                    case 'etudiant':
                        echo "Connexion réussie en tant qu'étudiant(e)";
                        break;
                    case 'professeur':
                        header("Location: ../../html/prof/acceuil_prof.html");
                        break;
                    case 'admin':
                        echo "Connexion réussie en tant qu'admin";
                        break;
                }
                exit();
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            $error = "Identifiant ou mot de passe incorrect.";
        }
    } else {
        $error = "Type de profil invalide.";
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
                            <input type="radio" name="type-p" value="etudiant" required>
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
                <?php if (isset($error)): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script src="../../js/connexion/connexion.js"></script>
</body>
</html>
