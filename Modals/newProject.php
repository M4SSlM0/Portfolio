<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
?>

<div class="column modal-container">
  <div class="fillX center">
    <div class="fillX center textBig modal-title">New project</div>
    <button class="text button close-modal" onclick="closeModal()">
      &times;
    </button>
  </div>
  <div class="fillX center">
    <div class="gradient modal-project-image" id="image">
      <textarea
        class="fill textSmall modal-project-description"
        maxlength="352"
        name="modal-description"
        id="modal-description"
        placeholder="Insert the description here"
        ondragstart="return false;"
        ondrop="return false;"
      ></textarea>
      <div class="center text drop-area" id="drop-area">
        Drop image here to load
      </div>
      <input type="hidden" name="hidden-input" id="hidden-input"/>
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
              <option value="<?= $row["ID"] ?>"><?= $row["Nome"] ?></option>
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
          <option value="InCorso">In corso</option>
          <option value="Finito">Finito</option>
          <option value="InPausa">In pausa</option>
          <option value="Scartato">Scartato</option>
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
          min="1"
          step="1"
          value="1"
        />
      </div>
    </div>
    <div class="column center modal-input-container">
      <div class="center text modal-label">Visibility:</div>
      <div class="custom-select">
        <select name="modal-visibility" id="modal-visibility">
          <option value="0">Pubblico</option>
          <option value="1">Privato</option>
        </select>
      </div>
    </div>
  </div>
  <div class="fillX center">
    <button
      class="center text gradient button modal-submit"
      id="modal-submit"
      onclick="closeModal()"
      hx-post="../Modals/submitNewProject.php"
      hx-trigger="click"
      hx-target="#debug"
      hx-swap="innerHTML"
      hx-include="#modal-description, #hidden-input, #modal-status, #modal-type, #modal-hours, #modal-visibility"
    >
    <div 
        hx-post="../MyProjects/search.php"
        hx-trigger="click from:#modal-submit"
        hx-target="#result"
        hx-swap="innerHTML"
        hx-include="#search-type, #search-description"></div>
      Create
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
  /*debug
  dropArea.ondrop = function (e) {
    console.log("drop");
    e.preventDefault();
  };*/
  /*--------add client side input validation--------*/
</script>
