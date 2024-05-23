<html>
  <head>
    <link rel="stylesheet" href="../Themes/theme.css" />
    <link rel="stylesheet" href="../MyBS/misc.css" />
    <link rel="stylesheet" href="../MyBS/custom-inputs.css">
    <link rel="stylesheet" href="../Modals/modals.css">
    <link rel="stylesheet" href="./navbar.css" />
    <link rel="stylesheet" href="../Profile/profile.css">
    <link rel="stylesheet" href="../MyProjects/myProjects.css">
    <link rel="stylesheet" href="../Search/search.css">
    <link rel="stylesheet" href="../Profiles/profiles.css">
    <script src="../HTMX/htmx.min.js"></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
  </head>
  <body class="fill">
    <div class="fill" id="page">
      <div hx-post="../Profile" hx-trigger="load" hx-swap="outerHTML" hx-target="this"></div>
    </div>
    <div class="fillY row navbar-container">
      <div class="fillY column navbar" id="navbar">
        <div class="fillX center">
          <div class="center pfp gradient">Placeholder pfp</div>
        </div>
        <div class="fillX column center navbar-buttons-container">
            <button class="center text gradient button navbar-button" hx-post="../Profile" hx-trigger="click" hx-target="#page" hx-swap="innerHTML">Profile</button>
            <button class="center text gradient button navbar-button" hx-post="../MyProjects" hx-trigger="click" hx-target="#page" hx-swap="innerHTML">MyProjects</button>
            <button class="center text gradient button navbar-button" hx-post="../Search" hx-trigger="click" hx-target="#page" hx-swap="innerHTML">Home</button>
            <button class="center text gradient button navbar-button" hx-post="../Profiles" hx-trigger="click" hx-target="#page" hx-swap="innerHTML">Ricerca utenti</button>
        </div>
        <div class="fillX center">
            <button class="center text button logout-button" hx-post="../Home/logout.php" hx-trigger="click" hx-swap="innerHTML">Disconnect</button>
        </div>
        <div class="center gradient button navbar-settings">Placeholder settings</div>
      </div>
      <div class="fillY grow column">
        <div class="border-top"></div>
        <button class="center pointer textSmall collapse-button" id="navbar-collpse">&#8660</button>
        <div class="grow border-bottom"></div>
      </div>
    </div>
    <?php
      include "../Modals/customModal.html";
      /*session_start();
      session_destroy();*/
    ?>
    
    <script>
      var collapseButton = document.getElementById("navbar-collpse");

        collapseButton.addEventListener("click", function () {
          this.classList.toggle("active");
          var content = document.getElementById("navbar");
          if (content.style.width) {
            content.style.width = null;
          } else {
            content.style.width = 25.65 + "vw";
          }
        });
    </script>
  </body>
</html>
