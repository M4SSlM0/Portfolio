<?php
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
?>
<div class="fill row">
  <div class="fillY column search-form-container">
    <div class="fillX center textBig search-title">Search</div>
    <div class="search-form">
      <div class="fillX center row">
        <div class="fillX center column">
          <div class="fillX center text search-form-label">Description:</div>
          <div class="fillX center custom-input-text">
            <input type="text" name="description" id="description"/>
          </div>
        </div>
        <div class="fillX center column">
          <div class="fillX center text search-form-label">User:</div>
          <div class="fillX center custom-input-text">
            <input type="text" name="username" id="username"/>
          </div>
        </div>
      </div>
      <div class="fillX center row">
        <div class="fillX center column">
          <div class="fillX center text search-form-label">Type:</div>
          <div class="custom-select">
            <select name="type" id="type">
              <?php
                $results = $conn->query("SELECT * FROM tipi");
                while($row = $results->fetch_array(MYSQLI_ASSOC)){?>
                  <option value="<?= $row["ID"] ?>"><?= $row["Nome"] ?></option>
                <?php
                }
              ?>
              <option value="default" selected>All</option>
            </select>
          </div>
        </div>
        <div class="FillX center">
          <button class="center gradient text button search-submit" hx-post="../Search/search.php" hx-trigger="click" hx-target="#search-results" hx-swap="innerHTML" hx-include="#description, #username, #type">Search</button>
        </div>
      </div>
    </div>
  </div>
  <div class="grow gradient search-results" id="search-results">
    <div hx-post="../Search/search.php" hx-trigger="load" hx-swap="outerHTML"></div>
  </div>
</div>
