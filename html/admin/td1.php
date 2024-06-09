<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
$td_number = isset($_GET['td_number']) ? (int)$_GET['td_number'] : 1; // Remplacez par le numéro approprié pour chaque fichier (1, 2 ou 3)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TD <?php echo $td_number; ?> - Student Table</title>
    <link rel="stylesheet" href="../../css/admin/styles.css">
</head>
<body class=".body_details_td">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="success-message" id="success-message">
            VOUS AVEZ MODIFIÉ ID : <?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'N/A'; ?>
        </div>
    <?php endif; ?>
    <h1 classe="titre_td_list">TD <?php echo $td_number; ?> - Liste des étudiants</h1>
    <table class="main-table">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>TD</th>
                <th>TP</th>
                <th>Année</th>
                <th>Moyenne</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT pe.ID_Etudiants, pe.nom, pe.prenom, g.numero_TD, g.lettre_TP, pe.annee_univ, AVG(n.note) AS moyenne
                    FROM Profil_etudiant pe
                    JOIN Groupe g ON pe.ID_groupe = g.ID_groupe
                    LEFT JOIN Note n ON pe.ID_Etudiants = n.ID_Etudiants
                    WHERE g.numero_TD = ?
                    GROUP BY pe.ID_Etudiants";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$td_number]);
            $result = $stmt->fetchAll();

            if (count($result) > 0) {
                foreach ($result as $row) {
                    $moyenne = is_null($row['moyenne']) ? 'N/A' : number_format($row['moyenne'], 2);
                    echo "<tr class='main-row' data-id='{$row['ID_Etudiants']}'>
                            <td>{$row['ID_Etudiants']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['prenom']}</td>
                            <td>{$row['numero_TD']}</td>
                            <td>{$row['lettre_TP']}</td>
                            <td>{$row['annee_univ']}</td>
                            <td>{$moyenne}</td>
                            <td class='actions'>
                                <button class='edit'></button>
                                <button class='delete'></button>
                                <button class='dropdown'></button>
                            </td>
                          </tr>";

                    // Ajouter la table de détails juste après la ligne principale
                    echo "<tr class='detail-row hidden' data-id='{$row['ID_Etudiants']}'>
                            <td colspan='8'>
                                <table class='detail-table'>
                                    <thead>
                                        <tr>
                                            <th>Nom de l'évaluation</th>
                                            <th>Module</th>
                                            <th>Type</th>
                                            <th>Coefficient</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                    
                    $sqlDetails = "SELECT n.note, e.nom_evaluation, e.type_evaluation, e.coefficient 
                                   FROM Note n
                                   JOIN Évalutation e ON n.id_evaluation = e.id_evaluation
                                   WHERE n.ID_Etudiants = ?";
                    $stmtDetails = $pdo->prepare($sqlDetails);
                    $stmtDetails->execute([$row['ID_Etudiants']]);
                    $resultDetails = $stmtDetails->fetchAll();

                    if (count($resultDetails) > 0) {
                        foreach ($resultDetails as $detailRow) {
                            echo "<tr>
                                    <td>{$detailRow['nom_evaluation']}</td>
                                    <td>Module</td>
                                    <td>{$detailRow['type_evaluation']}</td>
                                    <td>{$detailRow['coefficient']}</td>
                                    <td>{$detailRow['note']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Aucune note trouvée</td></tr>";
                    }

                    echo "        </tbody>
                                </table>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Aucun étudiant trouvé</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <script src="../../js/admin/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.add('hide');
                }, 2000); // Ajoute la classe 'hide' après 2 secondes
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 2500); // Cache le message après la transition
            }
        });
    </script>
</body>
</html>
