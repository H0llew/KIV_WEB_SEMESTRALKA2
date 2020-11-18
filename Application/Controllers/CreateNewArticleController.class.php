<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku se správou uživatele (user_management)
 */
class CreateNewArticleController implements IController
{

    // fce s databazi pro prihlasovani uzivatele
    private $userModel;
    // fce s dazabazi pro prispevky
    private $articleModel;

    public function __construct()
    {
        require_once(DIR_MODELS . "/UserModel.class.php");
        $this->userModel = new UserModel();
        require_once(DIR_MODELS . "/ArticleModel.class.php");
        $this->articleModel = new ArticleModel($this->userModel->getPDO());
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
        if ($tplData["isLogged"]) {
            $tplData["isBanned"] = $this->userModel->isUserBanned();
            $tplData["isAdmin"] = $this->userModel->isUserAdmin();
            //$tplData["successfulUpload"] = true;

            $tplData["userFullName"] = $this->getUserFullName($this->userModel->getLoggedUserData());
            $this->checkPOST();
        }
        ob_start();
        require(DIR_VIEWS . "/CreateNewArticleTemplate.tpl.php");
        return ob_get_clean();
    }

    /**
     * Zkontroluje POST
     */
    public function checkPOST()
    {
        global $tplData;

        if (!isset($_POST["action"]))
            return;

        if ($_POST["action"] == "upload")
            $tplData["successfulUpload"] = $this->checkIfUpload();
    }

    /**
     * Zkouma zda byl zadan pozadavek na upload clanku na server
     *
     * @return bool true-> pokud byl pozadavek uspesny
     */
    public function checkIfUpload()
    {
        if (!(isset($_POST["fabstract"]) && isset($_POST["fheading"])))
            return false;

        //-
        $_POST["fabstract"] = htmlspecialchars($_POST["fabstract"]);
        $_POST["fheading"] = htmlspecialchars($_POST["fheading"]);
        //-

        $res = $this->userModel->getUserID();
        if ($res == -1)
            return false;

        //presun slozku z tmp do adresare clanku
        //$uploadfile = $_SERVER["DOCUMENT_ROOT"] . ARTICLES_PATH . basename($_FILES["ffile"]["name"]);
        $date = date("Y/m/d H:i:s");
        $date2 = date("Y-m-d-H-i-s");
        $uploadfile = $_SERVER["DOCUMENT_ROOT"] . ARTICLES_PATH . "$res" . $this->userModel->getUserID() . "+" . $date2 . ".pdf";
        if (!(move_uploaded_file($_FILES["ffile"]["tmp_name"], $uploadfile)))
            return false; // soubor nebyl nahran pomoci POST metody

        // zkontroluj zda se jedna o pdf
        $mime = $_FILES["ffile"]["type"];
        $type = substr($mime, strlen($mime) - 3);
        if ($type != ALLOWED_FILE_TYPE)
            return false;

        // vloz clanke do db tabulky
        return $this->articleModel->createNewArticle($uploadfile, $_POST["fheading"], $_POST["fabstract"], $date);
    }

    /**
     * Ziska uzivatelovo jmeno
     *
     * @return string jmeno
     */
    private function getUserFullName($userData)
    {
        if (empty($userData))
            return "notLoggedIn";

        return $userData["prijmeni"] . " " . $userData["jmeno"];
    }
}