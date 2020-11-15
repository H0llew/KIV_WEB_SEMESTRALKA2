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
     * Vypise kontaktni formular
     */
    public function getContactForm()
    {
        ?>
        <h2 class="text-center py-5 custom-text-primary h1" style="color: #083B66">Kontaktujte nás</h2>
        <!-- formular -->
        <form>
            <div class="form-group">
                <label for="femail" class="custom-text-secondary">Email</label>
                <input type="email" class="form-control" id="femail" aria-describedby="emailHelp"
                       placeholder="Zadejte email">
                <small id="emailHelp" class="form-text text-muted">Zadejte email, na který Vám máme odpovědět.</small>
            </div>
            <div class="form-group">
                <label for="fsubject" class="custom-text-secondary">Předmět</label>
                <input type="text" class="form-control" id="fsubject" name="fsubject" placeholder="Zadejte předmět"
                       required><br>
            </div>
            <div class="form-group">
                <label for="ftext" class="custom-text-secondary">Zpráva</label>
                <textarea class="form-control" id="ftext" name="ftext" placeholder="Zadejte zprávu"
                          rows="10" required></textarea>
            </div>
            <button type="button" class="btn btn-light w-100 py-2 custom-btn-submit-long" data-toggle="popover"
                    title="Upozornění!" data-content="Tato funkce není momentálně k dispozici">Odeslat
            </button>
            <script>
                $(document).ready(function () {
                    $('[data-toggle="popover"]').popover();
                });
            </script>
        </form>
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
        $pageContent->getContactForm();
        ?>
    </div>
    <?php
    $pageTpl->getFooter();
    ?>
    </body>
<?php
$pageTpl->getEnd();