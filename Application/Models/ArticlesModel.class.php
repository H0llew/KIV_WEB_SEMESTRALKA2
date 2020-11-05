<?php

require_once "DatabaseModel.class.php";
require_once "UserModel.class.php";
require_once "SessionsModel.class.php";

/**
 * Obsahuje funkce pro praci s tabulkou obsahujici clanky uzivatelu
 */
class ArticlesModel extends DatabaseModel
{
    private $table_articles = TABLE_CLANEK;
    private $userDB;

    public function __construct()
    {
        parent::__construct();
        $this->userDB = new UserModel();
    }
}