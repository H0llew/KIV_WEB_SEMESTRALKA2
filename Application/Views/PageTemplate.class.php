<?php

/**
 * Zakladni sablon kazde stranky aplikace.
 * Sablona pouziva pro stylizaci bootstrap 4
 */
class PageTemplate
{
    // zakladni metody

    /**
     * Vytvori hlavicku ('head') HTML5 webove stranky
     *
     * @param string $title titulek stranky
     */
    public function getHead(string $title)
    {
        ?>
        <!doctype HTML>
        <html lang="cs">
        <head>
            <title> <?php echo $title ?> </title>
            <meta charset="UTF-8">

            <!-- mobile first -->
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- bootstrap 4 -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <!-- custom css -->
            <link rel="stylesheet" href="customStylesheet.css">
        </head>
        <?php
    }

    /**
     * Zakonci webovou stranku
     */
    public function getEnd()
    {
        ?>
        </html>
        <?php
    }

    // obecne rozsireni sablony

    /**
     * Vytvori navigacni listu webove stranky
     *
     * @param bool $isLogged je uzivatel prihlasen?
     * @param bool $isAdmin je uzivatel admin?
     */
    public function getNavbar(bool $isLogged = false, bool $isAdmin = false)
    {
        ?>
        <!-- navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
            <!-- kolapsujici navbar -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- logo -->
            <div class="order-0">
                <a class="navbar-brand py-0 my-0" href="index.php">
                    <img src="../Res/Logo.png" style="width: 70%;height: 70%" alt="">
                </a>
            </div>
            <!-- navbar -->
            <div class="navbar-collapse collapse order-2" id="collapsibleNavbar">
                <!-- hl. navbar -->
                <ul class="nav navbar-nav mx-auto">
                    <!-- uvod -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#uvod">Úvod</a>
                    </li>
                    <!-- o nas -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#o_nas">O nás</a>
                    </li>
                    <!-- sponzori -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#sponzori">Sponzoři</a>
                    </li>
                    <!-- terminy -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=terms">Termíny</a>
                    </li>
                    <!-- kontakt -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=contact">Kontakt</a>
                    </li>
                    <!-- clanky -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=articles">Články</a>
                    </li>
                </ul>
                <!-- uzivatele -->
                <ul class="nav navbar-nav">
                    <?php
                    if ($isLogged) {
                        if ($isAdmin) {
                            ?>
                            <!-- admin -->
                            <li class=" nav-item">
                                <a class="nav-link custom-btn-primary" href="index.php?page=admin">Admin</a>
                            </li>
                            <?php
                        }

                        ?>
                        <!-- sprava uzivatele -->
                        <li class="nav-item">
                            <a class="nav-link custom-btn-secondary" href="index.php?page=user_management">Správa
                                uživatele</a>
                        </li>
                        <!-- odhlasit se -->
                        <li class="nav-item">
                            <a class="nav-link custom-btn-primary" href="index.php?page=login&action=logout">Odhlásit
                                se</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <!-- prihlasit se -->
                        <li class="nav-item">
                            <a class="nav-link custom-btn-secondary" href="index.php?page=login">Přihlásit se</a>
                        </li>
                        <!-- registrovat se -->
                        <li class="nav-item">
                            <a class="nav-link custom-btn-primary" href="index.php?page=registration">Registrovat se</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </nav>
        <?php
    }

    /**
     * Vytvori paticku webove stranky
     */
    public function getFooter()
    {
        ?>
        <footer class="mt-auto">
            <div style="background-color: #083B66" class="py-3 d-flex justify-content-center">
                <span class="custom-light-text">&copy; 2020-<?php echo date("Y") ?> Vytvořil Martin Jakubašek</span>
            </div>
        </footer>
        <?php
    }

    // specialni akce

    /**
     * Pokud je specialni akce, bude zde
     */
    public function getSpecialEvent()
    {
        ?>
        <!-- specialni udalost -->
        <div class="jumbotron jumbotron-fluid py-2" style="background-color: black;margin-bottom: 0">
            <div class="container-fluid d-flex justify-content-center">
                <!-- text -->
                <a href="index.php?page=terms">
                    <div class="row border border-warning">
                        <div class="col-sm-4 text-warning px-0 mx-0" style="text-align: center">
                            <h3>COVID-19<br>INFO</h3>
                        </div>
                        <div class="col-8 m-auto p-0 text-light">
                            <p>Pro více informací týkající se omezení v souvislosti se současnou krizí klikněte ZDE</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <?php
    }
}