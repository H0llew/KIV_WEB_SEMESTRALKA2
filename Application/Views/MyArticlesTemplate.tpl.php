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
     * Vrati prispevky ve fazi overovani nebo prirazovani recenzentu
     *
     * @param array $articles prispevky
     */
    public function getNotVerifiedArticles(array $articles)
    {
        ?>
        <!-- clanky cekajici na schvaleni -->
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Příspěvky čekající na schválení</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Vaše příspěvky čekající na přiřazení
                recenzentům nebo
                čekající na recenzce od
                recenzentů
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr class="custom-text-primary">
                            <th>Název</th>
                            <th>Datum vložení</th>
                            <th>Status</th>
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
                                    <?php
                                    if ($row["status"] == 0) {
                                        echo "Příspěvek čeká na přiřazení recenzentů";
                                    } else {
                                        echo "Příspěvek čeká na zrecenzování od přiřazených recenzentů";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticleWaiting<?php echo $count ?>">
                                        Podrobnosti
                                    </button>
                                </td>
                            </tr>
                            <div class="modal" id="textArticleWaiting<?php echo $count ?>">
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
                                                           required>
                                                </div>
                                                <div class="py-2"><span
                                                            class="custom-text-secondary">Autor: </span><?php echo $row["prijmeni"] . " " . $row["jmeno"] ?>
                                                </div>
                                                <div class="py-2"><span
                                                            class="custom-text-secondary">Datum: </span><?php echo $row["datum"] ?>
                                                </div>
                                                <input type="hidden" name="fdate" id="fdate"
                                                       value="<?php echo $row["datum"] ?>">
                                                <div class="form-group">
                                                    <label for="fabstract"
                                                           class="custom-text-secondary">Abstrakt</label>
                                                    <textarea id="fabstract" name="fabstract" class="form-control"
                                                              rows="10"
                                                              cols="50"
                                                              required><?php echo $row["abstrakt"] ?></textarea>
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
                                                <div class="form-group">
                                                    <label for="ffile" class="custom-text-secondary">Nový soubor</label>
                                                    <input type="hidden" name="MAX_FILE_SIZE" value="300000000">
                                                    <input type="file" name="ffile"
                                                           class="form-control-file"
                                                           id="ffile<?php echo $count ?>"
                                                           onchange="checkFileExt('<?php echo $count ?>')">
                                                    <small id="ffileValidationBlock<?php echo $count ?>"
                                                           class="form-text text-muted text-warning"
                                                           style="display: none">
                                                        Soubor musí mít koncovku '.pdf'
                                                    </small>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <?php
                                                    if ($row["status"] == 0) {
                                                        ?>
                                                        <button type="submit" class="btn btn-primary custom-btn-primary"
                                                                name="action"
                                                                id="article" value="edit">
                                                            Editovat příspěvek
                                                        </button>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <span class="py-2 custom-text-secondary">Příspěvěk nelze editovat,
                                                            protože je již přiřazen recenztům k zrecenzování</span>
                                                        <?php
                                                    }
                                                    ?>
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
     * Vrati zamitnute prispevky uzivatele
     *
     * @param array $articles overezene prispevky
     */
    public function getDismissedArticles(array $articles)
    {
        ?>
        <!-- zamitnute clanky -->
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Zamítnuté příspěvky</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Vaše zamítnuté příspěvky. Editací příspěvku
                můžete požádat o přehodnocení.
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
                                            data-target="#textArticleRevsD<?php echo $count ?>">
                                        Hodnocení
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticleD<?php echo $count ?>">
                                        Podrobnosti
                                    </button>
                                </td>
                            </tr>
                            <div class="modal" id="textArticleD<?php echo $count ?>">
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
                            <div class="modal" id="textArticleRevsD<?php echo $count ?>">
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
        <?php
    }

    /**
     * Vrati overene prispevky uzivatele
     *
     * @param array $articles overezene prispevky
     */
    public function getVerifiedArticles(array $articles)
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
        <?php
    }

    /**
     * Tlacitka pro navigaci mezi strankami jinymi nez vse spolecnem menu
     *
     * @param bool $isReviewer je uzivatel  recenzent?
     */
    public function getPageTopBtns(bool $isReviewer)
    {
        ?>
        <div class="justify-content-center text-center">
            <a class="btn btn-primary custom-btn-secondary" href="index.php?page=create_article">Napsat nový
                příspěvek</a>
            <?php
            if ($isReviewer) {
                ?>
                <a class="btn btn-primary custom-btn-primary" href="index.php?page=my_reviews">Moje Recenze</a>
                <?php
            }
            ?>
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
    <div class="container py-2">
        <?php
        $pageContent->getPageTopBtns($tplData["isReviewer"]);
        ?>
        <hr>
        <?php
        $pageContent->getVerifiedArticles($tplData["userArticles"]["approved"]);
        ?>
        <hr>
        <?php
        $pageContent->getNotVerifiedArticles($tplData["userArticles"]["waiting"]);
        ?>
        <hr>
        <?php
        $pageContent->getDismissedArticles($tplData["userArticles"]["dissmised"]);
        ?>
    </div>
    <?php
    $pageTpl->getFooter();
    ?>
    </body>
<?php
$pageTpl->getEnd();


