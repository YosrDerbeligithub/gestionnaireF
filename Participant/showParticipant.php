<?php
include_once "../connect_ddb.php";

// Vérification si un ID de participant est passé en paramètre
if (isset($_GET['id_participant'])) {
    $id_participant = mysqli_real_escape_string($conn, $_GET['id_participant']);

    // Récupérer les informations du participant depuis la base de données
    $sql = "SELECT * FROM Participants WHERE id_participant = '$id_participant'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $participant = mysqli_fetch_assoc($result);
    } else {
        die("Participant non trouvé.");
    }
} else {
    die("ID de participant non spécifié.");
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information du Participant</title>
    <link rel="stylesheet" href="../style.css">
    <style>
    body {
        background-color: #FFF9D0;
        padding-top: 10px;
        width: 90%;
        margin: 0 auto; /* Center the page horizontally */
        font-size: 18px; /* Increase font size */
    }

    h1 {
        margin-top: -50px; /* Move title closer to the top */
        margin-bottom: 50px;
        text-align: center;
        font-size: 2.5em;
    }

    table {
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        border-collapse: collapse;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 20px;
    }

    th, td {
        padding: 15px;
        text-align: center;
    }

    th {
        background-color: #5AB2FF;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f8f8f8;
    }

    .button-container {
        margin-top: 50px; /* Add more space between table and button */
        text-align: center;
    }

    .button-container a {
        margin-top: 10px;
        background-color: #8fd4f9;
        padding: 12px 30px; /* Increase padding */
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        font-size: 1.2em; /* Increase font size */
        margin: 20px 0;
    }

    a:hover {
        background-color: #CAF4FF; /* Background color on hover */
    }
</style>

</head>
<body>
    <h1>Information du Participant</h1>
    <div class="table-container">
        <table>
            <tr>
                <th>Nom et Prénom</th>
                <td><?php echo htmlspecialchars($participant['nom_et_prénom']); ?></td>
            </tr>
            <tr>
                <th>Numéro CIN</th>
                <td><?php echo htmlspecialchars($participant['num_cin']); ?></td>
            </tr>
            <tr>
                <th>Direction</th>
                <td><?php echo htmlspecialchars($participant['direction']); ?></td>
            </tr>
            <tr>
                <th>Nom Entreprise</th>
                <td><?php echo htmlspecialchars($participant['nom_entreprise']); ?></td>
            </tr>
            <tr>
                <th>Thème de formation</th>
                <td><?php echo htmlspecialchars($participant['thème_formation']); ?></td>
            </tr>
            <tr>
                <th>Date de début</th>
                <td><?php echo htmlspecialchars($participant['début_cycle']); ?></td>
            </tr>
        </table>
    </div>
    <div class="button-container">
        <a href="../Admin/admin_participant.php">Retour</a>
    </div>
</body>
</html>
