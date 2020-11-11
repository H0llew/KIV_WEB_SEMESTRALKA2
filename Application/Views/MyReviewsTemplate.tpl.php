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
                                <td><?php echo $row["nazev"] ?></td>
                                <td><?php echo $row["userName"] ?></td>
                                <td><?php echo $row["datum"] ?></td>
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
                                                <p><span>Název: </span><?php echo $row["nazev"] ?></p>
                                            </div>
                                            <div class="form-text custom-text-secondary">
                                                <p><span>Autor: </span><?php echo $row["userName"] ?></p>
                                            </div>
                                            <div>
                                                <p><span>Datum nahrání: </span><?php echo $row["datum"] ?></p>
                                            </div>
                                            <div>
                                                <p><span>Abstrakt: </span><?php echo $row["abstrakt"] ?></p>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" id="ffilePath" name="ffilePath"
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
                            <div class="modal" id="textArticleRev<?php echo $count ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header text justify-content-center">
                                            <h4 class="modal-title custom-text-primary">Příspěvek</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!-- forma -->
                                            <form enctype="multipart/form-data" action="" method="POST">
                                                <div class="form-group">
                                                    <label for="fkrit1" class="custom-text-secondary">Krit1</label>
                                                    <input type="text" name="fkrit1" class="form-control"
                                                           id="fkrit1" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fkrit2" class="custom-text-secondary">Krit2</label>
                                                    <input type="text" name="fkrit2" class="form-control"
                                                           id="fkrit2" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fkrit3" class="custom-text-secondary">Krit3</label>
                                                    <input type="text" name="fkrit3" class="form-control"
                                                           id="fkrit3" required>
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
                                                            name="action"
                                                            id="article" value="upload"
                                                            disabled>
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
                                <td><?php echo $row["nazev"] ?></td>
                                <td><?php echo $row["datum"] ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticleRevs<?php echo $count ?>">
                                        Hodnocení
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticle<?php echo $count ?>">
                                        Podrobnosti
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
                                            <form enctype="multipart/form-data" action="" method="POST">
                                                <div class="form-group">
                                                    <label for="fheading" class="custom-text-secondary">Název</label>
                                                    <input type="text" name="fheading" class="form-control"
                                                           id="fabstract" value="<?php echo $row["nazev"] ?>"
                                                           readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fdate" class="custom-text-secondary">Datum
                                                        nahrání</label>
                                                    <input type="text" name="fdate" class="form-control"
                                                           id="fdate" value="<?php echo $row["datum"] ?>"
                                                           readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fabstract"
                                                           class="custom-text-secondary">Abstrakt</label>
                                                    <textarea id="fabstract" name="fabstract" class="form-control"
                                                              rows="10"
                                                              cols="50"
                                                              readonly><?php echo $row["abstrakt"] ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fname" class="custom-text-secondary">Autor</label>
                                                    <input type="text" name="fname" class="form-control"
                                                           id="fname"
                                                           placeholder="<?php echo $row["userName"] ?>"
                                                           readonly>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" id="ffilePath" name="ffilePath"
                                                           value="<?php echo $row["soubor"] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <?php $row["soubor"] = str_replace("C:/xampp/htdocs", "", $row["soubor"]) ?>
                                                    <a href="<?php echo $row["soubor"] ?>"
                                                       download="<?php echo $row["nazev"] ?>"> Stáhnout pdf
                                                        článek</a>
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
                            <div class="modal" id="textArticleRevs<?php echo $count ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header text justify-content-center">
                                            <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni0"]["autor"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni0"]["krit1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni0"]["krit2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni0"]["krit3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni0"]["zprava"] ?></p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni1"]["autor"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni1"]["krit1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni1"]["krit2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni1"]["krit3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni1"]["zprava"] ?></p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni2"]["autor"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni2"]["krit1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni2"]["krit2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni2"]["krit3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni2"]["zprava"] ?></p>
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
$pageTpl->getHead("test");
?>
    <body>
    <?php
    // kontex stranky
    $pageTpl->getNavbar(false, false);
    ?>
    <div class="container">
        <?php
        $pageContent->getPageTopBtns();
        ?>
        <hr>
        <?php
        $pageContent->getRequestedReviews($tplData["notVerifiedArticles"]);
        ?>
        <hr>
        <?php
        $pageContent->getMyReviews($tplData["dismissedArticles"]);
        ?>
    </div>
    </body>
<?php
$pageTpl->getEnd();


