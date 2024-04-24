<html>
  <head>
    <title>3DProject</title>
    <link rel="stylesheet" href="./login.css" />
    <link rel="stylesheet" href="../Themes/theme-classic.css" />
  </head>
  <body>
    <?php
      $dbHost = "localhost";
      $dbUser = "root";
      $dbPass = "";
      $dbName = "3dprojectdb";
      $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

      $isValid=false;
      $errorMessage="";
      $stmt;
      $result;

      session_start(); //-----------------------------------------------------------------------------------------
    ?>
    <div class="container">
      <div class="login-form-container">
        <form class="login-form" method="post" action="./">
          <div class="login-form-logo-container">
            <div class="login-form-logo">Placeholder logo</div>
          </div>
          <div class="login-title-container">
            <div class="login-title">Login</div>
          </div>
          <div class="login-input-container">
            <?php 
              if(!isset($_POST['email'])) $errorMessage="";
              else if($_POST["email"]=="") $errorMessage="*Email cannot be empty";
              else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errorMessage="*Invalid email";
              else {
                $stmt=$conn->prepare("SELECT * FROM utenti WHERE Email = ?");
                $stmt->bind_param("s",$_POST['email']);
                $stmt->execute();
                if(mysqli_num_rows($result=$stmt->get_result())==0) $errorMessage="*Email not registrated";
                else 
                {
                  $isValid=true; 
                  $_SESSION["ses_mail"] = $_POST['email']; //------------------------------------------------------
                }
              }
            ?>
              <input
                class="login-input <?= $errorMessage==""?"":"error" ?> <?= isset($_POST["email"])?"active":"" ?>"
                id="login-input-email"
                name="email"
                value="<?php if(isset($_POST["email"])) echo $_POST["email"] ?>"
                type="email"
                maxlength="64"
                required
              />
              <label class="login-input-label" for="login-input-email"
                >Email</label
              >
              <div class="login-input-error"><?= $errorMessage ?></div>
          </div>
          <div class="login-input-container">
            <?php
              $errorMessage="";
              if(!isset($_POST["password"])) $errorMessage="";
              else if($_POST["password"]=="") $errorMessage="*Password cannot be empty";
              else if($isValid){
                if((($result->fetch_assoc())['Password'] != md5($_POST['password']))) $errorMessage="*Wrong email or password";
                else{
                  header("Location: ../Home");
                  exit;
                }
              } 
            ?>
            <input
              class="login-input <?= $errorMessage==""?"":"error" ?>"
              id="login-input-password"
              name="password"
              type="password"
              maxlength="64"
              required
            />
            <label class="login-input-label" for="login-input-password"
              >Password</label
            >
            <div class="login-input-error"><?= $errorMessage ?></div>
          </div>
          <div class="login-submit-button-container">
            <button class="login-submit-button gradient-custom" type="submit">LOG IN</button>
          </div>
          <div class="login-signin-link-container">
            <a class="login-signin-link" href="../Signin">New user?</a>
          </div>
          <div class="login-copyright-container">
            <div class="login-copyright">
              Copyright &copy 2024. Do tf you want with it.
            </div>
          </div>
        </form>
      </div>
      <div class="login-background gradient-custom"></div>
    </div>


    <script>
      var coll = document.getElementsByClassName("login-input");
      var i;

      for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("change", function () {
          if(this.value=="") this.classList.remove("active");
          else this.classList.add("active")
        });
      }
    </script>
  </body>
</html>
