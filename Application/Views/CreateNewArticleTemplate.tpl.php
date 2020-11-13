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
//$tplData["userFullName"] // jmeno a prijmeni uzivatele
//$tplData["successfulUpload"] // povedl se upload clanku na server?

// metody stranky
$pageContent = new class {

    public function getNewArticleSection(string $userFullName)
    {
        ?>
        <!-- novy clanek -->
        <article class="py-5">
            <div class="card">
                <div class="card-title custom-text-primary mx-auto my-auto" style="padding-top: 1em">
                    <h2>Napsat nový příspěvek<h2>
                </div>
                <hr>
                <div class="text-center">
                    <div class="card-text custom-text-primary py-2">
                        <div class="card-subtitle">
                            <h5>Pokyny pro psaní článů<h5>
                        </div>
                    </div>
                    <div class="card-text py-2">
                        <div class="card-subtitle custom-text-secondary"><strong>Jazyk příspěvků</strong></div>
                        <p>Příspěvky mohou být napsány v českém, anglickém a německém jazyce.</p>
                    </div>
                    <div class="card-text py-2">
                        <div class="card-subtitle custom-text-secondary"><strong>Rozsah příspěvků</strong></div>
                        <p>Příspěvky nejsou nijak rozsahově omezené.</p>
                    </div>
                    <div class="card-text py-2">
                        <div class="card-subtitle custom-text-secondary"><strong>Formát příspěvků</strong></div>
                        <p>Je nutné dodržovat pravopisná a typografická pravidla při psaní článků.</p>
                    </div>
                    <div class="card-text py-2">
                        <div class="card-subtitle custom-text-secondary"><strong>Nahrání a kontrola příspěvku</strong>
                        </div>
                        <p>Příspěvek je třeba odevzdat ve formátu PDF.<br> Po úspěšném nahrání bude příspěvek přidělen
                            třem recenzentům na zrecenování a následně <br>
                            schválen/zamítnut administrátorem stránky, na základě hodnocení recenzentů.</p>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary custom-btn-primary" data-toggle="modal"
                                data-target="#text1">
                            napsat nový příspěvek
                        </button>
                    </div>
                </div>
            </div>
        </article>
        <!-- vytvoreni noveho clanku -->
        <div class="modal" id="text1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header text justify-content-center">
                        <h4 class="modal-title custom-text-primary">Nový příspěvek</h4>
                    </div>
                    <div class="modal-body">
                        <!-- forma -->
                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="form-group">
                                <label for="fheading" class="custom-text-secondary">Název</label>
                                <input type="text" name="fheading" class="form-control" id="fabstract" required>
                            </div>
                            <div class="form-group">
                                <label for="fabstract" class="custom-text-secondary">Abstrakt</label>
                                <textarea id="fabstract" name="fabstract" class="form-control" rows="10"
                                          cols="50" required></textarea>
                            </div>
                            <div class="py-2"><span
                                        class="custom-text-secondary">Autor: </span><?php echo $userFullName ?></div>
                            <div class="form-group">
                                <label for="ffile" class="custom-text-secondary">Soubor</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="300000000">
                                <input type="file" name="ffile" class="form-control-file" id="ffile"
                                       onchange="checkFileExt()" required>
                                <small id="ffileValidationBlock" class="form-text text-muted text-warning"
                                       style="display: none">
                                    Soubor musí mít koncovku '.pdf'
                                </small>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary custom-btn-primary" name="action"
                                        id="article" value="upload"
                                        disabled>
                                    Odeslat článek k ověření
                                </button>
                            </div>
                        </form>
                        <script>
                            function checkFileExt(num = "") {
                                var file = document.getElementById("ffile" + num).value;
                                const index = file.lastIndexOf(".");
                                const fileExt = file.substring(index + 1, file.length);

                                if (fileExt.toLowerCase() == "pdf") {
                                    document.getElementById("ffileValidationBlock" + num).style.display = "none";
                                    document.getElementById("article").disabled = false;
                                } else {
                                    document.getElementById("ffileValidationBlock" + num).style.display = "block";
                                    document.getElementById("article").disabled = true;
                                }
                            }
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger custom-btn-secondary" data-dismiss="modal">Zavřít
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Vypise uspesne nahrani prispevku
     */
    public function showSuccessfulUpload()
    {
        ?>
        <h2 class="h3 custom-text-primary text-center py-5">Úspěšně jste se napsali nový příspěvek.<br>Nyní uvidíte v
            záložce vaších článků nový neschválený příspěvek. Až Váš příspěvěk bude ohodnocen třema recenzenty, uvidíte
            příspěvěk ve schválených/zamítnutých příspěvcích.</h2>
        <?php
    }

    /**
     * Vypise neuspesne nahrani prispevku
     */
    public function showUploadFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Nahrání příspěvku selhalo.</strong> Zkuste akci později.
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
        if (!isset($tplData["successfulUpload"])) {
            $pageContent->getNewArticleSection($tplData["userFullName"]);
        } else {
            if ($tplData["successfulUpload"]) {
                $pageContent->showSuccessfulUpload();
            } else {
                $pageContent->showUploadFailed();
                $pageContent->getNewArticleSection($tplData["userFullName"]);
            }
        }
        ?>
    </div>
    </body>
<?php
$pageTpl->getEnd();


