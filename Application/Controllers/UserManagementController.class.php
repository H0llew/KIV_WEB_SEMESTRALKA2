<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni stranku (index)
 */
class UserManagementController implements IController
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
        require(DIR_VIEWS . "/UserManagementTemplate.tpl.php");
        return ob_get_clean();
    }
}