<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku se správou uživatele (user_management)
 */
class CreateNewArticleController implements IController
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
        $tplData["userFullName"] = "test";
        //$tplData["successfulUpload"] = true;

        ob_start();
        require(DIR_VIEWS . "/CreateNewArticleTemplate.tpl.php");
        return ob_get_clean();
    }
}