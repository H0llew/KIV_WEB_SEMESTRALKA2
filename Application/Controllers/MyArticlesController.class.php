<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku se clanky (my_articles)
 */
class MyArticlesController implements IController
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
            $tplData["isReviewer"] = $this->userModel->isUserReviewer();

            // test
            /*

            $tplData["notVerifiedArticles"] = array(
                0 => array(
                    "nazev" => "TEST NÁZEV",
                    "datum" => "TEST DATUM",
                    "status" => "TEST STATUS",
                    "abstrakt" => "TEST ABSTRAKT",
                    "userName" => "TEST USERNAME",
                    "soubor" => "TEST SOUBOR",
                )
            );

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

            */
            // test

            $this->checkPOST();

            $tplData["userArticles"] = $this->articleModel->getLoggedUserArticles();
            $tplData["userArticles"]["waiting"] = $this->assignAllArticlesStatus($tplData["userArticles"]["waiting"]);
            $tplData["userArticles"]["approved"] = $this->assignRevsToArticles($tplData["userArticles"]["approved"]);
            $tplData["userArticles"]["dissmised"] = $this->assignRevsToArticles($tplData["userArticles"]["dissmised"]);
        }
        ob_start();
        require(DIR_VIEWS . "/MyArticlesTemplate.tpl.php");
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

        if ($_POST["action"] == "edit")
            $tplData["edit"] = $this->checkIfEdit();

        if ($_POST["action"] == "delete")
            $tplData["delete"] = $this->checkIfDelete();

    }

    /**
     * Zkontroluje zda byl editovan prispevek
     *
     * @return bool true-> pokud se editace povedla
     */
    private function checkIfEdit()
    {
        if (!(isset($_POST["fdate"]) && isset($_POST["ffilePath"]) && $_POST["fheading"] && $_POST["fabstract"]))
            return false;

        //-
        $_POST["fdate"] = htmlspecialchars($_POST["fdate"]);
        $_POST["fheading"] = htmlspecialchars($_POST["fheading"]);
        $_POST["fabstract"] = htmlspecialchars($_POST["fabstract"]);
        //-

        $userID = $this->userModel->getUserID();
        if ($userID == -1)
            return false;

        if (!empty($_FILES["ffile"]["name"])) {
            if (!(move_uploaded_file($_FILES["ffile"]["tmp_name"], $_POST["ffilePath"])))
                return false; // soubor nebyl nahran pomoci POST metody
        }

        return $this->articleModel->updateSelectedArticle($_POST["fdate"], $_POST["ffilePath"], $_POST["fheading"], $_POST["fabstract"]);
    }

    /**
     * Zkontroluje zda byl zadan pozadavek pro odstraneni prispevku
     *
     * @return bool true->pokud byl prispevek uspesne zmazan
     */
    private function checkIfDelete()
    {
        if (!(isset($_POST["id"])))
            return false;

        return $this->articleModel->deleteArticle($_POST["id"]);
    }

    // utils

    /**
     * Priradi vsem clankum novy status a to zda maji recenzenta nebo ne
     *
     * @param $articles array clanky
     */
    private function assignAllArticlesStatus(array $articles)
    {
        for ($i = 0; $i < count($articles); $i++) {
            $articles[$i] = $this->assignArticleStatus($articles[$i]);
        }

        return $articles;
    }

    /**
     * Priradi prispevku novou hodnotu a to jeho status (1 = existuje recenzet; 0 neexistuje recenzet)
     *
     * @param $articleEntry
     * @return
     */
    private function assignArticleStatus($articleEntry)
    {
        $res = $this->articleModel->existArticleReview($articleEntry["id_clanek"]);
        if ($res)
            $articleEntry["status"] = "1";
        else
            $articleEntry["status"] = "0";

        return $articleEntry; // nebere jen jako referenci ?
    }

    /**
     * priradi clankum recenze
     */
    private function assignRevsToArticles(array $articles)
    {
        $res = [];
        foreach ($articles as $row) {

            $revs = $this->articleModel->getArticleReviews($row["id_clanek"]);
            $row["hodnoceni"] = $revs;
            array_push($res, $row);

        }
        return $res;
    }

}