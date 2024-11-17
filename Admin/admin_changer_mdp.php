<?php
include_once "../connect_ddb.php";

session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Initialisation des variables
$new_id_admin = "";
$new_mdp = "";
$success_message = "";
$error_message = "";

// Vérification si le formulaire de changement a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_id_admin = mysqli_real_escape_string($conn, $_POST['new_id_admin']);
    $new_mdp = mysqli_real_escape_string($conn, $_POST['new_mdp']);
    $current_id_admin = $_SESSION['id_admin'];

    // Requête SQL pour mettre à jour les informations de l'admin
    $sql_update = "UPDATE Admin SET id_admin = '$new_id_admin', mdp = '$new_mdp' WHERE id_admin = '$current_id_admin'";
    if (mysqli_query($conn, $sql_update)) {
        $_SESSION['id_admin'] = $new_id_admin; // Mettre à jour la session
        $success_message = "ID et mot de passe mis à jour avec succès!";
    } else {
        $error_message = "Erreur lors de la mise à jour : " . mysqli_error($conn);
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
    <title>Changement ID/Mot de passe</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-size: 18px;
            padding-top: 0;
            width: 90%;
            margin: 0 auto;
            background-color:#FFF9D0;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 2em;
        }
        form {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
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
            font-size: 18px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            font-size: 18px;
        }
        .success {
            color: green;
            font-size: 18px;
        }
        .error {
            color: red;
            font-size: 18px;
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
        .kk{
            margin-bottom: 40px; /* Add space for the fixed navbar */
            font-size: 2.5em;
        }
        input[type="submit"].aj  {
    padding: 10px 20px;
    cursor: pointer;
    background-color: #A0DEFF; /* Couleur bleu clair pour le bouton */
    border: none;
    color: #fff;
    border-radius: 6px;
}
input[type="submit"].aj:hover {
        background-color: #CAF4FF; /* Couleur de fond au survol */
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
    <h1 classe="kk">Changement ID/Mot de passe</h1>
    <?php if (!empty($success_message)): ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php elseif (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="new_id_admin">Nouvel ID Admin :</label>
            <input type="text" id="new_id_admin" name="new_id_admin" value="<?php echo htmlspecialchars($new_id_admin); ?>" required>
        </div>
        <div class="form-group">
            <label for="new_mdp">Nouveau Mot de passe :</label>
            <input type="password" id="new_mdp" name="new_mdp" required>
        </div>
        <div class="form-group">
            <input class="aj" type="submit" value="Changer">
        </div>
    </form>
</body>
</html>
