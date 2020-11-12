<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni stranku (index)
 *
 * @deprecated
 */
class AdminControllerOLD implements IController
{
    /** instance tabulky s uzivateli */
    private $userDB;
    private $articleDB;
    private $reviewDB;

    public function __construct()
    {
        require_once(DIR_MODELS . "/UserModel.class.php");
        require_once(DIR_MODELS . "/ArticlesModel.class.php");
        require_once(DIR_MODELS . "/RevModel.class.php");
        $this->userDB = new UserModelOLD();
        $this->articleDB = new ArticlesModel();
        $this->reviewDB = new RevModel();
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

        $tplData["isAdmin"] = $this->userDB->isUserAdmin();
        $tplData["notAArticles"] = $this->articleDB->getNotAssignedArticles();
        $tplData["notReviewedArticles"] = $this->articleDB->getAssignedButNoValidArticles();
        $tplData["deniedArticles"] = []; //$this->articleDB->getDeniedAssignedArticles();
        $tplData["reviewedArticles"] = $this->articleDB->getAssignedBAndValidArticles();

        $tplData["reviewers"] = $this->userDB->getAllReviewers();

        //get uzivatele
        $users = $this->userDB->getAllUsers();
        if (!empty($users))
            $tplData["users"] = $users;

        $this->checkIfDeny();
        $this->checkIfAssign();

        ob_start();
        require(DIR_VIEWS . "/AdminTemplateOLD.tpl.php");
        return ob_get_clean();
    }

    public function checkIfDeny()
    {
        if (true)//!$_POST["deny"])
            return false;

        $this->articleDB->changeStatus($_POST["id"], 2);
    }

    public function checkIfAssign()
    {
        if (isset($_POST["assign"])) {
            $value = $_POST["assign"];
            $review = $_POST["frecenzent"];

            $this->reviewDB->createNewEmptyReview($_POST["frecenzent"], $_POST["assign"]);
        }
    }

}