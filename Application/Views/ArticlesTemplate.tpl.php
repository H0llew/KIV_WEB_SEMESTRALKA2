<?php

// data
global $tplData;

require_once("PageTemplate.class.php");

$tmp = new PageTemplate();
// zacatek stranky
$tmp->getTop($tplData["title"]);
$tmp->getNavbar(isset($tplData["isLogged"]) ? $tplData["isLogged"] : false);
?>
<?php
if (isset($tplData["isLogged"]) && $tplData["isLogged"]) {
    ?>
    <a href="index.php?page=user_management">napsat nový článek</a>
    <a href="index.php?page=user_management">moje články</a>
    <?php
}
?>
    TEST-CLANKY
<?php

$tmp->getBottom();
