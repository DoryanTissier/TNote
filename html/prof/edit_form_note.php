<?php
include '../../PHP/Page_login/db_connect.php';

$id_evaluation = $_GET['id_evaluation'];
$sql_evaluation = "SELECT * FROM Evaluation WHERE id_evaluation = :id_evaluation";
$stmt_evaluation = $pdo->prepare($sql_evaluation);
$stmt_evaluation->execute(['id_evaluation' => $id_evaluation]);
$evaluation = $stmt_evaluation->fetch(PDO::FETCH_ASSOC);

$sql_notes = "SELECT E.ID_Etudiants, E.nom, E.prenom, G.numero_TD, G.lettre_TP, N.note 
              FROM Note N
              JOIN Profil_etudiant E ON N.ID_Etudiants = E.ID_Etudiants
              JOIN Groupe G ON E.ID_groupe = G.ID_groupe
              WHERE N.id_evaluation = :id_evaluation
              ORDER BY G.numero_TD, G.lettre_TP, E.nom";
$stmt_notes = $pdo->prepare($sql_notes);
$stmt_notes->execute(['id_evaluation' => $id_evaluation]);
$notes = $stmt_notes->fetchAll(PDO::FETCH_ASSOC);

$professeur_nom = "Nom du Professeur";  // Vous devrez récupérer ceci de votre session ou base de données
?>

<form action="edit_note.php" method="post">
    <div class="popup-header">
        <p>Professeur: <?= htmlspecialchars($professeur_nom) ?></p>
        <div>
            <label for="nom_evaluation-<?= $evaluation['id_evaluation'] ?>">Nom de l'évaluation:</label>
            <input type="text" id="nom_evaluation-<?= $evaluation['id_evaluation'] ?>" name="nom_evaluation" value="<?= htmlspecialchars($evaluation['nom_evaluation']) ?>" required>
        </div>
        <div>
            <label for="date_jour-<?= $evaluation['id_evaluation'] ?>">Date de l'évaluation:</label>
            <input type="date" id="date_jour-<?= $evaluation['id_evaluation'] ?>" name="date_jour" value="<?= htmlspecialchars($evaluation['date_jour']) ?>" required>
        </div>
        <div>
            <label for="coefficient-<?= $evaluation['id_evaluation'] ?>">Coefficient:</label>
            <input type="number" id="coefficient-<?= $evaluation['id_evaluation'] ?>" name="coefficient" value="<?= htmlspecialchars($evaluation['coefficient']) ?>" required>
        </div>
        <input type="hidden" name="id_evaluation" value="<?= $evaluation['id_evaluation'] ?>">
        <input type="hidden" name="ID_prof" value="<?= $evaluation['ID_prof'] ?>">
    </div>
    <div class="popup-table">
        <?php
        $current_td = '';
        $current_tp = '';
        foreach ($notes as $note):
            if ($current_td !== $note['numero_TD']) {
                if ($current_td !== '') {
                    echo '</tbody></table>';
                }
                $current_td = $note['numero_TD'];
                $current_tp = ''; // Reset TP for new TD
                echo '<table><thead><tr><th colspan="5" class="td-header">TD' . htmlspecialchars($note['numero_TD']) . ' <input type="checkbox" class="select-all-td" data-td="' . htmlspecialchars($note['numero_TD']) . '"></th></tr></thead><tbody>';
            }
            if ($current_tp !== $note['lettre_TP']) {
                if ($current_tp !== '') {
                    echo '</tbody></table>';
                }
                $current_tp = $note['lettre_TP'];
                echo '<table><thead><tr><th colspan="5" class="tp-header">TP' . htmlspecialchars($note['lettre_TP']) . ' <input type="checkbox" class="select-all-tp" data-tp="' . htmlspecialchars($note['lettre_TP']) . '" data-td="' . htmlspecialchars($note['numero_TD']) . '"></th></tr>';
                echo '<tr><th>N°</th><th>Nom</th><th>Prénom</th><th>Note</th><th>Présence</th></tr></thead><tbody>';
            }
            ?>
            <tr>
                <td><?= htmlspecialchars($note['ID_Etudiants']) ?></td>
                <td><?= htmlspecialchars($note['nom']) ?></td>
                <td><?= htmlspecialchars($note['prenom']) ?></td>
                <td><input type="number" step="0.1" min="0" max="20" name="note-<?= $note['ID_Etudiants'] ?>" value="<?= htmlspecialchars($note['note']) ?>"></td>
                <td><input type="checkbox" name="presence-<?= $note['ID_Etudiants'] ?>" class="presence-checkbox" data-td="<?= htmlspecialchars($note['numero_TD']) ?>" data-tp="<?= htmlspecialchars($note['lettre_TP']) ?>" <?= $note['note'] !== null ? 'checked' : '' ?>></td>
            </tr>
        <?php endforeach; ?>
        </tbody></table>
    </div>
    <button type="submit">Modifier</button>
</form>
