<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku s prihlasenim (login)
 */
class LoginController implements IController
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
        //$tplData["loginSuccessful"] = false;
        $tplData["emailTaken"] = false;

        ob_start();
        require(DIR_VIEWS . "/LoginTemplate.tpl.php");
        return ob_get_clean();
    }
}