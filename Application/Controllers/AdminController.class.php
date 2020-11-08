<?php

require_once("IController.interface.php");

/**
 * Ovladac pro uvodni stranku (index)
 */
class AdminController implements IController
{
    /** instance tabulky s uzivateli */
    private $userDB;
    private $articleDB;

    public function __construct()
    {
        require_once(DIR_MODELS . "/UserModel.class.php");
        require_once(DIR_MODELS . "/ArticlesModel.class.php");
        $this->userDB = new UserModel();
        $this->articleDB = new ArticlesModel();
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
        $tplData["deniedArticles"] = $this->articleDB->getDeniedAssignedArticles();

        $tplData["reviewers"] = $this->userDB->getAllReviewers();
        print_r($tplData["reviewers"]);

        //get uzivatele
        $users = $this->userDB->getAllUsers();
        if (!empty($users))
            $tplData["users"] = $users;

        $this->checkIfDeny();

        ob_start();
        require(DIR_VIEWS . "/AdminTemplate.tpl.php");
        return ob_get_clean();
    }

    public function checkIfDeny()
    {
        if (!$_POST["deny"])
            return false;

        echo $_POST["id"];
        $this->articleDB->changeStatus($_POST["id"], 2);
    }

}