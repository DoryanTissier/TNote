<?php
session_start();
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php

// Vérifiez si les données du formulaire sont envoyées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_td = $_POST['name_td'];
    $effectif_a = $_POST['effectif_a'];
    $effectif_b = $_POST['effectif_b'];

    // Validation des données (ajoutez d'autres validations selon vos besoins)
    if (empty($name_td) || empty($effectif_a) || empty($effectif_b)) {
        $_SESSION['message'] = "Tous les champs sont obligatoires.";
        $_SESSION['message_type'] = "error";
    } else {
        try {
            // Commencer la transaction
            $pdo->beginTransaction();

            // Insérez les données dans la base de données pour l'effectif A
            $stmt_a = $pdo->prepare("INSERT INTO Groupe (numero_TD, effectif, lettre_TP) VALUES (?, ?, 'a')");
            $stmt_a->execute([$name_td, $effectif_a]);

            // Insérez les données dans la base de données pour l'effectif B
            $stmt_b = $pdo->prepare("INSERT INTO Groupe (numero_TD, effectif, lettre_TP) VALUES (?, ?, 'b')");
            $stmt_b->execute([$name_td, $effectif_b]);

            // Valider la transaction
            $pdo->commit();

            if ($stmt_a->rowCount() > 0 && $stmt_b->rowCount() > 0) {
                $_SESSION['message'] = "Nouveaux TDs ajoutés avec succès.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors de l'ajout des TDs.";
                $_SESSION['message_type'] = "error";
            }
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            $_SESSION['message'] = "Erreur lors de l'ajout des TDs: " . $e->getMessage();
            $_SESSION['message_type'] = "error";
        }
    }
    header("Location: page_admin.php");
    exit();
}
?>
