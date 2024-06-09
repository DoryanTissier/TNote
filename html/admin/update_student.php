<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $numero_TD = $_POST['numero_TD'];
    $lettre_TP = $_POST['lettre_TP'];
    $annee_univ = $_POST['annee_univ'];
    $mot_de_Passe = $_POST['mot_de_Passe'];

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Mettre à jour les informations de base de l'étudiant
        $sql = "UPDATE Profil_etudiant SET nom=?, prenom=?, annee_univ=?, mot_de_Passe=? WHERE ID_Etudiants=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $annee_univ, $mot_de_Passe, $id]);

        // Mettre à jour les informations du groupe
        $updateGroupe = "UPDATE Groupe SET numero_TD=?, lettre_TP=? WHERE ID_groupe=(SELECT ID_groupe FROM Profil_etudiant WHERE ID_Etudiants=?)";
        $stmtGroupe = $pdo->prepare($updateGroupe);
        $stmtGroupe->execute([$numero_TD, $lettre_TP, $id]);

        // Valider la transaction
        $pdo->commit();

        header("Location: td{$numero_TD}.php?success=1&id={$id}");
        exit;
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        echo "Erreur lors de la mise à jour des informations de l'étudiant: " . $e->getMessage();
        exit();
    }
} else {
    echo "Méthode de requête non supportée.";
}
?>
