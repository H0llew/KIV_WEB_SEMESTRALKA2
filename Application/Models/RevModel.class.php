<?php

require_once "DatabaseModel.class.php";
require_once "UserModelOLD.class.php";
require_once "ArticlesModel.class.php";

class RevModel extends DatabaseModel
{
    private $table_reviews = TABLE_RECENZE;
    private $userDB;
    private $articleDB;

    public function __construct($articleDB = null)
    {
        parent::__construct();
        $this->userDB = new UserModelOLD();
        $this->articleDB = $articleDB;
    }

    // zjisti mi zda existuje recenze k clanku (v jakemkoliv stadiu)
    public function existReview(int $id_clanek)
    {
        $review = $this->selectFromTable(TABLE_RECENZE, "id_clanek={$id_clanek}");
        if (empty($review))
            return false;
        return true;
    }

    public function existValidReview(int $id_clanek)
    {
        $review = $this->selectFromTable(TABLE_RECENZE, "id_clanek={$id_clanek}")[0];
        if ((intval($review["hodnoceni"]) != -1)) {
            return true;
        }

        return false;
    }

    public function createNewEmptyReview(int $id_user, int $id_clanek)
    {
        $insertStatement = "id_uzivatel, id_clanek, hodnoceni, zprava";
        $insertValues = "{$id_user}, '{$id_clanek}', '-1', '-1'";

        return $this->insertIntoTable($this->table_reviews, $insertStatement, $insertValues);
    }

    public function updateReview(int $id_user, int $id_clanek, int $hodnoceni, string $zprava)
    {
        $updateStatementWithValues = "hodnoceni='{$hodnoceni}', zprava='{$zprava}'";
        $whereStatement = "id_clanek='{$id_clanek}'";

        return $this->updateInTable($this->table_reviews, $updateStatementWithValues, $whereStatement);
    }

    public function getUserArticleForRev(int $id_user)
    {
        $revs = $this->selectFromTable($this->table_reviews, "id_uzivatel= {$id_user} AND hodnoceni= '-1'");
        $article = [];
        foreach ($revs as $row) {
            $recenzeID = $row["id_clanek"];
            $a = $this->articleDB->selectFromTable(TABLE_CLANEK, "id_clanek={$recenzeID}")[0];
            array_push($article, $a);
        }
        return $article;
    }
}

?>