<?php
   $id_formation = $_GET['id_formation'];
   include_once "../connect_ddb.php";
   $sql = "DELETE FROM Formation WHERE id_formation= $id_formation ";
   if (mysqli_query($conn, $sql)){
    header("location:showFormation.php?message=DeleteSuccessful");
   }
   else{
    header("location:showFormation.php?message=DeleteFailed");
   }
?>