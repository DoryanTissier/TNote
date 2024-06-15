<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Supprimer les notes de l'étudiant
        $deleteNotesSql = "DELETE FROM Note WHERE ID_Etudiants = ?";
        $stmt = $pdo->prepare($deleteNotesSql);
        $stmt->execute([$id]);

        // Supprimer l'étudiant
        $deleteStudentSql = "DELETE FROM Profil_etudiant WHERE ID_Etudiants = ?";
        $stmt = $pdo->prepare($deleteStudentSql);
        $stmt->execute([$id]);

        // Valider la transaction
        $pdo->commit();
        echo 'success';
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Invalid request method or missing student ID";
}
?>
