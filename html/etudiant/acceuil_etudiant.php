<?php
session_start();
include '../../PHP/Page_login/db_connect.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['profile_type'] !== 'etudiant') {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}

$id_p = $_SESSION['user_id'];

// Requête pour récupérer les informations de l'étudiant connecté
$sql_etudiant = "SELECT E.ID_Etudiants, E.nom, E.prenom, G.annee, G.lettre_TP, G.numero_TD
                 FROM Profil_etudiant E
                 JOIN Groupe G ON E.ID_groupe = G.ID_groupe
                 WHERE E.ID_Etudiants = :id_p";
$stmt_etudiant = $pdo->prepare($sql_etudiant);
$stmt_etudiant->execute(['id_p' => $id_p]);
$etudiant = $stmt_etudiant->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    echo "Erreur : Étudiant non trouvé.";
    exit();
}

// Requête SQL pour récupérer les notes de l'étudiant connecté
$sql_notes = "SELECT ID_Etudiants, note
              FROM Note
              WHERE ID_Etudiants = :id_p
              ORDER BY ID_Etudiants";
$stmt_notes = $pdo->prepare($sql_notes);
$stmt_notes->execute(['id_p' => $id_p]);
$notes = $stmt_notes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes de l'Étudiant</title>
    <link rel="stylesheet" href="../../css/etudiant/etudiant_header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
            font-weight: normal;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td {
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="header">
  <div class="dlogo">
      <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
  </div>
  <div class="navbar">
      <button class="value">Note</button>
      <a href="../page_profile/page_profile_etudiant.php" class="value" style="text-decoration: none;">Profil</a>
      <a href="../../PHP/Page_login/logout.php" class="value" style="text-decoration: none;">Déconnexion</a>
  </div>
</div>

<h2>Notes de <?php echo htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?></h2>

<?php if (count($notes) > 0): ?>
    <table>
        <tr>
            <th>ID Étudiants</th>
            <th>Note</th>
        </tr>
        <?php foreach ($notes as $note): ?>
            <tr>
                <td><?php echo htmlspecialchars($note['ID_Etudiants']); ?></td>
                <td><?php echo htmlspecialchars($note['note']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Aucune note disponible.</p>
<?php endif; ?>

</body>
</html>