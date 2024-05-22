<?php

if (isset($_POST['userId'])) {
  $userId = $_POST['userId'];

  session_start();

  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM utenti WHERE ID = $userId";
  $exe_OttieniUtenti = $conn->query($sql);

  if ($exe_OttieniUtenti->num_rows > 0) {

    $user = $exe_OttieniUtenti->fetch_assoc();
    ?>
      <div class="modal-container">
        <div class="fillX center column modal-profile-container">
          <div class="fillX center">
          <div class="fillX center textBig modal-title">Dettagli utente</div>
            <button class="text button close-modal" style="top: 28vh;" onclick="closeModal()">
              &times;
            </button>
          </div>
          <div class="fillX center">
            <img src="<?= $user["ImmagineProfilo"] ?>" alt="" class="center modal-profile-pfp">
          </div>
          <div class="fillX center">
            <div class="center text modal-label">Nome utente: <?= $user["NomeUtente"] ?></div>
          </div>
          <div class="fillX center">
            <div class="center text modal-label">Email: <?= $user["Email"] ?></div>
          </div>
        </div>
      </div>
    <?php
  } else {
    echo "Utente non trovato.";
  }
  $conn->close();
} else {
  echo "ID utente non specificato.";
}
?>
