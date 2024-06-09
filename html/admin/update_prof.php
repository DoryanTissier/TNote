<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $ressources = isset($_POST['ressources']) ? $_POST['ressources'] : [];
    $sae = isset($_POST['sae']) ? $_POST['sae'] : [];

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Mettre à jour les informations de base du professeur
        $sql_update_prof = "UPDATE Profil_prof SET nom = ?, prenom = ?, mot_de_passe = ? WHERE ID_prof = ?";
        $stmt_update_prof = $pdo->prepare($sql_update_prof);
        $stmt_update_prof->execute([$nom, $prenom, $mot_de_passe, $id]);

        // Mettre à jour les ressources associées
        // Supprimer les anciennes ressources
        $sql_delete_ressources = "DELETE FROM Liaison_ressources_prof WHERE id_prof = ?";
        $stmt_delete_ressources = $pdo->prepare($sql_delete_ressources);
        $stmt_delete_ressources->execute([$id]);

        // Ajouter les nouvelles ressources
        $sql_insert_ressources = "INSERT INTO Liaison_ressources_prof (id_prof, num_ressource) VALUES (?, ?)";
        $stmt_insert_ressources = $pdo->prepare($sql_insert_ressources);
        foreach ($ressources as $num_ressource) {
            $stmt_insert_ressources->execute([$id, $num_ressource]);
        }

        // Mettre à jour les SAE associées
        // Supprimer les anciennes SAE
        $sql_delete_sae = "DELETE FROM Liaison_SAE_prof WHERE id_prof = ?";
        $stmt_delete_sae = $pdo->prepare($sql_delete_sae);
        $stmt_delete_sae->execute([$id]);

        // Ajouter les nouvelles SAE
        $sql_insert_sae = "INSERT INTO Liaison_SAE_prof (id_prof, nom_SAE) VALUES (?, ?)";
        $stmt_insert_sae = $pdo->prepare($sql_insert_sae);
        foreach ($sae as $nom_sae) {
            $stmt_insert_sae->execute([$id, $nom_sae]);
        }

        // Valider la transaction
        $pdo->commit();

        header("Location: page_admin.php");
        exit();
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
        exit();
    }
} else {
    echo "Données invalides.";
    exit();
}
?>
