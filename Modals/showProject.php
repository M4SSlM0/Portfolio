<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  session_start();

  $project = $conn->query("SELECT * FROM progetti WHERE ID='".$_POST["project-id"]."'")->fetch_assoc();
  $userId = $_SESSION["userID"];
?>


<div class="column modal-container">
  <input type='hidden' value="<?= $project["ID"] ?>" id='project-id' name='project-id'>
  <div class="fillX center">
    <div class="fillX center textBig modal-title"><?= $userId == $project["FK_ID_Utente"] ? "Your project" : "Usernames' project" ?></div>
    <button class="text button close-modal" style="top: 5vh;" onclick="closeModal()">
      &times;
    </button>
  </div>
  <div class="fillX center">
    <div class="gradient modal-project-image" id="image" style="background-image: url('<?= $project["Immagine"] ?>')">
      <div class="fillX center textSmall modal-description"><?= $project["Descrizione"] ?></div>
    </div>
  </div>
  <div class="fillX row center">
    <div class="column center modal-input-container">
      <div class="center text modal-label">Type:</div>
      <div class="center text modal-label"><?= $project["FK_ID_Tipo"] ?></div>
    </div>
    <div class="column center modal-input-container">
      <div class="center text modal-label">Status:</div>
      <div class="center text modal-label"><?= $project["Status"] ?></div>
    </div>
  </div>
  <div class="fillX row center">
    <div class="column center modal-input-container">
      <div class="center text modal-label">Hours:</div>
      <div class="center text modal-label"><?= $project["ContatoreOre"] ?></div>
    </div>
    <div class="column center modal-input-container">
      <div class="center text modal-label">Visibility:</div>
      <div class="center text modal-label"><?= $project["Visibilita"] == 0 ? "Pubblico" : "Privato" ?></div>
    </div>
  </div>
    <?php if($userId == $project["FK_ID_Utente"]){ ?>
    <div class="fillX center row">
      <button
        class="center text gradient button modal-submit"
        name="modal-trigger"
        value="edit"
        hx-post="../Modals/editProject.php"
        hx-trigger="click"
        hx-target="#modal"
        hx-swap="innerHTML"
        hx-include="#project-id"
        >
        Edit
      </button>
      <button
        class="center text gradient button modal-submit"
        id="modal-submit"
        onclick="closeModal()"
        hx-post="../Modals/deleteProject.php"
        hx-trigger="click"
        hx-target="#debug"
        hx-swap="innerHTML"
        hx-include="#project-id"
      >
      <div
        hx-post="../MyProjects/search.php"
        hx-trigger="click from:#modal-submit"
        hx-target="#result"
        hx-swap="innerHTML"
        hx-include="#search-type, #search-description"></div>
        Delete
      </button>
    </div>
    <?php } ?>
  </div>
</div>
<div id="debug"></div>