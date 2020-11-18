<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku s registraci (registration)
 */
class RegistrationController implements IController
{
    // fce s databazi pro prihlasovani uzivatele
    private $loginModel;

    // fce s databazi pro prihlasovani uzivatele
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

        $tplData["isLogged"] = $this->loginModel->isUserLoggedIn(); //-
        $tplData["isAdmin"] = $this->userModel->isUserAdmin();

        $this->checkPOST();

        ob_start();
        require(DIR_VIEWS . "/RegistrationTemplate.tpl.php");
        return ob_get_clean();
    }

    // pro formulare

    /**
     * Zkontroluje _POST request
     */
    public function checkPOST()
    {
        global $tplData;

        // nebyla provedena akce _POST
        if (!isset($_POST["action"]))
            return;

        if ($_POST["action"] == "registration") {
            $tplData["emailTaken"] = $this->checkIfEmailUsed();
            // email je zabran
            if ($tplData["emailTaken"]) {
                $tplData["registrationSuccessful"] = false;
                return;
            }
            // email neni pouzivan
            $tplData["registrationSuccessful"] = $this->checkIfRegistration();
        }
    }

    /**
     * Zkontroluje _POST pro akci registrace
     *
     * @return bool true -> pokud se povedlo registrovat se
     */
    public function checkIfRegistration(): bool
    {
        // zkontroluj predavane parametry
        if (!(isset($_POST["femail"]) && isset($_POST["ff_name"]) && isset($_POST["fl_name"])
            && isset($_POST["fpassword"]) && isset($_POST["fpassword2"])))
            return false;

        //-
        $_POST["femail"] = htmlspecialchars($_POST["femail"]);
        $_POST["ff_name"] = htmlspecialchars($_POST["ff_name"]);
        $_POST["fl_name"] = htmlspecialchars($_POST["fl_name"]);
        //-

        return $this->loginModel->registerNewUser($_POST["femail"], $_POST["fpassword"], $_POST["ff_name"], $_POST["fl_name"]);
    }

    /**
     * Zkontroluje jestli zadany email je uz zabran
     *
     * @return bool true -> pokud je email zabran
     */
    public function checkIfEmailUsed(): bool
    {
        if (!(isset($_POST["femail"])))
            return false;

        return $this->loginModel->emailExists($_POST["femail"]);
    }
}