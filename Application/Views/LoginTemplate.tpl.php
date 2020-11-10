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

// metody stranky
$pageContent = new class {

    /**
     * Vypise formular potrebny pro prihlaseni do aplikace
     */
    public function showLoginForm()
    {
        ?>
        <!-- login -->
        <h2 class="text-center py-5 custom-text-primary h1">PŘIHLÁSIT SE</h2>
        <!-- form -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="femail" class="custom-text-secondary">E-mail</label>
                <input type="email" class="form-control" id="femail" name="femail" placeholder="Zadejte E-mail"><br>
            </div>
            <div class="form-group">
                <label for="fpassword" class="custom-text-secondary">Heslo</label>
                <input type="password" class="form-control" id="fpassword" name="fpassword" placeholder="Heslo"><br>
            </div>

            <a href="#" data-toggle="popover" title="Upozornění!"
               data-content="Tato funkce není momentálně k dispozici">Zapomněl jsem heslo!</a>
            <script>
                $(document).ready(function () {
                    $('[data-toggle="popover"]').popover();
                });
            </script>

            <button type="submit" name="action" value="login"
                    class="btn btn-light w-100 py-2 custom-btn-submit-long custom-text-primary">
                Odeslat
            </button>
        </form>
        <?php
    }

    /**
     * Vypise, ze uzivatel je jiz prihlasen a nema cenu se prihlasovat
     */
    public function showAlreadyLogged()
    {
        ?>
        <h2 class="h1 custom-text-primary text-center py-5">Již jste přihlášeni!</h2>
        <?php
    }

    /**
     * Vypise uspesne prihlaseni
     */
    public function showSuccessfulLogin()
    {
        ?>
        <h2 class="h1 custom-text-primary text-center py-5">Úspěšně jste se přihlásili!</h2>
        <?php
    }

    /**
     * Vypise neuspesne prihlaseni
     */
    public function showLoginFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Přihlášení selhalo.</strong> Ověřte prosím vyplněný email a vyplněné heslo.
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
        if (!isset($tplData["loginSuccessful"])) {
            // pravdepodobne nebyl proveden pokus o prihlaseni
            if ($tplData["isLogged"]) {
                // uzivatel je prihlasen
                $pageContent->showAlreadyLogged();
            } else {
                // uzivatel neni prihlasen
                $pageContent->showLoginForm();
            }
        } else {
            // probehl pokus o prihlaseni
            if ($tplData["loginSuccessful"]) {
                $pageContent->showSuccessfulLogin();
            } else {
                $pageContent->showLoginFailed();
                $pageContent->showLoginForm();
            }
        }
        ?>
    </div>
    </body>
<?php
$pageTpl->getEnd();