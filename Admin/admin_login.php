<?php
include_once "../connect_ddb.php";

session_start();

// Initialisation des variables
$id_admin = "";
$mdp = "";
$error_message = "";

// Vérification si le formulaire de login a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_admin = mysqli_real_escape_string($conn, $_POST['id_admin']);
    $mdp = mysqli_real_escape_string($conn, $_POST['mdp']);

    // Requête SQL pour vérifier les identifiants de l'admin
    $sql_login = "SELECT * FROM Admin WHERE id_admin = '$id_admin' AND mdp = '$mdp'";
    $result_login = mysqli_query($conn, $sql_login);

    if (mysqli_num_rows($result_login) == 1) {
        $_SESSION['id_admin'] = $id_admin;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error_message = "ID ou mot de passe incorrect.";
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
    <title>Login Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-size: 18px;
            padding-top: 0;
            width: 90%;
            margin: 0 auto;
            background-color: #FFF9D0;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            
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
        input[type="submit"].aj {
            padding: 12px 24px;
            cursor: pointer;
            font-size: 18px;
            background-color: #8fd4f9;
            border: none;
            border-radius: 5px;
            color: #fff;
        }
        input[type="submit"].aj:hover {
            background-color: #CAF4FF;
        }
        .error {
            color: red;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>Login Admin</h1>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="id_admin">ID Admin :</label>
            <input type="text" id="id_admin" name="id_admin" value="<?php echo htmlspecialchars($id_admin); ?>" required>
        </div>
        <div class="form-group">
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required>
        </div>
        <div class="form-group">
            <input class="aj" type="submit" value="Login">
        </div>
    </form>
</body>
</html>
