<?php
if (isset($_POST['send'])) {
    if (isset($_POST['thème']) && isset($_POST['mode']) && $_POST['thème'] != "" && $_POST['mode'] != "") {
        include_once "../connect_ddb.php";
        $thème = mysqli_real_escape_string($conn, $_POST['thème']);
        $mode = mysqli_real_escape_string($conn, $_POST['mode']);
        $sql = "INSERT INTO Formation (thème_formation, mode_formation) VALUES ('$thème', '$mode')";
        if (mysqli_query($conn, $sql)) {
            header("Location: showFormation.php");
        } else {
            $error = mysqli_error($conn);
            header("Location: addFormation.php?message=AddFailed&error=" . urlencode($error));
        }
    } else {
        header("Location: addFormation.php?message=EmptyFields");
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Formation</title>
    <link rel="stylesheet" href="../style.css">
    <style>
    body {
        background-color: #FFF9D0;
    }
    input[type="submit"].aj {
        background-color: #A0DEFF;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        color: #fff; /* Ajout de la couleur de texte */
    }
    input[type="submit"].aj:hover {
        background-color: #CAF4FF; /* Couleur de fond au survol */
    }
    .link.back {
        background-color: #A0DEFF;
        padding: 10px 20px;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        margin-top: 10px;
        display: inline-block;
    }
    .link.back:hover {
        background-color: #CAF4FF;
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
        <h1>Ajouter une formation</h1>
        <input type="text" name="thème" placeholder="Thème de formation" required>
        <input type="text" name="mode" placeholder="Mode de formation" required>
        <input class="aj" type="submit" name="send" value="Ajouter">
        <a href="showFormation.php" class="link back">Annuler</a>
    </form>
</body>
</html>
