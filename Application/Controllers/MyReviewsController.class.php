<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku se clanky (my_articles)
 */
class MyReviewsController implements IController
{

    /**
     * Preda kod stranky ve stringu
     *
     * @param string $pageTitle titulek stranky
     * @return string kod stranky
     */
    public function show(string $pageTitle): string
    {
        global $tplData;
        $tplData = [];

        $tplData["isLogged"] = false;
        $tplData["isAdmin"] = false;

        $tplData["notVerifiedArticles"] = array(
            0 => array(
                "nazev" => "TEST NÁZEV",
                "datum" => "TEST DATUM",
                "status" => "TEST STATUS",
                "abstrakt" => "TEST ABSTRAKT",
                "userName" => "TEST USERNAME",
                "soubor" => "TEST SOUBOR",
            )
        );

        $tplData["dismissedArticles"] = array(
            0 => array(
                "nazev" => "TEST NÁZEV",
                "datum" => "TEST DATUM",
                "status" => "TEST STATUS",
                "abstrakt" => "TEST ABSTRAKT",
                "userName" => "TEST USERNAME",
                "soubor" => "TEST SOUBOR",

                "hodnoceni0" => array(
                    "autor" => "TEST AUTOR",
                    "krit1" => "KRIT 1",
                    "krit2" => "KRIT 2",
                    "krit3" => "KRIT 3",
                    "zprava" => "TEST ZPRAVA"
                ),
                "hodnoceni1" => array(
                    "autor" => "TEST AUTOR",
                    "krit1" => "KRIT 1",
                    "krit2" => "KRIT 2",
                    "krit3" => "KRIT 3",
                    "zprava" => "TEST ZPRAVA"
                ),
                "hodnoceni2" => array(
                    "autor" => "TEST AUTOR",
                    "krit1" => "KRIT 1",
                    "krit2" => "KRIT 2",
                    "krit3" => "KRIT 3",
                    "zprava" => "TEST ZPRAVA"
                )
            )
        );

        ob_start();
        require(DIR_VIEWS . "/MyReviewsTemplate.tpl.php");
        return ob_get_clean();
    }
}