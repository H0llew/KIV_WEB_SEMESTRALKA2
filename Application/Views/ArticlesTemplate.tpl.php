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
            <div class="card border-0">
                <div class="card-body">
                    <div class="custom-text-secondary text-center">
                        <p class="h5">
                            Zde najdete volně dostupné příspěvky týkající se financí a bankovnictví.
                        </p>
                        <p>
                            Pokud chcete publikovat příspěvek je potřeba být registrován.
                        </p>
                    </div>
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
                                <th>Hodnocení</th>
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
                                        <?php
                                        $hod = $row["hodnoceni"];
                                        $hod1 = $hod[0];
                                        $hod2 = $hod[1];
                                        $hod3 = $hod[2];
                                        $rev = round(((($hod1["hodnoceni1"] + $hod1["hodnoceni2"] + $hod1["hodnoceni3"]) / 3)
                                                + (($hod2["hodnoceni1"] + $hod2["hodnoceni2"] + $hod2["hodnoceni3"]) / 3)
                                                + (($hod1["hodnoceni3"] + $hod3["hodnoceni2"] + $hod3["hodnoceni3"]) / 3)) / 3, 2);
                                        echo $rev;
                                        ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleRevsA<?php echo $count ?>">
                                            Podrobné hodnocení
                                        </button>
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
                                                    <p>
                                                        <span class="custom-text-secondary">Název: </span><?php echo $row["nazev"] ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <div>
                                                        <p>
                                                            <span class="custom-text-secondary">Autor:</span> <?php echo $row["prijmeni"] . " " . $row["jmeno"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p>
                                                        <span class="custom-text-secondary">Datum nahrání: </span><?php echo $row["datum"] ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <p>
                                                        <span class="custom-text-secondary">Abstrakt:<br></span><?php echo $row["abstrakt"] ?>
                                                    </p>
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
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni"][0]["prijmeni"] . " " . $row["hodnoceni"][0]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                                <div><span class="custom-text-secondary">Téma:</span>
                                                                    <?php
                                                                    echo $row["hodnoceni"][0]["hodnoceni1"]
                                                                    ?></div>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni"][0]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni"][0]["hodnoceni3"] ?></span><br>
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
                                                                    ?></div>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni"][1]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni"][1]["hodnoceni3"] ?></span><br>
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
                                                                    ?></div>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni"][2]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni"][2]["hodnoceni3"] ?></span><br>
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


