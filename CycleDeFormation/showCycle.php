<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Liste de Cycles de Formation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <style>
        .link {
            background-color: #8fd4f9;
        }
        .link:hover {
            background-color: #CAF4FF; /* Couleur de fond au survol du bouton Ajouter */
        }
        body {
            padding-top: 150px;
            background-color:#FFF9D0;
        }
        main {
            width: 90%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background-color: #fff;
        }
        th {
            background-color: #5AB2FF;
            color: #fff;
        }
        .colordiff{
            background-color: #EEF5FF;
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
        .h1{
            margin-bottom: 90px; /* Add space for the fixed navbar */
            font-size: 2.7em;
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
    <h1 class="h1"> liste des Cycles de Formation </h1>
    <main>
        <div class="link_container container">
            <a href="addCycle.php" class="link">Ajouter un Cycle de Formation</a>
        </div>
        <?php
        include_once "../connect_ddb.php";
        
        // Liste des cycles de formation avec les formateurs associés
        $sql = "SELECT c.*, f.thème_formation, f.mode_formation
                FROM CycleDeFormation c 
                JOIN Formation f ON c.id_formation = f.id_formation";
        
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "Erreur SQL : " . mysqli_error($conn);
            exit;
        }

        if (mysqli_num_rows($result) > 0) {
            // Afficher les cycles de formation avec les détails
            while ($row = mysqli_fetch_assoc($result)) {
                // Récupérer les ID des formateurs et leurs noms
                $formateurs_ids = explode(',', $row['formateurs']);
                $formateurs_noms = [];
                
                if (!empty($formateurs_ids)) {
                    $ids_placeholder = implode(',', array_fill(0, count($formateurs_ids), '?'));
                    $stmt = $conn->prepare("SELECT nom_et_prénom_formateur FROM Formateur WHERE id_formateur IN ($ids_placeholder)");
                    
                    // Lier les IDs des formateurs
                    $stmt->bind_param(str_repeat('i', count($formateurs_ids)), ...$formateurs_ids);
                    $stmt->execute();
                    $result_formateurs = $stmt->get_result();
                    
                    while ($formateur = $result_formateurs->fetch_assoc()) {
                        $formateurs_noms[] = $formateur['nom_et_prénom_formateur'];
                    }
                }
                
                $formateurs_affichage = implode(', ', $formateurs_noms);
                ?>
                <div class="cycle-container">
                    <table>
                        <tr>
                            <th>Nom d'entreprise</th>
                            <td><?= htmlspecialchars($row['nom_entreprise'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Numéro d'action</th>
                            <td><?= htmlspecialchars($row['num_action'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Crédit d'impôt</th>
                            <td><?= $row['crédit_impot'] ? 'Oui' : 'Non' ?></td>
                        </tr>
                        <tr>
                            <th>Droit de tirage individuel</th>
                            <td><?= $row['droit_tirage_individuel'] ? 'Oui' : 'Non' ?></td>
                        </tr>
                        <tr>
                            <th>Droit de tirage collectif</th>
                            <td><?= $row['droit_tirage_collectif'] ? 'Oui' : 'Non' ?></td>
                        </tr>
                        <tr>
                            <th>Formation</th>
                            <td>Thème: <?= htmlspecialchars($row['thème_formation'], ENT_QUOTES, 'UTF-8') ?>, Mode: <?= htmlspecialchars($row['mode_formation'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Lieu de déroulement</th>
                            <td><?= htmlspecialchars($row['lieu_déroulement'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Gouvernorat</th>
                            <td><?= htmlspecialchars($row['gouvernorat'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Début du cycle</th>
                            <td><?= htmlspecialchars($row['début_cycle'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Fin du cycle</th>
                            <td><?= htmlspecialchars($row['fin_cycle'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Horaire de début</th>
                            <td><?= htmlspecialchars($row['horaire_début'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Horaire de fin</th>
                            <td><?= htmlspecialchars($row['horaire_fin'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Début de la pause</th>
                            <td><?= htmlspecialchars($row['début_pause'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Fin de la pause</th>
                            <td><?= htmlspecialchars($row['fin_pause'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th>Formateurs</th>
                            <td><?= htmlspecialchars($formateurs_affichage, ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <td class="colordiff">
                                <a href="modifyCycle.php?id_cycle=<?= htmlspecialchars($row['id_cycle'], ENT_QUOTES, 'UTF-8') ?>"><img src="../images/write.png" alt="Modifier"></a></td>
                            <td class="colordiff">
                                <a href="deleteCycle.php?id_cycle=<?= htmlspecialchars($row['id_cycle'], ENT_QUOTES, 'UTF-8') ?>"><img src="../images/remove.png" alt="Supprimer"></a></td>
                        </tr>
                    </table>
                    <br> <br>
                </div>
                <?php
            }
        } else {
            echo "<div class='message'>Aucun cycle de formation trouvé !</div>";
        }
        ?>
    </main>
</body>
</html>
