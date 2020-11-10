<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku s registraci (registration)
 */
class RegistrationController implements IController
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
        $tplData["registrationSuccessful"] = true;

        ob_start();
        require(DIR_VIEWS . "/RegistrationTemplate.tpl.php");
        return ob_get_clean();
    }
}