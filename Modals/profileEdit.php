<?php
  session_start();

  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  $user = $conn->query("SELECT * FROM utenti WHERE ID = '".$_SESSION["userId"]."'")->fetch_assoc();
?>
<div class="modal-container">
  <div class="column modal-profile-edit-container">
    <div class="fillX center">
      <div class="fillX center textBig modal-title">Modifica profilo utente</div>
      <button class="text button close-modal" style="top: 17vh;" onclick="closeModal()">
        &times;
      </button>
    </div>
    <div class="fillX center">
      <img src="<?= $user["ImmagineProfilo"] ?>" alt="" class="center modal-profile-pfp" id="modal-edit-profile-pfp">
    </div>
    <div class="fillX center column">
      <div class="center text modal-label">Cambia nome utente:</div>
      <div class="fillX center row">
        <input type="hidden" name="Edit" id="submit-edit-username" value="Username">
        <div class="custom-input-text">
          <input type="text" id="username" name="NewUsername" value="<?= $user["NomeUtente"] ?>">
        </div>
        <button class="center text gradient button modal-profile-edit-button" hx-post="../Modals/submitEditProfile.php" hx-trigge="click" hx-target="#result", hx-swap="innerHTML" hx-include="#username, #submit-edit-username">Salva</button>
      </div>
    </div>
    <div class="fillX center column">
      <div class="center text modal-label">Cambia foto profilo:</div>
      <div class="fillX center row">
        <input type="hidden" name="Edit" id="submit-edit-pfp" value="ProfilePicture">
        <div class="custom-input-text">
          <input type="file" id="profile_pic" name="ProfilePicture" calss="modal-profile-image-input">
        </div>
        <button class="center text gradient button modal-profile-edit-button" hx-post="../Modals/submitEditProfile.php" hx-trigge="click" hx-target="#result", hx-swap="innerHTML" hx-include="#profile_pic, #submit-edit-pfp" hx-encoding='multipart/form-data' id="submit-edit-pfp">Salva</button>
        <div hx-post="../Modals/updatePfp.php" hx-trigger="click delay:0.2s from:#submit-edit-pfp" hx-target="#modal-edit-profile-pfp" hx-swap="outerHTML"></div>
      </div>
    </div>
    <div class="fillX center column">
      <div class="center text modal-label">Cambia password:</div>
      <div class="fillX center row">
        <input type="hidden" name="Edit" id="submit-edit-password" value="Password">
        <div class="custom-input-text">
          <input type="password" id="password" name="NewPassword">
        </div>
        <button class="center text gradient button modal-profile-edit-button" hx-post="../Modals/submitEditProfile.php" hx-trigge="click" hx-target="#result", hx-swap="innerHTML" hx-include="#password, #submit-edit-password">Salva</button>
      </div>
    </div>
    <div hx-post="../Profile" hx-trigger="click from:.modal-profile-edit-button" hx-target="#page" hx-swap="innerHTML"></div>
    <div class="fillX center text modal-label" id="result"></div>
  </div>
</div>
