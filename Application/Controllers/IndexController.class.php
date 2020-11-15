<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni stranku (index)
 */
class IndexController implements IController
{
    // fce s databazi pro prihlasovani uzivatele
    private $userModel;

    public function __construct()
    {
        require_once(DIR_MODELS . "/UserModel.class.php");
        $this->userModel = new UserModel();
    }

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

        $tplData["title"] = $pageTitle;

        $tplData["isLogged"] = $this->userModel->isUserLoggedIn();
        $tplData["isAdmin"] = $this->userModel->isUserAdmin();

        ob_start();
        require(DIR_VIEWS . "/IndexTemplate.tpl.php");
        return ob_get_clean();
    }
}