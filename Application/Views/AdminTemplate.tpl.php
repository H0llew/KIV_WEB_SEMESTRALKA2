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
     * Zmena stranky (bud sprava uzivatelu nebo prispevku)
     *
     * @param int $page stranka
     */
    public function switchPages(int $page)
    {
        ?>
        <div class="text-center py-2">
            <div class="btn-group-vertical py-2 mx-auto">
                <?php
                switch ($page) {
                    case 0: ?> <a href="index.php?page=admin&view=1" class="btn btn-primary custom-btn-primary py-3">Správa
                        příspěvků</a> <?php
                        break;
                    case 1: ?> <a href="index.php?page=admin&view=0" class="btn btn-primary custom-btn-primary py-3">Správa
                        uživatelů</a> <?php
                        break;
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Vrati uzivatele aplikace vsechny
     *
     * @param array $users uzivatelel
     * @param int $userWeight vaha prihlaseneho uzivatele
     */
    public function getUserManagement(array $users, int $userWeight)
    {
        ?>
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Uživatelé</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Seznam všech uživatelů aplikace.
            </div>
            <div class="card-body">
                <div class="py-4">
                    <form action="" method="POST">
                        <label for="sort">Řadit podle</label>
                        <select name="sort" id="sort">
                            <option value="jmeno">Jméno</option>
                            <option value="prijmeni">Přijmení</option>
                            <option value="email">Email</option>
                            <option value="vaha">Role</option>
                        </select>
                        <button type="submit" class="btn btn-primary custom-btn-secondary" id="sort" name="action"
                                value="sort">
                            Seřadit
                        </button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Jméno</th>
                            <th>Přijmení</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="custom-text-secondary">
                        <?php
                        foreach ($users as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row["jmeno"] ?></td>
                                <td><?php echo $row["prijmeni"] ?></td>
                                <td><?php echo $row["email"] ?></td>
                                <td><?php echo $row["nazev"] ?></td>
                                <?php
                                if ($row["vaha"] < $userWeight) {
                                    ?>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="id" id="id"
                                                   value="<?php echo $row["id_uzivatel"] ?>">

                                            <label for="frole">Změnit roli:</label>
                                            <select name="frole" id="frole">
                                                <option value="<?php echo $row["id_pravo"] ?>" selected>Vyberte novou
                                                    roli...
                                                </option>
                                                <option value="4">Uživatel</option>
                                                <option value="3">Recenzent</option>
                                                <option value="2">Admin</option>
                                            </select>

                                            <button class="btn btn-primary custom-btn-secondary" id="rolec"
                                                    name="action" value="crole">
                                                Potvrdit změnu
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="id" id="id"
                                                   value="<?php echo $row["id_uzivatel"] ?>">
                                            <button class="btn btn-primary custom-btn-secondary" type="submit"
                                                    name="action" id="action" value="deleteUser">
                                                SMAZAT!
                                            </button>
                                        </form>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
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
     * Zobrazi prispevky cekajici na prirazeni recenzentu
     *
     * @param array $articles clanky
     * @param array $reviewers recenzenti
     */
    public function getWaitingArticles(array $articles, array $reviewers)
    {
        //clanky ktere potrebuji priradit hodnotitele
        ?>
        <!-- schvalene clanky -->
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Příspěvky čekající na přířazení
                    recenzentů</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Příspěvkům je nutné přiřadit 3 recenzenty, před
                možností schválit/zamítnout článek.
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr class="custom-text-primary">
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
                                            data-target="#textArticleWaiting<?php echo $count ?>">
                                        Podrobnosti
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticleWaitingRevs<?php echo $count ?>">
                                        Přiřadit recenzenty
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
                                            <div>
                                                <p><span>Název: </span><?php echo $row["nazev"] ?></p>
                                            </div>
                                            <div class="form-text custom-text-secondary">
                                                <p>
                                                    <span>Autor: </span><?php echo $row["prijmeni"] . " " . $row["jmeno"] ?>
                                                </p>
                                            </div>
                                            <div>
                                                <p><span>Datum nahrání: </span><?php echo $row["datum"] ?></p>
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
                            <div class="modal" id="textArticleWaitingRevs<?php echo $count ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header text justify-content-center">
                                            <h4 class="modal-title custom-text-primary">Recenzenti</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $row["id_clanek"] ?>">
                                                <div class="col-auto my-1">
                                                    <label class="mr-sm-2" for="frecenzent1-<?php echo $count ?>">Hodnotitel
                                                        1:</label>
                                                    <select class="custom-select mr-sm-2"
                                                            id="frecenzent1-<?php echo $count ?>" name="rev1"
                                                            onchange="checkRevievers<?php echo $count ?>()">
                                                        <?php
                                                        foreach ($reviewers as $rowR) {
                                                            ?>
                                                            <option value="<?php echo $rowR["id_uzivatel"] ?>"><?php echo $rowR["prijmeni"] . " " . $rowR["jmeno"] . " (" . $rowR['email'] . ")" ?></option> <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-auto my-1">
                                                    <label class="mr-sm-2" for="frecenzent2-<?php echo $count ?>">Hodnotitel
                                                        2:</label>
                                                    <select class="custom-select mr-sm-2"
                                                            id="frecenzent2-<?php echo $count ?>" name="rev2"
                                                            onchange="checkRevievers<?php echo $count ?>()">
                                                        <?php
                                                        foreach ($reviewers as $rowR) {
                                                            ?>
                                                            <option value="<?php echo $rowR["id_uzivatel"] ?>"><?php echo $rowR["prijmeni"] . " " . $rowR["jmeno"] . " (" . $rowR['email'] . ")" ?></option> <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-auto my-1">
                                                    <label class="mr-sm-2" for="frecenzent3-<?php echo $count ?>">Hodnotitel
                                                        3:</label>
                                                    <select class="custom-select mr-sm-2"
                                                            id="frecenzent3-<?php echo $count ?>" name="rev3"
                                                            onchange="checkRevievers<?php echo $count ?>()">
                                                        <?php
                                                        foreach ($reviewers as $rowR) {
                                                            ?>
                                                            <option value="<?php echo $rowR["id_uzivatel"] ?>"><?php echo $rowR["prijmeni"] . " " . $rowR["jmeno"] . " (" . $rowR['email'] . ")" ?></option> <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-primary custom-btn-primary"
                                                            name="action"
                                                            id="articleRevAss-<?php echo $count ?>" value="assign"
                                                            disabled>
                                                        Potvrdit Recenzenty
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
                            <script>
                                function checkRevievers<?php echo $count ?>() {
                                    const rev1 = document.getElementById("frecenzent1-<?php echo $count ?>").value;
                                    const rev2 = document.getElementById("frecenzent2-<?php echo $count ?>").value;
                                    const rev3 = document.getElementById("frecenzent3-<?php echo $count ?>").value;

                                    const btn = document.getElementById("articleRevAss-<?php echo $count ?>");

                                    btn.disabled = !(rev1 !== rev2 && rev1 !== rev3 && rev2 !== rev3);
                                }
                            </script>
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
     * Zobrazi prispevky ve stavu recenzovani a zrecenzovane prispevky cekajici na schvaleni
     *
     * @param array $articles
     */
    public function getNeedReviewsArticles(array $articles)
    {
        // prispevky cekajici na zrecenzovani
        ?>
        <!-- prispevky ktere cekaji na zrecenzovani od recenzentu -->
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Příspěvky čekající na zrecenzování a
                    schválení</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky čekající na zrecenzování a schváení
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr class="custom-text-primary">
                            <th>Název</th>
                            <th>Autor</th>
                            <th>Datum vložení</th>
                            <th></th>
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
                                            data-target="#textArticleNeedRevs<?php echo $count ?>">
                                        Hodnocení
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticleNeed<?php echo $count ?>">
                                        Podrobnosti
                                    </button>
                                </td>
                                <?php
                                if ($row["valid"]) {
                                    ?>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $row["id_clanek"] ?>">
                                            <button type="submit" class="btn btn-primary custom-btn-secondary"
                                                    value="approve" name="action">
                                                Scvhálit
                                            </button>
                                        </form>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <div class="modal" id="textArticleNeed<?php echo $count ?>">
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
                                                <p><span>Datum nahrání: </span><?php echo $row["datum"] ?></p>
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
                            <div class="modal" id="textArticleNeedRevs<?php echo $count ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header text justify-content-center">
                                            <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni1"]["prijmeni"] . " " . $row["hodnoceni1"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma:
                                                                <?php
                                                                echo $row["hodnoceni1"]["hodnoceni1"]
                                                                ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni1"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni1"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni1"]["zprava"] ?></p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni2"]["prijmeni"] . " " . $row["hodnoceni2"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni2"]["hodnoceni1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni2"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni2"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni2"]["zprava"] ?></p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni3"]["prijmeni"] . " " . $row["hodnoceni3"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni3"]["hodnoceni1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni3"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni3"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni3"]["zprava"] ?></p>
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
     * Zobrazi prispevky schvalene
     *
     * @param array $articles
     */
    public function getAssignedAndApproved(array $articles)
    {
        ?>
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Potvrzené příspěvky</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky viditelné pro všechny uživatele.
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr class="custom-text-primary">
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
                                            data-target="#textArticleApRevs<?php echo $count ?>">
                                        Hodnocení
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary custom-btn-secondary"
                                            data-toggle="modal"
                                            data-target="#textArticleAp<?php echo $count ?>">
                                        Podrobnosti
                                    </button>
                                </td>
                            </tr>
                            <div class="modal" id="textArticleAp<?php echo $count ?>">
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
                                                <p><span>Datum nahrání: </span><?php echo $row["datum"] ?></p>
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
                            <div class="modal" id="textArticleApRevs<?php echo $count ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header text justify-content-center">
                                            <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni1"]["prijmeni"] . " " . $row["hodnoceni1"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma:
                                                                <?php
                                                                echo $row["hodnoceni1"]["hodnoceni1"]
                                                                ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni1"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni1"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni1"]["zprava"] ?></p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni2"]["prijmeni"] . " " . $row["hodnoceni2"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni2"]["hodnoceni1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni2"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni2"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni2"]["zprava"] ?></p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni3"]["prijmeni"] . " " . $row["hodnoceni3"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni3"]["hodnoceni1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni3"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni3"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni3"]["zprava"] ?></p>
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
     * Vrati nec
     *
     * @param array $articles
     */
    public function getDismissed(array $articles)
    {
        ?>
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Zamítnuté příspěvky</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky čekající na zrecenzování a schváení
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr class="custom-text-primary">
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
                                <td><?php echo $row["userName"] ?></td>
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
                                                <p><span>Datum nahrání: </span><?php echo $row["datum"] ?></p>
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
                                                    <span>Hodnocení: <?php echo $row["hodnoceni1"]["prijmeni"] . " " . $row["hodnoceni1"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma:
                                                                <?php
                                                                echo $row["hodnoceni1"]["hodnoceni1"]
                                                                ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni1"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni1"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni1"]["zprava"] ?></p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni2"]["prijmeni"] . " " . $row["hodnoceni2"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni2"]["hodnoceni1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni2"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni2"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni2"]["zprava"] ?></p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Hodnocení: <?php echo $row["hodnoceni3"]["prijmeni"] . " " . $row["hodnoceni3"]["jmeno"] ?></span><br>
                                                    <hr>
                                                    <div class="py-2">
                                                        <div class="">
                                                            <span>Téma: <?php echo $row["hodnoceni3"]["hodnoceni1"] ?></span><br>
                                                            <span>Technická kvalita: <?php echo $row["hodnoceni3"]["hodnoceni2"] ?></span><br>
                                                            <span>Jazyková kvalita: <?php echo $row["hodnoceni3"]["hodnoceni3"] ?></span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p> Poznámka: <?php echo $row["hodnoceni3"]["zprava"] ?></p>
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
        $pageContent->switchPages($tplData["page"]);
        ?>
        <hr>
        <?php
        switch ($tplData["page"]) {
            case 0:
                $pageContent->getUserManagement($tplData["users"], $tplData["userWeight"]);
                break;
            case 1:
                $pageContent->getWaitingArticles($tplData["waiting"], $tplData["reviewers"]);
                echo "<hr>";
                $pageContent->getNeedReviewsArticles($tplData["needReview"]);
                echo "<hr>";
                $pageContent->getAssignedAndApproved($tplData["approved"]);
                echo "<hr>";
                $pageContent->getDismissed($tplData["notApproved"]);
                break;
        }
        ?>
    </div>
    <?php
    $pageTpl->getFooter();
    ?>
    </body>
<?php
$pageTpl->getEnd();