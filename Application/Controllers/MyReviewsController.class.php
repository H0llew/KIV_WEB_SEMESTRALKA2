<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku se clanky (my_articles)
 */
class MyReviewsController implements IController
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

        $tplData["title"] = $pageTitle;

        $tplData["isLogged"] = $this->userModel->isUserLoggedIn();
        $tplData["isAdmin"] = $this->userModel->isUserAdmin();

        $this->checkPOST();

        $tplData["articles"] = $this->articleModel->getLoggedUserReviews();
        $this->getUserReviews();

        $tplData["nonValid"] = $this->getUserReviews();
        $tplData["valid"] = $this->getUserDoneReviews();

        ob_start();
        require(DIR_VIEWS . "/MyReviewsTemplate.tpl.php");
        return ob_get_clean();
    }

    private function getUserReviews()
    {
        $reviews = $this->articleModel->getLoggedUserReviews();

        $resNonValid = [];
        foreach ($reviews["nonValid"] as $row) {
            $article = $this->articleModel->getArticle($row["id_clanek"]);

            $row["article"] = $article[0];
            array_push($resNonValid, $row);
        }

        return $resNonValid;
    }

    private function getUserDoneReviews()
    {
        $reviews = $this->articleModel->getLoggedUserReviews();

        $resNonValid = [];
        foreach ($reviews["valid"] as $row) {
            $article = $this->articleModel->getArticle($row["id_clanek"]);

            $row["article"] = $article[0];
            $row["hodnoceni"] = $this->articleModel->getArticleReviews($row["article"]["id_clanek"]);
            array_push($resNonValid, $row);
        }

        return $resNonValid;
    }

    private function checkPOST()
    {
        if (!isset($_POST["action"]))
            return;

        if ($_POST["action"] = "review")
            $this->checkIfReview();
    }

    private function checkIfReview()
    {
        if (!(isset($_POST["fh1"]) && isset($_POST["fh2"]) && isset($_POST["fh3"]) && isset($_POST["fabstract"]) && isset($_POST["id"])))
            return false;

        return $this->articleModel->updateReview($_POST["id"], $_POST["fh1"], $_POST["fh2"], $_POST["fh3"], $_POST["fabstract"]);
    }
}