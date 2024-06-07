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
      <button class="value">Profile</button>
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
                </div>
            </div>

            <!-- Popup pour ajouter des notes -->
            <div id="popup-add-<?= $ressource['num_ressource'] ?>" class="popup">
                <div class="popup-content">
                    <span class="popup-close">&times;</span>
                    <div class="popup-header">
                        <p>Professeur: <?= htmlspecialchars($professeur_nom) ?></p>
                        <div>
                            <label for="ressources-<?= $ressource['num_ressource'] ?>">Ressources: </label>
                            <select id="ressources-<?= $ressource['num_ressource'] ?>">
                                <?php foreach ($ressources as $r): ?>
                                    <option value="<?= $r['num_ressource'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="saes-<?= $ressource['num_ressource'] ?>">SAE: </label>
                            <select id="saes-<?= $ressource['num_ressource'] ?>">
                                <?php foreach ($saes as $sae): ?>
                                    <option value="<?= $sae['nom_SAE'] ?>"><?= htmlspecialchars($sae['nom_SAE']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
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
                </div>
            </div>

            <!-- Popup pour ajouter des notes -->
            <div id="popup-add-<?= $sae['nom_SAE'] ?>" class="popup">
                <div class="popup-content">
                    <span class="popup-close">&times;</span>
                    <div class="popup-header">
                        <p>Professeur: <?= htmlspecialchars($professeur_nom) ?></p>
                        <div>
                            <label for="ressources-<?= $sae['nom_SAE'] ?>">Ressources: </label>
                            <select id="ressources-<?= $sae['nom_SAE'] ?>">
                                <?php foreach ($ressources as $r): ?>
                                    <option value="<?= $r['num_ressource'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="saes-<?= $sae['nom_SAE'] ?>">SAEs: </label>
                            <select id="saes-<?= $sae['nom_SAE'] ?>">
                                <?php foreach ($saes as $sa): ?>
                                    <option value="<?= $sa['nom_SAE'] ?>"><?= htmlspecialchars($sa['nom_SAE']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<script src="../../js/prof/acceuil_prof.js"></script>
</body>
</html>
