<?php
session_start();
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Supprimer les notes associées aux évaluations du professeur
        $stmt = $pdo->prepare("DELETE n FROM Note n
                               JOIN Evaluation e ON n.id_evaluation = e.id_evaluation
                               WHERE e.ID_prof = ?");
        $stmt->execute([$id]);

        // Supprimer les évaluations liées à ce professeur
        $stmt = $pdo->prepare("DELETE FROM Evaluation WHERE ID_prof = ?");
        $stmt->execute([$id]);

        // Supprimer les liaisons avec les ressources
        $stmt = $pdo->prepare("DELETE FROM Liaison_ressources_prof WHERE id_prof = ?");
        $stmt->execute([$id]);

        // Supprimer les liaisons avec les SAE
        $stmt = $pdo->prepare("DELETE FROM Liaison_SAE_prof WHERE id_prof = ?");
        $stmt->execute([$id]);

        // Supprimer le profil du professeur
        $stmt = $pdo->prepare("DELETE FROM Profil_prof WHERE ID_prof = ?");
        $stmt->execute([$id]);

        // Valider la transaction
        $pdo->commit();

        $_SESSION['message'] = "Professeur supprimé avec succès.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        $_SESSION['message'] = "Erreur lors de la suppression du professeur : " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    header("Location: page_admin.php");
    exit();
} else {
    $_SESSION['message'] = "Requête invalide.";
    $_SESSION['message_type'] = "error";
    header("Location: page_admin.php");
    exit();
}
?>
