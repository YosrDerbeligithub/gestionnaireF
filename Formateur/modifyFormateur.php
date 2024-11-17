<?php
// Afficher les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifiez et récupérez l'ID du formateur à partir de l'URL
$id_formateur = isset($_GET['id_formateur']) ? intval($_GET['id_formateur']) : 0;
if ($id_formateur == 0) {
    die("Invalid formateur ID");
}

if (isset($_POST['send'])) {
    if (isset($_POST['nom']) && isset($_POST['spécialité']) && isset($_POST['nom_entreprise']) && $_POST['nom'] != "" && $_POST['spécialité'] != "" && $_POST['nom_entreprise'] != "") {
        include_once "../connect_ddb.php";
        
        $nom = mysqli_real_escape_string($conn, $_POST['nom']);
        $spécialité = mysqli_real_escape_string($conn, $_POST['spécialité']);
        $nom_entreprise = mysqli_real_escape_string($conn, $_POST['nom_entreprise']);
        
        $sql = "UPDATE Formateur SET nom_et_prénom_formateur = ?, spécialité = ?, nom_entreprise = ? WHERE id_formateur = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $nom, $spécialité, $nom_entreprise, $id_formateur);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location:showFormateur.php");
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
            header("location:modifyFormateur.php?id_formateur=$id_formateur&message=ModificationFailed");
        }
    } else {
        header("location:modifyFormateur.php?id_formateur=$id_formateur&message=EmptyFields");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Formateur</title>
    <link rel="stylesheet" href="../style.css">
    <style>
    body {
        background-color: #FFF9D0;
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
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }
    input[type="text"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }
    </style>
</head>
<body>
    <?php
    include_once "../connect_ddb.php";

    // Vérifiez si la connexion est établie
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM Formateur WHERE id_formateur = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'i', $id_formateur);
    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing statement: " . mysqli_stmt_error($stmt));
    }
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("Error getting result: " . mysqli_stmt_error($stmt));
    }

    if ($row = mysqli_fetch_assoc($result)) {
    ?>
    <form action="" method="post">
        <h1>Modifier un formateur</h1>
        <input type="text" name="nom" value="<?= htmlspecialchars($row['nom_et_prénom_formateur']) ?>" placeholder="Nom et Prénom">
        <input type="text" name="spécialité" value="<?= htmlspecialchars($row['spécialité']) ?>" placeholder="Spécialité">
        <input type="text" name="nom_entreprise" value="<?= htmlspecialchars($row['nom_entreprise']) ?>" placeholder="Nom de l'entreprise">
        <input class="aj" type="submit" name="send" value="Modifier">
        <a href="showFormateur.php" class="link back">Annuler</a>
    </form> 
    <?php  
    } else {
        echo "Formateur not found";
    }
    ?>
</body>
</html>
