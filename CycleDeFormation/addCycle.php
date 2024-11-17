<?php
// Inclusion du fichier de connexion à la base de données
include_once "../connect_ddb.php";

// Initialisation des variables
$nom_entreprise = $num_action = $credit_impot = $droit_tirage_individuel = $droit_tirage_collectif = $lieu_deroulement = $gouvernorat = $debut_cycle = $fin_cycle = $horaire_debut = $horaire_fin = $debut_pause = $fin_pause = "";
$nom_entreprise_err = $num_action_err = $id_formation_err = "";

// Récupération des formations disponibles
$formations = [];
$sql_formations = "SELECT id_formation, CONCAT(thème_formation, ' - ', mode_formation) AS label 
                   FROM Formation 
                   WHERE id_formation NOT IN (SELECT DISTINCT id_formation FROM CycleDeFormation)";
$result_formations = mysqli_query($conn, $sql_formations);
if (!$result_formations) {
    die("Erreur lors de la récupération des formations disponibles : " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result_formations)) {
    $formations[] = $row;
}

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
if (!$result_all_formateurs) {
    die("Erreur lors de la récupération des formateurs disponibles : " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result_all_formateurs)) {
    $all_formateurs[] = $row;
}

// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Echappement sécurisé des données
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

    // Validation des champs obligatoires
    if (empty($nom_entreprise)) {
        $nom_entreprise_err = "Veuillez saisir le nom de l'entreprise.";
    }
    if (empty($num_action)) {
        $num_action_err = "Veuillez saisir le numéro d'action.";
    }
    if (empty($id_formation)) {
        $id_formation_err = "Veuillez sélectionner une formation.";
    }

    // Si aucune erreur n'est détectée, procéder à l'insertion dans la base de données
    if (empty($nom_entreprise_err) && empty($num_action_err) && empty($id_formation_err)) {
        // Récupération des formateurs sélectionnés et concaténation en une chaîne
        $selected_formateurs = [];
        for ($i = 1; $i <= 4; $i++) {
            $formateur_id = mysqli_real_escape_string($conn, $_POST["formateur$i"]);
            if (!empty($formateur_id)) {
                $selected_formateurs[] = $formateur_id;
            }
        }
        $formateurs_str = implode(',', $selected_formateurs);

        // Insertion du cycle de formation
        $sql_insert = "INSERT INTO CycleDeFormation (nom_entreprise, num_action, crédit_impot, droit_tirage_individuel, droit_tirage_collectif, id_formation, lieu_déroulement, gouvernorat, début_cycle, fin_cycle, horaire_début, horaire_fin, début_pause, fin_pause, formateurs) 
                        VALUES ('$nom_entreprise', '$num_action', '$credit_impot', '$droit_tirage_individuel', '$droit_tirage_collectif', '$id_formation', '$lieu_deroulement', '$gouvernorat', '$debut_cycle', '$fin_cycle', '$horaire_debut', '$horaire_fin', '$debut_pause', '$fin_pause', '$formateurs_str')";

        if (mysqli_query($conn, $sql_insert)) {
            // Redirection vers la page de visualisation des cycles
            header("Location: showCycle.php");
            exit();
        } else {
            echo "Erreur lors de l'insertion : " . mysqli_error($conn);
        }
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Cycle de Formation</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            padding-top: 20px;
            background-color:#FFF9D0;
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
        .formateurs-group .form-group {
            width: 48%;
        }

        h1 {
            margin-bottom: 70px;
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
    </style>
    <script>
        function updateFormateurOptions(selectElement) {
            const selectedValues = Array.from(document.querySelectorAll('select.formateur')).map(select => select.value);
            const allOptions = Array.from(document.querySelectorAll('select.formateur option'));

            allOptions.forEach(option => {
                if (option.value !== "") {
                    option.disabled = selectedValues.includes(option.value) && option.selected === false;
                }
            });
        }
    </script>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1 style="text-align: center;">Ajouter Cycle de Formation</h1>

        <div class="form-group">
            <input type="text" id="nom_entreprise" name="nom_entreprise" placeholder="Nom Entreprise" value="<?php echo htmlspecialchars($nom_entreprise); ?>" required>
            <span class="error"><?php echo $nom_entreprise_err; ?></span>
        </div>

        <div class="form-group">
            <input type="number" id="num_action" name="num_action" placeholder="Numéro d'Action" value="<?php echo htmlspecialchars($num_action); ?>" required>
            <span class="error"><?php echo $num_action_err; ?></span>
        </div>

        <div class="form-group checkbox-group">
            <label for="crédit_impot">Crédit Impôt :</label>
            <input type="checkbox" id="crédit_impot" name="crédit_impot" <?php if ($credit_impot == 1) echo "checked"; ?>>
    
            <label for="droit_tirage_individuel">Droit Tirage Individuel :</label>
            <input type="checkbox" id="droit_tirage_individuel" name="droit_tirage_individuel" <?php if ($droit_tirage_individuel == 1) echo "checked"; ?>>
    
            <label for="droit_tirage_collectif">Droit Tirage Collectif :</label>
            <input type="checkbox" id="droit_tirage_collectif" name="droit_tirage_collectif" <?php if ($droit_tirage_collectif == 1) echo "checked"; ?>>
        </div>

        <div class="form-group">
            <label for="id_formation">Formation :</label>
            <select id="id_formation" name="id_formation" required>
                <option value="">Sélectionnez une formation</option>
                <?php foreach ($formations as $formation): ?>
                    <option value="<?= $formation['id_formation'] ?>">
                        <?= htmlspecialchars($formation['label']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="error"><?php echo $id_formation_err; ?></span>
        </div>

        <div class="form-group">
            <input type="text" id="lieu_déroulement" name="lieu_déroulement" placeholder="Lieu Déroulement" value="<?php echo htmlspecialchars($lieu_deroulement); ?>" required>
        </div>

        <div class="form-group">
            <input type="text" id="gouvernorat" name="gouvernorat" placeholder="Gouvernorat" value="<?php echo htmlspecialchars($gouvernorat); ?>" required>
        </div>

        <div class="inline-fields">
            <div class="form-group">
                <label for="début_cycle">Début Cycle :</label>
                <input type="date" id="début_cycle" name="début_cycle" value="<?php echo htmlspecialchars($debut_cycle); ?>" required>
            </div>

            <div class="form-group">
                <label for="fin_cycle">Fin Cycle :</label>
                <input type="date" id="fin_cycle" name="fin_cycle" value="<?php echo htmlspecialchars($fin_cycle); ?>" required>
            </div>
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

        <div class="formateurs-group">
    <?php for ($i = 1; $i <= 4; $i++): ?>
    <div class="form-group">
        <label for="formateur<?php echo $i; ?>">Formateur <?php echo $i; ?> :</label>
        <select id="formateur<?php echo $i; ?>" name="formateur<?php echo $i; ?>" class="formateur" onchange="updateFormateurOptions(this)">
            <option value="">Sélectionner un formateur</option>
            <?php foreach ($all_formateurs as $formateur): ?>
                <option value="<?php echo $formateur['id_formateur']; ?>"><?php echo htmlspecialchars($formateur['nom_et_prénom_formateur']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endfor; ?>
</div>


        <input class="aj" type="submit" name="send" value="Ajouter">
        <a href="showCycle.php" class="link back">Annuler</a>
    </form>
</body>
</html>