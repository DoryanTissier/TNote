<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/admin/admin_aj_td_prof_form.css">
</head>
<body>
<div class="body-aj-td">
    <div class="block-aj-td">
        <h1>SAISIE TD</h1>
        <!-- DEBUT formulaire -->
        <form class="form-aj-td" id="saisie-form" method="POST" action="insert_td.php">
            <!-- nom TD et effectifs-->
            <div class="input-aj-td-prof-etudiant">
                <input type="number" id="name_td" name="name_td" placeholder=" " required>
                <span>TD</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="number" id="effectif_a" name="effectif_a" placeholder=" " required>
                <span>Effectif A</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="number" id="effectif_b" name="effectif_b" placeholder=" " required>
                <span>Effectif B</span>
            </div>
            <!-- FIN nom TD et effectifs-->

            <!-- DEBUT button-->
            <div class="btn-effacer-ajouter">
                <button class="aj-td-btn-effacer" type="button" onclick="effacerDonnees()"><b>EFFACER</b></button>
                <button class="aj-td-btn-ajouter" type="submit"><b>AJOUTER</b></button>
            </div>
            <!-- FIN button -->
        </form>
        <!-- FIN formulaire -->
    </div>
</div>

<script>
    function effacerDonnees() {
        document.getElementById('saisie-form').reset();
    }
</script>
</body>
</html>
