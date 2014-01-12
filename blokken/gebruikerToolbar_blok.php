<?php

/**
 * @author Kevin
 * @copyright 2013
 */

global $huidigeGebruiker;

if (isset($huidigeGebruiker) && $huidigeGebruiker && $huidigeGebruiker["uId"] > 1)
{

    print ("<table class=\"lista\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">");
    print ("<tr>");
    print ("<td class=\"lista\" align=\"center\">Rang: " . $huidigeGebruiker["rang"] . "</td>\n");

    if ($huidigeGebruiker["adminToegang"] == "ja")
    {
        print ("\n<td align=\"center\" class=\"lista\"><a href=\"" . opties("siteurl") . "/?pagina=admin\">Admin CP</a></td>\n");
    }

    print ("<td class=lista align=center><a href=\"" . opties("siteurl") . "/?pagina=gebruiker\">Account</a></td>\n");

    print ("</tr>");
    print ("</table>");
} else
{
    print ("<form action=\"" . opties("siteurl") . "/?pagina=aanmelden\" name=\"login\" method=\"post\">");
    print ("<table class=\"lista\" border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">");
    print ("<tr>");
    print ("<td class=\"lista\" align=\"left\">");
    print ("<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">");
    print ("<tr>");
    print ("<td align=\"right\" class=\"lista\">Gebruikersnaam:</td>");
    print ("<td class=\"lista\"><input type=\"text\" size=\"15\" name=\"gebruiker\" value=\"" . (isset($_POST["gebruiker"]) ? $_POST["gebruiker"] : "") . "\" maxlength=\"40\" style=\"font-size:10px\" /></td>");
    print ("<td align=\"right\" class=\"lista\">Wachtwoord:</td>");
    print ("<td class=\"lista\"><input type=\"password\" size=\"15\" name=\"wachtwoord\" value=\"" . (isset($_POST["wachtwoord"]) ? $_POST["wachtwoord"] : "") . "\" maxlength=\"40\" style=\"font-size:10px\" /></td>");
    print ("<td class=\"lista\" align=\"center\"><input type=\"submit\" name=\"actie\" value=\"Aanmelden\" style=\"font-size:10px\" /></td>");
    print ("</tr>");
    print ("</table>");
    print ("</td>");
    print ("<td class=\"lista\" align=\"center\"><a href=\"#\">Registreren</a></td>");
    print ("<td class=\"lista\" align=\"center\"><a href=\"#\">Wachtwoord opvragen</a></td>");
    print ("</tr>");
    print ("</table>");
    print ("</form>");
}

?>