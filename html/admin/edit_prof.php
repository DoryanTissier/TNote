<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour récupérer les informations du professeur
    $sql_prof = "SELECT pp.ID_prof, pp.nom, pp.prenom, pp.mot_de_passe
                 FROM Profil_prof pp
                 WHERE pp.ID_prof = ?";
    $stmt_prof = $pdo->prepare($sql_prof);
    $stmt_prof->execute([$id]);
    $prof_data = $stmt_prof->fetch();

    if (!$prof_data) {
        echo "Aucun professeur trouvé avec cet ID.";
        exit;
    }

    // Requête pour récupérer les ressources associées au professeur
    $sql_ressources = "SELECT r.num_ressource, r.nom AS nom_ressource FROM Liaison_ressources_prof lrp
                       JOIN Ressources r ON lrp.num_ressource = r.num_ressource
                       WHERE lrp.id_prof = ?";
    $stmt_ressources = $pdo->prepare($sql_ressources);
    $stmt_ressources->execute([$id]);
    $ressources_associees = [];
    while ($row = $stmt_ressources->fetch()) {
        $ressources_associees[$row['num_ressource']] = $row['nom_ressource'];
    }

    // Requête pour récupérer les SAE associées au professeur
    $sql_sae = "SELECT ls.nom_SAE FROM Liaison_SAE_prof lsp
                JOIN SAE ls ON lsp.nom_SAE = ls.nom_SAE
                WHERE lsp.id_prof = ?";
    $stmt_sae = $pdo->prepare($sql_sae);
    $stmt_sae->execute([$id]);
    $sae_associees = [];
    while ($row = $stmt_sae->fetch()) {
        $sae_associees[] = $row['nom_SAE'];
    }
} else {
    echo "ID de professeur non spécifié.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer le professeur</title>
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
            <h1>PROFILE - PROFESSEUR(E)</h1>
            <!-- DEBUT formulaire -->
            <form class="form-aj-td" action="update_prof.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $prof_data['ID_prof']; ?>">
                <!-- nom et prénom -->
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="name_td" name="nom" value="<?php echo $prof_data['nom']; ?>" required>
                    <span>Nom</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="prenom_td" name="prenom" value="<?php echo $prof_data['prenom']; ?>" required>
                    <span>Prénom</span>
                </div>

                <div class="block-selection">
                    <input type="checkbox" id="ressources" class="dropdown-toggle">
                    <label for="ressources" class="dropdown-label ressources">ressources</label>
                    <div class="dropdown-menu">
                        <?php
                        $ressources = [
                            1 => "Hébergement",
                            2 => "Culture numérique",
                            3 => "Développement web",
                            4 => "Mathématiques",
                            5 => "Conception 3D",
                            6 => "Intégration",
                            7 => "Expression com. et rhétorique",
                            8 => "Culture artistique",
                            9 => "Python",
                            10 => "PPP",
                            11 => "Anglais"
                        ];
                        foreach ($ressources as $num => $nom) {
                            $checked = isset($ressources_associees[$num]) ? 'checked' : '';
                            echo "<label class='dropdown-item'>
                                    <input type='checkbox' name='ressources[]' value='$num' class='dropdown-checkbox ressources-checkbox' data-label='$nom' $checked> $nom
                                  </label>";
                        }
                        ?>
                    </div>
                </div>
                <div id="selected-options-ressources">
                    <div id="selected-list-ressources" class="selected-list">
                        <?php
                        foreach ($ressources_associees as $nom_ressource) {
                            echo "<div class='selected-item' data-value='$nom_ressource'>$nom_ressource</div>";
                        }
                        ?>
                    </div>
                </div>
                <br>            

                <div class="block-selection">
                    <input type="checkbox" id="sae" class="dropdown-toggle">
                    <label for="sae" class="dropdown-label sae">saé</label>
                    <div class="dropdown-menu">
                        <?php
                        $saes = [
                            "101", "102", "103", "104", "105", "106", "201", "202", "203", "204"
                        ];
                        foreach ($saes as $sae) {
                            $checked = in_array($sae, $sae_associees) ? 'checked' : '';
                            echo "<label class='dropdown-item'>
                                    <input type='checkbox' name='sae[]' value='$sae' class='dropdown-checkbox sae-checkbox' data-label='$sae' $checked> $sae
                                  </label>";
                        }
                        ?>
                    </div>
                </div>
                <div id="selected-options-sae">
                    <div id="selected-list-sae" class="selected-list">
                        <?php
                        foreach ($sae_associees as $sae) {
                            echo "<div class='selected-item' data-value='$sae'>$sae</div>";
                        }
                        ?>
                    </div>
                </div>
                
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="identifiant" name="identifiant" value="<?php echo $prof_data['nom'] . '.' . $prof_data['prenom']; ?>" class="not-empty" readonly>
                    <span>Identifiant</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="password" id="mot_de_passe" name="mot_de_passe" value="<?php echo $prof_data['mot_de_passe']; ?>" required>
                    <span>Mot de passe</span>
                </div>
                <!-- FIN nom et prénom -->

                <!-- DEBUT button-->
                <div class="btn-effacer-ajouter">
                    <button type="submit" formaction="delete_prof.php" class="aj-td-btn-effacer"><b>EFFACER</b></button>
                    <button type="submit" class="aj-td-btn-ajouter"><b>MODIFIER</b></button>
                </div>
                <!-- FIN button -->
            </form>
            <!-- FIN formulaire -->
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nomInput = document.getElementById('name_td');
            const prenomInput = document.getElementById('prenom_td');
            const identifiantInput = document.getElementById('identifiant');

            function updateIdentifiant() {
                const nom = nomInput.value.trim().toLowerCase();
                const prenom = prenomInput.value.trim().toLowerCase();
                identifiantInput.value = nom && prenom ? `${nom}.${prenom}` : '';
                identifiantInput.classList.toggle('not-empty', identifiantInput.value !== '');
            }

            nomInput.addEventListener('input', updateIdentifiant);
            prenomInput.addEventListener('input', updateIdentifiant);

            const deleteButton = document.querySelector('.aj-td-btn-effacer');
            deleteButton.addEventListener('click', function(event) {
                if (!confirm("Êtes-vous sûr de vouloir supprimer ce professeur ?")) {
                    event.preventDefault(); // Empêche l'envoi du formulaire si l'utilisateur annule
                }
            });

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
                        listItem.dataset.value = checkbox.getAttribute('data-label');
                        listElement.appendChild(listItem);
                    }
                });
            }

            function toggleCheckbox(label) {
                const checkbox = Array.from(document.querySelectorAll('.dropdown-checkbox')).find(
                    cb => cb.getAttribute('data-label') === label
                );
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    updateSelectedOptions();
                }
            }

            selectedListRessources.addEventListener('click', function(event) {
                if (event.target.classList.contains('selected-item')) {
                    toggleCheckbox(event.target.dataset.value);
                }
            });

            selectedListSae.addEventListener('click', function(event) {
                if (event.target.classList.contains('selected-item')) {
                    toggleCheckbox(event.target.dataset.value);
                }
            });

            // Initialiser les listes sélectionnées
            updateSelectedOptions();
        });
    </script>
</body>
</html>

