<?php
include '../../PHP/Page_login/db_connect.php';

try {
    // Récupérer les données du formulaire
    $id_evaluation = $_POST['id_evaluation'];
    $nom_evaluation = $_POST['nom_evaluation'];
    $date_jour = $_POST['date_jour'];
    $coefficient = $_POST['coefficient'];
    $ID_prof = $_POST['ID_prof'];

    // Mettre à jour l'évaluation
    $sql_update_evaluation = "UPDATE Evaluation SET nom_evaluation = :nom_evaluation, date_jour = :date_jour, coefficient = :coefficient WHERE id_evaluation = :id_evaluation";
    $stmt_update_evaluation = $pdo->prepare($sql_update_evaluation);
    $stmt_update_evaluation->execute([
        'nom_evaluation' => $nom_evaluation,
        'date_jour' => $date_jour,
        'coefficient' => $coefficient,
        'id_evaluation' => $id_evaluation
    ]);

    // Mettre à jour les notes des étudiants
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'note-') === 0) {
            $id_etudiant = str_replace('note-', '', $key);
            $note = $value;
            $presence = isset($_POST['presence-' . $id_etudiant]) ? 1 : 0;

            $sql_update_note = "UPDATE Note SET note = :note WHERE id_evaluation = :id_evaluation AND ID_Etudiants = :id_etudiant";
            $stmt_update_note = $pdo->prepare($sql_update_note);
            $stmt_update_note->execute([
                'note' => $note,
                'id_evaluation' => $id_evaluation,
                'id_etudiant' => $id_etudiant
            ]);
        }
    }

    header("Location: index.php"); // Rediriger vers la page d'accueil
    exit;
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
}
?>
