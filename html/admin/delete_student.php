<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    echo "Received ID: $id\n"; // Ajoutez ceci pour déboguer

    // Vérifiez que l'ID est un nombre
    if (is_numeric($id)) {
        echo "Valid ID\n"; // Ajoutez ceci pour déboguer
        
        try {
            // Commencer la transaction
            $pdo->beginTransaction();

            // Supprimer les notes associées
            $deleteNotesSql = "DELETE FROM Note WHERE ID_Etudiants = ?";
            $stmt = $pdo->prepare($deleteNotesSql);
            if ($stmt->execute([$id])) {
                echo "Notes deleted\n"; // Ajoutez ceci pour déboguer

                // Supprimer l'étudiant
                $deleteStudentSql = "DELETE FROM Profil_etudiant WHERE ID_Etudiants = ?";
                $stmt = $pdo->prepare($deleteStudentSql);
                if ($stmt->execute([$id])) {
                    echo "success";
                } else {
                    echo "error: " . $stmt->errorInfo()[2]; // Affiche l'erreur exacte
                }
            } else {
                echo "error deleting notes: " . $stmt->errorInfo()[2]; // Affiche l'erreur exacte
            }

            // Valider la transaction
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        echo "invalid_id";
    }
} else {
    echo "Invalid request method"; // Ajoutez ceci pour déboguer
}
?>
