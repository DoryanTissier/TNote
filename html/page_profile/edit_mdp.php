<?php
session_start();
include '../../PHP/Page_login/db_connect.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}

$id = $_SESSION['user_id'];

// Récupère le mot de passe actuel depuis la base de données
$sql_mdp = "";
if ($_SESSION['profile_type'] === 'professeur') {
    $sql_mdp = "SELECT mot_de_passe FROM Profil_prof WHERE ID_prof = ?";
} elseif ($_SESSION['profile_type'] === 'admin') {
    $sql_mdp = "SELECT mot_de_passe FROM Profil_admin WHERE ID_admin = ?";
} elseif ($_SESSION['profile_type'] === 'etudiant') {
    $sql_mdp = "SELECT mot_de_passe FROM Profil_etudiant WHERE ID_Etudiants = ?";
}

$stmt_mdp = $pdo->prepare($sql_mdp);
$stmt_mdp->execute([$id]);
$mdp_actuel = $stmt_mdp->fetchColumn();

// Vérifie si le mot de passe soumis est différent de l'actuel
if ($_POST['password'] != $mdp_actuel) {
    // Met à jour le mot de passe dans la base de données
    $nouveau_mdp = $_POST['password']; // Attention: il est recommandé de hasher le mot de passe avant de le stocker dans la base de données
    $sql_update_mdp = "";
    if ($_SESSION['profile_type'] === 'professeur') {
        $sql_update_mdp = "UPDATE Profil_prof SET mot_de_passe = ? WHERE ID_prof = ?";
    } elseif ($_SESSION['profile_type'] === 'admin') {
        $sql_update_mdp = "UPDATE Profil_admin SET mot_de_passe = ? WHERE ID_admin = ?";
    } elseif ($_SESSION['profile_type'] === 'etudiant') {
        $sql_update_mdp = "UPDATE Profil_etudiant SET mot_de_passe = ? WHERE ID_Etudiants = ?";
    }
    $stmt_update_mdp = $pdo->prepare($sql_update_mdp);
    $stmt_update_mdp->execute([$nouveau_mdp, $id]);

    // Redirige l'utilisateur vers une page de succès ou autre
    header("Location: ../../PHP/Page_login/logout.php");
    exit();
} else {
    // Mot de passe identique, peut afficher un message d'erreur ou rediriger
    if ($_SESSION['profile_type'] === 'professeur') {
        header("Location: page_profile_prof.php?error=same_password");
    }
    elseif ($_SESSION['profile_type'] === 'admin') {
        header("Location: page_profile_admin.php?error=same_password");
    }
    exit();
}
?>
