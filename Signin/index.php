<html>
  <head>
    <title>3Dproject</title>
    <link rel="stylesheet" href="./signin.css" />
    <link rel="stylesheet" href="../Themes/theme-classic.css" />
  </head>
  <body>
    <?php
      $dbHost = "localhost";
      $dbUser = "root";
      $dbPass = "";
      $dbName = "3dprojectdb";
      $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

      $isUsernameValid=false;
      $isEmailValid=false;
      $isPasswordValid=false;
      $errorMessage="";
      $stmt;
      $result;
    ?>
    <div class="container">
      <div class="signin-form-container">
        <form method="post" action="./" class="signin-form">
          <div class="signin-form-logo-container">
            <div class="signin-form-logo">Placeholder logo</div>
          </div>
          <div class="signin-title-container">
            <div class="signin-title">Sign in</div>
          </div>
          <div class="signin-input-container">
            <?php 
              if(!isset($_POST['username'])) $errorMessage="";
              else if($_POST["username"]=="") $errorMessage="*Username cannot be empty";
              else {
                $stmt=$conn->prepare("SELECT * FROM utenti WHERE NomeUtente = ?");
                $stmt->bind_param("s",$_POST['username']);
                $stmt->execute();
                if(mysqli_num_rows($result=$stmt->get_result())==1) $errorMessage="*Username already taken";
                else $isUsernameValid=true;
              }
            ?>
            <input
              class="signin-input <?= $errorMessage==""?"":"error" ?> <?php if(isset($_POST["username"])&& $_POST["username"]!="") echo "active"; else echo "" ?>"
              id="signin-input-username"
              value="<?php if(isset($_POST["username"])) echo $_POST["username"] ?>"
              type="text"
              name="username"
              required
            />
            <label class="signin-input-label" for="signin-input-username"
              >Username</label
            >
            <div class="signin-input-error"><?= $errorMessage ?></div>
          </div>
          <div class="signin-input-container">
            <?php 
            $errorMessage="";
              if(!isset($_POST['email'])) $errorMessage="";
              else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errorMessage="*Invalid email";
              else {
                $stmt=$conn->prepare("SELECT * FROM utenti WHERE Email = ?");
                $stmt->bind_param("s",$_POST['email']);
                $stmt->execute();
                if(mysqli_num_rows($result=$stmt->get_result())==1) $errorMessage="*Email already registrated";
                else $isEmailValid=true;
              }
            ?>
            <input
              class="signin-input <?= $errorMessage==""?"":"error" ?> <?php if(isset($_POST["email"])&& $_POST["email"]!="") echo "active"; else echo "" ?>"
              id="signin-input-email"
              value="<?php if(isset($_POST["email"])) echo $_POST["email"] ?>"
              type="email"
              name="email"
              required
            />
            <label class="signin-input-label" for="signin-input-email"
              >Email</label
            >
            <div class="signin-input-error"><?= $errorMessage ?></div>
          </div>
          <div class="signin-input-container">
            <?php
              $errorMessage="";
              if(!isset($_POST["password"])) $errorMessage="";
              else if($_POST["password"]=="") $errorMessage="*Password cannot be empty";
              else if(strlen($_POST["password"])<8) $errorMessage="*Password too short";
              else $isPasswordValid=true;
            ?>
            <input
              class="signin-input <?= $errorMessage==""?"":"error" ?>"
              id="signin-input-password"
              type="password"
              name="password"
              required
            />
            <label class="signin-input-label" for="signin-input-password"
              >Password</label
            >
            <div class="signin-input-error"><?= $errorMessage ?></div>
          </div>
          <div class="signin-input-container">
            <?php
              $errorMessage="";
              if(!isset($_POST["confirm-password"])) $errorMessage="";
              else if($_POST["confirm-password"]=="") $errorMessage="*Password cannot be empty";
              else if(strlen($_POST["confirm-password"])<8) $errorMessage="*Password too short";
              else if($_POST["password"]!=$_POST["confirm-password"]) $errorMessage="*Passwords don't match";
              else {
                if($isUsernameValid && $isEmailValid && $isPasswordValid) {
                  $stmt=$conn->prepare("INSERT INTO utenti (Email, Password, NomeUtente) VALUES (?,?,?)");
                  $stmt->bind_param("sss",$_POST['email'],md5($_POST["password"]),$_POST["username"]);
                  $stmt->execute();
                  header("Location: ../Home");
                  exit;
                }
              }
            ?>
            <input
              class="signin-input <?= $errorMessage==""?"":"error" ?>"
              id="signin-input-confirm-password"
              type="password"
              name="confirm-password";
              required
            />
            <label
              class="signin-input-label"
              for="signin-input-confirm-password"
              >Confirm passowrd</label
            >
            <div class="signin-input-error"><?= $errorMessage ?></div>
          </div>
          <div class="signin-submit-button-container">
            <button class="signin-submit-button gradient-custom">
              SIGN IN
            </button>
          </div>
          <div class="signin-login-link-container">
            <a class="signin-login-link" href="../Login">
              Already have an account?
            </a>
          </div>
          <div class="signin-copyright-container">
            <div class="signin-copyright">
              Copyright &copy 2024. Do tf you want with it.
            </div>
          </div>
        </form>
      </div>
      <div class="signin-background gradient-custom"></div>
    </div>

    <script>
      var coll = document.getElementsByClassName("signin-input");
      var i;

      for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("change", function () {
          if (this.value == "") this.classList.remove("active");
          else this.classList.add("active");
        });
      }
    </script>
  </body>
</html>
