<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku s registraci (registration)
 */
class RegistrationController implements IController
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
        if (isset($_POST["action"])) {
            echo "aga";
            $this->checkIfRegister();
        }

        ob_start();
        require(DIR_VIEWS . "/RegistrationTemplate.tpl.php");
        return ob_get_clean();
    }

    /**
     * Projde _POST a zjisti zda se chtel uzivatel prihlasit
     *
     * @return bool true -> pokud se povedlo prihlasit uzivatele
     */
    public function checkIfRegister()
    {
        if (!($_POST["action"] == "register")) {
            return false;
        }

        /*
        if (!(isset($_POST["femail"]) && isset($_POST["fpassword"]))) {
            return false;
        }
        */
        echo "Ahoh";
        return $this->userDB->registerNewUser($_POST["femail"], $_POST["fpassword"],$_POST["ff_name"], $_POST["fl_name"]);
    }
}