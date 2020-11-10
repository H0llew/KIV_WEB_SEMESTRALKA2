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
     * Vypise firmu a jeji cinnost
     */
    public function getIntroduction()
    {
        ?>
        <!-- uvod -->
        <article id="uvod">
            <!-- kdo a co -->
            <div class="card text-light custom-text-secondary" style="background-color: #083B66">
                <div class="card-body">

                    <h1 class="card-title h2"><span class="h1">FINANCLY</span><span class="custom-light-text"> - pořadatel konferencí o bankovnictví a
                    investicích</span></h1>
                    <hr>

                    <h2 class="card-subtitle text-right custom-light-text">Nabízíme pravidelné konference o investicích
                        a
                        bankovnictví, školení v této oblasti a také online
                        databázi článků zabývající se touto problematikou</h2>

                    <div class="row" style="padding-top: 15rem">
                        <div class="col-md-4">
                            <h4 class="card-subtitle"><span>KONFERENCE</span></h4>
                            <p class="card-text custom-light-text py-2">Nabízíme pravidelné konference o bankovnictví a
                                investicích po celé České
                                republice</p>
                        </div>
                        <div class="col-md-4">
                            <h4 class="card-subtitle"><span>ŠKOLENÍ</span></h4>
                            <p class="card-text custom-light-text py-2">Nabízíme školení zaměstnanců v bankovnictví a ve
                                finanční
                                správě</p>
                        </div>
                        <div class="col-md-4">
                            <h4 class="card-subtitle"><span>ČLÁNKY</span></h4>
                            <p class="card-text custom-light-text py-2">Nabízíme široký databázi článků týkající se této
                                tématiky</p>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <?php
    }

    /**
     * Vypise nedavne cinnosti firmy
     */
    public function getActions()
    {
        ?>
        <div id="ca" class="carousel slide" data-ride="carousel">
            <ul class="carousel-indicators">
                <li data-target="#ca" data-slide-to="0" class="active"></li>
                <li data-target="#ca" data-slide-to="1"></li>
                <li data-target="#ca" data-slide-to="2"></li>
                <li data-target="#ca" data-slide-to="3"></li>
            </ul>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../Res/Media/1.jpg" alt="akce1">
                    <p class="carousel-caption custom-btn-primary">Vánoční investiční konference 2019 v Praze</p>
                </div>
                <div class="carousel-item">
                    <img src="../Res/Media/2.jpg" alt="akce2">
                    <p class="carousel-caption custom-btn-primary">Brno fest - bankovní konference 2020 v Brně</p>
                </div>
                <div class="carousel-item">
                    <img src="../Res/Media/3.jpg" alt="akce3">
                    <p class="carousel-caption custom-btn-primary">Školení Eagle Bank 2020</p>
                </div>
                <div class="carousel-item">
                    <img src="../Res/Media/4.jpg" alt="akce4">
                    <p class="carousel-caption custom-btn-primary">Školení firmy Slaný 2020</p>
                </div>
            </div>

            <a class="carousel-control-prev" href="#ca" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#ca" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
        <?php
    }

    /**
     * Vypise o firme
     */
    public function getAboutUs()
    {
        ?>
        <!-- o nas -->
        <article id="o_nas">
            <div class="card">
                <!-- kdo jsme -->
                <div class="card-body custom-text-primary">
                    <h2 class="card-title">O Nás</h2>
                    <p class="card-text custom-text-secondary">Jsme organizace zabývající se pořádáním konferencí o
                        bankovnictví a
                        investicích,
                        proškolíme také Vaše zaměstnance v této problematice. Provozujeme
                        online semináře a poskytujeme online databázi článků,
                        které jsou ověřené našimi recenzenty.
                    </p>
                    <p class="card-text custom-text-secondary">Díky nám porozumíte těmto tématům a usnadní Vám to lepší
                        orientaci,
                        jak v profesionální, tak i v soukromé sféře. V
                        České republice působíme od roku 2006.
                    </p>
                </div>
                <!-- nas team -->
                <div class="card custom-text-primary">
                    <div class="card-body">
                        <h2 class="card-title">Náš tým</h2>
                        <div class="row custom-text-secondary" style="font-size: 90%">
                            <div class="card col-md border-0">
                                <img class="card-img-top" src="../Res/Media/Lide/4.jpg" alt="">
                                <p class="card-text text-center">Dr. Ing. Šimon Rybníček, CSc.<br>Ředitel/Lektor</p>
                            </div>
                            <div class="card col-md border-0">
                                <img class="card-img-top" src="../Res/Media/Lide/5.jpg" alt="">
                                <p class="card-text text-center">Ing. Svatopluk Maršík, Ph.D.<br>Lektor</p>
                            </div>
                            <div class="card col-md border-0">
                                <img class="card-img-top" src="../Res/Media/Lide/3.jpg" alt="">
                                <p class="card-text text-center">Ing. Lubor Šíp, Ph.D.<br>Lektor</p>
                            </div>
                            <div class="card col-md border-0">
                                <img class="card-img-top" src="../Res/Media/Lide/1.jpg" alt="">
                                <p class="card-text text-center">Mgr. Romana Nová, Ph.D.<br>Lektorka</p>
                            </div>
                            <div class="card col-md border-0">
                                <img class="card-img-top" src="../Res/Media/Lide/2.jpg" alt="">
                                <p class="card-text text-center">Ing. Vladimír Strejc, Ph.D.<br>Lektor</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <?php
    }

    /**
     * Vypise sponzory firmy
     */
    public function getSponsors()
    {
        ?>
        <!-- sponzori -->
        <article id="sponzori">
            <div class="card">
                <div class="card-body">
                    <h2 class="custom-text-primary">SPONZOŘI</h2>
                    <div class="row">
                        <div class="card border-0 col-md"><img src="../Res/Media/Sponzori/1.png" alt=""></div>
                        <div class="card border-0 col-md"><img src="../Res/Media/Sponzori/2.png" alt=""></div>
                        <div class="card border-0 col-md"><img src="../Res/Media/Sponzori/3.png" alt=""></div>
                    </div>
                </div>
            </div>
        </article>
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
        $pageContent->getIntroduction();
        ?>
        <hr><?php
        $pageContent->getActions();
        ?>
        <hr><?php
        $pageContent->getAboutUs();
        ?>
        <hr><?php
        $pageContent->getSponsors();
        ?>
    </div>
    </body>
<?php
$pageTpl->getEnd();