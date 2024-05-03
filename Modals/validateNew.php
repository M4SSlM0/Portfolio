<?php

    //https://base64.guru/developers/php/examples/decode-image
    $b64=explode(",",$_POST['image'])[1];
    $bin = base64_decode($b64);
    $size = getImageSizeFromString($bin);
    if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) 
        die('Base64 value is not a valid image');
    $ext = substr($size['mime'], 6);
    if (!in_array($ext, ['png', 'gif', 'jpeg'])) 
        die('Unsupported image type');
    //----------------------------------immagine--------------------------------------------

    $description;
    if(isset($_POST["modal-new-description"]) && $_POST["modal-new-description"]!="") $description=$_POST["modal-new-description"];

    //----------------------------------descrizione--------------------------------------------

    $type;
    if(isset($_POST["modal-new-type"]) && $_POST["modal-new-type"]!="") $type=$_POST["modal-new-type"];

    //----------------------------------tipo--------------------------------------------

    $status;
    if(isset($_POST["modal-new-status"]) && $_POST["modal-new-status"]!="") $status=$_POST["modal-new-status"];

    //----------------------------------stato--------------------------------------------

    $hours;
    if(isset($_POST["modal-new-hours"]) && $_POST["modal-new-hours"]!="0") $hours=$_POST["modal-new-hours"];

    //----------------------------------stato--------------------------------------------

    $visibility;
    if(isset($_POST["modal-new-visibility"]) && $_POST["modal-new-visibility"]!="") $visibility=$_POST["modal-new-visibility"];

    //----------------------------------stato--------------------------------------------

    $imgID = uniqid($prefix = "", $more_entropy = false);

    $img_file = "../Images/Projects/".$imgID.".{$ext}";

    file_put_contents($img_file, $bin);

    session_start();

    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "3dprojectdb";
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    $userID = $_SESSION["userID"];
    $now = date("Y-m-d H:i:s");

    $conn->query("INSERT INTO progetti(FK_ID_Utente, FK_ID_Tipo, Descrizione, Immagine, DataInizio, Status, DataFine, Visibilita, ContatoreOre) VALUES ('1','".$type."','".$description."','".$img_file."','".$now."','".$status."','null','".$visibility."','".$hours."')");

?>
