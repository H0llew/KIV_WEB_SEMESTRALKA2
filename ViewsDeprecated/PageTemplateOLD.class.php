<?php

/**
 * Obsahuje zakladni sablonu kazde strany aplikace
 * Pro stylizaci je pouzit bootstrap 4
 *
 * @deprecated stare
 */
class PageTemplateOLD
{
    // BASIC

    /**
     * Vytvori hlavicku HTML5 stranky a zaroven vytvori zacatek tela stranky
     *
     * @param string $title titulek stranky
     */
    public function getTop(string $title)
    {
        ?>
        <!doctype HTML>
        <html lang="cs">
        <head>
            <title><?php echo $title ?></title>
            <meta charset="UTF-8">

            <!-- mobile first -->
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- bootstrap 4 -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </head>
        <body>
        <?php
    }

    /**
     * Vytvori zakonceni webove stranky
     */
    public function getBottom()
    {
        ?>
        </body>
        </html>
        <?php
    }

    // UTILS

    /**
     * Vytvori navigacni listu aplikace
     *
     * @param bool $isLoggedIn je uzivatel prihlasen?
     */
    public function getNavbar(bool $isLoggedIn = false)
    {
        ?>
        <!-- navbar -->
        <nav class="my-nav navbar navbar-expand-lg sticky-top bg-light navbar-light">
            <!-- logo -->
            <a class="navbar-brand py-0 my-0" href="index.php">
                <img src="../Media/Logo.png" style="width: 70%;height: 70%" alt="">
            </a>
            <!-- Kolapsujici navbar -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- navbar -->
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="nav navbar-nav flex-fill justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#uvod">Úvod</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#o_nas">O nás</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#sponzori">Sponzoři</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Termíny</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kontakt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=articles">Články</a>
                    </li>
                </ul>
                <!-- user management -->
                <ul class="nav navbar-nav">
                    <?php
                    if ($isLoggedIn) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=user_management"
                               style="background-color: #1761A0;color: papayawhip">Správa uživatele</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=login&action=logout"
                               style="background-color: #083B66;color: papayawhip">Odhlásit se</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=login"
                               style="background-color: #1761A0;color: papayawhip">Přihlášení</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=registration"
                               style="background-color: #083B66;color: papayawhip">Registrace</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </nav>
        <?php
    }
}
