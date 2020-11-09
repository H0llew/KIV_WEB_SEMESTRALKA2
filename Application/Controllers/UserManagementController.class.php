<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku se správou uživatele (user_management)
 */
class UserManagementController implements IController
{
    /** instance tabulky s uzivateli */
    private $userDB;
    /** instance tabulky clanku */
    private $articlesDB;

    private $revDB;

    public function __construct()
    {
        require_once(DIR_MODELS . "/UserModel.class.php");
        require_once(DIR_MODELS . "/ArticlesModel.class.php");
        require_once(DIR_MODELS . "/RevModel.class.php");
        $this->userDB = new UserModel();
        $this->articlesDB = new ArticlesModel();
        $this->revDB = new RevModel($this->articlesDB);
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
        if ($tplData["isLogged"]) {
            $userData = $this->userDB->getLoggedUserData();
            if ($userData != null)
                $tplData["userName"] = $userData["prijmeni"] . " " . $userData["jmeno"];
            else
                $tplData["userName"] = "";

            $this->assignUserArticles();

            $tplData["isAdmin"] = $this->userDB->isUserAdmin();
            $tplData["isReviewer"] = $this->userDB->isUserReviewer();
            if ($tplData["isReviewer"]) {
                $tplData["needRev"] = $this->revDB->getUserArticleForRev($this->userDB->getLoggedUserData()["id_uzivatel"]);
            }
        }
        //pro fce stranky musi byt uzivatel prihlasen
        if ($tplData["isLogged"]) {
            // posilam nejake data z formulare?
            if (isset($_POST["action"])) {
                $tplData["uploaded"] = $this->checkIfUpload();
                $tplData["edit"] = $this->checkIfEdit();
            }
            if (isset($_POST["revi"])) {
                $tplData["didRevi"] = $this->checkIfRevi();
            }
        }

        $this->articlesDB->deleteSelectedArticle();

        ob_start();
        require(DIR_VIEWS . "/UserManagementTemplate.tpl.php");
        return ob_get_clean();
    }

    private function assignUserArticles()
    {
        $userArticles = $this->articlesDB->getLoggedUserArticles();
        if ($userArticles == null)
            return;

        global $tplData;
        $tplData["approved"] = $userArticles["approved"];
        $tplData["not_approved"] = $userArticles["not_approved"];
    }

    /**
     * Zkouma zda byl zadan pozadavek na upload clanku na server
     *
     * @return bool
     */
    private function checkIfUpload()
    {
        if (!($_POST["action"] == "upload"))
            return false;

        if (!(isset($_POST["fabstract"]) && isset($_POST["fname"])))
            return false;

        $userID = $this->userDB->getLoggedUserData();
        if ($this->userDB == null)
            return false;

        $userID = $userID["id_uzivatel"];

        // presun slozku z tmp do adresare clanku
        //$uploadfile = $_SERVER["DOCUMENT_ROOT"] . ARTICLES_PATH . basename($_FILES["ffile"]["name"]);
        $date = date("Y/m/d H:i:s");
        $date2 = date("Y-m-d-H-i-s");
        $uploadfile = $_SERVER["DOCUMENT_ROOT"] . ARTICLES_PATH . $userID . "+" . $date2 . ".pdf";
        if (!(move_uploaded_file($_FILES["ffile"]["tmp_name"], $uploadfile)))
            return false; // soubor nebyl nahran pomoci POST metody

        // zkontroluj zda se jedna o pdf
        $mime = $_FILES["ffile"]["type"];
        $type = substr($mime, strlen($mime) - 3);
        if ($type != ALLOWED_FILE_TYPE)
            return false;

        // vloz clanke do db tabulky
        return $this->articlesDB->createNewArticle($uploadfile, $_POST["fheading"], $_POST["fabstract"], $date);
    }

    private function checkIfEdit()
    {
        if (!($_POST["action"] == "edit"))
            return false;

        $userID = $this->userDB->getLoggedUserData();
        if ($this->userDB == null)
            return false;

        $userID = $userID["id_uzivatel"];

        if (!empty($_FILES["ffile"]["name"])) {
            if (!(move_uploaded_file($_FILES["ffile"]["tmp_name"], $_POST["ffilePath"])))
                return false; // soubor nebyl nahran pomoci POST metody
        }

        return $this->articlesDB->updateSelectedArticle($_POST["fdate"], $_POST["ffilePath"], $_POST["fheading"], $_POST["fabstract"]);
    }

    private function checkIfRevi(int $id_uzivatel = 1)
    {
        $clanekID = $_POST["revi"];
        $hodnoceni = (int)$_POST["fhodnoceni"];
        $zprava = $_POST["fmes"];

        return $this->revDB->updateReview($id_uzivatel, $clanekID, $hodnoceni, $zprava);
    }

}