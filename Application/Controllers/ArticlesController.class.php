<?php

require_once("IController.interface.php");

/**
 * Ovladac pro stranku se clanky (articles)
 */
class ArticlesController implements IController
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
        // nazev
        $tplData["title"] = $pageTitle;
        // prihlaseni
        $tplData["isLogged"] = $this->userModel->isUserLoggedIn();
        $tplData["isAdmin"] = $this->userModel->isUserAdmin();

        $tplData["articles"] = $this->getArticles();

        ob_start();
        require(DIR_VIEWS . "/ArticlesTemplate.tpl.php");
        return ob_get_clean();
    }

    private function getArticles()
    {
        $articles = $this->articleModel->getAllArticles(1);
        $res = [];
        foreach ($articles as $row) {
            $revs = $this->articleModel->getArticleReviews($row["id_clanek"]);
            $row["hodnoceni"] = $revs;

            array_push($res, $row);
        }

        return $res;
    }
}