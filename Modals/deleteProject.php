<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  $conn->query("DELETE FROM progetti WHERE id='".$_POST["project-id"]."'");
?>