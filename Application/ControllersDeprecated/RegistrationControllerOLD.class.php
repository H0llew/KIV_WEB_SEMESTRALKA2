<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku s registraci (registration)
 *
 * @deprecated stare
 */
class RegistrationControllerOLD implements IController
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

        if (isset($_POST["action"]) && $_POST["action"] == "register") {
            $tplData["email_used"] = $this->checkIfEmailUsed();
            if ($tplData["email_used"])
                $tplData["registration"] = $this->checkIfRegister();
        }
        $tplData["isLogged"] = $this->userDB->isUserLoggedIn();

        ob_start();
        require(DIR_VIEWS . "/RegistrationTemplateOLD.tpl.php");

        unset($tplData["email_used"]);
        unset($tplData["registration"]);

        return ob_get_clean();
    }

    /**
     * Zkontroluje jestli zadany email jiz nepatri nejakemu uzivateli
     *
     * @return bool true -> pokud email neexistuje v db
     */
    public function checkIfEmailUsed(): bool
    {
        if (!(isset($_POST["femail"]))) {
            return false;
        }

        return !$this->userDB->emailExists($_POST["femail"]);
    }

    /**
     * Projde _POST a zjisti zda se chtel uzivatel prihlasit
     *
     * @return bool true -> pokud se povedlo prihlasit uzivatele
     */
    public function checkIfRegister(): bool
    {
        if (!(isset($_POST["femail"]) && isset($_POST["ff_name"])) && isset($_POST["fl_name"])
            && isset($_POST["fpassword"]) && isset($_POST["fpassword2"])) {
            return false;
        }

        return $this->userDB->registerNewUser($_POST["femail"], $_POST["fpassword"], $_POST["ff_name"], $_POST["fl_name"]);
    }
}