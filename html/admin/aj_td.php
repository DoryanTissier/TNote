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
            <!-- nom TD et lettres -->
            <div class="input-aj-td-prof-etudiant">
                <input type="number" id="name_td" name="name_td" placeholder=" " required>
                <span>TD</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="letter_a" name="letter_a" placeholder=" " maxlength="1" required>
                <span>Groupe 1 (exemple : a )</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="letter_b" name="letter_b" placeholder=" " maxlength="1" required>
                <span>Groupe 2 (exemple : b )</span>
            </div>
            <!-- FIN nom TD et lettres -->

            <!-- DEBUT button de validation -->
            <div class="btn-effacer-ajouter">
                <button class="aj-td-btn-ajouter" type="button" onclick="afficherChampsEffectif()"><b>VALIDER</b></button>
            </div>
            <!-- FIN button de validation -->

            <!-- Champs d'effectifs dynamiques -->
            <div id="effectif-fields" style="display: none;">
                <!-- Les champs seront ajoutés ici par JavaScript -->
            </div>

            <!-- DEBUT button d'ajout -->
            <div class="btn-effacer-ajouter" id="submit-button" style="display: none;">
                <button class="aj-td-btn-effacer" type="button" onclick="effacerDonnees()"><b>EFFACER</b></button>
                <button class="aj-td-btn-ajouter" type="submit"><b>AJOUTER</b></button>
            </div>
            <!-- FIN button d'ajout -->
        </form>
        <!-- FIN formulaire -->
    </div>
</div>

<script>
    function afficherChampsEffectif() {
        const letterA = document.getElementById('letter_a').value.trim().toLowerCase();
        const letterB = document.getElementById('letter_b').value.trim().toLowerCase();

        if (letterA.length === 1 && letterB.length === 1 && letterA >= 'a' && letterA <= 'z' && letterB >= 'a' && letterB <= 'z' && letterA <= letterB) {
            const effectifFields = document.getElementById('effectif-fields');
            effectifFields.innerHTML = ''; // Clear previous fields

            for (let i = letterA.charCodeAt(0); i <= letterB.charCodeAt(0); i++) {
                const letter = String.fromCharCode(i);
                const div = document.createElement('div');
                div.className = 'input-aj-td-prof-etudiant';
                div.innerHTML = `
                    <input type="number" id="effectif_${letter}" name="effectif_${letter}" placeholder=" " required>
                    <span>Effectif ${letter.toUpperCase()}</span>
                `;
                effectifFields.appendChild(div);
            }

            effectifFields.style.display = 'block';
            document.getElementById('submit-button').style.display = 'flex';
        } else {
            alert('Veuillez entrer des lettres valides entre A et Z.');
        }
    }

    function effacerDonnees() {
        document.getElementById('saisie-form').reset();
        document.getElementById('effectif-fields').style.display = 'none';
        document.getElementById('submit-button').style.display = 'none';
    }
</script>
</body>
</html>
