<?php
include '../../PHP/Page_login/db_connect.php';

try {
    if (!isset($_POST['id_evaluation'])) {
        throw new Exception('ID de l\'évaluation manquant.');
    }

    $id_evaluation = $_POST['id_evaluation'];

    // Supprimer les notes associées à l'évaluation
    $query_delete_notes = "DELETE FROM Note WHERE id_evaluation = :id_evaluation";
    $stmt_delete_notes = $pdo->prepare($query_delete_notes);
    $stmt_delete_notes->execute(['id_evaluation' => $id_evaluation]);

    // Supprimer l'évaluation
    $query_delete_evaluation = "DELETE FROM Evaluation WHERE id_evaluation = :id_evaluation";
    $stmt_delete_evaluation = $pdo->prepare($query_delete_evaluation);
    $stmt_delete_evaluation->execute(['id_evaluation' => $id_evaluation]);

    header("Location: acceuil_prof.php"); // Rediriger vers la page d'accueil
    exit;
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
}
?>
