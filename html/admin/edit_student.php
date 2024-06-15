<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour récupérer les informations de l'étudiant
    $sql_student = "SELECT pe.ID_Etudiants, pe.nom, pe.prenom, pe.mot_de_Passe, g.numero_TD, g.lettre_TP, pe.annee_univ, pe.ID_groupe
                    FROM Profil_etudiant pe
                    JOIN Groupe g ON pe.ID_groupe = g.ID_groupe
                    WHERE pe.ID_Etudiants = ?";
    $stmt_student = $pdo->prepare($sql_student);
    $stmt_student->execute([$id]);
    $student_data = $stmt_student->fetch();

    if (!$student_data) {
        echo "Aucun étudiant trouvé avec cet ID.";
        exit;
    }

    // Requête pour récupérer les TP disponibles pour le TD de l'étudiant
    $sql_tp = "SELECT DISTINCT lettre_TP FROM Groupe WHERE numero_TD = ?";
    $stmt_tp = $pdo->prepare($sql_tp);
    $stmt_tp->execute([$student_data['numero_TD']]);
    $tp_options = $stmt_tp->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "ID d'étudiant non spécifié.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer l'étudiant</title>
    <link rel="stylesheet" href="../../css/admin/admin_aj_td_prof_form.css">
    <style>
        .selected-item {
            background-color: #e0e0e0;
            border-radius: 15px;
            padding: 5px 10px;
            display: inline-block;
            margin: 5px 0;
            cursor: pointer;
        }
        .selected-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .dropdown-menu {
            max-height: 150px; /* Adjust the height as needed */
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="body-aj-td">
        <div class="block-aj-td">
            <h1>PROFILE - ÉTUDIANT(E)</h1>
            <!-- DEBUT formulaire -->
            <form class="form-aj-td" action="update_student.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $student_data['ID_Etudiants']; ?>">
                <!-- nom et prénom -->
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="name_td" name="name_td" value="<?php echo htmlspecialchars($student_data['nom']); ?>" oninput="updateIdentifiant()" required>
                    <span>Nom</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="prenom_etudiant" name="prenom_etudiant" value="<?php echo htmlspecialchars($student_data['prenom']); ?>" oninput="updateIdentifiant()" required>
                    <span>Prénom</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="identifiant" name="identifiant" value="<?php echo strtolower(htmlspecialchars($student_data['nom'] . '.' . $student_data['prenom'])); ?>" readonly>
                    <span>Identifiant</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <select id="tp" name="tp" required>
                        <option value="" disabled>Choisissez un TP</option>
                        <?php
                        foreach ($tp_options as $tp) {
                            $selected = ($tp['lettre_TP'] == $student_data['lettre_TP']) ? 'selected' : '';
                            echo "<option value='{$tp['lettre_TP']}' $selected>{$tp['lettre_TP']}</option>";
                        }
                        ?>
                    </select>
                    <span>TP</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="number" id="td" name="td" value="<?php echo htmlspecialchars($student_data['numero_TD']); ?>" readonly>
                    <span>TD</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="annee_universitaire" name="annee_universitaire" maxlength="9" value="<?php echo htmlspecialchars($student_data['annee_univ']); ?>" oninput="formatYear()" required>
                    <span>Année universitaire</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="password" id="mot_de_passe" name="mot_de_passe" value="<?php echo htmlspecialchars($student_data['mot_de_Passe']); ?>" required>
                    <span>Mot de passe</span>
                </div>
                <!-- FIN nom et prénom -->

                <!-- DEBUT button-->
                <div class="btn-effacer-ajouter">
                    <button type="submit" class="aj-td-btn-ajouter"><b>MODIFIER</b></button>
                </div>
                <!-- FIN button -->
            </form>
            <!-- FIN formulaire -->
        </div>
    </div>
    <script>
        function updateIdentifiant() {
            const nom = document.getElementById('name_td').value.trim().toLowerCase();
            const prenom = document.getElementById('prenom_etudiant').value.trim().toLowerCase();
            const identifiantInput = document.getElementById('identifiant');
            identifiantInput.value = nom && prenom ? `${nom}.${prenom}` : '';
        }

        function formatYear() {
            const anneeInput = document.getElementById('annee_universitaire');
            const annee = anneeInput.value.replace(/[^0-9-]/g, ''); // Remove any non-numeric and non-dash characters
            anneeInput.value = annee.length > 9 ? annee.slice(0, 9) : annee; // Limit to 9 characters
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('name_td').addEventListener('input', updateIdentifiant);
            document.getElementById('prenom_etudiant').addEventListener('input', updateIdentifiant);
            document.getElementById('annee_universitaire').addEventListener('input', formatYear);
        });
    </script>
</body>
</html>

