<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni stranku (index)
 */
class AdminController implements IController
{

    // fce s databazi pro prihlasovani uzivatele
    private $userModel;
    // fce s databazi pro prihlasovani uzivatele
    private $articleModel;

    public function __construct()
    {
        require_once(DIR_MODELS . "/UserModel.class.php");
        $this->userModel = new UserModel();
        require_once(DIR_MODELS . "/ArticleModel.class.php");
        $this->articleModel = new ArticleModel();
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

        $tplData["title"] = $pageTitle;

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
        if ($tplData["page"] == 1) {

            $tplData["waiting"] = $this->getWaitingArticles();
            $tplData["reviewers"] = $this->userModel->getAllUsers(5, "", 5);

            $tplData["needReview"] = $this->getNeedReviewsArticles();
            $tplData["approved"] = $this->getArticlesWRevs(1);
            $tplData["notApproved"] = $this->getArticlesWRevs(2);
        }

        ob_start();
        require(DIR_VIEWS . "/AdminTemplate.tpl.php");
        return ob_get_clean();
    }

    /**
     * Zkontroluje zobrazovanou stranku
     *
     * @return int|mixed
     */
    public function checkPage()
    {
        if (isset($_GET["view"]))
            return $_GET["view"];
        return 0;
    }

    /**
     * Zkontroluje POST
     */
    public function checkPOST()
    {
        global $tplData;

        if (!isset($_POST["action"]))
            return;

        if ($_POST["action"] == "deleteUser")
            $this->checkIfDelete();

        if ($_POST["action"] == "sort")
            $tplData["sort"] = $_POST["sort"];

        if ($_POST["action"] == "crole")
            $this->checkIfRoleChange();

        // 1

        if ($_POST["action"] == "assign")
            $this->checkIfAssign();

        if ($_POST["action"] == "approve")
            $this->checkIfApprove();

    }

    /**
     * POST request delete?
     *
     * @return false|void
     */
    public function checkIfDelete()
    {
        if (!isset($_POST["id"]))
            return false;

        return $this->userModel->deleteUser($_POST["id"]);
    }

    /**
     * POST request sort?
     *
     * @return mixed|null
     */
    public function checkIfSort()
    {
        if (!isset($_POST["sort"]))
            return null;

        return $_POST["sort"];
    }

    /**
     * POST request změna role?
     *
     * @return bool|null
     */
    public function checkIfRoleChange()
    {
        if (!isset($_POST["id"]) && !isset($_POST["frole"]))
            return null;

        return $this->userModel->changeUserRole($_POST["id"], $_POST["frole"]);
    }

    private function getWaitingArticles()
    {
        $articles = $this->articleModel->getAllArticles(0);

        $result = [];
        foreach ($articles as $row) {

            $has3Rev = $this->articleModel->exist3ArticleReview($row["id_clanek"]);
            if ($has3Rev)
                continue;

            array_push($result, $row);
        }

        return $result;
    }

    private function getNeedReviewsArticles()
    {
        $articles = $this->articleModel->getAllArticles(0);

        $result = [];
        foreach ($articles as $row) {

            $has3Rev = $this->articleModel->exist3ArticleReview($row["id_clanek"]);
            if (!$has3Rev)
                continue;

            if ($this->articleModel->existsAllValidReviews($row["id_clanek"]))
                $row["valid"] = true;
            else
                $row["valid"] = false;

            $revs = $this->articleModel->getArticleReviews($row["id_clanek"]);
            $count = 1;
            foreach ($revs as $rev) {
                $row["hodnoceni" . $count++] = $rev;
            }

            array_push($result, $row);
        }

        return $result;
    }

    private function getArticlesWRevs(int $status)
    {
        $articles = $this->articleModel->getAllArticles($status);

        $result = [];
        foreach ($articles as $row) {

            $has3Rev = $this->articleModel->exist3ArticleReview($row["id_clanek"]);
            if (!$has3Rev)
                continue;

            if (!$this->articleModel->existsAllValidReviews($row["id_clanek"]))
                return [];

            $revs = $this->articleModel->getArticleReviews($row["id_clanek"]);
            $count = 1;
            foreach ($revs as $rev) {
                $row["hodnoceni" . $count++] = $rev;
            }

            array_push($result, $row);
        }

        return $result;
    }

    private function checkIfAssign()
    {
        if (!(isset($_POST["rev1"]) && isset($_POST["rev2"]) && isset($_POST["rev3"]) && isset($_POST["id"])))
            return false;

        $res = $this->articleModel->createNewEmptyReview($_POST["rev1"], $_POST["id"]);
        if (!$res)
            return false;
        $res = $this->articleModel->createNewEmptyReview($_POST["rev2"], $_POST["id"]);
        if (!$res)
            return false;
        $res = $this->articleModel->createNewEmptyReview($_POST["rev3"], $_POST["id"]);
        if (!$res)
            return false;

        return true;
    }

    private function checkIfApprove()
    {
        if (!isset($_POST["id"]))
            return false;

        $this->articleModel->updateArticleStatus($_POST["id"], 1);
    }
}