<html>
  <head>
    <link rel="stylesheet" href="../Themes/theme.css" />
    <link rel="stylesheet" href="./signin.css" />
    <link rel="stylesheet" href="../MyBS/misc.css" />
    <link rel="stylesheet" href="../MyBS/custom-inputs.css" />
  </head>
  <body class="notSelect">
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

      session_start();

      if(isset($_SESSION["userID"])) header("Location: ../Home");
    ?>
    <div class="row fill">
      <div class="fillY column background">
        <div class="center fillX">
          <div class="center gradient logo">Placeholder logo</div>
        </div>
        <div class="center fillX textHuge title">Login</div>
        <form class="column fillX center login-form" action="" method="post">
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
          <div class="column custom-input <?= $errorMessage==""?"":"error" ?> <?php if(isset($_POST["username"])&& $_POST["username"]!="") echo "active"; else echo "" ?>">
            <input
              class="text"
              name="username"
              type="text"
              maxlength="64"
              value="<?php if(isset($_POST["username"])) echo $_POST["username"] ?>"
              required
            />
            <label class="center">Username:</label>
            <div><?= $errorMessage ?></div>
          </div>
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
          <div class="column custom-input <?= $errorMessage==""?"":"error" ?> <?php if(isset($_POST["email"])&& $_POST["email"]!="") echo "active"; else echo "" ?>">
            <input
              class="text"
              name="email"
              type="email"
              maxlength="64"
              value="<?php if(isset($_POST["email"])) echo $_POST["email"] ?>"
              required
            />
            <label class="center">Email:</label>
            <div><?= $errorMessage ?></div>
          </div>
          <?php
            $errorMessage="";
            if(!isset($_POST["password"])) $errorMessage="";
            else if($_POST["password"]=="") $errorMessage="*Password cannot be empty";
            else if(strlen($_POST["password"])<8) $errorMessage="*Password too short";
            else $isPasswordValid=true;
          ?>
          <div class="column custom-input <?= $errorMessage==""?"":"error" ?>">
            <input
              class="text"
              name="password"
              type="password"
              maxlength="32"
              value=""
              required
            />
            <label class="center">Password:</label>
            <div><?= $errorMessage ?></div>
          </div>
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
                $stmt = $conn->prepare('SELECT * FROM utenti WHERE Email = ?');
                $stmt->bind_param("s", $_POST["email"]);
                $stmt->execute();
                $_SESSION["userID"] = $stmt->get_result()->fetch_assoc()["ID"];
                header("Location: ../Home");
                exit;
              }
            }
          ?>
          <div class="column custom-input <?= $errorMessage==""?"":"error" ?>">
            <input
              class="text"
              name="confirm-password"
              type="password"
              maxlength="32"
              value=""
              required
            />
            <label class="center">Confirm password:</label>
            <div><?= $errorMessage ?></div>
          </div>
          <div class="fillX center">
            <button class="center gradient text button login-submit">
              SIGN IN
            </button>
          </div>
        </form>
        <div class="fillX center">
          <a class="textSmall link" href="../Login">Already have an account?</a>
        </div>
      </div>
      <div class="grow gradient"></div>
    </div>
    <script>
      var coll = document.getElementsByClassName("custom-input");
      var i;

      for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("input", function () {
          if (this.firstElementChild.value == "")
            this.classList.remove("active");
          else this.classList.add("active");
          //console.log(this.firstElementChild.value);
        });
      }
    </script>
  </body>
</html>
