<?php
session_start();

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "3dprojectdb";
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_OttieniUsername = "SELECT NomeUtente FROM utenti WHERE email = '".$_SESSION["ses_mail"]."'";
$exe_OttieniUsername = $conn->query($sql_OttieniUsername);

if ($exe_OttieniUsername->num_rows > 0) {
  $row = $exe_OttieniUsername->fetch_assoc();
  $_SESSION["ses_user"] = $row["NomeUtente"];
}

$sql_CercaNumeroProgetti = "SELECT COUNT(p.ID) as NumID, 
                                   SUM(p.ContatoreOre) as NumOre
                            FROM progetti p JOIN utenti u ON p.FK_ID_Utente = u.ID 
                            WHERE email = '".$_SESSION["ses_mail"]."'";
                            

if ($_POST["projects-type"] != "All") {
  $sql_CercaNumeroProgetti .= " AND p.FK_ID_Tipo = '".$_POST["projects-type"]."'";
}

if ($_POST["projects-state"] != "All") {
  $sql_CercaNumeroProgetti .= " AND p.Status = '".$_POST["projects-state"]."'";
}

//var_dump($sql_CercaNumeroProgetti);

$exe_CercaNumeroProgetti = $conn->query($sql_CercaNumeroProgetti);


if (!$exe_CercaNumeroProgetti) { 
    echo "Errore nella query: " . $conn->error;
} else {
    if ($exe_CercaNumeroProgetti->num_rows > 0) {
        while ($row = $exe_CercaNumeroProgetti->fetch_assoc()) {
            $NumeroProgetti = $row['NumID']; 
            $NumeroOre = $row['NumOre'];
        }
    } else { 
        echo "Nessun risultato trovato per l'email ".$_SESSION["ses_mail"]; 
    }
}

$sql_CercaStatoProgetti1 = "SELECT SUM(CASE WHEN p.Status = 'InCorso' THEN 1 ELSE 0 END) AS NumInCo,
                                  SUM(CASE WHEN p.Status = 'InPausa' THEN 1 ELSE 0 END) AS NumInPa,
                                  SUM(CASE WHEN p.Status = 'Scartato' THEN 1 ELSE 0 END) AS NumScar,
                                  SUM(CASE WHEN p.Status = 'Finito' THEN 1 ELSE 0 END) AS NumFini
                            FROM progetti p 
                            JOIN utenti u ON p.FK_ID_Utente = u.ID 
                            WHERE u.email = '".$_SESSION['ses_mail']."'";

if ($_POST["projects-type"] != "All") {
  $sql_CercaStatoProgetti1 .= " AND p.FK_ID_Tipo = ".$_POST["projects-type"];
}
//var_dump($sql_CercaStatoProgetti1);

$exe_CercaStatoProgetti1  = $conn->query($sql_CercaStatoProgetti1);

if (!$exe_CercaStatoProgetti1) { 
    echo "Errore nella query: " . $conn->error;
} else {
    if ($exe_CercaStatoProgetti1->num_rows > 0) {
        while ($row = $exe_CercaStatoProgetti1->fetch_assoc()) {
            $NumeroInCorso = $row['NumInCo']; 
            $NumeroInPausa = $row['NumInPa'];
            $NumeroScartati = $row["NumScar"];
            $NumeroFiniti = $row["NumFini"];
        }
    } else { 
        echo "Nessun risultato trovato per l'email ".$_SESSION["ses_mail"]; 
    }
}

$sql_CercaTipi = "SELECT * FROM tipi";
$exe_CercaTipi = $conn->query($sql_CercaTipi);
$Tipi = array();
$NumeroPerTipo = array();

if ($exe_CercaTipi->num_rows > 0) { 
    while ($row = $exe_CercaTipi->fetch_assoc()) {
        $Tipi[] = $row["Nome"];
        $sql_CercaStatoProgetti2 = "SELECT COUNT(p.ID) AS sium
                                    FROM progetti p 
                                    JOIN utenti u ON p.FK_ID_Utente = u.ID 
                                    WHERE u.email = '".$_SESSION['ses_mail']."' AND p.FK_ID_Tipo = '".$row["ID"]."'";

        if ($_POST["projects-state"] != "All") {
          $sql_CercaStatoProgetti2 .= " AND p.Status = '".$_POST["projects-state"]."'";
        }

        $exe_CercaStatoProgetti2 = $conn->query($sql_CercaStatoProgetti2);              

        if (!$exe_CercaStatoProgetti2) { 
            echo "Errore nella query: " . $conn->error;
        } else {
            if ($exe_CercaStatoProgetti2->num_rows > 0) {
                while ($row2 = $exe_CercaStatoProgetti2->fetch_assoc()) {
                    $NumeroPerTipo[] = $row2["sium"];
                }
            } else { 
                echo "Nessun risultato trovato per l'email " . $_SESSION["ses_mail"]; 
            }
        }
    }
}

// Query per ottenere il numero di progetti per l'utente loggato
    $sql_ProgettiUtente = "SELECT COUNT(p.ID) AS NumProgettiUtente
                          FROM progetti p
                          JOIN utenti u ON p.FK_ID_Utente = u.ID
                          WHERE u.email = '".$_SESSION["ses_mail"]."'";

    $exe_ProgettiUtente = $conn->query($sql_ProgettiUtente);

    if ($exe_ProgettiUtente->num_rows > 0) {
        $row = $exe_ProgettiUtente->fetch_assoc();
        $NumProgettiUtente = $row['NumProgettiUtente'];
    } else {
        $NumProgettiUtente = 0;
    }

/*foreach ($Tipi as $a) { 
    echo $a . " ";
}
foreach ($NumeroPerTipo as $b) { 
    echo $b . " ";
}*/

$conn->close();
?>
<div class="column profile-ststs-chart">
  <div class="fillX center row">
    <div class="center text gradient profile-chart-value"><?= $NumeroProgetti?> Progetti</div>
    <div class="center text gradient profile-chart-value"><?= $NumeroOre?> Ore</div>
  </div>
  <div class="FillX center textTiny chart-title">States:</div>
  <div class="center">
    <div class="center profile-chart">
      <canvas id="myChart2"></canvas>
      <script>
        var xValues = <?php echo json_encode($Tipi, JSON_HEX_TAG); ?>;
        var yValues = <?php echo json_encode($NumeroPerTipo, JSON_HEX_TAG); ?>;
        var barColors = ["#b91d47", "#00aba9", "#2b5797", "#e8c3b9"];

        var chart = new Chart("myChart2", {
          type: "pie",
          data: {
            labels: xValues,
            datasets: [
              {
                backgroundColor: barColors,
                data: yValues,
              },
            ],
          },
          options: {
            maintainAspectRatio: false,
            title: {
              display: true,
              text: "",
            },
            legend: {
              labels: {
                fontColor: "white", //set your desired color
              },
            },
          },
        });

        chart.canvas.parentNode.style.height = "253px";
        chart.canvas.parentNode.style.width = "506px";
      </script>
    </div>
  </div>
  <div class="FillX center textTiny chart-title">Types:</div>
  <div class="center">
    <div class="center profile-chart">
      <canvas id="myChart1"></canvas>
      <script>
        var xValues = ["In corso", "In pausa", "Finito", "Scartato"];
        var yValues = [<?php echo $NumeroInCorso ?>, <?php echo $NumeroInPausa ?>, <?php echo $NumeroScartati?>, <?php echo $NumeroFiniti ?>];
        var barColors = ["#b91d47", "#00aba9", "#2b5797", "#e8c3b9"];

        var chart = new Chart("myChart1", {
          type: "pie",
          data: {
            labels: xValues,
            datasets: [
              {
                backgroundColor: barColors,
                data: yValues,
              },
            ],
          },
          options: {
            maintainAspectRatio: false,
            title: {
              display: true,
              text: "",
            },
            legend: {
              labels: {
                fontColor: "white", //set your desired color
              },
            },
          },
        });

        chart.canvas.parentNode.style.height = "253px";
        chart.canvas.parentNode.style.width = "506px";
      </script>
    </div>
  </div>
</div>
<?php 
  include "info.php";
?>
