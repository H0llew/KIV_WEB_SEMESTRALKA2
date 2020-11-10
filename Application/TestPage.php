<?php

// zajisteni zakladni sablony webove stranky
require_once("settings.inc.php");
require_once(DIR_VIEWS . "/PageTemplate.class.php");
$pageTpl = new PageTemplate();

// webova stranka
$pageTpl->getHead("test");
?>
    <body>
    <?php
    // kontex stranky
    $pageTpl->getNavbar(false, false);
    ?>
    </body>
<?php
$pageTpl->getEnd();


