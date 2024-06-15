<?php
session_start();
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php

// Vérifiez si les données du formulaire sont envoyées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_td = $_POST['name_td'];
    $letters = range(strtolower($_POST['letter_a']), strtolower($_POST['letter_b']));
    $error = false;

    // Validation des données
    if (empty($name_td) || empty($letters)) {
        $_SESSION['message'] = "Tous les champs sont obligatoires.";
        $_SESSION['message_type'] = "error";
        $error = true;
    }

    // Validation des effectifs
    foreach ($letters as $letter) {
        if (empty($_POST['effectif_' . $letter])) {
            $_SESSION['message'] = "Tous les champs d'effectifs sont obligatoires.";
            $_SESSION['message_type'] = "error";
            $error = true;
            break;
        }
    }

    if (!$error) {
        try {
            // Commencer la transaction
            $pdo->beginTransaction();

            foreach ($letters as $letter) {
                $effectif = $_POST['effectif_' . $letter];
                $stmt = $pdo->prepare("INSERT INTO Groupe (numero_TD, effectif, lettre_TP) VALUES (?, ?, ?)");
                $stmt->execute([$name_td, $effectif, $letter]);
            }

            // Valider la transaction
            $pdo->commit();

            $_SESSION['message'] = "Nouveaux TDs ajoutés avec succès.";
            $_SESSION['message_type'] = "success";
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
