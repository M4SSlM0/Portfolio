<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  session_start();

  if($_POST["like-state"] == "like"){
    $conn->query("INSERT INTO mipiace(FK_ID_Utente, FK_ID_Progetto) VALUES ('".$_SESSION["userID"]."','".$_POST["project-id"]."')");
  }
  else if($_POST["like-state"] == "unlike"){
    $conn->query("DELETE FROM mipiace WHERE FK_ID_Utente = '".$_SESSION["userID"]."' AND FK_ID_Progetto = '".$_POST["project-id"]."'");
  }
?>