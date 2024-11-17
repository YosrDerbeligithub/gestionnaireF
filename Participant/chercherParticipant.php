<?php
include_once "../connect_ddb.php";

// Initialisation des variables
$search_theme = "";
$search_date = "";
$participants = [];

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Echappement sécurisé du terme de recherche
    $search_theme = mysqli_real_escape_string($conn, $_POST['search_theme']);
    $search_date = mysqli_real_escape_string($conn, $_POST['search_date']);

    // Requête SQL pour rechercher les participants par thème de formation ou début de cycle
    $sql_search = "SELECT * FROM Participants WHERE thème_formation LIKE '%$search_theme%' OR début_cycle = '$search_date'";
    $result_search = mysqli_query($conn, $sql_search);

    if (!$result_search) {
        die("Erreur lors de la recherche : " . mysqli_error($conn));
    }

    // Récupération des résultats
    while ($row = mysqli_fetch_assoc($result_search)) {
        $participants[] = $row;
    }
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
    <title>Chercher Participants</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Global Styles */
        body {
            padding-top: 150px; /* Space for fixed navbar */
            background-color: #FFF9D0; /* Light yellow background */
            margin-bottom: 150px;
            font-family: Arial, sans-serif;
        }

        main {
            width: 90%;
            margin: 0 auto;
        }

        /* Navbar Styles */
        .navbar {
            display: flex;
            background-color: #5AB2FF; /* Navbar background */
            justify-content: space-around;
            padding: 1em;
            width: 90%;
            border-radius: 15px;
            position: fixed;
            top: 0;
            z-index: 1000;
            margin-bottom: 40px; /* Space below navbar */
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 0.5em 1em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #CAF4FF; /* Hover background */
        }

        /* Title Styles */
        .h1 {
            margin-bottom: 20px; /* Space below title */
            margin-top: -60px; 
            font-size: 2.5em;
            text-align: center; /* Center the title */
            color:  #000; /* Same color as navbar for consistency */
        }

        /* Form Styles */
        form {
            width: 100%;
            max-width: 800px;
            margin: 20px auto 50px; /* Center the form and add space below */
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #FFF; /* White background for form */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            width: 100%;
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .form-group-row {
            display: flex;
            width: 100%;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .form-group-row .form-group {
            flex: 1;
            margin-right: 10px;
        }

        .form-group-row .form-group:last-child {
            margin-right: 0;
        }

        .form-group label {
            font-size: 1.2em;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1.1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Submit Button Styles */
        input[type="submit"].aj {
            background-color: #8fd4f9;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            color: #fff; /* Text color */
            transition: background-color 0.3s ease;
        }

        input[type="submit"].aj:hover {
            background-color: #CAF4FF; /* Hover background */
        }

        /* Table Styles */
        table {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto 20px;
            background-color: #FFF; /* White table background */
            border-collapse: collapse;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            font-size: 1em;
        }

        table th {
            background-color: #5AB2FF; /* Header background */
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8; /* Alternating row colors */
        }

        tr:hover {
            background-color: #f1f1f1; /* Hover row color */
        }

        /* Results Title */
        .res {
            margin-top: 0px; /* Space above results */
            font-size: 2.5em;
            text-align: center;
            margin-bottom: 50px;
            color: #000; /* Consistent color */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-group-row {
                flex-direction: column;
            }

            .form-group-row .form-group {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .navbar {
                flex-direction: column;
                align-items: center;
            }

            .navbar a {
                margin-bottom: 10px;
            }

            .h1, .res {
                font-size: 2em;
                color: #000;
            }

            form {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="../Formation/showFormation.php">Gérer Formations</a>
        <a href="../Formateur/showFormateur.php">Gérer Formateurs</a>
        <a href="../CycleDeFormation/showCycle.php">Gérer Cycles</a>
        <a href="../Participant/chercherParticipant.php">Chercher Participants</a>
        <a href="../Admin/admin_changer_mdp.php">Modifier Mot de Passe</a>
    </div>

    <!-- Page Title -->
    <h1 class="h1">Chercher Participants</h1>

    <!-- Search Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group-row">
            <div class="form-group">
                <label for="search_theme">Thème de formation :</label>
                <input type="text" id="search_theme" name="search_theme" value="<?php echo htmlspecialchars($search_theme); ?>" placeholder="Entrez le thème de formation">
            </div>
            <div class="form-group">
                <label for="search_date">Date de début :</label>
                <input type="date" id="search_date" name="search_date" value="<?php echo htmlspecialchars($search_date); ?>">
            </div>
        </div>
        <div class="form-group">
            <input class="aj" type="submit" value="Chercher">
        </div>
    </form>

    <!-- Search Results -->
    <?php if (count($participants) > 0): ?>
        <h1 class="res">Résultats de la recherche :</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Participant</th>
                    <th>Nom et Prénom</th>
                    <th>Numéro CIN</th>
                    <th>Direction</th>
                    <th>Nom Entreprise</th>
                    <th>Thème Formation</th>
                    <th>Début Cycle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participants as $participant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($participant['id_participant']); ?></td>
                        <td><?php echo htmlspecialchars($participant['nom_et_prénom']); ?></td>
                        <td><?php echo htmlspecialchars($participant['num_cin']); ?></td>
                        <td><?php echo htmlspecialchars($participant['direction']); ?></td>
                        <td><?php echo htmlspecialchars($participant['nom_entreprise']); ?></td>
                        <td><?php echo htmlspecialchars($participant['thème_formation']); ?></td>
                        <td><?php echo htmlspecialchars($participant['début_cycle']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p style="text-align:center; font-size:1.2em; color:#333;">Aucun participant trouvé pour les critères de recherche spécifiés.</p>
    <?php endif; ?>
</body>
</html>
