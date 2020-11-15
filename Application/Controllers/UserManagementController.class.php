<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni strunku uzivatele
 */
class UserManagementController implements IController
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
        $tplData["isReviewer"] = $this->userModel->isUserReviewer();

        $this->checkPOST();

        $tplData["userData"] = $this->userModel->getLoggedUserData();

        ob_start();
        require(DIR_VIEWS . "/UserManagementTemplate.tpl.php");
        return ob_get_clean();
    }

    /**
     * Zjisti zda byl poslan pozadavek v POST
     */
    public function checkPOST()
    {
        global $tplData;

        if (!isset($_POST["action"]))
            return;

        if (($_POST["action"] == "update"))
            $tplData["updateSuc"] = $this->checkIfUpdate();
    }

    /**
     * Zjisti zda byl zaslan pozadavek pro zmenu udaju
     *
     * @return bool true -> pokud se podari aktualizovat udaje
     */
    public function checkIfUpdate()
    {
        if (!(isset($_POST["femail"]) && isset($_POST["fname"]) && isset($_POST["flname"]) && isset($_POST["fpassword"])))
            return false;


        if (!password_verify($_POST["fpassword"], $this->userModel->getLoggedUserData()["heslo"]))
            return false;

        return $this->userModel->updateUserData($_POST["femail"], $_POST["fname"], $_POST["flname"],
            (isset($_POST["npassword"]) && $_POST["npassword"] != "") ? $_POST["npassword"] : $_POST["fpassword"]);
    }
}