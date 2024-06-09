<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajout étudiant</title>
    <link rel="stylesheet" href="../../css/admin/admin_aj_td_prof_form.css">
</head>
<body>
<div class="body-aj-td">
    <div class="block-aj-td">
        <h1>PROFILE - ÉTUDIANT(E)</h1>
        <!-- DEBUT formulaire -->
        <form class="form-aj-td" action="add_student.php" method="POST">
            <!-- nom TD et effectifs-->
            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="name_td" name="name_td" oninput="updateIdentifiant()" required>
                <span>Nom</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="prenom_etudiant" name="prenom_etudiant" oninput="updateIdentifiant()" required>
                <span>Prénom</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="identifiant" name="identifiant" readonly>
                <span>Identifiant</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="tp" name="tp" required>
                <span>TP</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="number" id="td" name="td" required>
                <span>TD</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="annee_universitaire" name="annee_universitaire" maxlength="9" oninput="formatYear()" required>
                <span>Année universitaire</span>
            </div>
            <div class="input-aj-td-prof-etudiant">
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                <span>Mot de passe</span>
            </div>
            <!-- FIN nom TD et effectifs-->

            <!-- DEBUT button-->
            <div class="btn-effacer-ajouter">
                <button type="button" class="aj-td-btn-effacer" onclick="effacerDiv()"><b>EFFACER</b></button>
                <button type="submit" class="aj-td-btn-ajouter"><b>AJOUTER</b></button>
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
        const identifiant = `${nom}.${prenom}`;
        document.getElementById('identifiant').value = identifiant;
        updateLabel('identifiant');
    }

    function updateLabel(id) {
        const input = document.getElementById(id);
        if (input.value.trim() !== "") {
            input.classList.add('not-empty');
        } else {
            input.classList.remove('not-empty');
        }
    }

    function effacerDiv() {
        // Sélectionnez tous les champs de formulaire à réinitialiser
        document.querySelectorAll('.form-aj-td input').forEach(input => input.value = "");
        updateIdentifiant(); // Réinitialiser l'identifiant
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser l'état des labels des inputs
        document.querySelectorAll('.input-aj-td-prof-etudiant input').forEach(input => {
            updateLabel(input.id);
            input.addEventListener('input', () => updateLabel(input.id));
        });
    });

    function formatYear() {
        const input = document.getElementById('annee_universitaire');
        let value = input.value.replace(/[^0-9]/g, ''); // Enlever tous les caractères non numériques
        if (value.length > 4) {
            value = value.slice(0, 4) + '-' + value.slice(4, 8);
        }
        input.value = value;
    }
</script>
</body>
</html>




