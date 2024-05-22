<?php
  session_start();

  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  $user = $conn->query("SELECT * FROM utenti WHERE ID = '".$_SESSION["userId"]."'")->fetch_assoc();
?>

<img src="<?= $user["ImmagineProfilo"] ?>" alt="" class="center modal-profile-pfp" id="modal-edit-profile-pfp">