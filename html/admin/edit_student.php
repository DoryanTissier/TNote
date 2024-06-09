<?php
include_once '../../PHP/Page_login/db_connect.php'; // Utiliser le bon chemin pour inclure db_connect.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour récupérer les informations de l'étudiant
    $sql_student = "SELECT pe.ID_Etudiants, pe.nom, pe.prenom, pe.mot_de_Passe
                    FROM Profil_etudiant pe
                    WHERE pe.ID_Etudiants = ?";
    $stmt_student = $pdo->prepare($sql_student);
    $stmt_student->execute([$id]);
    $student_data = $stmt_student->fetch();

    if (!$student_data) {
        echo "Aucun étudiant trouvé avec cet ID.";
        exit;
    }

    // Requête pour récupérer les ressources associées à l'étudiant
    $sql_ressources = "SELECT r.num_ressource, r.nom AS nom_ressource 
                       FROM Liaison_ressources_prof lrp
                       JOIN Ressources r ON lrp.num_ressource = r.num_ressource
                       WHERE lrp.id_prof = ?";
    $stmt_ressources = $pdo->prepare($sql_ressources);
    $stmt_ressources->execute([$id]);
    $ressources_associees = [];
    while ($row = $stmt_ressources->fetch()) {
        $ressources_associees[$row['num_ressource']] = $row['nom_ressource'];
    }

    // Requête pour récupérer les SAE associées à l'étudiant
    $sql_sae = "SELECT ls.nom_SAE 
                FROM Liaison_SAE_prof lsp
                JOIN SAE ls ON lsp.nom_SAE = ls.nom_SAE
                WHERE lsp.id_prof = ?";
    $stmt_sae = $pdo->prepare($sql_sae);
    $stmt_sae->execute([$id]);
    $sae_associees = [];
    while ($row = $stmt_sae->fetch()) {
        $sae_associees[] = $row['nom_SAE'];
    }
} else {
    echo "ID d'étudiant non spécifié.";
    exit;
}

// Requête pour récupérer toutes les ressources disponibles
$sql_all_ressources = "SELECT num_ressource, nom FROM Ressources";
$result_all_ressources = $pdo->query($sql_all_ressources);
$all_ressources = [];
while ($row = $result_all_ressources->fetch()) {
    $all_ressources[$row['num_ressource']] = $row['nom'];
}

// Requête pour récupérer toutes les SAE disponibles
$sql_all_sae = "SELECT nom_SAE FROM SAE";
$result_all_sae = $pdo->query($sql_all_sae);
$all_saes = [];
while ($row = $result_all_sae->fetch()) {
    $all_saes[] = $row['nom_SAE'];
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
                    <input type="text" id="name_td" name="nom" value="<?php echo $student_data['nom']; ?>" oninput="updateIdentifiant()" required>
                    <span>Nom</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="text" id="prenom_td" name="prenom" value="<?php echo $student_data['prenom']; ?>" oninput="updateIdentifiant()" required>
                    <span>Prénom</span>
                </div>

                <div class="block-selection">
                    <input type="checkbox" id="ressources" class="dropdown-toggle">
                    <label for="ressources" class="dropdown-label ressources">ressources</label>
                    <div class="dropdown-menu">
                        <?php
                        foreach ($all_ressources as $num => $nom) {
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
                        foreach ($all_saes as $sae) {
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
                    <input type="text" id="identifiant" name="identifiant" value="<?php echo $student_data['nom'] . '.' . $student_data['prenom']; ?>" class="not-empty" readonly>
                    <span>Identifiant</span>
                </div>
                <div class="input-aj-td-prof-etudiant">
                    <input type="password" id="mot_de_passe" name="mot_de_passe" value="<?php echo $student_data['mot_de_Passe']; ?>" required>
                    <span>Mot de passe</span>
                </div>
                <!-- FIN nom et prénom -->

                <!-- DEBUT button-->
                <div class="btn-effacer-ajouter">
                    <button type="submit" formaction="delete_student.php" class="aj-td-btn-effacer"><b>EFFACER</b></button>
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
                if (!confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?")) {
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
