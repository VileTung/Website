<?php

global $huidigeGebruiker;

startBlok("Menu");

print ("<tr><td class=blocklist align=center><a href=./>Home</a></td></tr>\n");
print ("<tr><td class=blocklist align=center><a href=#>Link1</a></td></tr>\n");
print ("<tr><td class=blocklist align=center><a href=#>Link2</a></td></tr>\n");

if ($huidigeGebruiker["uId"] == 1 || !$huidigeGebruiker)
{
    print ("<tr><td class=blocklist align=center><a href=\"" . opties("siteurl") . "/?pagina=aanmelden\">Inloggen</a></td></tr>\n");
} else
{
    print ("<tr><td class=blocklist align=center><a href=\"" . opties("siteurl") . "/?pagina=afmelden\">Afmelden</a></td></tr>\n");
}

eindeBlok();

?>
