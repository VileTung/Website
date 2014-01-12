<?php

//Cookies verwijderen
afmeldCookie();

//Blok starten
startBlok("Afmelden");

print ("<br /><br />");
print ("<div align=\"center\">Je bent nu uitgelogd.<br />");
print ("Je kunt nu gebruik gaan maken van de site!</div>");
print ("<br /><br />");
print ("<script type=\"text/javascript\">window.setTimeout(function() {location.href = '" . opties("siteurl") . "';}, 500);</script>");

//Blok afsluiten
eindeBlok();

?>