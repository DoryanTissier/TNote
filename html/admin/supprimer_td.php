<?php
session_start();
include_once '../../PHP/Page_login/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['profile_type'])) {
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}

if (!isset($_GET['td_number'])) {
    $_SESSION['message'] = "Numéro de TD non spécifié.";
    $_SESSION['message_type'] = "error";
    header("Location: page_admin.php");
    exit();
}

$td_number = (int)$_GET['td_number'];

try {
    $pdo->beginTransaction();

    // Supprimer les notes associées aux étudiants du TD
    $sql = "DELETE n 
            FROM Note n
            JOIN Profil_etudiant pe ON n.ID_Etudiants = pe.ID_Etudiants
            JOIN Groupe g ON pe.ID_groupe = g.ID_groupe
            WHERE g.numero_TD = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$td_number]);

    // Supprimer les étudiants du TD
    $sql = "DELETE pe
            FROM Profil_etudiant pe
            JOIN Groupe g ON pe.ID_groupe = g.ID_groupe
            WHERE g.numero_TD = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$td_number]);

    // Supprimer le groupe associé au TD
    $sql = "DELETE FROM Groupe WHERE numero_TD = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$td_number]);

    $pdo->commit();

    $_SESSION['message'] = "Le TD et toutes les données associées ont été supprimés avec succès.";
    $_SESSION['message_type'] = "success";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['message'] = "Erreur lors de la suppression du TD : " . $e->getMessage();
    $_SESSION['message_type'] = "error";
}

header("Location: page_admin.php");
exit();
?>
