<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  $project = $conn->query("SELECT * FROM progetti WHERE ID='".$_POST["project-id"]."'")->fetch_assoc();
?>

<div class="column modal-container">
  <input type="hidden" value="<?= $project["ID"] ?>" id="project-id" name="project-id">
  <div class="fillX center">
    <div class="fillX center textBig modal-title">Edit project</div>
    <button class="text button close-modal" onclick="closeModal()">
      &times;
    </button>
  </div>
  <div class="fillX center">
    <div class="gradient modal-project-image" id="image" style="background-image: url('<?= $project["Immagine"] ?>')">
      <textarea
        class="fill textSmall modal-project-description"
        maxlength="352"
        name="modal-description"
        id="modal-description"
        placeholder="Insert the description here"
        ondragstart="return false;"
        ondrop="return false;"
      ><?= $project["Descrizione"]?></textarea>
      <div class="center text drop-area" id="drop-area">
        Drop image here to load
      </div>
      <input type="hidden" name="hidden-input" id="hidden-input" value="<?php 
        $path = $project["Immagine"];
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        echo $base64;
      ?>" />
    </div>
  </div>
  <div class="fillX row center">
    <div class="column center modal-input-container">
      <div class="center text modal-label">Type:</div>
      <div class="custom-select">
        <select name="modal-type" id="modal-type">
          <?php
            $results = $conn->query("SELECT * FROM tipi");
            while($row = $results->fetch_array(MYSQLI_ASSOC)){?>
              <option value="<?= $row["ID"] ?>"<?php if($row["ID"] == $project["FK_ID_Tipo"]) echo "selected" ?>><?= $row["Nome"] ?></option>
            <?php
            }
          ?>
        </select>
      </div>
    </div>
    <div class="column center modal-input-container">
      <div class="center text modal-label">Status:</div>
      <div class="custom-select">
        <select name="modal-status" id="modal-status">
          <option value="InCorso" <?php if($project["Status"] == "InCorso") echo "selected" ?>>In corso</option>
          <option value="Finito" <?php if($project["Status"] == "Finito") echo "selected" ?>>Finito</option>
          <option value="InPausa" <?php if($project["Status"] == "InPausa") echo "selected" ?>>In pausa</option>
          <option value="Scartato" <?php if($project["Status"] == "Scartato") echo "selected" ?>>Scartato</option>
        </select>
      </div>
    </div>
  </div>
  <div class="fillX row center">
    <div class="column center modal-input-container">
      <div class="center text modal-label">Hours:</div>
      <div class="custom-input-number">
        <input
          type="number"
          name="modal-hours"
          id="modal-hours"
          min="<?= $project["ContatoreOre"] ?>"
          step="1"
          value="<?= $project["ContatoreOre"] ?>"
        />
      </div>
    </div>
    <div class="column center modal-input-container">
      <div class="center text modal-label">Visibility:</div>
      <div class="custom-select">
        <select name="modal-visibility" id="modal-visibility">
          <option value="0" <?php if($project["Visibilita"] == "0") echo "selected" ?>>Pubblico</option>
          <option value="1" <?php if($project["Visibilita"] == "1") echo "selected" ?>>Privato</option>
        </select>
      </div>
    </div>
  </div>
  <div class="fillX center">
    <button
      class="center text gradient button modal-submit"
      id="submit-edit"
      hx-post="../Modals/submitEditProject.php"
      hx-trigger="click"
      hx-target="#debug"
      hx-swap="innerText"
      hx-include="#modal-description, #hidden-input, #modal-status, #modal-type, #modal-hours, #modal-visibility, #project-id"
    >
      <div 
        hx-post="../Modals/showProject.php"
        hx-trigger="click from:#submit-edit"
        hx-target="#modal"
        hx-swap="innerHTML"
        hx-include="#project-id"
      ></div>
      <div
        hx-post="../MyProjects/search.php"
        hx-trigger="click from:#submit-edit"
        hx-target="#result"
        hx-swap="innerHTML"
        hx-include="#search-type, #search-description">
      </div>
        Submit
      </button>
      <button
        class="center text gradient button modal-submit"
        hx-post="../Modals/showProject.php"
        hx-trigger="click"
        hx-target="#modal"
        hx-swap="innerHTML"
        hx-include="#project-id"
      >
        Cancel
    </button>
  </div>
</div>
<div id="debug"></div>
<script>
  dropArea = document.getElementById("drop-area");
  image = document.getElementById("image");
  input = document.getElementById("hidden-input");
  dropArea.ondrop = function (event) {
    event.preventDefault();
    dropArea.classList.remove("dragover");
    console.log("drop");
    let dt = event.dataTransfer;
    let files = dt.files;
    let reader = new FileReader();
    reader.readAsDataURL(files[0]);
    reader.onloadend = () => {
      image.style.backgroundImage = "url('" + reader.result + "')";
      input.value = reader.result;
    };
  };

  dropArea.ondragover = function (e) {
    e.preventDefault();
    dropArea.classList.add("dragover");
  };
  dropArea.ondragleave = function () {
    dropArea.classList.remove("dragover");
  };
</script>