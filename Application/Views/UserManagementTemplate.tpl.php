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

    public function getButtons()
    {
        ?>
        <div class="text-center py-2">
            <div class="btn-group-vertical py-2 mx-auto">
                <a href="index.php?page=create_article" class="btn btn-primary custom-btn-primary py-3">Napsat novou
                    recenzi</a>
                <a href="index.php?page=my_articles" class="btn btn-primary custom-btn-primary py-3">Moje příspěvky</a>
                <a href="index.php?page=my_reviews" class="btn btn-primary custom-btn-primary py-3">Moje recenze</a>
            </div>
        </div>
        <?php
    }

    public function getUserData()
    {
        ?>
        <div class="card mx-auto" style="width: 30rem">
            <div class="card-title">
                <h4 class="text-center">Osobní údaje</h4>
            </div>
            <div class="card-body">
                <p><span>email: </span>email</p>
                <p><span>jmeno </span>jmeno</p>
                <p><span>prijmeni </span>prijmeni</p>
                <p><span>role </span>role</p>
            </div>
        </div>
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
        $pageContent->getButtons();
        ?>
        <hr>
        <?php
        $pageContent->getUserData();
        ?>
    </div>
    </body>
<?php
$pageTpl->getEnd();