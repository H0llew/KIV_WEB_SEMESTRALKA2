<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni stranku (index)
 */
class AdminController implements IController
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

        $tplData["page"] = 1;

        $tplData["users"] = array(
            0 => array(
                "jmeno" => "jmeno",
                "prijmeni" => "prijmeni",
                "email" => "email",
                "role" => "role"
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
        require(DIR_VIEWS . "/AdminTemplate.tpl.php");
        return ob_get_clean();
    }
}