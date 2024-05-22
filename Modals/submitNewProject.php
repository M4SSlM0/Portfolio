<?php
  $b64=explode(",",$_POST['hidden-input'])[1];
  $bin = base64_decode($b64);
  $size = getImageSizeFromString($bin);
  if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) 
    die('Base64 value is not a valid image');
  $ext = substr($size['mime'], 6);
  if (!in_array($ext, ['png', 'gif', 'jpeg'])) 
    die('Unsupported image type');

  $description;
  if(isset($_POST["modal-description"]) && $_POST["modal-description"]!="") $description=$_POST["modal-description"];

  $type;
  if(isset($_POST["modal-type"]) && $_POST["modal-type"]!="") $type=$_POST["modal-type"];

  
  $status;
  if(isset($_POST["modal-status"]) && $_POST["modal-status"]!="") $status=$_POST["modal-status"];
  
  $hours;
  if(isset($_POST["modal-hours"]) && $_POST["modal-hours"]!="0") $hours=$_POST["modal-hours"];
  
  $visibility;
  if(isset($_POST["modal-visibility"]) && $_POST["modal-visibility"]!="") $visibility=$_POST["modal-visibility"];

  $imgID = uniqid($prefix = "", $more_entropy = false);

  $img_file = "../Images/Projects/".$imgID.".{$ext}";

  file_put_contents($img_file, $bin);

  session_start();

  $dbHost = "localhost";
  $dbUser = "root";  $dbPass = "";
  $dbName = "3dprojectdb";
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  $userID = $_SESSION["userID"];
  $now = date("Y-m-d H:i:s");

  $stmt = $conn->prepare("INSERT INTO progetti(FK_ID_Utente, FK_ID_Tipo, Descrizione, Immagine, DataInizio, Status, DataFine, Visibilita, ContatoreOre) VALUES (?, ?, ?, ?, ?, ?,'null', ?, ?)");
  $stmt->bind_param("iissssii", $_SESSION["userID"], $type, $description, $img_file, $now, $status, $visibility, $hours);
  $stmt->execute();
?>