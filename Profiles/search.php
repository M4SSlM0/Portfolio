<?php
  session_start();

  // Connetti al database
  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
  }

  $sql_ControllaAdmin = "SELECT Admin FROM utenti WHERE ID = '".$_SESSION["userId"]."'";
  $exe_ControllaAdmin = $conn->query($sql_ControllaAdmin);

  if ($exe_ControllaAdmin->num_rows > 0) {
      $row = $exe_ControllaAdmin->fetch_assoc();
      $superutente = $row['Admin']; 
  }

  if (!$superutente) {
    echo "Non sei autorizzato ad accedere a questa pagina.";
    echo "Verrai riportato alla home tra 3 secondi.";
    // Codice HTML con meta tag per il redirect
    echo '<meta http-equiv="refresh" content="3;url=../Home/index.php">';

    
    
  } else {

    // Definisci la query SQL base
    $sql_OttieniUtenti = "SELECT utenti.*, 
                          COUNT(progetti.ID) AS NumProgetti, 
                          SUM(CASE WHEN progetti.Status = 'Completato' THEN 1 ELSE 0 END) AS NumProgettiCompletati, 
                          ROUND((SUM(CASE WHEN progetti.Status = 'Completato' THEN 1 ELSE 0 END) / COUNT(progetti.ID)),1) * 100 AS PercentualeProgettiCompletati
                          FROM utenti 
                          LEFT JOIN progetti ON utenti.ID = progetti.FK_ID_Utente 
                          WHERE utenti.Email != ?";

    // RICERCA PER NOME
    if (isset($_POST['search']) && !empty($_POST['search'])) {
        $cerca = $_POST['search'];
        $sql_OttieniUtenti .= " AND utenti.NomeUtente LIKE ?";
    }

    // Aggiungi il filtro per gli admin
    if (isset($_POST['admin_filter']) && $_POST['admin_filter'] !== '') {
        $cercaAdmin = $_POST['admin_filter'];
        $sql_OttieniUtenti .= " AND utenti.Admin = ?";
    }

    // Aggiungi il filtro per i bannati
    if (isset($_POST['ban_filter']) && $_POST['ban_filter'] !== '') {
        $cercaBan = $_POST['ban_filter'];
        $sql_OttieniUtenti .= " AND utenti.IsBan = ?";
    }

    // Gestisci l'ordinamento
    $order_by = "";
    if (isset($_POST['order_by']) && !empty($_POST['order_by'])) {
      switch ($_POST['order_by']) {
        case 'nome_asc':
          $order_by = " ORDER BY utenti.NomeUtente ASC";
          break;
        case 'nome_desc':
          $order_by = " ORDER BY utenti.NomeUtente DESC";
          break;
        case 'progetti_totali':
          $order_by = " ORDER BY NumProgetti DESC, utenti.NomeUtente ASC";
          break;
        case 'progetti_completati':
          $order_by = " ORDER BY NumProgettiCompletati DESC, utenti.NomeUtente ASC";
          break;
        default:
          $order_by = "";
          break;
      }
    }

    $sql_OttieniUtenti .= " GROUP BY utenti.ID" . $order_by;

    // Prepara lo statement con i parametri necessari
    $stmt = $conn->prepare($sql_OttieniUtenti);

    // Costruisci i parametri dinamicamente
    $params = [$_SESSION["ses_mail"]];
    $types = "s";

    if (isset($cerca)) {
        $params[] = "%$cerca%";
        $types .= "s";
    }
    if (isset($cercaAdmin)) {
        $params[] = $cercaAdmin;
        $types .= "i";
    }
    if (isset($cercaBan)) {
        $params[] = $cercaBan;
        $types .= "i";
    }

    // Bind dei parametri
    $stmt->bind_param($types, ...$params);

    // Esegui la query
    $stmt->execute();
    $exe_OttieniUtenti = $stmt->get_result();

    if ($exe_OttieniUtenti->num_rows > 0) {
      // Mostra l'elenco degli utenti
      while ($row = $exe_OttieniUtenti->fetch_assoc()) {
        
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
      <?php
      }?>
      <script>
        var projects = document.getElementsByClassName("user");
        console.log(projects);
        for (let i = 0; i < projects.length; i++) {
          showModal(projects[i], "click");
        }
      </script>
      <?php
    } else {
      echo "Nessun utente trovato.";
    }
    $stmt->close();
    $conn->close();
}
?>