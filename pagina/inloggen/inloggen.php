<?php

/**
 * @author Kevin
 * @copyright 2013
 */

if (!defined("TOEGANG"))
{
    print ("Pagina kon niet worden gevonden!");
    die();
}

//Blok starten
startBlok("Aanmelden");

//Als we al zijn ingelogd, hebben we hier niets te zoeken
if ($huidigeGebruiker["uId"] != 1)
{
    print ("<br /><br />");
    print ("<div align=\"center\">Je bent al ingelogd, dus hier hebben we niets te zoeken!");
    print ("<br /><br />");
    print ("<script type=\"text/javascript\">window.setTimeout(function() {location.href = '" . opties("siteurl") . "';}, 1);</script>");
}
//We gaan inloggen
elseif (isset($_POST["actie"]) and $_POST["actie"] == "Aanmelden")
{
    //Niet alle gegevens zijn ingevuld
    if ($_POST["gebruiker"] == "" or $_POST["wachtwoord"] == "")
    {
        print ("<br /><br /><div align=\"center\"><font size=\"2\" color=\"#FF0000\">Niet alle gegevens zijn ingevuld!</font></div>");

        //Inlog velden
        aanmelden();
    } else
    {
        $gegevens = mysql_query("SELECT `id`, `zout`, `wachtwoord` FROM `gebruikers` WHERE `gebruikersnaam` = '" . mysql_real_escape_string($_POST["gebruiker"]) . "'");

        //Gegevens uit Query halen
        $antwoord = mysql_fetch_array($gegevens);

        if (mysql_num_rows($gegevens) == 1 && hash("sha512", $antwoord["zout"] . hash("sha512", $_POST["wachtwoord"])) == $antwoord["wachtwoord"])
        {
            //Gegevens kloppen
            aanmeldCookie($antwoord["id"], hash("sha512", $antwoord["zout"] . hash("sha512", $_POST["wachtwoord"])));

            print ("<br /><br />");
            print ("<div align=\"center\">Je wordt over enkele seconde doorgestuurd!<br />");
            print ("Als je browser geen JavaScript ondersteund, klik dan <a href=\"" . opties("siteurl") . "\">hier</a>!");
            print ("<br /><br />");
            print ("<script type=\"text/javascript\">window.setTimeout(function() {location.href = '" . opties("siteurl") . "';}, 1);</script>");
        } else
        {
            //Ongeldige gegevens
            print ("<br /><br /><div align=\"center\"><font size=\"2\" color=\"#FF0000\">De gegevens komen niet overeen!</font></div>");

            //Inlog velden
            aanmelden();
        }

    }

} else
{
    //Inlog velden
    aanmelden();
}

//Blok afsluiten
eindeBlok();

function aanmelden()
{
    print ("<br />");
    print ("<form method=\"post\" action=\"?pagina=aanmelden\">");
    print ("<table align=\"center\" class=\"lista\" border=\"0\" cellpadding=\"10\">");
    print ("<tr><td align=\"right\" class=\"header\">Gebruikersnaam:</td><td class=\"lista\"><input type=\"text\" size=\"40\" name=\"gebruiker\" value=\"" . (isset($_POST["gebruiker"]) ? $_POST["gebruiker"] : "") . "\" maxlength=\"40\" /></td></tr>");
    print ("<tr><td align=\"right\" class=\"header\">Wachtwoord:</td><td class=\"lista\"><input type=\"password\" size=\"40\" name=\"wachtwoord\" value=\"" . (isset($_POST["wachtwoord"]) ? $_POST["wachtwoord"] : "") . "\" maxlength=\"40\" /></td></tr>");
    print ("<tr><td colspan=\"2\" class=\"header\" align=\"center\"><input type=\"submit\" name=\"actie\" value=\"Aanmelden\" /></td></tr>");
    print ("<tr><td colspan=\"2\" class=\"header\" align=\"center\">Cookies moeten aanstaan om voobij dit punt te komen!</td></tr>");
    print ("</table>");
    print ("</form>");
    print ("<p align=\"center\">");
    print ("<a href=\"#\">Registreren</a>&nbsp;&nbsp;&nbsp;<a href=\"#\">Wachtwoord opvragen</a>");
    print ("</p>");
}

/*$salttest = saltMaken();

echo $salttest."<--<br />";
echo hash("sha512", $salttest . hash("sha512", "D1b3a4d7F1"));*/

?>