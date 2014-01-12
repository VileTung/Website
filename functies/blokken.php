<?php

/**
 * @author Kevin
 * @copyright 2013
 */

function hoofdMenu()
{
    $query = mysql_query("SELECT * FROM `blokken` WHERE `positie` = 't' AND `status` = 'ja' ORDER BY `positieId`");
    $i = 0;
    $blokken = array();

    while ($fetch = mysql_fetch_array($query))
    {
        $blokken[$i++] = $fetch["blok"];
    }

    foreach ($blokken as $entry)
    {
        include ("blokken/" . $entry . "_blok.php");
    }
}

function middenMenu()
{
    $query = mysql_query("SELECT * FROM `blokken` WHERE `positie` = 'm' AND `status` = 'ja' ORDER BY `positieId`");
    $i = 0;
    $blokken = array();

    while ($fetch = mysql_fetch_array($query))
    {
        $blokken[$i++] = $fetch["blok"];
    }

    foreach ($blokken as $entry)
    {
        include ("blokken/" . $entry . "_blok.php");
    }
}


function zijMenu($positie)
{
    $query = mysql_query("SELECT * FROM `blokken` WHERE `positie` = '" . $positie . "' AND `status` = 'ja' ORDER BY `positieId`");
    $i = 0;
    $blokken = array();

    while ($fetch = mysql_fetch_array($query))
    {
        $blokken[$i++] = $fetch["blok"];
    }

    if (count($blokken) > 0)
    {
        print ("<td width=\"200\" valign=top>");

        foreach ($blokken as $entry)
        {
            include ("blokken/" . $entry . "_blok.php");
        }

        print ("</td>");
    }
}

?>