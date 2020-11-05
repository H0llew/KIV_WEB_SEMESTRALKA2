<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku se správou uživatele (user_management)
 */
class UserManagementController implements IController
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
        // prihlaseni
        $tplData["isLogged"] = $this->userDB->isUserLoggedIn();
        //pro fce stranky musi byt uzivatel prihlasen
        if ($tplData["isLogged"]) {
            // posilam nejake data z formulare?
            if (isset($_POST["action"])) {
                $tplData["uploaded"] = $this->checkIfUpload();
            }
        }

        ob_start();
        require(DIR_VIEWS . "/UserManagementTemplate.tpl.php");
        return ob_get_clean();
    }

    private function checkIfUpload()
    {
        if (!($_POST["action"] == "upload"))
            return false;

        if (!(isset($_POST["fabstract"]) && isset($_POST["fname"])))
            return false;

        echo $_FILES["ffile"]["type"];

        $uploadfile = $_SERVER["DOCUMENT_ROOT"] . ARTICLES_PATH . basename($_FILES["ffile"]["name"]);
        if (!(move_uploaded_file($_FILES["ffile"]["tmp_name"], $uploadfile)))
            return false; // soubor nebyl nahran pomoci POST metody

        return true;
    }
}