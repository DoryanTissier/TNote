<?php
include '../../PHP/Page_login/db_connect.php'; // Inclusion du fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $td_id = $_POST['td_id'];

    // Requête pour supprimer le TD
    $sql = "DELETE FROM Groupe WHERE numero_TD = :td_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':td_id', $td_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
