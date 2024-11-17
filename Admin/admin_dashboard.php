<?php
session_start();
if (!isset($_SESSION['id_admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <style>
                        .link {
            background-color: #A0DEFF;
        }
        body {
            background-color: #FFF9D0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .navbar {
            display: flex;
            background-color: #5AB2FF; /* Dark mauve */
            justify-content: space-around;
            padding: 1em;
            width: 90%;
            border-radius: 15px; /* Rounded corners */
            position: fixed;
            top: 0;
            z-index: 1000;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 0.5em 1em;
        }
        .navbar a:hover {
            background-color: #CAF4FF; /* Slightly lighter mauve */
        }
        h1 {
            margin-top: 80px; /* Add space for the fixed navbar */
            font-size: 5em;
            opacity: 0.3; /* Lower opacity */
        }
        p {
            margin-top: 20px;
            font-size: 1.6em;
            opacity: 0.7;
        }
    </style>
</head>
<body>
<div class="navbar">
        <a href="../Formation/showFormation.php">Gérer Formations</a>
        <a href="../Formateur/showFormateur.php">Gérer Formateurs</a>
        <a href="../CycleDeFormation/showCycle.php">Gérer Cycles</a>
        <a href="../Participant/chercherParticipant.php">Chercher Participants</a>
        <a href="../Admin/admin_changer_mdp.php">Modifier Mot de Passe</a>
    </div>
    <h1>Bienvenue à l'interface admin</h1>
    <p>Choisissez une option dans la barre de navigation ci-dessus.</p>
</body>
</html>
