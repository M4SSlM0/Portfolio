  <?php
        $condizioni;

        
          session_start();

            $dbHost = "localhost";
            $dbUser = "root";
            $dbPass = "";
            $dbName = "3dprojectdb";
            $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

            $sql_OttieniUsername = "SELECT NomeUtente FROM utenti WHERE email = '".$_SESSION["ses_mail"]."'";
            $exe_OttieniUsername = $conn->query($sql_OttieniUsername);
                
            if($exe_OttieniUsername->num_rows > 0) {
                $row = $exe_OttieniUsername->fetch_assoc();
                $_SESSION["ses_user"] = $row["NomeUtente"]; 
            }


        //echo $_POST["projects-state"]."--";
        echo $_POST["projects-state"];
            if($_POST["projects-state"]=="All")
            {
                $_sql_CercaNumeroProgetti = "";
            }
            else if($_POST["projects-state"]=="InCorso")
            {
                $_sql_CercaNumeroProgetti = "AND status = 'InCorso'";
            } 
            else if($_POST["projects-state"]=="InPausa")
            {
               $_sql_CercaNumeroProgetti = "AND status = 'InPausa'";
            }
            else if ($_POST["projects-state"]=="Finito")
            {
               $_sql_CercaNumeroProgetti = "AND status = 'Finito'";
            }
            else if ($_POST["projects-state"]=="Scartato")
            {
               $_sql_CercaNumeroProgetti = "AND status = 'Scartato'";
            }

        $sql_CercaNumeroProgetti = "SELECT COUNT(p.ID) as NumID, 
                                           SUM(p.ContatoreOre) as NumOre
                                    FROM progetti p JOIN utenti u ON p.FK_ID_Utente = u.ID 
                                    WHERE email = '".$_SESSION["ses_mail"]."'";

        $exe_CercaNumeroProgetti = $conn->query($sql_CercaNumeroProgetti);

        if (!$exe_CercaNumeroProgetti) { echo "Errore nella query: " . $conn->error;} 
        else 
        {
            if ($exe_CercaNumeroProgetti->num_rows > 0) {
                while ($row = $exe_CercaNumeroProgetti->fetch_assoc())
                { $NumeroProgetti = $row['NumID']; 
                  $NumeroOre = $row['NumOre'];}
            } 
            else { echo "Nessun risultato trovato per l'email ".$_SESSION["ses_mail"]; }
        }

        $sql_CercaStatoProgetti = "SELECT SUM(CASE WHEN p.Status = 'InCorso' THEN 1 ELSE 0 END) AS NumInCo,
                                          SUM(CASE WHEN p.Status = 'InPausa' THEN 1 ELSE 0 END) AS NumInPa,
                                          SUM(CASE WHEN p.Status = 'Scartato' THEN 1 ELSE 0 END) AS NumScar,
                                          SUM(CASE WHEN p.Status = 'Finito' THEN 1 ELSE 0 END) AS NumFini
        
                                        FROM progetti p 
                                        JOIN utenti u ON p.FK_ID_Utente = u.ID 
                                        WHERE u.email = '".$_SESSION['ses_mail']."'".$_sql_CercaNumeroProgetti;
        
        $exe_CercaStatoProgetti  = $conn->query($sql_CercaStatoProgetti );

        if (!$exe_CercaStatoProgetti ) { echo "Errore nella query: " . $conn->error;} 
        else 
        {
            if ($exe_CercaStatoProgetti ->num_rows > 0) {
                while ($row = $exe_CercaStatoProgetti ->fetch_assoc())
                { $NumeroInCorso = $row['NumInCo']; 
                  $NumeroInPausa = $row['NumInPa'];
                  $NumeroScartati = $row["NumScar"];
                  $NumeroFiniti = $row["NumFini"];}
            } 
            else { echo "Nessun risultato trovato per l'email ".$_SESSION["ses_mail"]; }
        }
    ?>

<div class="profile-statistics-result-components-container">
          <div class="profile-statistics-result-component"><?php echo $NumeroProgetti?> Projects</div>
          <div class="profile-statistics-result-component"><?php echo $NumeroOre?> Hours</div>
        </div>
        <div class="profile-statistics-result-chart-container">
          <canvas class="profile-statistics-result-chart" id="myChart"></canvas>
          <script>
            var xValues = ["In corso", "In pausa", "Finito", "Scartato"];
            var yValues = [<?php echo $NumeroInCorso ?>, <?php echo $NumeroInPausa ?>, <?php echo $NumeroScartati?>, <?php echo $NumeroFiniti ?>];
            var barColors = ["#b91d47", "#00aba9", "#2b5797", "#e8c3b9"];

            new Chart("myChart", {
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
          </script>
        </div>
      </div>