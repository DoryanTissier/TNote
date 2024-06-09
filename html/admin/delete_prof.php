<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Supprimer les ressources associées au professeur
        $sql_delete_ressources = "DELETE FROM Liaison_ressources_prof WHERE id_prof = ?";
        $stmt_delete_ressources = $pdo->prepare($sql_delete_ressources);
        $stmt_delete_ressources->execute([$id]);

        // Supprimer les SAE associées au professeur
        $sql_delete_sae = "DELETE FROM Liaison_SAE_prof WHERE id_prof = ?";
        $stmt_delete_sae = $pdo->prepare($sql_delete_sae);
        $stmt_delete_sae->execute([$id]);

        // Supprimer le professeur
        $sql_delete_prof = "DELETE FROM Profil_prof WHERE ID_prof = ?";
        $stmt_delete_prof = $pdo->prepare($sql_delete_prof);
        $stmt_delete_prof->execute([$id]);

        // Valider la transaction
        $pdo->commit();

        header("Location: page_admin.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "ID de professeur non spécifié.";
    exit();
}
?>
