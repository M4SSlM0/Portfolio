<?php
  session_start();

  $dbHost = "localhost";
  $dbUser = "root";
  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ses_mail = $_SESSION["ses_mail"];
      

    // USERNAME
    if (isset($_POST["Edit"]) && $_POST["Edit"] == "Username" && isset($_POST["NewUsername"])) {
      $newUsername = $_POST["NewUsername"];
      $query = "UPDATE utenti SET NomeUtente = ? WHERE Email = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ss", $newUsername, $ses_mail);
      $stmt->execute();
      
      $_SESSION["ses_user"]=$newUsername;
    }
    else ;

    // PROFILO (Non funziona)((ora si ma non chiedermi come))
    if (isset($_POST["Edit"]) && $_POST["Edit"] == "ProfilePicture" && isset($_FILES["ProfilePicture"])) {
      //var_dump($_FILES);
      $target_dir = "../Images/ProfilePictures/"; // Percorso della cartella sul server
      $profile_picture_name = uniqid() . '_' . basename($_FILES["ProfilePicture"]["name"]); // Nome univoco per il file
      $target_file = $target_dir . $profile_picture_name;
      if (move_uploaded_file($_FILES["ProfilePicture"]["tmp_name"], $target_file)) {
        $profile_picture_path = $target_dir . $profile_picture_name; // Salva il percorso dell'immagine
        $query = "UPDATE utenti SET ImmagineProfilo = ? WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $profile_picture_path, $ses_mail);
        $stmt->execute();
      } else {
        echo "Si è verificato un errore durante il caricamento dell'immagine.";
      }
    //??
    }

    
    // PASSWORD
    if (isset($_POST["Edit"]) && $_POST["Edit"] == "Password" && isset($_POST["NewPassword"])) {
      $newPassword = $_POST["NewPassword"];
      if (strlen($newPassword) >= 8) {
        $newPasswordMD5 = md5($newPassword); // Cripta la nuova password con MD5
        $query = "UPDATE utenti SET Password = ? WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $newPasswordMD5, $ses_mail);
        $stmt->execute();
      } else {
        echo "La password deve essere lunga almeno 8 caratteri.";
      }
    }
  }
?>