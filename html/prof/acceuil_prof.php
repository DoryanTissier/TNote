<?php
session_start();
include '../../PHP/Page_login/db_connect.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['profile_type'] !== 'professeur') {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}

$id_prof = $_SESSION['user_id'];

// Récupérer les informations du professeur connecté
$sql_prof = "SELECT nom, prenom FROM Profil_prof WHERE ID_prof = :id_prof";
$stmt_prof = $pdo->prepare($sql_prof);
$stmt_prof->execute(['id_prof' => $id_prof]);
$professeur = $stmt_prof->fetch();
$professeur_nom = $professeur['nom'] . ' ' . $professeur['prenom'];

// Requête pour récupérer les ressources associées au professeur
$sql_ressources = "SELECT R.num_ressource, R.nom 
                   FROM Ressources R 
                   JOIN Liaison_ressources_prof LRP ON R.num_ressource = LRP.num_ressource 
                   WHERE LRP.id_prof = :id_prof";
$stmt_ressources = $pdo->prepare($sql_ressources);
$stmt_ressources->execute(['id_prof' => $id_prof]);
$ressources = $stmt_ressources->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer les SAE associées au professeur
$sql_sae = "SELECT S.nom_SAE 
            FROM SAE S 
            JOIN Liaison_SAE_prof LSP ON S.nom_SAE = LSP.nom_SAE 
            WHERE LSP.id_prof = :id_prof";
$stmt_sae = $pdo->prepare($sql_sae);
$stmt_sae->execute(['id_prof' => $id_prof]);
$saes = $stmt_sae->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer les étudiants triés par TD et TP
$sql_etudiants = "SELECT E.ID_Etudiants, E.nom, E.prenom, G.annee, G.lettre_TP, G.numero_TD
                  FROM Profil_etudiant E
                  JOIN Groupe G ON E.ID_groupe = G.ID_groupe
                  ORDER BY G.numero_TD, G.lettre_TP, E.nom";
$stmt_etudiants = $pdo->prepare($sql_etudiants);
$stmt_etudiants->execute();
$etudiants = $stmt_etudiants->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Card</title>
  <link rel="stylesheet" href="../../css/prof/prof_header.css">
  <link rel="stylesheet" href="../../css/prof/acceuil_prof.css">
</head>
<body>
<div class="header">
  <div class="dlogo">
      <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
  </div>
  <div class="navbar">
      <button class="value">Note</button>
      <a href="../page_profile/page_profile_prof.php" class="value" style="text-decoration: none;">Profil</a>
      <a href="../../PHP/Page_login/logout.php" class="value" style="text-decoration: none;">Déconnexion</a>
  </div>
</div>


    <!-- Container principal pour centrer les cartes -->
    <div class="card-container">
        <?php foreach ($ressources as $ressource): ?>
            <div class="card">
                <div class="card-text" data-popup-id="popup-view-<?= $ressource['num_ressource'] ?>">
                    <p><?= htmlspecialchars($ressource['nom']) ?></p>
                </div>
                <div class="card-icons">
                    <button class="card-btn" data-popup-id="popup-modify-<?= $ressource['num_ressource'] ?>"><img src="../../source/img/icon/noir/ancien-n.png" alt="modifier_note" width="40"></button>
                    <button class="card-btn" data-popup-id="popup-add-<?= $ressource['num_ressource'] ?>"><img src="../../source/img/icon/noir/ajouter-n.png" alt="ajouter_note" width="60"></button>
                </div>
            </div>

            <!-- Popup pour consulter les notes par élève -->
            <div id="popup-view-<?= $ressource['num_ressource'] ?>" class="popup">
                <div class="popup-content">
                    <span class="popup-close">&times;</span>
                    <p>Consulter les notes pour la ressource: <?= htmlspecialchars($ressource['nom']) ?></p>
                </div>
            </div>

            <!-- Popup pour modifier les notes ajoutées -->
            <div id="popup-modify-<?= $ressource['num_ressource'] ?>" class="popup">
                <div class="popup-content">
                    <span class="popup-close">&times;</span>
                    <p>Modifier les notes pour la ressource: <?= htmlspecialchars($ressource['nom']) ?></p>
                    <div class="evaluation-list">
                        <?php
                        // Requête pour récupérer les évaluations liées à la ressource
                        $sql_evaluations = "SELECT * FROM Evaluation WHERE num_ressource = :num_ressource";
                        $stmt_evaluations = $pdo->prepare($sql_evaluations);
                        $stmt_evaluations->execute(['num_ressource' => $ressource['num_ressource']]);
                        $evaluations = $stmt_evaluations->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($evaluations as $evaluation):
                            echo '<div class="evaluation">';
                            echo '<h3>' . htmlspecialchars($evaluation['nom_evaluation']) . '</h3>';

                            // Ajout des boutons modifier et supprimer
                            echo '<button class="modify-evaluation" data-evaluation-id="' . $evaluation['id_evaluation'] . '">Modifier</button>';
                            echo '<form action="supp_note.php" method="POST" style="display:inline;">';
                            echo '<input type="hidden" name="id_evaluation" value="' . $evaluation['id_evaluation'] . '">';
                            echo '<button type="submit" class="delete-evaluation" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette évaluation ?\');">Supprimer</button>';
                            echo '</form>';

                            // Requête pour récupérer les notes des étudiants par évaluation
                            $sql_notes = "SELECT E.ID_Etudiants, E.nom, E.prenom, G.numero_TD, G.lettre_TP, N.note 
                                        FROM Note N
                                        JOIN Profil_etudiant E ON N.ID_Etudiants = E.ID_Etudiants
                                        JOIN Groupe G ON E.ID_groupe = G.ID_groupe
                                        WHERE N.id_evaluation = :id_evaluation
                                        ORDER BY G.numero_TD, G.lettre_TP, E.nom";
                            $stmt_notes = $pdo->prepare($sql_notes);
                            $stmt_notes->execute(['id_evaluation' => $evaluation['id_evaluation']]);
                            $notes = $stmt_notes->fetchAll(PDO::FETCH_ASSOC);

                            $tds = [];
                            foreach ($notes as $note) {
                                $td = $note['numero_TD'];
                                $tp = $note['lettre_TP'];
                                if (!isset($tds[$td])) {
                                    $tds[$td] = [];
                                }
                                if (!isset($tds[$td][$tp])) {
                                    $tds[$td][$tp] = ['notes' => [], 'count' => 0, 'sum' => 0];
                                }
                                if ($note['note'] !== null) {
                                    $tds[$td][$tp]['notes'][] = $note['note'];
                                    $tds[$td][$tp]['count']++;
                                    $tds[$td][$tp]['sum'] += $note['note'];
                                }
                            }

                            foreach ($tds as $td => $tps) {
                                $td_displayed = false;
                                foreach ($tps as $tp => $data) {
                                    if ($data['count'] > 0) {
                                        if (!$td_displayed) {
                                            echo '<div class="td"><h4>TD' . htmlspecialchars($td) . '</h4>';
                                            $td_displayed = true;
                                        }
                                        $average = $data['sum'] / $data['count'];
                                        echo '<div class="tp">';
                                        echo '<h5>TP' . htmlspecialchars($tp) . '</h5>';
                                        echo '<p>Moyenne: ' . round($average, 2) . '</p>';
                                        echo '</div>';
                                    }
                                }
                                if ($td_displayed) {
                                    echo '</div>';
                                }
                            }

                            echo '</div>';
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>

        

            <!-- Popup pour ajouter des notes -->
            <div id="popup-add-<?= $ressource['num_ressource'] ?>" class="popup">
                <div class="popup-content">
                    <span class="popup-close">&times;</span>
                    <form action="add_note.php" method="post">
                        <div class="popup-header">
                            <p>Professeur: <?= htmlspecialchars($professeur_nom) ?></p>
                            <div>
                                <label for="ressources-<?= $ressource['num_ressource'] ?>">Ressources: </label>
                                <select id="ressources-<?= $ressource['num_ressource'] ?>" name="num_ressource">
                                    <?php foreach ($ressources as $r): ?>
                                        <option value="<?= $r['num_ressource'] ?>" <?= $r['num_ressource'] == $ressource['num_ressource'] ? 'selected' : '' ?>><?= htmlspecialchars($r['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="saes-<?= $ressource['num_ressource'] ?>">SAE: </label>
                                <select id="saes-<?= $ressource['num_ressource'] ?>" name="nom_SAE">
                                    <option value="">Sélectionnez une SAE</option>
                                    <?php foreach ($saes as $sae): ?>
                                        <option value="<?= $sae['nom_SAE'] ?>"><?= htmlspecialchars($sae['nom_SAE']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="nom_evaluation-<?= $ressource['num_ressource'] ?>">Nom de l'évaluation:</label>
                                <input type="text" id="nom_evaluation-<?= $ressource['num_ressource'] ?>" name="nom_evaluation" required>
                            </div>
                            <div>
                                <label for="date_jour-<?= $ressource['num_ressource'] ?>">Date de l'évaluation:</label>
                                <input type="date" id="date_jour-<?= $ressource['num_ressource'] ?>" name="date_jour" required>
                            </div>
                            <div>
                                <label for="coefficient-<?= $ressource['num_ressource'] ?>">Coefficient:</label>
                                <input type="number" id="coefficient-<?= $ressource['num_ressource'] ?>" name="coefficient" required>
                            </div>
                            <input type="hidden" name="ID_prof" value="<?= $id_prof ?>">
                        </div>
                        <div class="popup-table">
                            <?php
                            $current_td = '';
                            $current_tp = '';
                            foreach ($etudiants as $etudiant):
                                if ($current_td !== $etudiant['numero_TD']) {
                                    if ($current_td !== '') {
                                        echo '</tbody></table>';
                                    }
                                    $current_td = $etudiant['numero_TD'];
                                    $current_tp = ''; // Reset TP for new TD
                                    echo '<table><thead><tr><th colspan="5" class="td-header">TD' . htmlspecialchars($etudiant['numero_TD']) . ' <input type="checkbox" class="select-all-td" data-td="' . htmlspecialchars($etudiant['numero_TD']) . '"></th></tr></thead><tbody>';
                                }
                                if ($current_tp !== $etudiant['lettre_TP']) {
                                    if ($current_tp !== '') {
                                        echo '</tbody></table>';
                                    }
                                    $current_tp = $etudiant['lettre_TP'];
                                    echo '<table><thead><tr><th colspan="5" class="tp-header">TP' . htmlspecialchars($etudiant['lettre_TP']) . ' <input type="checkbox" class="select-all-tp" data-tp="' . htmlspecialchars($etudiant['lettre_TP']) . '" data-td="' . htmlspecialchars($etudiant['numero_TD']) . '"></th></tr>';
                                    echo '<tr><th>N°</th><th>Nom</th><th>Prénom</th><th>Note</th><th>Présence</th></tr></thead><tbody>';
                                }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($etudiant['ID_Etudiants']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                                    <td><input type="number" step="0.1" min="0" max="20" name="note-<?= $etudiant['ID_Etudiants'] ?>"></td>
                                    <td><input type="checkbox" name="presence-<?= $etudiant['ID_Etudiants'] ?>" class="presence-checkbox" data-td="<?= htmlspecialchars($etudiant['numero_TD']) ?>" data-tp="<?= htmlspecialchars($etudiant['lettre_TP']) ?>"></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody></table>
                        </div>
                        <button type="submit">Soumettre</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>


        <?php foreach ($saes as $sae): ?>
            <div class="card">
                <div class="card-text" data-popup-id="popup-view-<?= $sae['nom_SAE'] ?>">
                    <p><?= htmlspecialchars($sae['nom_SAE']) ?></p>
                </div>
                <div class="card-icons">
                    <button class="card-btn" data-popup-id="popup-modify-<?= $sae['nom_SAE'] ?>"><img src="../../source/img/icon/noir/ancien-n.png" alt="modifier_note" width="40"></button>
                    <button class="card-btn" data-popup-id="popup-add-<?= $sae['nom_SAE'] ?>"><img src="../../source/img/icon/noir/ajouter-n.png" alt="ajouter_note" width="60"></button>
                </div>
            </div>

            <!-- Popup pour consulter les notes par élève -->
            <div id="popup-view-<?= $sae['nom_SAE'] ?>" class="popup">
                <div class="popup-content">
                    <span class="popup-close">&times;</span>
                    <p>Consulter les notes pour la SAE: <?= htmlspecialchars($sae['nom_SAE']) ?></p>
                </div>
            </div>

            <!-- Popup pour modifier les notes ajoutées -->
            <div id="popup-modify-<?= $sae['nom_SAE'] ?>" class="popup">
                    <div class="popup-content">
                        <span class="popup-close">&times;</span>
                        <p>Modifier les notes pour la SAE: <?= htmlspecialchars($sae['nom_SAE']) ?></p>
                        <div class="evaluation-list">
                            <?php
                            // Requête pour récupérer les évaluations liées à la SAE
                            $sql_evaluations = "SELECT * FROM Evaluation WHERE nom_SAE = :nom_SAE";
                            $stmt_evaluations = $pdo->prepare($sql_evaluations);
                            $stmt_evaluations->execute(['nom_SAE' => $sae['nom_SAE']]);
                            $evaluations = $stmt_evaluations->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($evaluations as $evaluation):
                                echo '<div class="evaluation">';
                                echo '<h3>' . htmlspecialchars($evaluation['nom_evaluation']) . '</h3>';

                                // Ajout des boutons modifier et supprimer
                                echo '<button class="modify-evaluation" data-evaluation-id="' . $evaluation['id_evaluation'] . '">Modifier</button>';
                                echo '<form action="supp_note.php" method="POST" style="display:inline;">';
                                echo '<input type="hidden" name="id_evaluation" value="' . $evaluation['id_evaluation'] . '">';
                                echo '<button type="submit" class="delete-evaluation" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette évaluation ?\');">Supprimer</button>';
                                echo '</form>';

                                // Requête pour récupérer les notes des étudiants par évaluation
                                $sql_notes = "SELECT E.ID_Etudiants, E.nom, E.prenom, G.numero_TD, G.lettre_TP, N.note 
                                            FROM Note N
                                            JOIN Profil_etudiant E ON N.ID_Etudiants = E.ID_Etudiants
                                            JOIN Groupe G ON E.ID_groupe = G.ID_groupe
                                            WHERE N.id_evaluation = :id_evaluation
                                            ORDER BY G.numero_TD, G.lettre_TP, E.nom";
                                $stmt_notes = $pdo->prepare($sql_notes);
                                $stmt_notes->execute(['id_evaluation' => $evaluation['id_evaluation']]);
                                $notes = $stmt_notes->fetchAll(PDO::FETCH_ASSOC);

                                $tds = [];
                                foreach ($notes as $note) {
                                    $td = $note['numero_TD'];
                                    $tp = $note['lettre_TP'];
                                    if (!isset($tds[$td])) {
                                        $tds[$td] = [];
                                    }
                                    if (!isset($tds[$td][$tp])) {
                                        $tds[$td][$tp] = ['notes' => [], 'count' => 0, 'sum' => 0];
                                    }
                                    if ($note['note'] !== null) {
                                        $tds[$td][$tp]['notes'][] = $note['note'];
                                        $tds[$td][$tp]['count']++;
                                        $tds[$td][$tp]['sum'] += $note['note'];
                                    }
                                }

                                foreach ($tds as $td => $tps) {
                                    $td_displayed = false;
                                    foreach ($tps as $tp => $data) {
                                        if ($data['count'] > 0) {
                                            if (!$td_displayed) {
                                                echo '<div class="td"><h4>TD' . htmlspecialchars($td) . '</h4>';
                                                $td_displayed = true;
                                            }
                                            $average = $data['sum'] / $data['count'];
                                            echo '<div class="tp">';
                                            echo '<h5>TP' . htmlspecialchars($tp) . '</h5>';
                                            echo '<p>Moyenne: ' . round($average, 2) . '</p>';
                                            echo '</div>';
                                        }
                                    }
                                    if ($td_displayed) {
                                        echo '</div>';
                                    }
                                }

                                echo '</div>';
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>

            <!-- Popup pour ajouter des notes -->
            <div id="popup-add-<?= $sae['nom_SAE'] ?>" class="popup">
                <div class="popup-content">
                    <span class="popup-close">&times;</span>
                    <form action="add_note.php" method="post">
                        <div class="popup-header">
                            <p>Professeur: <?= htmlspecialchars($professeur_nom) ?></p>
                            <div>
                                <label for="ressources-<?= $sae['nom_SAE'] ?>">Ressources: </label>
                                <select id="ressources-<?= $sae['nom_SAE'] ?>" name="num_ressource">
                                    <?php foreach ($ressources as $r): ?>
                                        <option value="<?= $r['num_ressource'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="saes-<?= $sae['nom_SAE'] ?>">SAE: </label>
                                <select id="saes-<?= $sae['nom_SAE'] ?>" name="nom_SAE">
                                    <option value="<?= $sae['nom_SAE'] ?>"><?= htmlspecialchars($sae['nom_SAE']) ?></option>
                                </select>
                            </div>
                            <div>
                                <label for="nom_evaluation-<?= $sae['nom_SAE'] ?>">Nom de l'évaluation:</label>
                                <input type="text" id="nom_evaluation-<?= $sae['nom_SAE'] ?>" name="nom_evaluation">
                            </div>
                            <div>
                                <label for="date_jour-<?= $sae['nom_SAE'] ?>">Date de l'évaluation:</label>
                                <input type="date" id="date_jour-<?= $sae['nom_SAE'] ?>" name="date_jour">
                            </div>
                            <div>
                                <label for="coefficient-<?= $sae['nom_SAE'] ?>">Coefficient:</label>
                                <input type="number" id="coefficient-<?= $sae['nom_SAE'] ?>" name="coefficient">
                            </div>
                            <input type="hidden" name="ID_prof" value="<?= $id_prof ?>">
                        </div>
                        <div class="popup-table">
                            <?php
                            $current_td = '';
                            $current_tp = '';
                            foreach ($etudiants as $etudiant):
                                if ($current_td !== $etudiant['numero_TD']) {
                                    if ($current_td !== '') {
                                        echo '</tbody></table>';
                                    }
                                    $current_td = $etudiant['numero_TD'];
                                    $current_tp = ''; // Reset TP for new TD
                                    echo '<table><thead><tr><th colspan="5" class="td-header">TD' . htmlspecialchars($etudiant['numero_TD']) . ' <input type="checkbox" class="select-all-td" data-td="' . htmlspecialchars($etudiant['numero_TD']) . '"></th></tr></thead><tbody>';
                                }
                                if ($current_tp !== $etudiant['lettre_TP']) {
                                    if ($current_tp !== '') {
                                        echo '</tbody></table>';
                                    }
                                    $current_tp = $etudiant['lettre_TP'];
                                    echo '<table><thead><tr><th colspan="5" class="tp-header">TP' . htmlspecialchars($etudiant['lettre_TP']) . ' <input type="checkbox" class="select-all-tp" data-tp="' . htmlspecialchars($etudiant['lettre_TP']) . '" data-td="' . htmlspecialchars($etudiant['numero_TD']) . '"></th></tr>';
                                    echo '<tr><th>N°</th><th>Nom</th><th>Prénom</th><th>Note</th><th>Présence</th></tr></thead><tbody>';
                                }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($etudiant['ID_Etudiants']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                                    <td><input type="number" step="0.1" min="0" max="20" name="note-<?= $etudiant['ID_Etudiants'] ?>"></td>
                                    <td><input type="checkbox" name="presence-<?= $etudiant['ID_Etudiants'] ?>" class="presence-checkbox" data-td="<?= htmlspecialchars($etudiant['numero_TD']) ?>" data-tp="<?= htmlspecialchars($etudiant['lettre_TP']) ?>"></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody></table>
                        </div>
                        <button type="submit">Soumettre</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<script src="../../js/prof/acceuil_prof.js"></script>
</body>
</html>