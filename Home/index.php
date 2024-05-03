<html>
  <head>
    <title>3Dproject</title>
    <link rel="stylesheet" href="./home.css" />
    <link rel="stylesheet" href="../Themes/theme-classic.css" />
    <link rel="stylesheet" href="../Profile/profile.css">
    <link rel="stylesheet" href="../MyProjects/myProjects.css">
    <link rel="stylesheet" href="../Modals/customModals.css">
    <script src="../HTMX/htmx.min.js"></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
  </head>
  <body>
    <?php
      $dbHost = "localhost";
      $dbUser = "root";
      $dbPass = "";
      $dbName = "3dprojectdb";
      $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    ?>
    <div class="home-container">
      <div class="home-navbar-container">
        <div class="home-navbar-collapse" id="collapse">
          <div class="home-navbar-profile-container">
            Placeholder foto profilo
          </div>
          <div class="home-navbar-button-container">
            <button class="home-navbar-button">Home</button>
          </div>
          <div class="home-navbar-button-container">
            <button class="home-navbar-button">My Projects</button>
          </div>
          <div class="home-navbar-button-container">
            <button class="home-navbar-button">Placeholder 3</button>
          </div>
          <div class="home-navbar-button-container">
            <button class="home-navbar-button">Placeholder 4</button>
          </div>
          <div class="home-navbar-settings-container">
            Placeholder impostazioni
          </div>
          <div class="home-navbar-disconnect-button-container">
            <button class="home-navabr-diaconnect-button">
              Disconnetti
            </button>
          </div>
        </div>
        <div class="home-navbar-collapse-button-container">
          <div class="home-nabvar-collapse-border-top"></div>
          <button class="home-navbar-collapse-button">&#8660</button>
          <div class="home-nabvar-collapse-border-bottom">
          </div>
        </div>
      </div>
      <div class="home-page">
        <!--               \/   sostituisci con il percorso della pagina su cui devi lavorare piu' avanti implementero' il funzionamento della barra di navigazione-->
        <div hx-post="../MyProjects" hx-trigger="load" hx-swap="outerHTML" hx-target="this"></div>
      </div>
    </div>

    
    <script>
      var coll = document.getElementsByClassName("home-navbar-collapse-button");

        coll[0].addEventListener("click", function () {
          this.classList.toggle("active");
          var content = document.getElementById("collapse");
          if (content.style.maxWidth) {
            content.style.maxWidth = null;
          } else {
            content.style.maxWidth = 488 + "px";
          }
        });
    </script>
  </body>
</html>
