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
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr class="custom-text-primary">
                            <th>Název</th>
                            <th>Autor</th>
                            <th>Datum</th>
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
                                <td><?php echo $row["article"]["nazev"] ?></td>
                                <td><?php echo $row["article"]["prijmeni"] . " " . $row["article"]["jmeno"] ?></td>
                                <td><?php echo $row["article"]["datum"] ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticle<?php echo $count ?>">
                                        Podrobnosti
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticleRev<?php echo $count ?>">
                                        Zrecenzovat
                                    </button>
                                </td>
                            </tr>
                            <div class="modal" id="textArticle<?php echo $count ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header text justify-content-center">
                                            <h4 class="modal-title custom-text-primary">Příspěvek</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!-- forma -->
                                            <div>
                                                <p><span>Název: </span><?php echo $row["article"]["nazev"] ?></p>
                                            </div>
                                            <div class="form-text custom-text-secondary">
                                                <p>
                                                    <span>Autor: </span><?php echo $row["article"]["prijmeni"] . " " . $row["article"]["jmeno"] ?>
                                                </p>
                                            </div>
                                            <div>
                                                <p><span>Datum nahrání: </span><?php echo $row["article"]["datum"] ?>
                                                </p>
                                            </div>
                                            <div>
                                                <p><span>Abstrakt: </span><?php echo $row["article"]["abstrakt"] ?></p>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" id="ffilePath" name="ffilePath"
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
                            <div class="modal" id="textArticleRev<?php echo $count ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header text justify-content-center">
                                            <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!-- forma -->
                                            <form action="" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $row["id_recenze"] ?>">
                                                <div>
                                                    <label for="fh1">Téma:</label>
                                                    <select name="fh1">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fh2">Téchnická kvalita:</label>
                                                    <select name="fh2">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fh3">Jazyková kvalita:</label>
                                                    <select name="fh3">
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
                            <?php
                            $count++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
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
            <div class="card-title text-center py-2 custom-text-primary"><h4>Schválené příspěvky</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Vaše schválené příspěvky. Schválené příspěvky
                jsou viditelné všem přihlášeným a nepřihlášeným uživatelům.
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr class="custom-text-primary">
                            <th>Název</th>
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
                                <td><?php echo $row["article"]["nazev"] ?></td>
                                <td><?php echo $row["article"]["datum"] ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticleRevsMY<?php echo $count ?>">
                                        Hodnocení
                                    </button>
                                </td>
                                <td>
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
                                                <p><span>Název: </span><?php echo $row["article"]["nazev"] ?></p>
                                            </div>
                                            <div class="form-text custom-text-secondary">
                                                <p>
                                                    <span>Autor: </span><?php echo $row["article"]["prijmeni"] . " " . $row["article"]["jmeno"] ?>
                                                </p>
                                            </div>
                                            <div>
                                                <p><span>Datum nahrání: </span><?php echo $row["article"]["datum"] ?>
                                                </p>
                                            </div>
                                            <div>
                                                <p><span>Abstrakt: </span><?php echo $row["article"]["abstrakt"] ?></p>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" id="ffilePathWaiting" name="ffilePath"
                                                       value="<?php echo $row["soubor"] ?>">
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
                                                    <span>Hodnocení: <?php echo $row["hodnoceni"][0]["prijmeni"] . " " . $row["hodnoceni"][0]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma:
                                                                <?php
                                                                echo $row["hodnoceni"][0]["hodnoceni1"]
                                                                ?></span><br>
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
                                                    <span>Hodnocení: <?php echo $row["hodnoceni"][1]["prijmeni"] . " " . $row["hodnoceni"][1]["jmeno"] ?></span><br>
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
                                                    <span>Hodnocení: <?php echo $row["hodnoceni"][2]["prijmeni"] . " " . $row["hodnoceni"][2]["jmeno"] ?></span><br>
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
        $pageContent->getPageTopBtns();
        ?>
        <hr>
        <?php
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
    </body>
<?php
$pageTpl->getEnd();


