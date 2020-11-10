<?php

// data
global $tplData;

require_once("PageTemplateOLD.class.php");

$tmp = new PageTemplateOLD();
// zacatek stranky
$tmp->getTop("test");
$tmp->getNavbar(isset($tplData["isLogged"]) ? $tplData["isLogged"] : false);
?>
    TEST-INDEX
<?php

$tmp->getBottom();
