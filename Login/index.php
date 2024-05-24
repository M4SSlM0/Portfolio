<html>
  <head>
    <link rel="stylesheet" href="../Themes/theme.css" />
    <link rel="stylesheet" href="./login.css" />
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

      $isValid=false;
      $errorMessage="";
      $stmt;
      $result;

      session_start();

      if(isset($_SESSION["userID"])) header("location: ../Home");
    ?>
    <div class="row fill">
      <div class="fillY column background">
        <div class="center fillX">
          <div class="center gradient logo">Placeholder logo</div>
        </div>
        <div class="center fillX textHuge title">Login</div>
        <form class="column fillX center login-form" action="" method="post">
          <?php 
            if(!isset($_POST['email'])) $errorMessage="";
            else if($_POST["email"]=="") $errorMessage="*Email cannot be empty";
            else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errorMessage="*Invalid email";
            else {
              $stmt=$conn->prepare("SELECT * FROM utenti WHERE Email = ?");
              $stmt->bind_param("s",$_POST['email']);
              $stmt->execute();
              if(mysqli_num_rows($result=$stmt->get_result())==0) $errorMessage="*Email not registrated";
              else if($result["IsBan"] == 1) $errorMessage="]This account has been banned";
              else 
              {
                $isValid=true; 
              }
            }
          ?>
          <div class="column custom-input <?= $errorMessage==""?"":"error" ?> <?= isset($_POST["email"])?"active":"" ?>">
            <input class="text" name="email" type="email" maxlength="64" value="<?php if(isset($_POST["email"])) echo $_POST["email"] ?>" required/>
            <label class="center">Email:</label>
            <div><?= $errorMessage ?></div>
          </div>
          <?php
              $errorMessage="";
              if(!isset($_POST["password"])) $errorMessage="";
              else if($_POST["password"]=="") $errorMessage="*Password cannot be empty";
              else if($isValid){
                if((($row = $result->fetch_assoc())['Password'] != md5($_POST['password']))) $errorMessage="*Wrong email or password";
                else{
                  $_SESSION["ses_mail"] = $_POST['email'];
                  $_SESSION["userID"]=$row["ID"];
                  //var_dump($row["ID"]);
                  header("Location: ../Home");
                  exit;
                }
              } 
            ?>
          <div class="column custom-input <?= $errorMessage==""?"":"error" ?>">
            <input class="password" name="password" type="password" maxlength="32" required/>
            <label class="center">Password:</label>
            <div><?= $errorMessage ?></div>
          </div>
          <div class="fillX center">
            <button class="center gradient text button login-submit">
              LOG IN
            </button>
          </div>
        </form>
        <div class="fillX center">
          <a class="textSmall link" href="../Signin">New user?</a>
        </div>
        <div class="fillX center texttiny copyright">
          Copyright &copy 2024. Do tf you want with it.
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
