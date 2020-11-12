<?php

// zajisteni zakladni sablony webove stranky
require_once("settings.inc.php");
require_once("PageTemplate.class.php");
$pageTpl = new PageTemplate();

//predavana data z controlleru
global $tplData;
//pouzivana data
//$tplData["isLogged"];  // je prihlasen?
//$tplData["registrationSuccessful"]; // regisrace uspesna?
//$tplData["isAdmin"];  // je uzivatel admin?
//$tplData["emailTaken"]; // je email zabran?

// metody stranky
$pageContent = new class {

    /**
     * Vypise formular potrebny pro registraci uzivatele do aplikace
     */
    public function showRegistrationForm()
    {
        ?>
        <!-- registrace -->
        <h2 class="text-center py-5 custom-text-primary h1">REGISTRACE</h2>

        <form action="" method="POST">
            <div class="form-group">
                <label for="femail" class="custom-text-secondary">E-mail</label>
                <input type="email" class="form-control" id="femail" name="femail" placeholder="E-mail" required><br>
            </div>
            <div class="form-group">
                <label for="ff_name" class="custom-text-secondary">Křestní jméno</label>
                <input type="text" class="form-control" id="ff_name" name="ff_name" placeholder="Jméno" required><br>
            </div>
            <div class="form-group">
                <label for="fl_name" class="custom-text-secondary">Přijmení</label>
                <input type="text" class="form-control" id="fl_name" name="fl_name" placeholder="Přijmení" required><br>
            </div>
            <div class="form-group">
                <label for="fpassword" class="custom-text-secondary">Heslo</label>
                <input type="password" class="form-control" id="fpassword" name="fpassword" placeholder="Heslo"
                       required><br>

                <label for="fpassword2" class="custom-text-secondary">Heslo</label>
                <input type="password" class="form-control" id="fpassword2" name="fpassword2"
                       placeholder="Zopakujte Heslo" required
                       onchange="comparePw()"><br>
                <p id="ctext" class="text-warning py-0" style="font-size: small; display: none"> *zadaná hesla musí být
                    stejná </p>
            </div>
            <button type="submit" name="action" id="register" value="registration"
                    class="btn btn-light w-100 py-2 custom-btn-submit-long" disabled>
                Registrovat se
            </button>
        </form>
        <script>
            function comparePw() {
                var str1 = document.getElementById("fpassword").value;
                var str2 = document.getElementById("fpassword2").value;

                if (str1 === str2) {
                    document.getElementById("register").disabled = false;
                    document.getElementById("ctext").style.display = "none";
                } else {
                    document.getElementById("register").disabled = true;
                    document.getElementById("ctext").style.display = "block";
                }
            }
        </script>
        <?php
    }

    /**
     * Vypise, ze uzivatel je jiz prihlasen a nema cenu se prihlasovat
     */
    public function showAlreadyLogged()
    {
        ?>
        <h2 class="h1 custom-text-primary text-center py-5">Nemůžete se registrovat, když jste přihlášeni!</h2>
        <?php
    }

    /**
     * Vypise uspesnou registraci
     */
    public function showSuccessfulRegistration()
    {
        ?>
        <h2 class="h3 custom-text-primary text-center py-5">Děkujeme Vám za registraci! <br>
            Prosím zkontrolujte si svůj email a postupujte podle pokuný uvedených v emailu.</h2>
        <?php
    }

    /**
     * Vypise neuspesnou registraci
     */
    public function showRegistrationFailed()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Registrace selhala.</strong> Prosím zkuste to znovu později.
        </div>
        <?php
    }

    /**
     * Vypise neuspesnou registraci (duvod email je jiz zabran)
     */
    public function showEmailTaken()
    {
        ?>
        <div class="alert alert-danger text-center">
            <strong>Registrace selhala.</strong> Email je zabrán. Prosím použijte jiný email.
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
        if (!isset($tplData["registrationSuccessful"])) {
            // pravdepodobne nebyl proveden pokus o registraci
            if ($tplData["isLogged"]) {
                // uzivatel je prihlasen
                $pageContent->showAlreadyLogged();
            } else {
                // uzivatel neni prihlasen
                $pageContent->showRegistrationForm();
            }
        } else {
            // probehl pokus o registraci
            if ($tplData["registrationSuccessful"]) {
                $pageContent->showSuccessfulRegistration();
            } else {
                if (!$tplData["emailTaken"]) {
                    $pageContent->showRegistrationFailed();
                } else {
                    $pageContent->showEmailTaken();
                }
                $pageContent->showRegistrationForm();
            }
        }
        ?>
    </div>
    </body>
<?php
$pageTpl->getEnd();
