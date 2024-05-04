<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
?>
<div class="modal-container-outline">
  <div class="modal-container">
    <div class="modal-header">New Project</div>
    <div class="modal-body">
      <div class="modal-project-image-container" id="drop-area">
        <input class="hidden" type="text" name="image" id="hidden-input" />
        <textarea
          class="modal-project-description"
          placeholder="Se ni mondo ci fosse un po' di bene ..."
          name="modal-new-description"
          id="modal-new-description"
        ></textarea>
      </div>
      <div class="modal-row">
        <div class="modal-column">
          <div class="modal-label">Type:</div>
          <div class="custom-select">
            <select name="modal-new-type" id="modal-new-type">
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
        <div class="modal-column">
          <div class="modal-label">Status:</div>
          <div class="custom-select">
            <select name="modal-new-status" id="modal-new-status">
              <option value="In corso">In corso</option>
              <option value="">Finito</option>
              <option value="">In pausa</option>
              <option value="">Scartato</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-row">
        <div class="modal-column">
          <div class="modal-label">Hours:</div>
          <div class="modal-custom-input">
            <input
              class="modal-input"
              type="number"
              min="0"
              step=".25"
              value="0"
              name="modal-new-hours"
              id="modal-new-hours"
            />
          </div>
        </div>
        <div class="modal-column">
          <div class="modal-label">Visibility:</div>
          <div class="custom-select">
            <select name="modal-new-visibility" id="modal-new-visibility">
              <option value="0">Public</option>
              <option value="1">Private</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button
        class="modal-submit-button"
        id="modal-submit"
        onclick="closeModal()"
        hx-post="../Modals/validateNew.php"
        hx-trigger="click"
        hx-target="#custom-modal-body"
        hx-swap="innerHTML"
        hx-include="#hidden-input, #modal-new-description, #modal-new-type, #modal-new-status, #modal-new-hours, #modal-new-visibility"
      >
        Crea
      </button>
    </div>
  </div>
</div>

<script>
  dropArea = document.getElementById("drop-area");
  input = document.getElementById("hidden-input");
  dropArea.ondrop = (event) => {
    event.preventDefault();
    console.log("drop");
    let dt = event.dataTransfer;
    let files = dt.files;
    let reader = new FileReader();
    reader.readAsDataURL(files[0]);
    reader.onloadend = () => {
      console.log("fuckyou");
      dropArea.style.backgroundImage = "url('" + reader.result + "')";
      input.value = reader.result;
    };
  };
</script>
