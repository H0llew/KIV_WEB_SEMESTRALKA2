<?php

// zajisteni zakladni sablony webove stranky
require_once("settings.inc.php");
require_once("PageTemplate.class.php");
$pageTpl = new PageTemplate();

//predavana data z controlleru
global $tplData;
//pouzivana data
//$tplData["isLogged"];  // je prihlasen?
//$tplData["loginSuccessful"]; // prihlaseni uspesne?
//$tplData["isAdmin"];  // je uzivatel admin?
//$tplData["page"] // stranka

// metody stranky
$pageContent = new class {

    /**
     * Vrati info o prispevcik
     */
    public function getArticleInfo()
    {
        ?>
        <article>
            <div class="card">
                <div class="card-body">
                    <p>
                        Příspěvky týkající se financí a bankovnictví volně dostupné pro všechny.
                        Pokud chete publikovat příspěvěk je potřeba se zaregistrovat.
                    </p>
                </div>
            </div>
        </article>
        <?php
    }

    /**
     * Ukaze schvalene prispevky
     *
     * @param $articles
     */
    public function showArticles($articles)
    {
        ?>
        <article>
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Název</th>
                                <th>Autor</th>
                                <th>Datum vložení</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="custom-text-secondary">
                            <?php
                            $count = 0;
                            foreach ($articles as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row["nazev"] ?></td>
                                    <td><?php echo $row["prijmeni"] . " " . $row["jmeno"] ?></td>
                                    <td><?php echo $row["datum"] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleRevsA<?php echo $count ?>">
                                            Hodnocení
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleA<?php echo $count ?>">
                                            Podrobnosti
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal" id="textArticleA<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Příspěvek</h4>
                                            </div>
                                            <div class="modal-body">
                                                <!-- forma -->
                                                <div>
                                                    <p><span>Název: </span><?php echo $row["nazev"] ?></p>
                                                </div>
                                                <div class="form-text custom-text-secondary">
                                                    <p>
                                                        <span>Autor: </span><?php echo $row["prijmeni"] . " " . $row["jmeno"] ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <p><span>Datum nahrání: </span><?php echo $row["datum"] ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <p><span>Abstrakt: </span><?php echo $row["abstrakt"] ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" id="ffilePathWaiting" name="ffilePath"
                                                           value="<?php echo $row["soubor"] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <?php $row["soubor"] = str_replace("C:/xampp/htdocs", "", $row["soubor"]) ?>
                                                    <a href="<?php echo $row["soubor"] ?>"
                                                       download="<?php echo $row["nazev"] ?>"> Stáhnout pdf
                                                        článek</a>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger custom-btn-secondary"
                                                        data-dismiss="modal">
                                                    Zavřít
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal" id="textArticleRevsA<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span>Hodnocení: <?php echo $row["hodnoceni"][0]["prijmeni"] . $row["hodnoceni"][0]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                                <span>Téma: <?php echo $row["hodnoceni"][0]["hodnoceni1"] ?></span><br>
                                                                <span>Technická kvalita: <?php echo $row["hodnoceni"][0]["hodnoceni2"] ?></span><br>
                                                                <span>Jazyková kvalita: <?php echo $row["hodnoceni"][0]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p> Poznámka: <?php echo $row["hodnoceni"][0]["zprava"] ?></p>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span>Hodnocení: <?php echo $row["hodnoceni"][1]["prijmeni"] . $row["hodnoceni"][1]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                                <span>Téma: <?php echo $row["hodnoceni"][1]["hodnoceni1"] ?></span><br>
                                                                <span>Technická kvalita: <?php echo $row["hodnoceni"][1]["hodnoceni2"] ?></span><br>
                                                                <span>Jazyková kvalita: <?php echo $row["hodnoceni"][1]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p> Poznámka: <?php echo $row["hodnoceni"][1]["zprava"] ?></p>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span>Hodnocení: <?php echo $row["hodnoceni"][2]["prijmeni"] . $row["hodnoceni"][2]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                                <span>Téma: <?php echo $row["hodnoceni"][2]["hodnoceni1"] ?></span><br>
                                                                <span>Technická kvalita: <?php echo $row["hodnoceni"][2]["hodnoceni2"] ?></span><br>
                                                                <span>Jazyková kvalita: <?php echo $row["hodnoceni"][2]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p> Poznámka: <?php echo $row["hodnoceni"][2]["zprava"] ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger custom-btn-secondary"
                                                        data-dismiss="modal">
                                                    Zavřít
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $count++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </article>
        <?php
    }

};

// webova stranka
$pageTpl->getHead($tplData["title"]);
?>
    <body class="d-flex flex-column min-vh-100">
    <?php
    // kontex stranky
    $pageTpl->getSpecialEvent();
    $pageTpl->getNavbar($tplData["isLogged"], $tplData["isAdmin"]);
    ?>
    <div class="container">
        <?php
        $pageContent->getArticleInfo();
        $pageContent->showArticles($tplData["articles"]);
        ?>
    </div>
    <?php
    $pageTpl->getFooter();
    ?>
    </body>
<?php
$pageTpl->getEnd();


