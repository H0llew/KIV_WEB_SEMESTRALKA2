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

// metody stranky
$pageContent = new class {

    /**
     * Vrati tlacitka na zacatku stranky
     */
    public function getButtons(bool $isReviewer)
    {
        ?>
        <div class="text-center py-2">
            <div class="btn-group-vertical py-2 mx-auto">
                <a href="index.php?page=create_article" class="btn btn-primary custom-btn-primary py-3">Napsat nový
                    příspěvěk</a>
                <a href="index.php?page=my_articles" class="btn btn-primary custom-btn-primary py-3">Moje příspěvky</a>
                <?php
                if ($isReviewer) {
                    ?>
                    <a href="index.php?page=my_reviews" class="btn btn-primary custom-btn-primary py-3">Moje recenze</a>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Vypsana daat uzivatele
     *
     * @param $userData
     */
    public function getUserData($userData)
    {
        ?>
        <div class="card mx-auto pb-2 mb-4" style="width: 25rem">
            <div class="card-title">
                <h4 class="text-center custom-text-primary">Osobní údaje</h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="femail" class="custom-text-secondary">Email</label>
                        <input type="text" name="femail" class="form-control" id="femail"
                               value="<?php echo $userData["email"] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fname" class="custom-text-secondary">Jméno</label>
                        <input type="text" name="fname" class="form-control" id="fname"
                               value="<?php echo $userData["jmeno"] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="flname" class="custom-text-secondary">Příjmení</label>
                        <input type="text" name="flname" class="form-control" id="flname"
                               value="<?php echo $userData["prijmeni"] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="npassword" class="custom-text-secondary">Heslo</label>
                        <input type="password" class="form-control" id="npassword" name="npassword" placeholder="Heslo"
                               onchange="comparePw()"><br>

                        <label for="npassword2" class="custom-text-secondary">Heslo</label>
                        <input type="password" class="form-control" id="npassword2" name="npassword2"
                               placeholder="Zopakujte Heslo"
                               onchange="comparePw()"><br>
                        <p id="ctext" class="text-warning py-0" style="font-size: small; display: none"> *zadaná hesla
                            musí být
                            stejná </p>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="py-2 custom-text-primary">Změnu potvrďte zadáním současného hesla</div>
                        <label for="fpassword" class="custom-text-secondary">Heslo</label>
                        <input type="password" class="form-control" id="fpassword" name="fpassword" placeholder="Heslo"
                               required><br>
                    </div>
                    <button type="submit" name="action" id="register" value="update"
                            class="btn btn-light w-100 py-2">
                        Aktualizovat údaje
                    </button>
                </form>
                <script>
                    function comparePw() {
                        var str1 = document.getElementById("npassword").value;
                        var str2 = document.getElementById("npassword2").value;

                        if (str1 === str2) {
                            document.getElementById("register").disabled = false;
                            document.getElementById("ctext").style.display = "none";
                        } else {
                            document.getElementById("register").disabled = true;
                            document.getElementById("ctext").style.display = "block";
                        }
                    }
                </script>
            </div>
        </div>
        <?php
    }

    /**
     * Text uspesne aktualizace dat
     */
    public function getUpdateText()
    {
        ?>
        <div class="alert alert-success text-center">
            <strong>Aktualizace osobních údajů úspěšná</strong>
        </div>
        <?php
    }

    /**
     * Text neuspesne aktualizace dat
     */
    public function getUpdateTextFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Aktualizace údajů selhala.</strong> Prosím ujistěte se zda zadáváte správné heslo.
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
                $pageContent->getButtons($tplData["isReviewer"]);
                ?>
                <hr>
                <?php
                if (isset($tplData["updateSuc"])) {
                    if ($tplData["updateSuc"]) {
                        $pageContent->getUpdateText();
                    } else {
                        $pageContent->getUpdateTextFailed();
                    }
                }
                $pageContent->getUserData($tplData["userData"]);
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