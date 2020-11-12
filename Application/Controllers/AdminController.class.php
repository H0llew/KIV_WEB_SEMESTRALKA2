<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni stranku (index)
 */
class AdminController implements IController
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

        $tplData["isLogged"] = $this->userModel->isUserLoggedIn();
        $tplData["isAdmin"] = $this->userModel->isUserAdmin();

        $this->checkPOST();

        $tplData["page"] = $this->checkPage();
        if ($tplData["page"] == 0) {
            $tplData["userWeight"] = $this->userModel->getUserRoleWeight();
            if (!isset($tplData["sort"]))
                $tplData["sort"] = "vaha";
            $tplData["users"] = $this->userModel->getAllUsers(0, $tplData["sort"]);
            $tplData["itemsPerPage"] = 1;
            $tplData["pages"] = count($tplData["users"]) % $tplData["itemsPerPage"];
        }

        /**
         * $tplData["users"] = array(
         * 0 => array(
         * "jmeno" => "jmeno",
         * "prijmeni" => "prijmeni",
         * "email" => "email",
         * "role" => "role"
         * )
         * );
         */
        $tplData["dismissedArticles"] = array(
            0 => array(
                "nazev" => "TEST NÁZEV",
                "datum" => "TEST DATUM",
                "status" => "TEST STATUS",
                "abstrakt" => "TEST ABSTRAKT",
                "userName" => "TEST USERNAME",
                "soubor" => "TEST SOUBOR",

                "hodnoceni0" => array(
                    "autor" => "TEST AUTOR",
                    "krit1" => "KRIT 1",
                    "krit2" => "KRIT 2",
                    "krit3" => "KRIT 3",
                    "zprava" => "TEST ZPRAVA"
                ),
                "hodnoceni1" => array(
                    "autor" => "TEST AUTOR",
                    "krit1" => "KRIT 1",
                    "krit2" => "KRIT 2",
                    "krit3" => "KRIT 3",
                    "zprava" => "TEST ZPRAVA"
                ),
                "hodnoceni2" => array(
                    "autor" => "TEST AUTOR",
                    "krit1" => "KRIT 1",
                    "krit2" => "KRIT 2",
                    "krit3" => "KRIT 3",
                    "zprava" => "TEST ZPRAVA"
                )
            )
        );

        ob_start();
        require(DIR_VIEWS . "/AdminTemplate.tpl.php");
        return ob_get_clean();
    }

    public function checkPage()
    {
        if (isset($_GET["view"]))
            return $_GET["view"];
        return 0;
    }

    public function checkPOST()
    {
        global $tplData;

        if (!isset($_POST["action"]))
            return;

        if ($_POST["action"] == "deleteUser")
            $this->checkIfDelete();

        if ($_POST["action"] == "sort")
            $tplData["sort"] = $_POST["sort"];
    }

    public function checkIfDelete()
    {
        if (!isset($_POST["id"]))
            return false;

        return $this->userModel->deleteUser($_POST["id"]);
    }

    public function checkIfSort()
    {
        if (!isset($_POST["sort"]))
            return null;

        return $_POST["sort"];
    }
}