<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nom = $_POST['name_td'];
    $prenom = $_POST['prenom_etudiant'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $tp = $_POST['tp'];
    $annee_universitaire = $_POST['annee_universitaire'];

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Mettre à jour les informations de l'étudiant
        $updateStudentSql = "UPDATE Profil_etudiant 
                             SET nom = ?, prenom = ?, mot_de_Passe = ?, annee_univ = ? 
                             WHERE ID_Etudiants = ?";
        $stmt = $pdo->prepare($updateStudentSql);
        $stmt->execute([$nom, $prenom, $mot_de_passe, $annee_universitaire, $id]);

        // Obtenir les informations actuelles du groupe de l'étudiant
        $currentGroupSql = "SELECT g.ID_groupe, g.numero_TD, g.lettre_TP
                            FROM Groupe g
                            JOIN Profil_etudiant pe ON g.ID_groupe = pe.ID_groupe
                            WHERE pe.ID_Etudiants = ?";
        $stmt = $pdo->prepare($currentGroupSql);
        $stmt->execute([$id]);
        $currentGroup = $stmt->fetch();

        if (!$currentGroup) {
            throw new Exception("Groupe actuel non trouvé pour l'étudiant.");
        }

        // Vérifier si le groupe avec le TD et le TP mis à jour existe déjà
        $checkGroupSql = "SELECT ID_groupe, effectif FROM Groupe WHERE numero_TD = ? AND lettre_TP = ?";
        $stmt = $pdo->prepare($checkGroupSql);
        $stmt->execute([$currentGroup['numero_TD'], $tp]);
        $updatedGroup = $stmt->fetch();

        if ($updatedGroup) {
            // Mettre à jour l'effectif du nouveau groupe
            $newEffectif = $updatedGroup['effectif'] + 1;
            $updateEffectifSql = "UPDATE Groupe SET effectif = ? WHERE ID_groupe = ?";
            $stmt = $pdo->prepare($updateEffectifSql);
            $stmt->execute([$newEffectif, $updatedGroup['ID_groupe']]);

            $groupId = $updatedGroup['ID_groupe'];
        } else {
            // Créer un nouveau groupe avec le TP mis à jour
            $createGroupSql = "INSERT INTO Groupe (numero_TD, lettre_TP, annee, effectif) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($createGroupSql);
            $stmt->execute([$currentGroup['numero_TD'], $tp, $annee_universitaire, 1]);
            $groupId = $pdo->lastInsertId();
        }

        // Associer l'étudiant au groupe avec le TP mis à jour
        $updateGroupStudentSql = "UPDATE Profil_etudiant SET ID_groupe = ? WHERE ID_Etudiants = ?";
        $stmt = $pdo->prepare($updateGroupStudentSql);
        $stmt->execute([$groupId, $id]);

        // Réduire l'effectif de l'ancien groupe
        if ($currentGroup['ID_groupe'] != $groupId) {
            $reduceEffectifSql = "UPDATE Groupe SET effectif = effectif - 1 WHERE ID_groupe = ?";
            $stmt = $pdo->prepare($reduceEffectifSql);
            $stmt->execute([$currentGroup['ID_groupe']]);
        }

        // Valider la transaction
        $pdo->commit();
        header("Location: td1.php?td_number={$currentGroup['numero_TD']}&success=1&id=$id");
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Invalid request method or missing student ID";
}
?>

