<?php
// Inclusion du fichier de connexion à la base de données
include_once "../connect_ddb.php";

// Vérification si l'ID du cycle à supprimer est présent dans l'URL
if (!isset($_GET['id_cycle']) || !is_numeric($_GET['id_cycle'])) {
    die("ID de cycle invalide.");
}

$id_cycle = $_GET['id_cycle'];

// Suppression du cycle de formation
$sql_delete = "DELETE FROM CycleDeFormation WHERE id_cycle = $id_cycle";

if (mysqli_query($conn, $sql_delete)) {
    // Redirection vers la page de visualisation des cycles après suppression
    header("Location: showCycle.php");
    exit();
} else {
    echo "Erreur lors de la suppression du cycle : " . mysqli_error($conn);
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>
