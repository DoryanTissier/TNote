<?php
// Inclusion du fichier de connexion à la base de données
include '../../PHP/Page_login/db_connect.php';

// Fonction pour vérifier si une valeur est vide
function is_empty($value) {
    return !isset($value) || trim($value) === '';
}

try {
    // Récupération des données du formulaire
    if (!isset($_POST['ID_prof'], $_POST['nom_evaluation'], $_POST['date_jour'], $_POST['coefficient'])) {
        throw new Exception('Les données du formulaire sont incomplètes.');
    }

    $professeur_id = $_POST['ID_prof'];
    $num_ressource = isset($_POST['num_ressource']) ? $_POST['num_ressource'] : NULL;
    $nom_SAE = isset($_POST['nom_SAE']) ? $_POST['nom_SAE'] : NULL;
    $nom_evaluation = $_POST['nom_evaluation'];
    $date_jour = $_POST['date_jour'];
    $coefficient = $_POST['coefficient'];

    // Détermination du type d'évaluation
    $type_evaluation = is_empty($nom_SAE) ? 'ressource' : 'SAE';

    // Insertion des données dans la table Évaluation
    $query = "INSERT INTO Evaluation (type_evaluation, coefficient, nom_evaluation, date_jour, ID_prof, num_ressource, nom_SAE) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$type_evaluation, $coefficient, $nom_evaluation, $date_jour, $professeur_id, $num_ressource, $nom_SAE]);

    // Récupération de l'ID de l'évaluation nouvellement créée
    $id_evaluation = $pdo->lastInsertId();

    // Insertion des notes des étudiants dans la table Note
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'note-') === 0) {
            $id_etudiant = str_replace('note-', '', $key);
            $note = is_empty($value) ? NULL : $value;
            $presence = isset($_POST['presence-' . $id_etudiant]) ? 1 : 0;

            // Insertion de la note
            $query = "INSERT INTO Note (note, id_evaluation, ID_Etudiants) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$note, $id_evaluation, $id_etudiant]);
        }
    }

    // Redirection avec succès
    header("Location: acceuil_prof.php?success=1");
    exit();
} catch (Exception $e) {
    // Gestion des erreurs
    $error = "Erreur : " . $e->getMessage();
} catch (PDOException $e) {
    // Gestion des erreurs PDO
    $error = "Erreur de base de données : " . $e->getMessage();
}

// Si une erreur est attrapée, redirection avec message d'erreur
if (isset($error)) {
    header("Location: acceuil_prof.php?error=" . urlencode($error));
    exit();
}
?>
