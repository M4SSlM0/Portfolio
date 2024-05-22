<?php

    session_start();

    //var_dump($_POST);
    $type = "default"; 
    $description = "";

    if(!isset($_POST["load-page"])){
        if(isset($_POST["type"])) $type = $_POST["type"];
        if(isset($_POST["description"])) $description = trim($_POST["description"]);
        if(isset($_POST["username"])) $username = trim($_POST["username"]);
        $_SESSION["search-last-search"] = array("type" => $type, "description" => "%".$description."%", "username" => "%".$username."%");
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

    if(isset($_SESSION["search-last-search"])){
        $type = $_SESSION["search-last-search"]["type"];
        $description = $_SESSION["search-last-search"]["description"];
        $username = $_SESSION["search-last-search"]["username"];
        //var_dump($_SESSION["search-last-search"]);

        $query = "SELECT * FROM progetti JOIN utenti ON progetti.FK_ID_Utente = utenti.ID WHERE Visibilita = 0 AND FK_ID_Utente != '".$_SESSION["userId"]."'";
        $placeholders = "";
        $values = array();

        if($type != "default"){
            $query .= " AND FK_ID_tipo = ?";
            $placeholders .= "i";
            array_push($values, $type);
        }
        if($description != "%%"){ 
            $query .= " AND Descrizione LIKE ?";
            $placeholders .= "s";
            array_push($values, $description);
        }
        if($username != "%%"){
            $query .= " AND utenti.NomeUtente LIKE ?";
            $placeholders .= "s";
            array_push($values, $username);
        }

        //var_dump($query);

        $stmt = $conn->prepare($query);

        //array_unshift($values, $placeholders);
        //var_dump($values);
        //var_dump(count($values));
        $temp = $values;

        if(count($values) > 0){
            $stmt->bind_param($placeholders, ...$values);
           /* call_user_func_array(
                array($stmt, "bind_param"),
                $temp
            );*/
            //var_dump("debug");
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
                <button class="button project" style="background-image: url(<?= $rows[$i][4] ?>)" hx-post="../Modals/showProject.php" hx-trigger="click" hx-target="#modal" hx-swap="innerHTML" hx-include="#project-id-<?= $rows[$i][0] ?>"><?= $rows[$i][3] ?>
                    <input type="hidden" id="project-id-<?= $rows[$i][0] ?>" name="project-id" value="<?= $rows[$i][0] ?>" />
                </button>

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
<script>
    var projects = document.getElementsByClassName("project");
    console.log(projects);
    for (let i = 0; i < projects.length; i++) {
        showModal(projects[i], "click");
    }
</script>