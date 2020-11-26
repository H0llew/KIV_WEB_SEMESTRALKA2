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
//$tplData["notVerifiedArticles"] // clanky cekajici na schvaleni
//$tplData["isReviewer"] // je uzivatel recenzent?

// metody stranky
$pageContent = new class {

    /**
     * Vrati prispevky ocekavajici recenzy
     *
     * @param $articles
     */
    public function getRequestedReviews($articles)
    {
        ?>
        <!-- clanky cekajici na schvaleni -->
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Příspěvky čekající na recenzi</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky které Vám byli přiřazeny pro
                zrecenzování.
            </div>
            <div class="card-body">
                <?php
                if (!empty($articles)) {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr class="custom-text-primary">
                                <th>Název</th>
                                <th>Autor</th>
                                <th>Datum</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="custom-text-secondary">
                            <?php
                            $count = 0;
                            foreach ($articles as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row["article"]["nazev"] ?></td>
                                    <td><?php echo $row["article"]["prijmeni"] . " " . $row["article"]["jmeno"] ?></td>
                                    <td><?php echo $row["article"]["datum"] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleA<?php echo $count ?>">
                                            Podrobnosti
                                        </button>
                                        <?php
                                        if (!$row["first"]) {
                                            ?>
                                            <button type="button" class="btn btn-primary custom-btn-secondary"
                                                    data-toggle="modal"
                                                    data-target="#textArticleRevMY<?php echo $count ?>">
                                                Moje hodnocení
                                            </button>
                                            <?php
                                        }
                                        ?>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleRevA<?php echo $count ?>">
                                            <?php
                                            $res = "";
                                            $row["first"] ? $res = "Zrecenzovat" : $res = "Změnit recenzi";
                                            echo $res;
                                            ?>
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
                                                    <p>
                                                        <span class="custom-text-secondary">Název: </span><?php echo $row["article"]["nazev"] ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <div>
                                                        <p>
                                                            <span class="custom-text-secondary">Autor:</span> <?php echo $row["article"]["prijmeni"] . " " . $row["article"]["jmeno"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p>
                                                        <span class="custom-text-secondary">Datum nahrání: </span><?php echo $row["article"]["datum"] ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <p>
                                                        <span class="custom-text-secondary">Abstrakt:<br></span><?php echo $row["article"]["abstrakt"] ?>
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" id="ffilePathWaiting" name="ffilePath"
                                                           value="<?php echo $row["article"]["soubor"] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <?php $row["article"]["soubor"] = str_replace("C:/xampp/htdocs", "", $row["article"]["soubor"]) ?>
                                                    <a href="<?php echo $row["article"]["soubor"] ?>"
                                                       download="<?php echo $row["article"]["nazev"] ?>"> Stáhnout pdf
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
                                <div class="modal" id="textArticleRevA<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                            </div>
                                            <div class="modal-body">
                                                <!-- forma -->
                                                <form action="" method="POST">
                                                    <input type="hidden" name="id"
                                                           value="<?php echo $row["id_recenze"] ?>">
                                                    <div>
                                                        <label for="fh1-<?php echo $count ?>"
                                                               class="custom-text-secondary">Téma:</label>
                                                        <select name="fh1" id="fh1-<?php echo $count ?>">
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fh2-<?php echo $count ?>"
                                                               class="custom-text-secondary">Téchnická kvalita:</label>
                                                        <select name="fh2" id="fh2-<?php echo $count ?>">
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fh3-<?php echo $count ?>"
                                                               class="custom-text-secondary">Jazyková kvalita:</label>
                                                        <select name="fh3" id="fh3-<?php echo $count ?>">
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fabstract"
                                                               class="custom-text-secondary">Zpráva</label>
                                                        <textarea id="fabstract" name="fabstract" class="form-control"
                                                                  rows="10"
                                                                  cols="50" required></textarea>
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-primary custom-btn-primary"
                                                                name="action" value="review">
                                                            Odeslat recenzi
                                                        </button>
                                                    </div>
                                                </form>
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
                                <div class="modal" id="textArticleRevMY<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Moje hodnocení</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <p>
                                                        <span class="custom-text-secondary">Téma: </span><?php echo $row["hodnoceni1"] . "/5"?>
                                                        </p>
                                                    <p>
                                                        <span class="custom-text-secondary">Technická kvalita: </span><?php echo $row["hodnoceni2"] . "/5" ?>
                                                        </p>
                                                    <p>
                                                        <span class="custom-text-secondary">Jazyková kvalita: </span><?php echo $row["hodnoceni3"] . "/5" ?>
                                                        </p>
                                                    <p>
                                                        <span class="custom-text-secondary">Zpráva:<br></span><?php echo $row["zprava"] ?>
                                                    </p>
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
                    <?php
                } else {
                    ?>
                    <div><p class="text-center">Nemáte přiřazeny žádné příspěvky pro recenzi.</p></div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Vrati moje recenze
     *
     * @param $articles
     */
    public function getMyReviews($articles)
    {
        ?>
        <!-- schvalene clanky -->
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Vaše zrecenzované příspěvky</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Vaše zrecenzované příspěvky, které byli
                schváleny/zamítnuty.
            </div>
            <div class="card-body">
                <?php
                if (!empty($articles)) {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr class="custom-text-primary">
                                <th>Název</th>
                                <th>Autor</th>
                                <th>Datum vložení</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="custom-text-secondary">
                            <?php
                            $count = 0;
                            foreach ($articles as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row["article"]["nazev"] ?></td>
                                    <td><?php echo $row["article"]["prijmeni"] . " " . $row["article"]["jmeno"] ?></td>
                                    <td><?php echo $row["article"]["datum"] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleRevsMY<?php echo $count ?>">
                                            Hodnocení
                                        </button>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleMY<?php echo $count ?>">
                                            Podrobnosti
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal" id="textArticleMY<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Příspěvek</h4>
                                            </div>
                                            <div class="modal-body">
                                                <!-- forma -->
                                                <div>
                                                    <p>
                                                        <span class="custom-text-secondary">Název: </span><?php echo $row["article"]["nazev"] ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <div>
                                                        <p>
                                                            <span class="custom-text-secondary">Autor:</span> <?php echo $row["article"]["prijmeni"] . " " . $row["article"]["jmeno"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p>
                                                        <span class="custom-text-secondary">Datum nahrání: </span><?php echo $row["article"]["datum"] ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <p>
                                                        <span class="custom-text-secondary">Abstrakt:<br></span><?php echo $row["article"]["abstrakt"] ?>
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" id="ffilePathWaiting" name="ffilePath"
                                                           value="<?php echo $row["article"]["soubor"] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <?php $row["article"]["soubor"] = str_replace("C:/xampp/htdocs", "", $row["article"]["soubor"]) ?>
                                                    <a href="<?php echo $row["article"]["soubor"] ?>"
                                                       download="<?php echo $row["article"]["nazev"] ?>"> Stáhnout pdf
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
                                <div class="modal" id="textArticleRevsMY<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni"][0]["prijmeni"] . " " . $row["hodnoceni"][0]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                                <div><span class="custom-text-secondary">Téma:</span>
                                                                    <?php
                                                                    echo $row["hodnoceni"][0]["hodnoceni1"]
                                                                    ?>/5
                                                                </div>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni"][0]["hodnoceni2"] ?>/5</span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni"][0]["hodnoceni3"] ?>/5</span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni"][0]["zprava"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni"][1]["prijmeni"] . " " . $row["hodnoceni"][1]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                                <div><span class="custom-text-secondary">Téma:</span>
                                                                    <?php
                                                                    echo $row["hodnoceni"][1]["hodnoceni1"]
                                                                    ?>/5
                                                                </div>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni"][1]["hodnoceni2"] ?>/5</span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni"][1]["hodnoceni3"] ?>/5</span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni"][1]["zprava"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni"][2]["prijmeni"] . " " . $row["hodnoceni"][2]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                                <div><span class="custom-text-secondary">Téma:</span>
                                                                    <?php
                                                                    echo $row["hodnoceni"][2]["hodnoceni1"]
                                                                    ?>/5
                                                                </div>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni"][2]["hodnoceni2"] ?>/5</span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni"][2]["hodnoceni3"] ?>/5</span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni"][2]["zprava"] ?>
                                                        </p>
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
                    <?php
                } else {
                    ?>
                    <div><p class="text-center">Nemáže žádné zrecenzované články, které jsou schválené/zamítnuté.</p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Vrati tlacitka pro navigacni menu rozsirujici
     */
    public function getPageTopBtns()
    {
        ?>
        <div class="justify-content-center text-center">
            <a class="btn btn-primary custom-btn-secondary" href="index.php?page=my_articles">Moje příspěvky</a>
        </div>
        <?php
    }

    /**
     * Vrati zda byla uspesne napsana recenze
     */
    public function getRevSuccessful()
    {
        ?>
        <div class="alert alert-success text-center">
            <strong>Recenze uložena!</strong>
        </div>
        <?php
    }

    /**
     * Vrati zda byl recence neuspena
     */
    public function getRevFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Recenzi se nepodařilo uložit!</strong> Prosím zkuste to později.
        </div>
        <?php
    }
};

// webova stranka
$pageTpl->getHead($tplData["title"]);
?>
    <body class="d-flex flex-column min-vh-100">
    <?php
    if ($tplData["isLogged"]) {
        ?>
        <?php
        if (!$tplData["isBanned"]) {
            ?>
            <?php
            // kontex stranky
            $pageTpl->getSpecialEvent();
            $pageTpl->getNavbar($tplData["isLogged"], $tplData["isAdmin"]);
            ?>
            <div class="container">
                <?php
                $pageContent->getPageTopBtns();
                ?>
                <hr>
                <?php
                if (isset($tplData["rev"])) {
                    if ($tplData["rev"])
                        $pageContent->getRevSuccessful();
                    else
                        $pageContent->getRevFailed();
                }
                $pageContent->getRequestedReviews($tplData["nonValid"]);
                ?>
                <hr>
                <?php
                $pageContent->getMyReviews($tplData["valid"]);
                ?>
            </div>
            <?php
            $pageTpl->getFooter();
            ?>
            <?php
        } else {
            ?>
            <div class="h4 text-center pt-5">jste zabanován</div>
            <?php
        }
        ?>
        <?php
    } else {
        ?>
        <div class="h4 text-center pt-5">Pro zobrazení stránky musíte být <a href="index.php?page=login">přihlášeni</a>
        </div>
        <?php
    }
    ?>
    </body>
<?php
$pageTpl->getEnd();


