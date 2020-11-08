<?php

require_once "DatabaseModel.class.php";

class RevModel extends DatabaseModel
{
    private $table_reviews = TABLE_RECENZE;
    private $userDB;

    public function __construct()
    {
        parent::__construct();
        $this->userDB = new UserModel();
    }

    // zjisti mi zda existuje recenze k clanku (v jakemkoliv stadiu)
    public function existReview(int $id_clanek)
    {
        $review = $this->selectFromTable(TABLE_RECENZE, "id_clanek={$id_clanek}");
        if (empty($review))
            return false;
        return true;
    }

    public function createNewEmptyReview(int $id_user, int $id_clanek)
    {
        $insertStatement = "id_uzivatel, id_clanek, hodnoceni, zprava";
        $insertValues = "{$id_user}, '{$id_clanek}', '-1', '-1'";

        return $this->insertIntoTable($this->table_reviews, $insertStatement, $insertValues);
    }
}

?>