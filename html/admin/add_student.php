<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['name_td'];
    $prenom = $_POST['prenom_etudiant'];
    $identifiant = strtolower($nom . '.' . $prenom); // Générer l'identifiant automatiquement
    $tp = $_POST['tp'];
    $td = $_POST['td'];
    $annee_univ = $_POST['annee_universitaire'];
    $mot_de_passe = $_POST['mot_de_passe'];

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Insérer dans la table Groupe si nécessaire
        $stmt = $pdo->prepare("INSERT INTO Groupe (numero_TD, lettre_TP, annee) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE numero_TD=numero_TD");
        $stmt->execute([$td, $tp, $annee_univ]);

        // Récupérer l'ID du groupe ou obtenir l'ID existant
        $groupe_id = $pdo->lastInsertId();
        if ($groupe_id == 0) {
            $stmt = $pdo->prepare("SELECT ID_groupe FROM Groupe WHERE numero_TD = ? AND lettre_TP = ? AND annee = ?");
            $stmt->execute([$td, $tp, $annee_univ]);
            $groupe_id = $stmt->fetchColumn();
        }

        // Insérer dans la table Profil_etudiant
        $stmt = $pdo->prepare("INSERT INTO Profil_etudiant (nom, prenom, annee_univ, mot_de_Passe, ID_groupe) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $annee_univ, $mot_de_passe, $groupe_id]);

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
