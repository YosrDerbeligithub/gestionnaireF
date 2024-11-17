<?php
include_once "../connect_ddb.php";

// Initialisation des variables
$nom_et_prénom = "";
$num_cin = "";
$direction = "";
$nom_entreprise = "";
$thème_formation = "";
$début_cycle = "";

// Récupération des thèmes de formation et des dates de début
$sql_themes = "
    SELECT f.thème_formation, c.début_cycle 
    FROM CycleDeFormation c
    JOIN Formation f ON c.id_formation = f.id_formation
";
$result_themes = mysqli_query($conn, $sql_themes);

if (!$result_themes) {
    die("Erreur lors de la récupération des thèmes de formation : " . mysqli_error($conn));
}

$themes = [];
while ($row = mysqli_fetch_assoc($result_themes)) {
    $themes[] = $row;
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Échapper les entrées utilisateur
    $nom_et_prénom = mysqli_real_escape_string($conn, $_POST['nom_et_prénom']);
    $num_cin = mysqli_real_escape_string($conn, $_POST['num_cin']);
    $direction = mysqli_real_escape_string($conn, $_POST['direction']);
    $nom_entreprise = mysqli_real_escape_string($conn, $_POST['nom_entreprise']);
    $thème_formation = mysqli_real_escape_string($conn, $_POST['thème_formation']);
    $début_cycle = mysqli_real_escape_string($conn, $_POST['début_cycle']);

    // Insérer les données dans la table Participants
    $sql_insert = "INSERT INTO Participants (nom_et_prénom, num_cin, direction, nom_entreprise, thème_formation, début_cycle) 
                   VALUES ('$nom_et_prénom', '$num_cin', '$direction', '$nom_entreprise', '$thème_formation', '$début_cycle')";

    if (mysqli_query($conn, $sql_insert)) {
        $id_participant = mysqli_insert_id($conn); // Récupérer l'ID du dernier enregistrement
        mysqli_close($conn);
        header("Location: showParticipant.php?id_participant=$id_participant");
        exit;
    } else {
        $error_message = "Erreur lors de l'inscription : " . mysqli_error($conn);
    }
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Inscription</title>
    <link rel="stylesheet" href="../style.css">
    <style>

body {
    background-color:#FFF9D0;
    padding-top: 0;
    width: 90%;
    margin: 0 auto; /* Center the page horizontally */
    font-size: 18px; /* Increase the font size */
}
* {
margin: 0;
padding: 0;
box-sizing: border-box;
font-family: 'Rowdies', cursive;
}
h1 {
    margin-top: 40px;
    margin-bottom: 20px;
    font-size: 40px; /* Increase the font size */
  
}
form {
    width: 100%; /* Take full width of the parent container */
    max-width: 800px; /* Limit the form width for better readability */
    margin: 20px auto 100px; /* Center the form horizontally and add space at the bottom */
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.form-group {
    flex: 1 1 45%;
    margin-bottom: 10px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 16px; /* Increase the font size */
}
.form-group input,
.form-group select {
    width: 100%;
    padding: 10px; /* Increase the padding */
    box-sizing: border-box;
    font-size: 16px; /* Increase the font size */
}
input[type="submit"].aj{
    padding: 12px 24px; /* Increase the padding */
    cursor: pointer;
    font-size: 18px; /* Increase the font size */
    background-color:#8fd4f9;
}
input[type="submit"].aj:hover {
background-color: #CAF4FF; /* Couleur de fond au survol */
}
</style>
</head>
<body>
    <h1>Formulaire d'Inscription</h1>
    <?php if (!empty($success_message)): ?>
        <p><?php echo $success_message; ?></p>
    <?php elseif (!empty($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="nom_et_prénom">Nom et Prénom :</label>
            <input type="text" id="nom_et_prénom" name="nom_et_prénom" value="<?php echo htmlspecialchars($nom_et_prénom); ?>" required>
        </div>
        <div class="form-group">
            <label for="num_cin">Numéro CIN :</label>
            <input type="text" id="num_cin" name="num_cin" value="<?php echo htmlspecialchars($num_cin); ?>" required>
        </div>
        <div class="form-group">
            <label for="direction">Direction :</label>
            <input type="text" id="direction" name="direction" value="<?php echo htmlspecialchars($direction); ?>" required>
        </div>
        <div class="form-group">
            <label for="nom_entreprise">Nom Entreprise :</label>
            <input type="text" id="nom_entreprise" name="nom_entreprise" value="<?php echo htmlspecialchars($nom_entreprise); ?>" required>
        </div>
        <div class="form-group">
            <label for="thème_formation">Thème de formation :</label>
            <select id="thème_formation" name="thème_formation" onchange="updateDate()" required>
                <option value="">Sélectionnez un thème</option>
                <?php foreach ($themes as $theme): ?>
                    <option value="<?php echo htmlspecialchars($theme['thème_formation']); ?>" data-date="<?php echo htmlspecialchars($theme['début_cycle']); ?>" <?php echo ($thème_formation == $theme['thème_formation']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($theme['thème_formation']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="début_cycle">Date de début :</label>
            <input type="date" id="début_cycle" name="début_cycle" value="<?php echo htmlspecialchars($début_cycle); ?>" readonly required>
        </div>
        <div class="form-group">
            <input class="aj" type="submit" value="Inscrire">
        </div>
    </form>
    
    <script>
        function updateDate() {
            var themeSelect = document.getElementById('thème_formation');
            var dateInput = document.getElementById('début_cycle');
            var selectedTheme = themeSelect.options[themeSelect.selectedIndex].getAttribute('data-date');
            dateInput.value = selectedTheme;
        }
    </script>
</body>
</html>
