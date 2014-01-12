<?php

error_reporting(E_ALL);

require_once ("functies/functies.php");

global $huidigeGebruiker;

//Heeft te maken met Cookies
//en variabelen
gebruiker();

//Voor pagina toegang
define("TOEGANG", "true");

$query = mysql_query("SELECT * FROM `pagina` WHERE `pagina` = '" . (isset($_GET["pagina"]) ? $_GET["pagina"] : "") . "'");
$pagina = mysql_fetch_array($query);

if (file_exists($standaardMap . $pagina["locatie"]) && isset($_GET["pagina"]) && $_GET["pagina"] != "" && $pagina["locatie"] != "")
{
    //Pagina starten
    standaardHeader($pagina["titel"]);

    //Inhoud weergeven
    require_once ($standaardMap . $pagina["locatie"]);

    //Pagina afsluiten
    standaardFooter();
}
//404 - Niet gevonden!
elseif (!mysql_num_rows($query) > 0 && isset($_GET["pagina"]))
{
    require_once ($standaardMap . "/404.php");
}
//Standaard pagina
else
{
    require_once ($standaardMap . "/hoofdpagina.php");
}

?>