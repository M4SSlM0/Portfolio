<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
?>
<div class="myProjects-container">
  <div class="myProjects-form-container">
    <div class="myProjects-search-form-container">
      <div class="myProjects-search-title">Search</div>
      <div class="myProjects-search-form">
        <div class="myProjects-search-input-container">
          <div class="myProjects-input-label">Type</div>
          <div class="custom-select">
            <select name="search-type" id="myProjects-search-type">
              <option value="default" selected>All</option>
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
        <div class="myProjects-search-input-container">
          <div class="myProjects-input-label">Description</div>
          <div>
            <input class="myProjects-search-input" type="text" name="search-description" id="myProjects-search-description" />
            <button class="myProjects-search-submit" hx-post="../MyProjects/search.php" hx-trigger="click" hx-target="#results" hx-swap="innerHTML" hx-include="#myProjects-search-type, #myProjects-search-description">Search</button>
          </div>
        </div>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:center;"><button id="new-project" hx-post="../Modals/modalNewProject.html" hx-trigger="click" hx-target="#custom-modal-body" hx-swap="innerHTML">+ New Project</button></div>
  </div>
  <div class="myProjects-background">
    <div class="myProjects-results" id="results">
      <div hx-post="../MyProjects/search.php" hx-trigger="load" hx-target="this" hx-swap="outerHTML"></div>
    </div>
  </div>
</div>
<?php include "../Modals/customModal.html" ?>
<script>
  showModal(document.getElementById("new-project"), "click");
</script>
