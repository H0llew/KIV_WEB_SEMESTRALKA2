<?php

// data
global $tplData;

require_once("PageTemplate.class.php");

$tmp = new PageTemplate();
// zacatek stranky
$tmp->getTop("test");
$tmp->getNavbar(isset($tplData["isLogged"]) ? $tplData["isLogged"] : false);
?>
    TEST-INDEX
<?php

$tmp->getBottom();
