<?php
   $id_formateur = $_GET['id_formateur'];
   include_once "../connect_ddb.php";
   $sql = "DELETE FROM Formateur WHERE id_formateur= $id_formateur ";
   if (mysqli_query($conn, $sql)){
    header("location:showFormateur.php?message=DeleteSuccessful");
   }
   else{
    header("location:showFormateur.php?message=DeleteFailed");
   }
?>