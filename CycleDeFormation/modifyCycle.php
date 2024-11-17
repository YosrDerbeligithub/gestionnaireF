<?php
// Inclusion du fichier de connexion à la base de données
include_once "../connect_ddb.php";

// Initialisation des variables
$nom_entreprise = $num_action = $credit_impot = $droit_tirage_individuel = $droit_tirage_collectif = $lieu_deroulement = $gouvernorat = $debut_cycle = $fin_cycle = $horaire_debut = $horaire_fin = $debut_pause = $fin_pause = "";
$nom_entreprise_err = $num_action_err = $id_formation_err = "";

// Vérification de l'ID du cycle à modifier (par exemple, passé via l'URL)
$id_cycle = isset($_GET['id_cycle']) ? mysqli_real_escape_string($conn, $_GET['id_cycle']) : null;

// Récupération des formations disponibles
$formations = [];
$sql_formations = "
    SELECT id_formation, CONCAT(thème_formation, ' - ', mode_formation) AS label 
    FROM Formation 
    WHERE id_formation NOT IN (
        SELECT DISTINCT id_formation 
        FROM CycleDeFormation
    )";
$result_formations = mysqli_query($conn, $sql_formations);

if ($result_formations) {
    while ($row = mysqli_fetch_assoc($result_formations)) {
        $formations[] = $row;
    }
} else {
    echo "Erreur lors de la récupération des formations : " . mysqli_error($conn);
}

// Récupération des formateurs disponibles
$all_formateurs = [];
$sql_all_formateurs = "SELECT id_formateur, nom_et_prénom_formateur 
                       FROM Formateur 
                       WHERE id_formateur NOT IN (
                           SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(formateurs, ',', numbers.n), ',', -1) AS id_formateur
                           FROM CycleDeFormation
                           JOIN (
                               SELECT 1 + a.N + b.N * 10 AS n
                               FROM 
                               (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL 
                                SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
                                CROSS JOIN 
                                (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL 
                                SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
                           ) AS numbers
                           ON CHAR_LENGTH(formateurs) <= CHAR_LENGTH(REPLACE(formateurs, ',', '')) + 1
                           WHERE numbers.n <= 1 + (CHAR_LENGTH(formateurs) - CHAR_LENGTH(REPLACE(formateurs, ',', '')))
                       )";
$result_all_formateurs = mysqli_query($conn, $sql_all_formateurs);

if ($result_all_formateurs) {
    while ($row = mysqli_fetch_assoc($result_all_formateurs)) {
        $all_formateurs[] = $row;
    }
} else {
    die("Erreur lors de la récupération des formateurs disponibles : " . mysqli_error($conn));
}

// Récupération des données du cycle à modifier
$selected_formateurs = [];
if ($id_cycle) {
    $sql_cycle_data = "SELECT formateurs FROM CycleDeFormation WHERE id_cycle = $id_cycle";
    $result_cycle_data = mysqli_query($conn, $sql_cycle_data);
    if ($result_cycle_data) {
        $cycle_data = mysqli_fetch_assoc($result_cycle_data);
        if ($cycle_data && isset($cycle_data['formateurs'])) {
            // Récupération des IDs des formateurs existants dans le cycle
            $selected_formateurs = explode(',', $cycle_data['formateurs']);
        }
    } else {
        die("Erreur lors de la récupération des données du cycle : " . mysqli_error($conn));
    }
}

// Récupération des données du cycle à modifier
if ($id_cycle) {
    $sql_cycle = "SELECT * FROM CycleDeFormation WHERE id_cycle = $id_cycle";
    $result_cycle = mysqli_query($conn, $sql_cycle);
    if ($result_cycle) {
        $cycle_data = mysqli_fetch_assoc($result_cycle);
        if ($cycle_data) {
            // Assignation des valeurs récupérées aux variables de formulaire
            $nom_entreprise = $cycle_data['nom_entreprise'];
            $num_action = $cycle_data['num_action'];
            $credit_impot = $cycle_data['crédit_impot'];
            $droit_tirage_individuel = $cycle_data['droit_tirage_individuel'];
            $droit_tirage_collectif = $cycle_data['droit_tirage_collectif'];
            $id_formation = $cycle_data['id_formation'];
            $lieu_deroulement = $cycle_data['lieu_déroulement'];
            $gouvernorat = $cycle_data['gouvernorat'];
            $debut_cycle = $cycle_data['début_cycle'];
            $fin_cycle = $cycle_data['fin_cycle'];
            $horaire_debut = $cycle_data['horaire_début'];
            $horaire_fin = $cycle_data['horaire_fin'];
            $debut_pause = $cycle_data['début_pause'];
            $fin_pause = $cycle_data['fin_pause'];
            $selected_formateurs = explode(',', $cycle_data['formateurs']);
        }
    } else {
        echo "Erreur lors de la récupération des données du cycle : " . mysqli_error($conn);
    }
}

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation et assignation des données
    $nom_entreprise = mysqli_real_escape_string($conn, $_POST['nom_entreprise']);
    $num_action = mysqli_real_escape_string($conn, $_POST['num_action']);
    $credit_impot = isset($_POST['crédit_impot']) ? 1 : 0;
    $droit_tirage_individuel = isset($_POST['droit_tirage_individuel']) ? 1 : 0;
    $droit_tirage_collectif = isset($_POST['droit_tirage_collectif']) ? 1 : 0;
    $id_formation = mysqli_real_escape_string($conn, $_POST['id_formation']);
    $lieu_deroulement = mysqli_real_escape_string($conn, $_POST['lieu_déroulement']);
    $gouvernorat = mysqli_real_escape_string($conn, $_POST['gouvernorat']);
    $debut_cycle = mysqli_real_escape_string($conn, $_POST['début_cycle']);
    $fin_cycle = mysqli_real_escape_string($conn, $_POST['fin_cycle']);
    $horaire_debut = mysqli_real_escape_string($conn, $_POST['horaire_début']);
    $horaire_fin = mysqli_real_escape_string($conn, $_POST['horaire_fin']);
    $debut_pause = mysqli_real_escape_string($conn, $_POST['début_pause']);
    $fin_pause = mysqli_real_escape_string($conn, $_POST['fin_pause']);

    // Capture formateur IDs
    $selected_formateurs = [];
    for ($i = 1; $i <= 4; $i++) {
        $formateur_id = mysqli_real_escape_string($conn, $_POST["formateur$i"]);
        if (!empty($formateur_id)) {
            $selected_formateurs[] = $formateur_id;
        }
    }
    $formateurs_str = implode(',', $selected_formateurs);

    // Mise à jour des données du cycle dans la base de données
    if (empty($nom_entreprise_err) && empty($num_action_err) && empty($id_formation_err)) {
        $sql_update = "UPDATE CycleDeFormation SET 
                        nom_entreprise = '$nom_entreprise', 
                        num_action = '$num_action', 
                        crédit_impot = '$credit_impot', 
                        droit_tirage_individuel = '$droit_tirage_individuel', 
                        droit_tirage_collectif = '$droit_tirage_collectif', 
                        id_formation = '$id_formation', 
                        lieu_déroulement = '$lieu_deroulement', 
                        gouvernorat = '$gouvernorat', 
                        début_cycle = '$debut_cycle', 
                        fin_cycle = '$fin_cycle', 
                        horaire_début = '$horaire_debut', 
                        horaire_fin = '$horaire_fin', 
                        début_pause = '$debut_pause', 
                        fin_pause = '$fin_pause',
                        formateurs = '$formateurs_str' 
                        WHERE id_cycle = $id_cycle";

        if (mysqli_query($conn, $sql_update)) {
            header("Location: showCycle.php?id_cycle=$id_cycle");
            exit;
        } else {
            echo "Erreur lors de la mise à jour des données du cycle de formation : " . mysqli_error($conn);
        }
    }
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Cycle de Formation</title>
    <link rel="stylesheet" href="../style.css">
    <style>
body {
            padding-top: 20px;
            background-color:#FFF9D0;
        }
        input[type="submit"].aj {
background-color: #8fd4f9;
padding: 10px 20px;
border: none;
border-radius: 5px;
cursor: pointer;
font-size: 16px;
margin-top: 10px;
color: #fff;
}

input[type="submit"].aj:hover {
background-color: #CAF4FF; /* Hover background color */
}

.link.back {
display: inline-block; /* Make it behave like a button */
background-color: #8fd4f9;
padding: 10px 20px;
border: none;
border-radius: 5px;
cursor: pointer;
font-size: 16px;
margin-top: 10px;
color: #fff; /* Text color */
text-align: center; /* Center the text */
text-decoration: none; /* Remove underline */
line-height: normal; /* Ensure alignment consistency */
}

.link.back:hover {
background-color: #CAF4FF;
}

input[type="submit"].aj, .link.back {
vertical-align: middle; /* Align elements vertically */
}
form {
        width: 800px;
        margin: 0 auto; /* Centrer le formulaire */
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 16px;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="date"],
    .form-group input[type="time"],
    .form-group select {
        width: 100%;
        padding: 8px;
        font-size: 16px;
    }

    .form-group .error {
        color: red;
        font-size: 16px;
    }

    .checkbox-group {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .checkbox-group label {
        margin-right: 10px;
        font-size: 16px;
    }

    .inline-fields {
        display: flex;
        justify-content: space-between;
    }

    .inline-fields .form-group {
        width: 48%;
    }

    .formateurs-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .formateur-row {
        display: flex;
        width: 48%;
    }

    .form-group {
        width: 100%;
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 16px;
    }

    .form-group select {
        width: 100%;
        padding: 8px;
        font-size: 16px;
    }
    .formateur-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* Deux colonnes égales */
    gap: 10px; /* Espacement entre les éléments */
   }

   .formateur-group {
    margin-bottom: 10px; /* Espacement entre les groupes */
   }
   .formateur-group select {
    width: 100%; /* Utilise toute la largeur disponible */
    padding: 8px; /* Ajoute un padding pour l'espacement interne */
    font-size: 16px; /* Taille de police */
    border: 2px solid #ccc; /* Bordure fine */
    border-radius: 4px; /* Coins arrondis */
    box-sizing: border-box; /* Inclure la bordure et le padding dans la largeur totale */
}
.formateur-group label {
    font-size: 16px; /* Taille de police plus grande pour les labels */
    margin-bottom: 6px; /* Espacement inférieur pour séparer les labels des selects */
    display: block; /* Assure que chaque label occupe une ligne distincte */
}

    h1 {
        margin-bottom: 70px;
    }
</style>
<script>
    function updateFormateurOptions(select) {
        // Récupère la valeur sélectionnée dans le dropdown courant
        var selectedValue = select.value;
        
        // Parcourt tous les autres dropdowns dans la même ligne
        var selectors = select.parentNode.parentNode.querySelectorAll('select');
        selectors.forEach(function(selector) {
            // Vérifie que le dropdown n'est pas celui sur lequel l'événement onchange est déclenché
            if (selector !== select) {
                // Parcourt toutes les options pour désélectionner le formateur sélectionné dans les autres dropdowns
                var options = selector.options;
                for (var i = 0; i < options.length; i++) {
                    if (options[i].value === selectedValue) {
                        options[i].disabled = select.value !== ""; // Désactive l'option si une sélection est effectuée
                        options[i].selected = false; // Désélectionne l'option
                    } else {
                        options[i].disabled = false; // Réactive les autres options
                    }
                }
            }
        });
    }
</script>
</head>
<body>
    <h2>Modifier Cycle de Formation</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id_cycle=" . $id_cycle; ?>" method="post">
        <div class="form-group">
            <label for="nom_entreprise">Nom Entreprise :</label>
            <input type="text" id="nom_entreprise" name="nom_entreprise" value="<?php echo htmlspecialchars($nom_entreprise); ?>" required>
            <span class="error"><?php echo $nom_entreprise_err; ?></span>
        </div>
        <div class="form-group">
            <label for="num_action">Numéro Action :</label>
            <input type="text" id="num_action" name="num_action" value="<?php echo htmlspecialchars($num_action); ?>" required>
            <span class="error"><?php echo $num_action_err; ?></span>
        </div>
        <div class="form-group">
            <label for="crédit_impot">Crédit Impôt :</label>
            <input type="checkbox" id="crédit_impot" name="crédit_impot" <?php echo ($credit_impot) ? 'checked' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="droit_tirage_individuel">Droit Tirage Individuel :</label>
            <input type="checkbox" id="droit_tirage_individuel" name="droit_tirage_individuel" <?php echo ($droit_tirage_individuel) ? 'checked' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="droit_tirage_collectif">Droit Tirage Collectif :</label>
            <input type="checkbox" id="droit_tirage_collectif" name="droit_tirage_collectif" <?php echo ($droit_tirage_collectif) ? 'checked' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="id_formation">Formation :</label>
            <select id="id_formation" name="id_formation" required>
                <option value="">Sélectionnez une formation</option>
                <?php foreach ($formations as $formation) : ?>
                    <option value="<?php echo $formation['id_formation']; ?>" <?php echo ($id_formation == $formation['id_formation']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($formation['label']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="error"><?php echo $id_formation_err; ?></span>
        </div>
        <div class="form-group">
            <label for="lieu_déroulement">Lieu Déroulement :</label>
            <input type="text" id="lieu_déroulement" name="lieu_déroulement" value="<?php echo htmlspecialchars($lieu_deroulement); ?>" required>
        </div>
        <div class="form-group">
            <label for="gouvernorat">Gouvernorat :</label>
            <input type="text" id="gouvernorat" name="gouvernorat" value="<?php echo htmlspecialchars($gouvernorat); ?>" required>
        </div>
        <div class="form-group">
            <label for="début_cycle">Début Cycle :</label>
            <input type="date" id="début_cycle" name="début_cycle" value="<?php echo htmlspecialchars($debut_cycle); ?>" required>
        </div>
        <div class="form-group">
            <label for="fin_cycle">Fin Cycle :</label>
            <input type="date" id="fin_cycle" name="fin_cycle" value="<?php echo htmlspecialchars($fin_cycle); ?>" required>
        </div>
        <div class="inline-fields">
            <div class="form-group">
                <label for="horaire_début">Horaire Début :</label>
                <input type="time" id="horaire_début" name="horaire_début" value="<?php echo htmlspecialchars($horaire_debut); ?>" required>
            </div>
            <div class="form-group">
                <label for="horaire_fin">Horaire Fin :</label>
                <input type="time" id="horaire_fin" name="horaire_fin" value="<?php echo htmlspecialchars($horaire_fin); ?>" required>
            </div>
        </div>
        <div class="inline-fields">
            <div class="form-group">
                <label for="début_pause">Début Pause :</label>
                <input type="time" id="début_pause" name="début_pause" value="<?php echo htmlspecialchars($debut_pause); ?>" required>
            </div>
            <div class="form-group">
                <label for="fin_pause">Fin Pause :</label>
                <input type="time" id="fin_pause" name="fin_pause" value="<?php echo htmlspecialchars($fin_pause); ?>" required>
            </div>
        </div>
        <div id="formateur-selectors" class="formateur-grid">
    <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="formateur-group">
            <label for="formateur<?php echo $i; ?>">Formateur <?php echo $i; ?>:</label>
            <select name="formateur<?php echo $i; ?>" onchange="updateFormateurOptions(this)">
                <option value="">Sélectionner un formateur</option>
                <?php foreach ($all_formateurs as $formateur): ?>
                    <option value="<?php echo $formateur['id_formateur']; ?>" <?php echo (in_array($formateur['id_formateur'], $selected_formateurs)) ? 'selected' : ''; ?>>
                        <?php echo $formateur['nom_et_prénom_formateur']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endfor; ?>
</div>

        <input class="aj" type="submit" name="send" value="Modifier">
        <a href="showCycle.php" class="link back">Annuler</a>
    </form>
</body>
</html>
