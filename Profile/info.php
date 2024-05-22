<?php

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "3dprojectdb";
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_ProgettiUtente = "SELECT COUNT(p.ID) AS NumProgettiUtente
                        FROM progetti p
                        JOIN utenti u ON p.FK_ID_Utente = u.ID
                        WHERE u.email = '".$_SESSION["ses_mail"]."'";

$exe_ProgettiUtente = $conn->query($sql_ProgettiUtente);

    if ($exe_ProgettiUtente->num_rows > 0) 
    {
        $row = $exe_ProgettiUtente->fetch_assoc();
        $NumProgettiUtente = $row['NumProgettiUtente'];
    }
    else 
    {
        $NumProgettiUtente = 0;
    }

$sql_TipiProgetti = "SELECT t.Nome AS TipoNome, COUNT(p.ID) AS NumProgetti
                     FROM tipi t
                     LEFT JOIN progetti p ON t.ID = p.FK_ID_Tipo
                     LEFT JOIN utenti u ON p.FK_ID_Utente = u.ID
                     WHERE u.email = '".$_SESSION["ses_mail"]."'
                     GROUP BY t.Nome";

$exe_TipiProgetti = $conn->query($sql_TipiProgetti);

$progettiPerTipo = [];
    if ($exe_TipiProgetti->num_rows > 0) 
    {
        while ($row = $exe_TipiProgetti->fetch_assoc()) 
        {
            $progettiPerTipo[$row['TipoNome']] = $row['NumProgetti'];
            //echo $row["TipoNome"].":".$progettiPerTipo[$row['TipoNome']]."<br>";
        }
    } else {
        $progettiPerTipo = [];
    }

$sql_OreTotaliUtente = "SELECT SUM(p.ContatoreOre) AS OreTotaliUtente
                        FROM progetti p
                        JOIN utenti u ON p.FK_ID_Utente = u.ID
                        WHERE u.email = '".$_SESSION["ses_mail"]."'";

$exe_OreTotaliUtente = $conn->query($sql_OreTotaliUtente);

    if ($exe_OreTotaliUtente->num_rows > 0) 
    { 
        $row = $exe_OreTotaliUtente->fetch_assoc();
        $OreTotaliUtente = $row['OreTotaliUtente'];
    } else {
        $OreTotaliUtente = 0;
    }

//echo "Ore: ".$OreTotaliUtente."<br>";

$sql_OrePerTipo = "SELECT t.Nome AS TipoNome, SUM(p.ContatoreOre) AS OrePerTipo
                   FROM tipi t
                   LEFT JOIN progetti p ON t.ID = p.FK_ID_Tipo
                   LEFT JOIN utenti u ON p.FK_ID_Utente = u.ID
                   WHERE u.email = '".$_SESSION["ses_mail"]."'
                   GROUP BY t.Nome";

$exe_OrePerTipo = $conn->query($sql_OrePerTipo);

$orePerTipo = [];
if ($exe_OrePerTipo->num_rows > 0) 
{
    while ($row = $exe_OrePerTipo->fetch_assoc()) 
    {
        $orePerTipo[$row['TipoNome']] = $row['OrePerTipo'];
        //echo "Tempo per ".$row["TipoNome"].":".$orePerTipo[$row['TipoNome']]."<br>";
    }
} else {
    $orePerTipo = [];
}

$sql_ProgettiCompletati = "SELECT COUNT(p.ID) AS NumProgettiCompletati
                            FROM progetti p
                            JOIN utenti u ON p.FK_ID_Utente = u.ID
                            WHERE u.email = '".$_SESSION["ses_mail"]."'
                            AND p.Status = 'Completato'";

$exe_ProgettiCompletati = $conn->query($sql_ProgettiCompletati);

if ($exe_ProgettiCompletati->num_rows > 0) 
{
    $row = $exe_ProgettiCompletati->fetch_assoc();
    $NumProgettiCompletati = $row['NumProgettiCompletati'];
    //echo "Progetti completati: ".$NumProgettiCompletati."<br>";
} else {
    $NumProgettiCompletati = 0;
}

$sql_ProgettiScartati = "SELECT COUNT(p.ID) AS NumProgettiScartati
                            FROM progetti p
                            JOIN utenti u ON p.FK_ID_Utente = u.ID
                            WHERE u.email = '".$_SESSION["ses_mail"]."'
                            AND p.Status = 'Scartato'";

$exe_ProgettiScartati = $conn->query($sql_ProgettiScartati);

if ($exe_ProgettiScartati->num_rows > 0) 
{
    $row = $exe_ProgettiScartati->fetch_assoc();
    $NumProgettiScartati = $row['NumProgettiScartati'];
    //echo "Progetti scartati: ".$NumProgettiScartati."<br>";
} else {
    $NumProgettiScartati = 0;
}
$conn->close();
?>
