<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['name_td'];
    $prenom = $_POST['prenom_td'];
    $identifiant = $_POST['identifiant_td'];
    $mdp = $_POST['mdp_td'];
    $ressources = isset($_POST['ressources']) ? $_POST['ressources'] : [];
    $sae = isset($_POST['sae']) ? $_POST['sae'] : [];

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Insérer dans la table Profil_prof
        $stmt = $pdo->prepare("INSERT INTO Profil_prof (nom, prenom, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prenom, $mdp]);
        
        // Récupérer l'ID du professeur
        $prof_id = $pdo->lastInsertId();

        // Insérer les ressources associées
        $stmtRessource = $pdo->prepare("INSERT INTO Liaison_ressources_prof (id_prof, num_ressource) VALUES (?, ?)");
        foreach ($ressources as $ressource) {
            $stmtRessource->execute([$prof_id, $ressource]);
        }

        // Insérer les SAE associées
        $stmtSae = $pdo->prepare("INSERT INTO Liaison_SAE_prof (id_prof, nom_SAE) VALUES (?, ?)");
        foreach ($sae as $saeItem) {
            $stmtSae->execute([$prof_id, $saeItem]);
        }

        // Valider la transaction
        $pdo->commit();

        header("Location: page_admin.php?success=1");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
}
?>
