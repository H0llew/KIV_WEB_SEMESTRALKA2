<?php

//data
global $tplData;

require_once("PageTemplateOLD.class.php");

$tmp = new PageTemplateOLD();
// zacatek stranky
$tmp->getTop($tplData["title"]);
$tmp->getNavbar(isset($tplData["isLogged"]) ? $tplData["isLogged"] : false);
?>
    <!-- Obsah stranky -->
    <div class="container">
        <?php
        if ((!isset($tplData["login"]) && (isset($tplData["isLogged"])) && $tplData["isLogged"]) ||
            ((isset($tplData["isLogged"])) && $tplData["isLogged"]))
        {
        ?><h3>Jste přihlášeni</h3><?php
        }
        else {
            ?>
        <!-- upozorneni -->
            <?php
            if (isset($tplData["login"]) && !$tplData["login"]) {
            ?>
                <div class="alert alert-danger text-center">
                    <strong>Přihlášení selhalo.</strong> Ověřte prosím vyplněný email a vyplněné heslo.
                </div>
            <?php
            }
            ?>
        <!-- login -->
        <h1 class="text-center py-5" style="color: #083B66">PŘIHLÁSIT SE</h1>

        <form action="" method="POST">
            <div class="form-group">
                <label for="femail" style="color: #1761A0">E-mail</label>
                <input type="email" class="form-control" id="femail" name="femail" placeholder="Zadejte E-mail"><br>

                <label for="fpassword" style="color: #1761A0">Předmět</label>
                <input type="password" class="form-control" id="fpassword" name="fpassword" placeholder="Heslo"><br>

                <button type="button" class="btn py-1" style="color: #1761A0">Zapomněl jsem heslo</button>
            </div>
            <button type="submit" name="action" value="login" class="btn btn-light w-100 py-2"
                    style="margin-bottom: 5rem">Odeslat
            </button>
        </form>
    </div>
<?php
        }

$tmp->getBottom();
