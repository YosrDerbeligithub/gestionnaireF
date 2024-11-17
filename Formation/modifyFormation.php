<?php
// Afficher les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifiez et récupérez l'ID du formateur à partir de l'URL
$id_formation = isset($_GET['id_formation']) ? intval($_GET['id_formation']) : 0;
if ($id_formation == 0) {
    die("Invalid formation ID");
}

if (isset($_POST['send'])) {
    if (isset($_POST['thème']) && isset($_POST['mode']) && $_POST['thème'] != "" && $_POST['mode'] != "") {
        include_once "../connect_ddb.php";
        $thème = mysqli_real_escape_string($conn, $_POST['thème']);
        $mode = mysqli_real_escape_string($conn, $_POST['mode']);
        $sql = "UPDATE Formation SET thème_formation = ?, mode_formation = ? WHERE id_formation = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssi', $thème, $mode, $id_formation);
            if (mysqli_stmt_execute($stmt)) {
                header("location:showFormation.php");
            } else {
                echo "Error: " . mysqli_stmt_error($stmt);
                header("location:modifyFormation.php?id_formation=$id_formation&message=ModificationFailed");
            }
            mysqli_stmt_close($stmt);
        } else {
            die("Error preparing statement: " . mysqli_error($conn));
        }
        mysqli_close($conn);
    } else {
        header("location:modifyFormation.php?id_formation=$id_formation&message=EmptyFields");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Formation</title>
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

    $sql = "SELECT * FROM Formation WHERE id_formation = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'i', $id_formation);
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
        <h1>Modifier une formation</h1>
        <?php
        if (isset($_GET['message'])) {
            if ($_GET['message'] == 'EmptyFields') {
                echo '<p style="color: red;">Please fill in all fields.</p>';
            } else if ($_GET['message'] == 'ModificationFailed') {
                echo '<p style="color: red;">An error occurred while updating the formation.</p>';
            }
        }
        ?>
        <input type="text" name="thème" value="<?= htmlspecialchars($row['thème_formation']) ?>" placeholder="Thème de formation">
        <input type="text" name="mode" value="<?= htmlspecialchars($row['mode_formation']) ?>" placeholder="Mode de formation">
        <input type="submit" class="aj" name="send" value="Modifier">
        <a href="showFormation.php" class="link back">Annuler</a>
    </form> 
    <?php  
    } else {
        echo "Formation not found";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>
</body>
</html>
