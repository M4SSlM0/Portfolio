<?php
session_start();


$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "3dprojectdb";
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

//var_dump($_POST);


if ($conn->connect_error) {
  die("Connessione al database fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $userId = $_POST['userId'];
  $action = $_POST['action'];


  if ($action == 'banna') {
    $sql = "UPDATE utenti SET IsBan = 1 WHERE ID = $userId";
  } elseif ($action == 'sbanna') {
    $sql = "UPDATE utenti SET IsBan = 0 WHERE ID = $userId";
  }

  if ($action == 'promuovi') {
    $sql = "UPDATE utenti SET Admin = 1 WHERE ID = $userId";
  } elseif ($action == 'declassa') {
    $sql = "UPDATE utenti SET Admin = 0 WHERE ID = $userId";
  }

  if ($conn->query($sql) === TRUE) {

    //header("Location: index.php");
    //exit;
  } else {
    echo "Errore durante l'esecuzione dell'operazione: " . $conn->error;
  }
}

$row = $conn->query("SELECT utenti.*, 
                          COUNT(progetti.ID) AS NumProgetti, 
                          SUM(CASE WHEN progetti.Status = 'Completato' THEN 1 ELSE 0 END) AS NumProgettiCompletati, 
                          ROUND((SUM(CASE WHEN progetti.Status = 'Completato' THEN 1 ELSE 0 END) / COUNT(progetti.ID)),1) * 100 AS PercentualeProgettiCompletati
                          FROM utenti 
                          LEFT JOIN progetti ON utenti.ID = progetti.FK_ID_Utente 
                          WHERE utenti.ID = '".$userId."'")->fetch_assoc();

$userClass = ($row["IsBan"] == 1 ? "ban" : ($row["Admin"] == 1 ? "admin" : "normal"));
?>
<div class="row profiles-profile <?= $userClass ?>" id="user-profile-<?= $row["ID"] ?>">
  <div class="center column">
    <img src="<?= $row["ImmagineProfilo"] ?> "class="center profiles-profile-pfp">
    <div class="center textSmall profiles-profile-username"><?php echo htmlspecialchars($row["NomeUtente"]) ?></div>
  </div>
  <div class="column center">
    <div class="center textTiny profiles-profile-statistic">ProgettiTotali: <?= $row["NumProgetti"] ?></div>
    <div class="center textTiny profiles-profile-statistic">Completati: <?= $row["PercentualeProgettiCompletati"] != 0 ? $row["PercentualeProgettiCompletati"]."%" : 0 ?></div>
  </div>
  <div class="column center">
    <input type="hidden" name="userId" id="profiles-Id-<?= $row["ID"] ?>" value="<?= $row["ID"] ?>">
    <?php
      if ($row["Admin"] == 1) { ?>
        <input type='hidden' name='action' id="declassa" value='declassa'>
        <button class="center gradient textTiny button profiles-profile-button" hx-post="../Profiles/function.php" hx-trigger="click" hx-target="#user-profile-<?= $row["ID"] ?>" hx-swap="outerHTML" hx-include="#profiles-Id-<?= $row["ID"] ?>, #declassa">Declassa da superutente</button>
      <?php
      }
      else{ ?>
        <input type='hidden' name='action' id="promuovi" value='promuovi'>
        <button class="center gradient textTiny button profiles-profile-button" hx-post="../Profiles/function.php" hx-trigger="click" hx-target="#user-profile-<?= $row["ID"] ?>" hx-swap="outerHTML" hx-include="#profiles-Id-<?= $row["ID"] ?>, #promuovi">Promuovi a superutente</button>
      <?php
      }
    ?>
    <?php
      if ($row["IsBan"] == 1) { ?>
        <input type='hidden' name='action' id="sbanna" value='sbanna'>
        <button class="center gradient textTiny button profiles-profile-button" hx-post="../Profiles/function.php" hx-trigger="click" hx-target="#user-profile-<?= $row["ID"] ?>" hx-swap="outerHTML" hx-include="#profiles-Id-<?= $row["ID"] ?>, #sbanna">Rimuovi ban</button>
      <?php
      }
      else{ ?>
        <input type='hidden' name='action' id="banna" value='banna'>
        <button class="center gradient textTiny button profiles-profile-button" hx-post="../Profiles/function.php" hx-trigger="click" hx-target="#user-profile-<?= $row["ID"] ?>" hx-swap="outerHTML" hx-include="#profiles-Id-<?= $row["ID"] ?>, #banna">Ban utente</button>
      <?php
      }
    ?>
    <button class="center gradient textTiny button profiles-profile-button user" hx-post="../Modals/userProfile.php" hx-trigger="click" hx-target="#modal" hx-swap="innerHTML"  hx-include="#profiles-Id-<?= $row["ID"] ?>">Informazioni utente</button>
  </div>
</div>

<script>
  var projects = document.getElementsByClassName("user");
  console.log(projects);
  for (let i = 0; i < projects.length; i++) {
    showModal(projects[i], "click");
  }
</script>
<?php

$conn->close();
?>
