<?php

error_reporting(E_ALL);

//MySQL connect
$link = mysql_connect("localhost", "root", "");
mysql_select_db("pkm", $link);

//Anders krijgen we rare tekens
mysql_query("SET NAMES utf8");

//Standaardmap
$standaardMap = $_SERVER["DOCUMENT_ROOT"] . "/site";

//Belangrijke onderdelen
require_once (dirname(__file__) . "/blokken.php");

function saltMaken()
{
    $karakters = "!@#$%^&*()_+=/-abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($karakters), 0);
}

function aanmeldCookie($id, $wachtwoord)
{
    setcookie("doc", $id, 0x7fffffff, "/");
    setcookie("code", $wachtwoord, 0x7fffffff, "/");
}

function afmeldCookie()
{
    setcookie("doc", "", 0x7fffffff, "/");
    setcookie("code", "", 0x7fffffff, "/");
}

function opties($naam)
{
    $waarde = mysql_fetch_array(mysql_query("SELECT `waarde` FROM `opties` WHERE `naam` = '" . mysql_real_escape_string($naam) . "'"));

    return $waarde["waarde"];
}

function gebruiker()
{
    global $huidigeGebruiker;

    unset($GLOBALS["huidigeGebruiker"]);

    //Gast
    if (empty($_COOKIE["doc"]) or empty($_COOKIE["code"]))
    {
        $id = 1;
    }
    //Gebruiker
    elseif (isset($_COOKIE["doc"]) and isset($_COOKIE["code"]))
    {
        $id = $_COOKIE["doc"];
    }
    //Gast
    else
    {
        $id = 1;
    }

    //Uiteindelijke test, kijken of het echt een gebruiker is
    $gegevens = mysql_query("SELECT `id`, `zout`, `wachtwoord` FROM `gebruikers` WHERE `id` = '" . mysql_real_escape_string($id) . "'");

    //Gegevens uit Query halen
    $antwoord = mysql_fetch_array($gegevens);

    //Als deze vergelijking faalt, is het alsnog een gast!
    if (isset($_COOKIE["code"]))
    {
        if (mysql_num_rows($gegevens) < 1 or $antwoord["wachtwoord"] != $_COOKIE["code"])
        {
            $id = 1;
        }
        //Toch een gebruiker
        else
        {
            $id = $_COOKIE["doc"];
        }
        //Toch een gast
    } else
    {
        $id = 1;
    }

    $gebruiker = mysql_fetch_array(mysql_query("SELECT `gebruikers`.*, `gebruikers`.`id` AS `uId`, `gebruikersrang`.* FROM `gebruikers` INNER JOIN `gebruikersrang` ON `gebruikers`.`rangId`=`gebruikersrang`.`rangId` WHERE `gebruikers`.`id` = '" . mysql_real_escape_string($id) . "'"));

    $GLOBALS["huidigeGebruiker"] = $gebruiker;
    unset($gebruiker);
}

function standaardFooter($normalePagina = true)
{
    if ($normalePagina)
    {
        require_once ("./style/footer.php");
    }

    print ("</body>\n</html>\n");
}

function standaardHeader($titel, $normalePagina = true)
{
    global $huidigeGebruiker;

    // default settings for blocks/menu
    if (!isset($GLOBALS["charset"]))
    {
        $GLOBALS["charset"] = "utf8";
    }

    header("Content-Type: text/html; charset=utf8");

    if ($titel == "")
    {
        $titel = opties("sitenaam");
    } else
    {
        $titel = opties("sitenaam") . " - " . htmlspecialchars($titel);
    }

    print ("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
    print ("<html><head>");
    print ("<title>" . $titel . "</title>");

    // get user's style
    print ("<link rel=\"stylesheet\" href=\"./style/style.css\" type=\"text/css\" />");

    print ("</head>");
    print ("<body>");

    if ($normalePagina)
    {
        require_once ("./style/header.php");
    }
}

function startBlok($titel = "<em>Geen</em>")
{
    print ("<br /><table class=\"lista\" cellpadding=\"0\" cellspacing=\"0\" width=\"95%\" align=\"center\">");
    print ("<tr>");
    print ("<td class=\"block\" align=\"center\" height=\"20px\" colspan=\"1\"><strong>" . $titel . "</strong></td>");
    print ("</tr>");
    print ("<tr>");
    print ("<td width=\"100%\" align=\"justify\" valign=top>");
}

function eindeBlok($colspan = 1)
{
    print ("</td>");
    print ("</tr>");
    print ("<tr>");
    print ("<td class=\"block\" colspan=\"1\" align=\"center\" height=\"20px\"></td>");
    print ("</tr>");
    print ("</table>");
}


// ----------------------------------------------------------------
class ocr_captcha
{
    var $key; // ultra private static text
    var $long; // size of text
    var $lx; // width of picture
    var $ly; // height of picture
    var $nb_noise; // nb of background noisy characters
    var $filename; // file of captcha picture stored on disk
    var $imagetype = "png"; // can also be "png";
    var $public_key; // public key
    var $font_file = "./include/adlibn.ttf";
    function ocr_captcha($long = 6, $lx = 120, $ly = 30, $nb_noise = 25)
    {
        $this->key = md5("A nicely little text to stay private and use for generate private key");
        $this->long = $long;
        $this->lx = $lx;
        $this->ly = $ly;
        $this->nb_noise = $nb_noise;
        $this->public_key = substr(md5(uniqid(rand(), true)), 0, $this->long); // generate public key with entropy
    }

    function get_filename($public = "")
    {
        global $TORRENTSDIR;
        if ($public == "")
            $public = $this->public_key;
        return $TORRENTSDIR . "/" . $public . "." . $this->imagetype;
    }

    // generate the private text coming from the public text, using $this->key (not to be public!!), all you have to do is here to change the algorithm
    function generate_private($public = "")
    {
        if ($public == "")
            $public = $this->public_key;
        return substr(md5($this->key . $public), 16 - $this->long / 2, $this->long);
    }

    // check if the public text is link to the private text
    function check_captcha($public, $private)
    {
        // when check, destroy picture on disk
        if (file_exists($this->get_filename($public)))
            unlink($this->get_filename($public));
        return (strtolower($private) == strtolower($this->generate_private($public)));
    }

    // display a captcha picture with private text and return the public text
    function make_captcha($noise = true)
    {
        $private_key = $this->generate_private();
        $image = imagecreatetruecolor($this->lx, $this->ly);
        $back = ImageColorAllocate($image, intval(rand(224, 255)), intval(rand(224, 255)), intval(rand(224, 255)));
        ImageFilledRectangle($image, 0, 0, $this->lx, $this->ly, $back);
        if ($noise)
        { // rand characters in background with random position, angle, color
            for ($i = 0; $i < $this->nb_noise; $i++)
            {
                $size = intval(rand(6, 14));
                $angle = intval(rand(0, 360));
                $x = intval(rand(10, $this->lx - 10));
                $y = intval(rand(0, $this->ly - 5));
                $color = imagecolorallocate($image, intval(rand(160, 224)), intval(rand(160, 224)), intval(rand(160, 224)));
                $text = chr(intval(rand(45, 250)));
                ImageTTFText($image, $size, $angle, $x, $y, $color, $this->font_file, $text);
            }
        } else
        { // random grid color
            for ($i = 0; $i < $this->lx; $i += 10)
            {
                $color = imagecolorallocate($image, intval(rand(160, 224)), intval(rand(160, 224)), intval(rand(160, 224)));
                imageline($image, $i, 0, $i, $this->ly, $color);
            }
            for ($i = 0; $i < $this->ly; $i += 10)
            {
                $color = imagecolorallocate($image, intval(rand(160, 224)), intval(rand(160, 224)), intval(rand(160, 224)));
                imageline($image, 0, $i, $this->lx, $i, $color);
            }
        }
        // private text to read
        for ($i = 0, $x = 5; $i < $this->long; $i++)
        {
            $r = intval(rand(0, 128));
            $g = intval(rand(0, 128));
            $b = intval(rand(0, 128));
            $color = ImageColorAllocate($image, $r, $g, $b);
            $shadow = ImageColorAllocate($image, $r + 128, $g + 128, $b + 128);
            $size = intval(rand(12, 17));
            $angle = intval(rand(-30, 30));
            $text = strtoupper(substr($private_key, $i, 1));
            ImageTTFText($image, $size, $angle, $x + 2, 26, $shadow, $this->font_file, $text);
            ImageTTFText($image, $size, $angle, $x, 24, $color, $this->font_file, $text);
            $x += $size + 2;
        }
        if ($this->imagetype == "jpg")
            imagejpeg($image, $this->get_filename(), 100);
        else
            imagepng($image, $this->get_filename());
        ImageDestroy($image);
    }

    function display_captcha($noise = true)
    {
        $this->make_captcha($noise);
        $res = "<input type=hidden name='public_key' value='" . $this->public_key . "'>\n";
        $res .= "<img align=middle src='" . $this->get_filename() . "' border='0'>\n";
        return $res;
    }
}
// ----------------------------------------------------------------

// EOF


?>