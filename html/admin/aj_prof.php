<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Requête pour récupérer toutes les ressources
$sql_ressources = "SELECT num_ressource, nom FROM Ressources";
$stmt_ressources = $pdo->query($sql_ressources);
$ressources = [];
if ($stmt_ressources->rowCount() > 0) {
    while ($row = $stmt_ressources->fetch()) {
        $ressources[$row['num_ressource']] = $row['nom'];
    }
}

// Requête pour récupérer toutes les SAE
$sql_sae = "SELECT nom_SAE FROM SAE";
$stmt_sae = $pdo->query($sql_sae);
$saes = [];
if ($stmt_sae->rowCount() > 0) {
    while ($row = $stmt_sae->fetch()) {
        $saes[] = $row['nom_SAE'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajout professeure</title>
    <link rel="stylesheet" href="../../css/admin/admin_aj_td_prof_form.css">
    <style>
        .dropdown-menu {
            max-height: 150px; /* Adjust the height as needed */
            overflow-y: auto;
        }
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
    </style>
</head>
<body>
<div class="body-aj-td">
    <div class="block-aj-td">
        <h1>PROFILE - PROFESSEUR(E)</h1>
        <!-- DEBUT formulaire -->
        <form class="form-aj-td" id="prof-form" action="add_prof.php" method="POST">
            <!-- nom TD et effectifs-->
            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="name_td" name="name_td" oninput="updateIdentifiant()" placeholder=" " required>
                <span>nom</span>
            </div>

            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="prenom_td" name="prenom_td" oninput="updateIdentifiant()" placeholder=" " required>
                <span>prénom</span>
            </div>

            <div class="block-selection">
                <input type="checkbox" id="ressources" class="dropdown-toggle">
                <label for="ressources" class="dropdown-label ressources">ressources</label>
                <div class="dropdown-menu">
                    <?php
                    foreach ($ressources as $num => $nom) {
                        echo "<label class='dropdown-item'>
                                <input type='checkbox' name='ressources[]' value='$num' class='dropdown-checkbox ressources-checkbox' data-label='$nom'> $nom
                              </label>";
                    }
                    ?>
                </div>
            </div>
            <div id="selected-options-ressources">
                <div id="selected-list-ressources" class="selected-list"></div>
            </div>
            <br>            

            <div class="block-selection">
                <input type="checkbox" id="sae" class="dropdown-toggle">
                <label for="sae" class="dropdown-label sae">saé</label>
                <div class="dropdown-menu">
                    <?php
                    foreach ($saes as $sae) {
                        echo "<label class='dropdown-item'>
                                <input type='checkbox' name='sae[]' value='$sae' class='dropdown-checkbox sae-checkbox' data-label='$sae'> $sae
                              </label>";
                    }
                    ?>
                </div>
            </div>
            <div id="selected-options-sae">
                <div id="selected-list-sae" class="selected-list"></div>
            </div>

            <div class="input-aj-td-prof-etudiant">
                <input type="text" id="identifiant_td" name="identifiant_td" readonly placeholder=" " required>
                <span>identifiant</span>
            </div>

            <div class="input-aj-td-prof-etudiant">
                <input type="password" id="mdp_td" name="mdp_td" placeholder=" " required>
                <span>mots de passe</span>
            </div>
            <!-- FIN nom TD et effectifs-->

            <!-- DEBUT button-->
            <div class="btn-effacer-ajouter">
                <button type="button" class="aj-td-btn-effacer" id="effacer-btn"><b>EFFACER</b></button>
                <button type="submit" class="aj-td-btn-ajouter"><b>AJOUTER</b></button>
            </div>
            <!-- FIN button -->

        </form>
        <!-- FIN formulaire -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('prof-form');
        const effacerBtn = document.getElementById('effacer-btn');

        const nameTd = document.getElementById('name_td');
        const prenomTd = document.getElementById('prenom_td');
        const identifiantTd = document.getElementById('identifiant_td');

        const ressourcesCheckboxes = document.querySelectorAll('.ressources-checkbox');
        const saeCheckboxes = document.querySelectorAll('.sae-checkbox');

        const selectedListRessources = document.getElementById('selected-list-ressources');
        const selectedListSae = document.getElementById('selected-list-sae');

        ressourcesCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedOptions);
        });

        saeCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedOptions);
        });

        nameTd.addEventListener('input', updateIdentifiant);
        prenomTd.addEventListener('input', updateIdentifiant);

        function updateIdentifiant() {
            const nom = nameTd.value.trim().toLowerCase();
            const prenom = prenomTd.value.trim().toLowerCase();
            const identifiant = `${nom}.${prenom}`;
            identifiantTd.value = identifiant;
            updateLabel('identifiant_td');
        }

        function updateLabel(id) {
            const input = document.getElementById(id);
            if (input.value.trim() !== "") {
                input.classList.add('not-empty');
            } else {
                input.classList.remove('not-empty');
            }
        }

        function updateSelectedOptions() {
            updateList(ressourcesCheckboxes, selectedListRessources);
            updateList(saeCheckboxes, selectedListSae);
        }

        function updateList(checkboxes, listElement) {
            listElement.innerHTML = '';
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const listItem = document.createElement('div');
                    listItem.textContent = checkbox.getAttribute('data-label');
                    listItem.className = 'selected-item';
                    listElement.appendChild(listItem);
                }
            });
        }

        effacerBtn.addEventListener('click', function() {
            form.reset();
            selectedListRessources.innerHTML = '';
            selectedListSae.innerHTML = '';
            identifiantTd.value = '';
            updateLabel('identifiant_td');
        });
    });
</script>
</body>
</html>
