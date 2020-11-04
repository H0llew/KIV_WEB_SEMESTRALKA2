<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku s prihlasenim (login)
 */
class LoginController implements IController
{
    /** instance tabulky s uzivateli */
    private $userDB;

    public function __construct()
    {
        require_once(DIR_MODELS . "/UserModel.class.php");
        $this->userDB = new UserModel();
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

        if (isset($_POST["action"])) {
            // prihlaseni
            $tplData["login"] = $this->checkIfLogin();
        }
        // get protoze je to obsazeno v linku v PageTemplate (optimalni?)
        if (isset($_GET["action"])) {
            // odhlaseni
            $tplData["login"] = $this->checkIfLogout();
        }
        $tplData["isLogged"] = $this->userDB->isUserLoggedIn();

        ob_start();
        require(DIR_VIEWS . "/LoginTemplate.tpl.php");
        // vynulujeme login
        unset($tplData["login"]);
        return ob_get_clean();
    }

    /**
     * Projde _POST a zjisti zda se chtel uzivatel prihlasit
     *
     * @return bool true -> pokud se povedlo prihlasit uzivatele
     */
    public function checkIfLogin()
    {
        if (!($_POST["action"] == "login"))
            return false;

        if (!(isset($_POST["femail"]) && isset($_POST["fpassword"]))) {
            return false;
        }

        return $this->userDB->loginUser($_POST["femail"], $_POST["fpassword"]);
    }

    /**
     * Projde _POST a zjisti zda byl poslan pozadavek na odhlaseni
     *
     * @return bool true -> pokud se povedlo odhlaseni uzivatele
     */
    public function checkIfLogout()
    {
        if (!($_GET["action"] == "logout"))
            return false;

        if (!$this->userDB->isUserLoggedIn())
            return false;

        $this->userDB->logoutUser();
        return true;
    }
}