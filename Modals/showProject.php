<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  session_start();

  $project = $conn->query("SELECT * FROM progetti JOIN utenti ON progetti.FK_ID_Utente = utenti.ID WHERE progetti.ID='".$_POST["project-id"]."'")->fetch_assoc();
  $userId = $_SESSION["userID"];
  //var_dump($_POST["project-id"]);
  //var_dump($project);
?>


<div class="column modal-container">
  <input type='hidden' value="<?= $_POST["project-id"] ?>" id='project-id' name='project-id'>
  <div class="fillX center">
    <div class="fillX center textBig modal-title"><?= $userId == $project["FK_ID_Utente"] ? "Your project" : $project["NomeUtente"]. "'s project" ?></div>
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
    <?php }
    else { ?>
      <div class="row">
        <?php
          $result = $conn->query("SELECT * FROM mipiace WHERE FK_ID_Utente = '".$_SESSION["userID"]."' AND FK_ID_Progetto = '".$_POST["project-id"]."'");
          $liked = mysqli_num_rows($result) != 0 ? true : false;
        ?>
        <div class="button modal-likes" id="like-button" style="background-image: url('../Images/Other/like<?= $liked ? "1" : "" ?>.png')"></div>
        <?php
          $result = $conn->query("SELECT * FROM mipiace WHERE FK_ID_Progetto = '".$_POST["project-id"]."'");
          $likes = mysqli_num_rows($result);
          //var_dump($result->fetch_assoc());
        ?>
        <div class="center" style="height: 5vh"><?= $likes ?></div>
        <input type="hidden" name="like-state" id="like-state" value="<?= $liked ? "unlike" : "like" ?>">
        <div hx-post="../Modals/likes.php" hx-trigger="click from:#like-button" hx-swap="none" hx-include="#project-id, #like-state"></div>
      </div>
    <?php 
    } ?>
  </div>
</div>
<div id="debug"></div>
<script>
  var likeBtn = document.getElementById("like-button");
  var projId = document.getElementById("project-id").value;
  //console.log(projId);
  likeBtn.addEventListener("click", () => {
    //console.log(likeBtn.style.backgroundImage);
    var counter =  Number(likeBtn.nextElementSibling.textContent);
    //console.log(likeBtn.nextElementSibling.textContent);
    console.log(counter);
    if(likeBtn.style.backgroundImage === 'url("../Images/Other/like.png")'){
      counter++;
      likeBtn.style.backgroundImage = "url('../Images/Other/like1.png')";
      likeBtn.nextElementSibling.textContent = (counter + "");
      var likeBtn1 = document.getElementById("likes-id-"+projId);
      likeBtn1.style.backgroundImage = "url('../Images/Other/like1.png')";
      likeBtn1.nextElementSibling.textContent = (counter + "");
      //console.log("like");
    } 
    else if(likeBtn.style.backgroundImage === 'url("../Images/Other/like1.png")') {
      counter--;
      likeBtn.style.backgroundImage = "url('../Images/Other/like.png')";
      likeBtn.nextElementSibling.textContent = (counter + "");
      var likeBtn1 = document.getElementById("likes-id-"+projId);
      likeBtn1.style.backgroundImage = "url('../Images/Other/like.png')";
      likeBtn1.nextElementSibling.textContent = (counter + "");
      //console.log("unlike");
    }
    //console.log(counter);
  });
</script>