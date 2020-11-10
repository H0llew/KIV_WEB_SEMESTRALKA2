<?php

// zajisteni zakladni sablony webove stranky
require_once("settings.inc.php");
require_once("PageTemplate.class.php");
$pageTpl = new PageTemplate();

//predavana data z controlleru
global $tplData;
//pouzivana data
//$tplData["isLogged"];  // je prihlasen?
//$tplData["isAdmin"];  // je uzivatel admin?

// metody stranky
$pageContent = new class {

    /**
     * Vypise aktualni akce
     */
    public function getTerms()
    {
        ?>
        <h2 class="h3 custom-text-primary text-center py-5">Všechny akce (konference a školení) jsou zrušeny na
            neurčito. <br>
            Důvodem je probíhající krize spojená s covid-19. <br>
            Momentálně pro Vás připravujeme web konferenci (plánovaná začátkem prosince).</h2>
        <?php
    }
};

// webova stranka
$pageTpl->getHead("test");
?>
    <body>
    <?php
    // kontex stranky
    $pageTpl->getNavbar($tplData["isLogged"], $tplData["isAdmin"]);
    ?>
    <div class="container">
        <?php
        $pageContent->getTerms();
        ?>
    </div>
    </body>
<?php
$pageTpl->getEnd();


