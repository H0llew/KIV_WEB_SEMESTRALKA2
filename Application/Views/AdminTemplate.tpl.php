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

    public function switchPages(int $page)
    {
        ?>
        <div class="text-center py-2">
            <div class="btn-group-vertical py-2 mx-auto">
                <?php
                switch ($page) {
                    case 0: ?> <a href="#" class="btn btn-primary custom-btn-primary py-3">Správa příspěvků</a> <?php
                        break;
                    case 1: ?> <a href="#" class="btn btn-primary custom-btn-primary py-3">Správa uživatelů</a> <?php
                        break;
                }
                ?>
            </div>
        </div>
        <?php
    }

    public function getUserManagement(array $users)
    {
        ?>
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Uživatelé</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Seznam všech uživatelů aplikace.
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Jméno</th>
                            <th>Přijmení</th>
                            <th>Email</th>
                            <th>Role</th>
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
                                <td><?php echo $row["role"] ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link custom-btn-secondary" href="#">Předchozí</a></li>
                        <li class="page-item"><a class="page-link custom-btn-primary" href="#">1</a></li>
                        <li class="page-item"><a class="page-link custom-btn-primary" href="#">2</a></li>
                        <li class="page-item"><a class="page-link custom-btn-primary" href="#">3</a></li>
                        <li class="page-item"><a class="page-link custom-btn-secondary" href="#">Další</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }

    public function getArticleManagement(array $articles)
    {

    }

    public function getAssignRev(array $articles)
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
                        foreach ($articles

                                 as $row) {
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
                                            data-target="#textArticleRevs<?php echo $count ?>">
                                        Přiřadit recenzenty
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
                                            <h4 class="modal-title custom-text-primary">Recenzenti</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <div class="col-auto my-1">
                                                    <label class="mr-sm-2" for="frecenzent1">Hodnotitel 1:</label>
                                                    <select class="custom-select mr-sm-2" id="frecenzent1">
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                                <div class="col-auto my-1">
                                                    <label class="mr-sm-2" for="frecenzent2">Hodnotitel 2:</label>
                                                    <select class="custom-select mr-sm-2" id="frecenzent2">
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                                <div class="col-auto my-1">
                                                    <label class="mr-sm-2" for="frecenzent3">Hodnotitel 3:</label>
                                                    <select class="custom-select mr-sm-2" id="frecenzent3">
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-primary custom-btn-primary"
                                                            name="action"
                                                            id="article" value="upload">
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

    public function getAssignedButNotAprooved(array $articles)
    {
        ?>
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Příspěvky čekající na zrecenzování a
                    schvální</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky čekající na zrecenzování a schváení
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

    public function getAssignedAndApproved(array $articles)
    {
        ?>
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Příspěvky čekající na zrecenzování a
                    schvální</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky čekající na zrecenzování a schváení
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

    public function getDismissed(array $articles)
    {
        ?>
        <div class="card">
            <div class="card-title text-center py-2 custom-text-primary"><h4>Příspěvky čekající na zrecenzování a
                    schvální</h4></div>
            <div class="card-subtitle text-center custom-text-secondary">Příspěvky čekající na zrecenzování a schváení
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
        $pageContent->switchPages($tplData["page"]);
        ?>
        <hr>
        <?php
        switch ($tplData["page"]) {
            case 0:
                $pageContent->getUserManagement($tplData["users"]);
                break;
            case 1:
                $pageContent->getAssignRev($tplData["dismissedArticles"]);
                $pageContent->getAssignedButNotAprooved($tplData["dismissedArticles"]);
                break;
        }
        ?>
    </div>
    </body>
<?php
$pageTpl->getEnd();