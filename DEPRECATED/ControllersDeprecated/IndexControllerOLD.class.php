<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni stranku (index)
 *
 * @deprecated stare
 */
class IndexControllerOLD implements IController
{
    /** instance tabulky s uzivateli */
    //private $userDB;

    public function __construct()
    {
        //require_once(DIR_MODELS . "/UserModel.class.php");
        //$this->userDB = new UserModel();
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
        // nazev
        $tplData["title"] = $pageTitle;
        // prihlaseni
       // $tplData["isLogged"] = $this->userDB->isUserLoggedIn();

        ob_start();
        require(DIR_VIEWS . "/IndexTemplateOLD.tpl.php");
        return ob_get_clean();
    }
}