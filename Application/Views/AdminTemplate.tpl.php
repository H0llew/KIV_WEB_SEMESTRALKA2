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
                    <table class="table table-striped table-borderless">
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
                            if ($row["heslo"] === "0")
                                continue;
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

                                            <div class="row">
                                                <label for="frole"></label>
                                                <select name="frole" id="frole">
                                                    <option value="<?php echo $row["id_pravo"] ?>" selected>Vyberte
                                                        novou
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
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="id" id="id"
                                                   value="<?php echo $row["id_uzivatel"] ?>">
                                            <div class="d-flex flex-row">
                                                <?php
                                                if ($row["isBanned"] == 0) {
                                                    ?>
                                                    <button class="btn btn-outline-info" type="submit"
                                                            name="action" id="ban" value="banUser">
                                                        BAN
                                                    </button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div><strong>UŽIVATEL JE ZABANOVÁN!<strong></div>
                                                    <?php
                                                }
                                                ?>
                                                <button class="btn btn-outline-danger" type="submit"
                                                        name="action" id="delete" value="deleteUser">
                                                    SMAZAT
                                                </button>
                                            </div>
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
                    <?php
                    if (!empty($articles)) {
                        ?>
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
                                    <td><?php echo $row["nazev"] ?></td>
                                    <td><?php echo $row["prijmeni"] . " " . $row["jmeno"] ?></td>
                                    <td><?php echo $row["datum"] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleWaiting<?php echo $count ?>">
                                            Podrobnosti
                                        </button>
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
                                <div class="modal" id="textArticleWaitingRevs<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Recenzenti</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="POST">
                                                    <input type="hidden" name="id"
                                                           value="<?php echo $row["id_clanek"] ?>">
                                                    <div class="col-auto my-1">
                                                        <label class="mr-sm-2 custom-text-secondary"
                                                               for="frecenzent1-<?php echo $count ?>">Recenzent
                                                            1:</label>
                                                        <select class="custom-select mr-sm-2"
                                                                id="frecenzent1-<?php echo $count ?>" name="rev1"
                                                                onchange="checkIfDiff<?php echo $count ?>()">
                                                            <?php
                                                            foreach ($reviewers as $rowR) {
                                                                ?>
                                                                <option value="<?php echo $rowR["id_uzivatel"] ?>"><?php echo $rowR["prijmeni"] . " " . $rowR["jmeno"] . " (" . $rowR['email'] . ")" ?></option> <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-auto my-1">
                                                        <label class="mr-sm-2 custom-text-secondary"
                                                               for="frecenzent2-<?php echo $count ?>">Recenzent
                                                            2:</label>
                                                        <select class="custom-select mr-sm-2"
                                                                id="frecenzent2-<?php echo $count ?>" name="rev2"
                                                                onchange="checkIfDiff<?php echo $count ?>()">
                                                            <?php
                                                            foreach ($reviewers as $rowR) {
                                                                ?>
                                                                <option value="<?php echo $rowR["id_uzivatel"] ?>"><?php echo $rowR["prijmeni"] . " " . $rowR["jmeno"] . " (" . $rowR['email'] . ")" ?></option> <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-auto my-1">
                                                        <label class="mr-sm-2 custom-text-secondary"
                                                               for="frecenzent3-<?php echo $count ?>">Recenzent
                                                            3:</label>
                                                        <select class="custom-select mr-sm-2"
                                                                id="frecenzent3-<?php echo $count ?>" name="rev3"
                                                                onchange="checkIfDiff<?php echo $count ?>()">
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
                                                        <p id="ctext-<?php echo $count ?>" class="text-warning py-0"
                                                           style="font-size: small; display: block"> *Musí být vybrání
                                                            různí
                                                            recenzenti </p
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
                                    <script>
                                        function checkIfDiff<?php echo $count ?>() {
                                            const rev1 = document.getElementById("frecenzent1-<?php echo $count ?>").value;
                                            const rev2 = document.getElementById("frecenzent2-<?php echo $count ?>").value;
                                            const rev3 = document.getElementById("frecenzent3-<?php echo $count ?>").value;

                                            if (rev1 !== rev2 && rev1 !== rev3 && rev3 !== rev2) {
                                                document.getElementById("articleRevAss-<?php echo $count ?>").disabled = false;
                                                document.getElementById("ctext-<?php echo $count ?>").style.display = "none";
                                            } else {
                                                document.getElementById("articleRevAss-<?php echo $count ?>").disabled = true;
                                                document.getElementById("ctext-<?php echo $count ?>").style.display = "block";
                                            }
                                        }
                                    </script>
                                </div>
                                <?php
                                $count++;
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        ?>
                        <p class="text-center custom-text-primary">Žádné příspěvky nejsou k dispozici</p>
                        <?php
                    }
                    ?>
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
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky čekající na zrecenzování a shválení.
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php
                    if (!empty($articles)) {
                        ?>
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
                                    <td><?php echo $row["nazev"] ?></td>
                                    <td><?php echo $row["prijmeni"] . " " . $row["jmeno"] ?></td>
                                    <td><?php echo $row["datum"] ?></td>
                                    <td>
                                        <form action="" method="POST">
                                            <button type="button" class="btn btn-primary custom-btn-secondary"
                                                    data-toggle="modal"
                                                    data-target="#textArticleApRevsNR<?php echo $count ?>">
                                                Hodnocení
                                            </button>
                                            <button type="button" class="btn btn-primary custom-btn-secondary"
                                                    data-toggle="modal"
                                                    data-target="#textArticleApNR<?php echo $count ?>">
                                                Podrobnosti
                                            </button>
                                            <?php
                                            if ($row["valid"]) {
                                                ?>
                                                <input type="hidden" name="id" value="<?php echo $row["id_clanek"] ?>">
                                                <button type="submit" class="btn btn-outline-success"
                                                        value="approve" name="action">
                                                    Schválit
                                                </button>
                                                <button type="submit" class="btn btn-outline-danger"
                                                        value="dismiss" name="action">
                                                    Zamítnout
                                                </button>
                                                <?php
                                            }
                                            ?>
                                        </form>
                                    </td>
                                </tr>
                                <div class="modal" id="textArticleApNR<?php echo $count ?>">
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
                                <div class="modal" id="textArticleApRevsNR<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <?php
                                                        if (!($row["hodnoceni1"]["hodnoceni1"] == -1 && $row["hodnoceni1"]["hodnoceni2"] == -1 && $row["hodnoceni1"]["hodnoceni3"] == -1)) {
                                                            ?>
                                                            <div>
                                                                <span class="custom-text-secondary">Hodnocení:</span>
                                                                <span> <?php echo $row["hodnoceni1"]["prijmeni"] . " " . $row["hodnoceni1"]["jmeno"] ?></span><br>
                                                                <hr>
                                                                <div class="py-2">
                                                                    <div class="">
                                                            <span><span class="custom-text-secondary">Téma:</span>
                                                                <?php
                                                                echo $row["hodnoceni1"]["hodnoceni1"]
                                                                ?></span><br>
                                                                        <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni1"]["hodnoceni2"] ?></span><br>
                                                                        <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni1"]["hodnoceni3"] ?></span><br>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <p>
                                                                    <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni1"]["zprava"] ?>
                                                                </p>
                                                            </div>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <div>
                                                                <span class="custom-text-secondary">Hodnocení:</span>
                                                                <span> <?php echo $row["hodnoceni1"]["prijmeni"] . " " . $row["hodnoceni1"]["jmeno"] ?></span><br>
                                                                <hr>
                                                                <span class="text-center custom-text-primary">Recenzent ještě neohodnotil příspěvek</span>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <?php
                                                        if (!($row["hodnoceni2"]["hodnoceni1"] == -1 && $row["hodnoceni2"]["hodnoceni2"] == -1 && $row["hodnoceni2"]["hodnoceni3"] == -1)) {
                                                            ?>
                                                            <div>
                                                                <span class="custom-text-secondary">Hodnocení:</span>
                                                                <span> <?php echo $row["hodnoceni2"]["prijmeni"] . " " . $row["hodnoceni2"]["jmeno"] ?></span><br>
                                                                <hr>
                                                                <div class="py-2">
                                                                    <div class="">
                                                                        <div>
                                                                            <span class="custom-text-secondary">Téma:</span>
                                                                            <?php
                                                                            echo $row["hodnoceni2"]["hodnoceni1"]
                                                                            ?>
                                                                        </div>
                                                                        <br>
                                                                        <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni2"]["hodnoceni2"] ?></span><br>
                                                                        <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni2"]["hodnoceni3"] ?></span><br>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <p>
                                                                    <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni2"]["zprava"] ?>
                                                                </p>
                                                            </div>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <div>
                                                                <span class="custom-text-secondary">Hodnocení:</span>
                                                                <span> <?php echo $row["hodnoceni2"]["prijmeni"] . " " . $row["hodnoceni2"]["jmeno"] ?></span><br>
                                                                <hr>
                                                                <span class="text-center custom-text-primary">Recenzent ještě neohodnotil příspěvek</span>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <?php
                                                        if (!($row["hodnoceni3"]["hodnoceni1"] == -1 && $row["hodnoceni3"]["hodnoceni2"] == -1 && $row["hodnoceni3"]["hodnoceni3"] == -1)) {
                                                            ?>
                                                            <div>
                                                                <span class="custom-text-secondary">Hodnocení:</span>
                                                                <span> <?php echo $row["hodnoceni3"]["prijmeni"] . " " . $row["hodnoceni3"]["jmeno"] ?></span><br>
                                                                <hr>
                                                                <div class="py-2">
                                                                    <div class="">
                                                                        <div>
                                                                            <span class="custom-text-secondary">Téma:</span>
                                                                            <?php
                                                                            echo $row["hodnoceni3"]["hodnoceni1"]
                                                                            ?></div>
                                                                        <br>
                                                                        <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni3"]["hodnoceni2"] ?></span><br>
                                                                        <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni3"]["hodnoceni3"] ?></span><br>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <p>
                                                                    <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni3"]["zprava"] ?>
                                                                </p>
                                                            </div>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <div>
                                                                <span class="custom-text-secondary">Hodnocení:</span>
                                                                <span> <?php echo $row["hodnoceni3"]["prijmeni"] . " " . $row["hodnoceni3"]["jmeno"] ?></span><br>
                                                                <hr>
                                                                <span class="text-center custom-text-primary">Recenzent ještě neohodnotil příspěvek</span>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
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
                        <?php
                    } else {
                        ?>
                        <p class="text-center custom-text-primary">Žádné příspěvky nejsou k dispozici</p>
                        <?php
                    }
                    ?>
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
                    <?php
                    if (!empty($articles)) {
                        ?>
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
                                    <td><?php echo $row["nazev"] ?></td>
                                    <td><?php echo $row["prijmeni"] . " " . $row["jmeno"] ?></td>
                                    <td><?php echo $row["datum"] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleApRevs<?php echo $count ?>">
                                            Hodnocení
                                        </button>
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
                                <div class="modal" id="textArticleApRevs<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni1"]["prijmeni"] . " " . $row["hodnoceni1"]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                            <span><span class="custom-text-secondary">Téma:</span>
                                                                <?php
                                                                echo $row["hodnoceni1"]["hodnoceni1"]
                                                                ?></span><br>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni1"]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni1"]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni1"]["zprava"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni2"]["prijmeni"] . " " . $row["hodnoceni2"]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                            <span><span class="custom-text-secondary">Téma:</span>
                                                                <?php
                                                                echo $row["hodnoceni2"]["hodnoceni1"]
                                                                ?></span><br>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni2"]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni2"]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni2"]["zprava"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni3"]["prijmeni"] . " " . $row["hodnoceni3"]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                            <span><span class="custom-text-secondary">Téma:</span>
                                                                <?php
                                                                echo $row["hodnoceni3"]["hodnoceni1"]
                                                                ?></span><br>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni3"]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni3"]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni3"]["zprava"] ?>
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
                        <?php
                    } else {
                        ?>
                        <p class="text-center custom-text-primary">Žádné příspěvky nejsou k dispozici</p>
                        <?php
                    }
                    ?>
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
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky které zamítnul admin.
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php
                    if (!empty($articles)) {
                        ?>
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
                                    <td><?php echo $row["nazev"] ?></td>
                                    <td><?php echo $row["prijmeni"] . " " . $row["jmeno"] ?></td>
                                    <td><?php echo $row["datum"] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleApRevsD<?php echo $count ?>">
                                            Hodnocení
                                        </button>
                                        <button type="button" class="btn btn-primary custom-btn-secondary"
                                                data-toggle="modal"
                                                data-target="#textArticleApD<?php echo $count ?>">
                                            Podrobnosti
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal" id="textArticleApD<?php echo $count ?>">
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
                                <div class="modal" id="textArticleApRevsD<?php echo $count ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header text justify-content-center">
                                                <h4 class="modal-title custom-text-primary">Hodnocení</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni1"]["prijmeni"] . " " . $row["hodnoceni1"]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                            <span><span class="custom-text-secondary">Téma:</span>
                                                                <?php
                                                                echo $row["hodnoceni1"]["hodnoceni1"]
                                                                ?></span><br>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni1"]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni1"]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni1"]["zprava"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni2"]["prijmeni"] . " " . $row["hodnoceni2"]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                            <span><span class="custom-text-secondary">Téma:</span>
                                                                <?php
                                                                echo $row["hodnoceni2"]["hodnoceni1"]
                                                                ?></span><br>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni2"]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni2"]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni2"]["zprava"] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="custom-text-secondary">Hodnocení:</span>
                                                        <span> <?php echo $row["hodnoceni3"]["prijmeni"] . " " . $row["hodnoceni3"]["jmeno"] ?></span><br>
                                                        <hr>
                                                        <div class="py-2">
                                                            <div class="">
                                                            <span><span class="custom-text-secondary">Téma:</span>
                                                                <?php
                                                                echo $row["hodnoceni3"]["hodnoceni1"]
                                                                ?></span><br>
                                                                <span><span class="custom-text-secondary">Technická kvalita:</span> <?php echo $row["hodnoceni3"]["hodnoceni2"] ?></span><br>
                                                                <span><span class="custom-text-secondary">Jazyková kvalita:</span> <?php echo $row["hodnoceni3"]["hodnoceni3"] ?></span><br>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <span class="custom-text-secondary">Poznámka:</span> <?php echo $row["hodnoceni3"]["zprava"] ?>
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
                        <?php
                    } else {
                        ?>
                        <p class="text-center custom-text-primary">Žádné příspěvky nejsou k dispozici</p>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Vrati zda prirazeni recenzentu bylo uspesne
     */
    public function getAssignSuccessful()
    {
        ?>
        <div class="alert alert-success text-center">
            <strong>Recenze přiřazeny!</strong>
        </div>
        <?php
    }

    /**
     * Vrati zda prirazeni recenzentu bylo neuspesne
     */
    public function getAssignFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Recenze se nepodařilo přiřadit!</strong> Prosím zkuste to později.
        </div>
        <?php
    }

    /**
     * Vrati zda schvaleni prispevku bylo uspesne
     */
    public function getApproveSuccessful()
    {
        ?>
        <div class="alert alert-success text-center">
            <strong>Příspěvěk schválen!</strong>
        </div>
        <?php
    }

    /**
     * Vrati zda schvaleni prispevku bylo neuspesne
     */
    public function getApproveFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Příspěvek se nepodařilo schválit!</strong> Prosím zkuste to později.
        </div>
        <?php
    }

    /**
     * Vrati zda zamitnuti prispevku bylo uspesne
     */
    public function getDismissSuccessful()
    {
        ?>
        <div class="alert alert-success text-center">
            <strong>Příspěvěk zamítnut!</strong>
        </div>
        <?php
    }

    /**
     * Vrati zda zamitnuti prispevku bylo uspesne
     */
    public function getDismissFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Příspěvek se nepodařilo zamítnout!</strong> Prosím zkuste to později.
        </div>
        <?php
    }

    /**
     * Vrati zda byl uzivatel uspesne zabanovan
     */
    public function getBanSuccessful()
    {
        ?>
        <div class="alert alert-success text-center">
            <strong>Uživatel zabanován!</strong>
        </div>
        <?php
    }

    /**
     * Vrati zda byl uzivatel neuspesne zabanovan
     */
    public function getBanFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Uživatele nešlo zabanovat!</strong> Prosím zkuste to později.
        </div>
        <?php
    }

    /**
     * Vrati zda byl uzivatel uspesne smazan
     */
    public function getUserDeletedSuccesful()
    {
        ?>
        <div class="alert alert-success text-center">
            <strong>Uživatel smazán!</strong>
        </div>
        <?php
    }

    /**
     * Vrati zda nebyl uzivatel uspesne smazan
     */
    public function getUserDeletedFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Uživatele nešlo smazat!</strong> Prosím zkuste to později.
        </div>
        <?php
    }

    /**
     * Vrati zda zbyla uspesne zmenena role uzivatele
     */
    public function getRoleChangeFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Nepodařilo se změnit roli uživatele!</strong> Prosím zkuste to později.
        </div>
        <?php
    }

    /**
     * Vrati zda zbyla neuspesna zmenena role uzivatele
     */
    public function getRoleChangeSuccessful()
    {
        ?>
        <div class="alert alert-success text-center">
            <strong>Uživatelova role změněna!</strong>
        </div>
        <?php
    }
};

// webova stranka
$pageTpl->getHead($tplData["title"]);
?>
    <body class="d-flex flex-column min-vh-100">
    <?php
    if ($tplData["isAdmin"]) {
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
                $pageContent->switchPages($tplData["page"]);
                ?>
                <hr>
                <?php
                switch ($tplData["page"]) {
                    case 0:
                        if (isset($tplData["ban"])) {
                            if ($tplData["ban"])
                                $pageContent->getBanSuccessful();
                            else
                                $pageContent->getBanFailed();
                        }
                        if (isset($tplData["deleteUser"])) {
                            if ($tplData["deleteUser"])
                                $pageContent->getUserDeletedSuccesful();
                            else
                                $pageContent->getUserDeletedFailed();
                        }
                        if (isset($tplData["roleChange"])) {
                            if ($tplData["roleChange"])
                                $pageContent->getRoleChangeSuccessful();
                            else
                                $pageContent->getRoleChangeFailed();
                        }
                        $pageContent->getUserManagement($tplData["users"], $tplData["userWeight"]);
                        break;
                    case 1:
                        if (isset($tplData["assign"])) {
                            if ($tplData["assign"])
                                $pageContent->getAssignSuccessful();
                            else
                                $pageContent->getAssignFailed();
                        }
                        $pageContent->getWaitingArticles($tplData["waiting"], $tplData["reviewers"]);
                        echo "<hr>";
                        if (isset($tplData["approve"])) {
                            if ($tplData["approve"])
                                $pageContent->getApproveSuccessful();
                            else
                                $pageContent->getApproveFailed();
                        }
                        if (isset($tplData["dismiss"])) {
                            if ($tplData["dismiss"])
                                $pageContent->getDismissSuccessful();
                            else
                                $pageContent->getDismissFailed();
                        }
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
        <div class="h4 text-center pt-5">Pro zobrazení stránky musíte být adminem.
        </div>
        <?php
    }
    ?>
    </body>
<?php
$pageTpl->getEnd();