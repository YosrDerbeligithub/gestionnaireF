<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Liste de Formations</title>
    <link rel="stylesheet"  href="../style.css">
    <style>
    body {
        padding-top: 150px;
        background-color: #FFF9D0; /* Couleur de fond générale */
        margin-bottom: 150px;
    }

    main {
        width: 90%;
    }

    .navbar {
        display: flex;
        background-color: #5AB2FF; /* Couleur de fond de la navbar */
        justify-content: space-around;
        padding: 1em;
        width: 90%;
        border-radius: 15px;
        position: fixed;
        top: 0;
        z-index: 1000;
        margin-bottom: 10px; /* Align with showFormateur */
    }

    .navbar a {
        color: white;
        text-decoration: none;
        padding: 0.5em 1em;
    }

    .navbar a:hover {
        background-color: #CAF4FF; /* Couleur de fond au survol */
    }

    .h1 {
        margin-top: -22px;
        margin-bottom: 55px; /* Align with showFormateur */
        font-size: 2.5em;
    }

    a.link {
        background-color: #8fd4f9; /* Same color as showFormateur */
        padding: 10px 20px; /* Consistent padding */
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        display: inline-block;
        margin-top: 20px;
        font-size: 16px; /* Align font size */
    }

    a.link:hover {
        background-color: #CAF4FF; /* Couleur de fond au survol */
    }

    table {
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        border-collapse: collapse;
        width: 100%; /* Full width for consistency */
        border-radius: 10px;
        overflow: hidden;
        margin-top: 20px;
        background-color: #FFF; /* Couleur de fond du tableau */
    }

    th {
        text-align: center;
        background-color: #5AB2FF; /* Couleur de fond des entêtes de colonnes */
        color: #fff;
        padding: 15px; /* Same padding as showFormateur */
    }

    td {
        text-align: center;
        padding: 15px; /* Same padding as showFormateur */
    }

    tr:nth-child(even) {
        background-color: #f8f8f8;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    img {
        width: 30px;
        text-align: center;
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
    <h1 class="h1"> liste des Formations </h1>
    <main>
        <div class="link_container">
        <a href="addFormation.php" type="submit" class="link">Ajouter une Formation</a>
        </div>
        <table>
            <thead>
                <?php
                include_once "../connect_ddb.php";
                // Liste des formations
                $sql = "SELECT * FROM Formation";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                     // Afficher les formations
                ?>
                <tr>
                    <th>Thème de formation</th>
                    <th>Mode de formation</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Boucle pour afficher tous les résultats
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['thème_formation'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['mode_formation'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="image"><a href="modifyFormation.php?id_formation=<?= htmlspecialchars($row['id_formation'], ENT_QUOTES, 'UTF-8') ?>"><img src="../images/write.png" alt=""></a></td>
                    <td class="image"><a href="deleteFormation.php?id_formation=<?= htmlspecialchars($row['id_formation'], ENT_QUOTES, 'UTF-8') ?>"><img src="../images/remove.png" alt=""></a></td>
                </tr>
            <?php
            }
         } else {
            echo "<p class='message'>0 formation présente !</p>";
         }
         ?> 
            </tbody>
        </table>
    </main>
</body>
</html>
