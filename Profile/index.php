<?php
  session_start();

  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  $sql_OttieniUsername = "SELECT NomeUtente, ImmagineProfilo FROM utenti WHERE id = '".$_SESSION["userID"]."'";
  $exe_OttieniUsername = $conn->query($sql_OttieniUsername);
    
  if($exe_OttieniUsername->num_rows > 0) {
    $row = $exe_OttieniUsername->fetch_assoc();
    $_SESSION["ses_user"] = $row["NomeUtente"]; 
    $_SESSION["ses_pfp"] = $row["ImmagineProfilo"];
    if($_SESSION["ses_pfp"] == "") $_SESSION["ses_pfp"] = "../Images/Pfps/default.png";
  }
?>
<div class="fill row">
  <div class="fillY column profile-container">
    <div class="fillX center">
      <div class="gradient profile-picture-container" style="background-image: url(<?= $_SESSION["ses_pfp"];?>)">
        <div class="fill center text button profile-picture-edit" hx-post="../Modals/profileEdit.php" hx-trigger="click" hx-target="#modal" hx-swap="innerHTML" id="edit-profile">
          <script>showModal(document.getElementById("edit-profile"), "click");</script>
          Click to edit
        </div>
      </div>
    </div>
    <div class="fillX center textBig profile-username"><?= $_SESSION["ses_user"]?></div>
    <div class="fillX center text profile-title">Last project:</div>
    <?php
      //Trova Last Project
      $sql_TrovaUltimoProgetto = "SELECT * FROM progetti 
                                  JOIN utenti ON progetti.FK_ID_Utente = utenti.ID 
                                  WHERE NomeUtente = ?
                                  ORDER BY DataInizio DESC LIMIT 1"; //NO

        $stmt = $conn->prepare($sql_TrovaUltimoProgetto);
        $stmt->bind_param("s", $_SESSION["ses_user"]);
        $stmt->execute();

        $exe_TrovaUltimoProgetto = $stmt->get_result();

        if($exe_TrovaUltimoProgetto->num_rows > 0) 
        { 
            while ($row = $exe_TrovaUltimoProgetto->fetch_assoc()) 
            {
                $UltimoProgetto_Nome = $row["Descrizione"];
                $UltimoProgetto_Immagine = $row["Immagine"];
            }
        }  else {
          echo "Non ci sono progetti ";
       }
    ?>
    <div class="fillX center">
      <div class="center gradient button last-project-container">
        <div class="last-project" <?= isset($UltimoProgetto_Immagine) ? "style='background-image: url(".$UltimoProgetto_Immagine.")'" : "" ?>><?php if (isset($UltimoProgetto_Nome)) echo $UltimoProgetto_Nome;?></div>
      </div>
    </div>
  </div>
  <div class="grow gradient"></div>
  <div class="fillY column stats-container">
    <div class="fillX center textBig profile-stats-title">Statistics</div>
    <div
      class="fillX row profile-stats-form"
      hx-post="../Profile/chart.php"
      hx-trigger="input, load"
      hx-target="#chart"
      hx-swap="innerHTML"
      hx-include="#projects-state, #projects-type"
    >
      <div class="column">
        <div class="textSmall profile-stats-form-label">Project state:</div>
        <div class="textSmall custom-select">
          <select name="projects-state" id="projects-state">
            <option value="InCorso">In corso</option>
            <option value="InPausa">In pausa</option>
            <option value="Finito">Finito</option>
            <option value="Scartato">Scartato</option>
            <option value="All" selected>All</option>
          </select>
        </div>
      </div>
      <div class="column">
        <div class="textSmall profile-stats-form-label">Project type:</div>
        <div class="textSmall custom-select">
          <select name="projects-type" id="projects-type">
            <option value="">Illustrazione</option>
            <option value="">Icona</option>
            <option value="">Animazione</option>
            <option value="">Asset</option>
            <option value="" selected>All</option>
          </select>
        </div>
      </div>
    </div>
    <div class="fillX center">
      <div
        class="center gradient profile-ststs-chart-container"
        id="chart"
      ></div>
    </div>
  </div>
</div>
