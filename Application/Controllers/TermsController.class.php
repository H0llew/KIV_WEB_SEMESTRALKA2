<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku s terminy (terms)
 */
class TermsController implements IController
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

        ob_start();
        require(DIR_VIEWS . "/TermsTemplate.tpl.php");
        return ob_get_clean();
    }
}