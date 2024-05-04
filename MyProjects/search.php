<?php

    session_start();

    //var_dump($_POST);
    $type = "default"; 
    $description = "";

    if(!isset($_POST["load-page"])){
        if(isset($_POST["search-type"])) $type = $_POST["search-type"];
        if(isset($_POST["search-description"])) $description = trim($_POST["search-description"]);
        $_SESSION["myProjects-last-search"] = array("type" => $type, "description" => "%".$description."%");
    }

    //var_dump($type);
    //var_dump($description);

    //var_dump($_SESSION["myProjects-last-search"]);

    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "3dprojectdb";
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    $userID = $_SESSION["userID"];

    //var_dump($_SESSION);

    $result;

    if(isset($_SESSION["myProjects-last-search"])){
        $type = $_SESSION["myProjects-last-search"]["type"];
        $description = $_SESSION["myProjects-last-search"]["description"];
        //var_dump($_SESSION["myProjects-last-search"]);

        if($type != "default" && $description != ""){
            $stmt = $conn->prepare("SELECT * FROM progetti WHERE FK_ID_Utente = ? AND FK_ID_Tipo = ? AND Descrizione LIKE ?");
            $stmt->bind_param("iis", $userID, $type, $description);
        }
        else if($type != "default" && $description == ""){
            $stmt = $conn->prepare("SELECT * FROM progetti WHERE FK_ID_Utente = ? AND FK_ID_Tipo = ?");
            $stmt->bind_param("ii", $userID, $type);
        }
        else if($type == "default" && $description != ""){
            $stmt = $conn->prepare("SELECT * FROM progetti WHERE FK_ID_Utente = ? AND Descrizione LIKE ?");
            $stmt->bind_param("is", $userID, $description);   
        }
        else if($type == "default" && $description == ""){
            $stmt = $conn->prepare("SELECT * FROM progetti");
            $stmt->bind_param();   
        }
        $stmt->execute();
        $result=$stmt->get_result();
    }
    else{
        $result = $conn->query("SELECT * FROM progetti");
    }

    $currentPage = 1;
    $resultsPerPage = 10;
    $rowCounter = mysqli_num_rows($result);

    //var_dump($rowCounter);

    if(isset($_POST["load-page"]) && $_POST["load-page"] > 0 && $_POST["load-page"] <= $rowCounter) $currentPage = $_POST["load-page"];

    if($rowCounter > 0){

        $rows = $result->fetch_all();
        //var_dump($rowCounter);
        //var_dump($rows);
        //var_dump(($currentPage - 1) * $resultsPerPage);

        for ($i = ($currentPage - 1) * $resultsPerPage; $i < $resultsPerPage * $currentPage; $i++) { 
            //var_dump($i);
            if(!isset($rows[$i])) break;
            ?>
                <div class="project-placeholder" style="background-image: url(<?= $rows[$i][4] ?>)"><?= $rows[$i][3] ?></div>
            <?php
        }
        //var_dump($rowCounter);

        if($rowCounter > $resultsPerPage){?>
            <div class="myProjects-background-footer">
                <button name="load-page" value="<?= $currentPage-1 ?>" hx-post="../MyProjects/search.php" hx-trigger="click" hx-target="#results" hx-swap="innerHTML" hx-include="#current-search-page" <?= $currentPage==1 ? "disabled" : "" ?>><</button>
                <input type="hidden" name="current-search-page" value="<?= $currentPage ?>" id="#current-search-page">
                <?= $currentPage ?> out of <?= ceil($rowCounter / $resultsPerPage) ?>
                <button name="load-page" value="<?= $currentPage+1 ?>" hx-post="../MyProjects/search.php" hx-trigger="click" hx-target="#results" hx-swap="innerHTML" hx-include="#current-search-page" <?= $currentPage==$rowCounter ? "disabled" : "" ?>>></button>
            </div>
        <?php
        //-----------------------------fix HTML and css------------------------------------------------
        }
    }
    else{?>
        nessun risultato f
    <?php
    }

?>