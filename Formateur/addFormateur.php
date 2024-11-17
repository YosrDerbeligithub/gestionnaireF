<?php
if (isset($_POST['send'])) {
    if (isset($_POST['nom']) && isset($_POST['spécialité']) && isset($_POST['nom_entreprise']) && $_POST['nom'] != "" && $_POST['spécialité'] != "" && $_POST['nom_entreprise'] != "") {
        include_once "../connect_ddb.php";
        $nom = mysqli_real_escape_string($conn, $_POST['nom']);
        $spécialité = mysqli_real_escape_string($conn, $_POST['spécialité']);
        $nom_entreprise = mysqli_real_escape_string($conn, $_POST['nom_entreprise']);
        $sql = "INSERT INTO Formateur (nom_et_prénom_formateur, spécialité, nom_entreprise) VALUES ('$nom', '$spécialité', '$nom_entreprise')";
        if (mysqli_query($conn, $sql)) {
            header("Location: showFormateur.php");
        } else {
            $error = mysqli_error($conn);
            header("Location: addFormateur.php?message=AddFailed&error=" . urlencode($error));
        }
    } else {
        header("Location: addFormateur.php?message=EmptyFields");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Formateur</title>
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
    <form action="" method="post">
        <h1>Ajouter un formateur</h1>
        <input type="text" name="nom" placeholder="Nom et Prénom">
        <input type="text" name="spécialité" placeholder="Spécialité">
        <input type="text" name="nom_entreprise" placeholder="Nom de l'entreprise">
        <input class="aj" type="submit" name="send" value="Ajouter">
        <a href="showFormateur.php" class="link back">Annuler</a>
    </form>   
</body>
</html>