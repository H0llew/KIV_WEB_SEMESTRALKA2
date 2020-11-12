<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku s prihlasenim (login)
 */
class LoginController implements IController
{
    // fce s databazi pro prihlasovani uzivatele
    private $loginModel;
    // fce s databazi pro uzivatele
    private $userModel;

    public function __construct()
    {
        require_once(DIR_MODELS . "/LoginModel.class.php");
        $this->loginModel = new LoginModel();
        require_once(DIR_MODELS . "/UserModel.class.php");
        $this->userModel = new UserModel($this->loginModel->getPDO());
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

        $this->checkGET();
        $this->checkPOST();

        $tplData["isLogged"] = $this->loginModel->isUserLoggedIn();
        $tplData["isAdmin"] = $this->userModel->isUserAdmin();

        ob_start();
        require(DIR_VIEWS . "/LoginTemplate.tpl.php");
        return ob_get_clean();
    }

    /**
     * Zkontroluje _POST request
     */
    public function checkPOST()
    {
        global $tplData;

        // nebyla provedena akce _POST
        if (!isset($_POST["action"]))
            return;

        if ($_POST["action"] == "login") {
            $tplData["loginSuccessful"] = $this->checkIfLogin();
        }
    }

    /**
     * Zkontroluje _GET request
     */
    public function checkGET()
    {
        global $tplData;

        // nebyla provedena akce _POST
        if (!isset($_GET["action"]))
            return;

        if ($_GET["action"] == "logout") {
            $this->checkIfLogout();
        }
    }

    /**
     * Zkontroluje _POST pro akci prihlaseni
     *
     * @return bool true -> pokud se povedlo prihlaseni
     */
    public function checkIfLogin(): bool
    {
        if (!(isset($_POST["femail"]) && isset($_POST["fpassword"])))
            return false;

        return $this->loginModel->loginUser($_POST["femail"], $_POST["fpassword"]);
    }

    /**
     * Projde _POST a zjisti zda byl poslan pozadavek na odhlaseni
     *
     * @return bool true -> pokud se povedlo odhlaseni uzivatele
     */
    public function checkIfLogout(): bool
    {
        if (!$_GET["action"] == "logout")
            return false;

        if (!$this->loginModel->isUserLoggedIn())
            return false;

        $this->loginModel->logoutUser();
        return true;
    }
}