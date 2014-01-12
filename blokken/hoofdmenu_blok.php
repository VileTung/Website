<?php

/**
 * @author Kevin
 * @copyright 2013
 */

global $huidigeGebruiker;

print ("<table class=\"lista\" width=\"100%\">");
print ("<tr>");

if (!$huidigeGebruiker or $huidigeGebruiker["uId"] == 1)
{
    //Gast
    print ("<td class=\"header\" align=\"center\">Welkom!\n");
    print ("(<a href=\"" . opties("siteurl") . "/?pagina=aanmelden\">Aanmelden</a>)</td>");
} elseif ($huidigeGebruiker["uId"] != 1)
{
    print ("<td class=header align=center>Welkom terug " . $huidigeGebruiker["gebruikersnaam"] . "!\n");
    print ("(<a href=\"" . opties("siteurl") . "/?pagina=afmelden\">Afmelden</a>)</td>\n");
}

print ("<td class=header align=center><a href=\"" . opties("siteurl") . "\">Home</a></td>\n");

print ("</tr>");
print ("</table>");

?>