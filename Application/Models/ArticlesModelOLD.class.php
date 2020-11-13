<?php

require_once "DatabaseModel.class.php";
require_once "UserModelOLD.class.php";
require_once "SessionsModel.class.php";
require_once "RevModel.class.php";

/**
 * Obsahuje funkce pro praci s tabulkou obsahujici clanky uzivatelu
 *
 * @deprecated stare
 */
class ArticlesModelOLD extends DatabaseModel
{
    private $table_articles = TABLE_CLANEK;
    private $userDB;
    private $reviewDB;

    public function __construct()
    {
        parent::__construct();
        $this->userDB = new UserModelOLD();
        $this->reviewDB = new RevModel();
    }

    // public

    /**
     * Vytvori novy clanek v tabulke se clanky db. Clanek je vzdy prirazen prihlasenemu uzivateli a je vzdy neschvalen.
     *
     * @param string $filePath umisteni souboru na serveru
     * @param string $heading titulek clanku
     * @param string $abstract abstrak clanku
     * @param string $date datum vlozeni
     * @return bool true -> pokud se podarilo vlozit novy clanke to tabulky
     */
    /*
    public function createNewArticle(string $filePath, string $heading, string $abstract, string $date): bool
    {
        // je uzivatel prihlasen?
        if (!$this->userDB->isUserLoggedIn())
            return false;

        // zjisti id uzivatele
        $userID = $this->userDB->getLoggedUserData()["id_uzivatel"];
        if ($userID == null)
            return false;

        // existuje clanek na serveru ?
        if (!file_exists($filePath))
            return false;

        // vytvor clanek
        return $this->addNewArticle($userID, $filePath, $heading, $abstract, $date);
    }
    */

    /**
     * Vrati clanky prihlaseneho uzivatele
     *
     * @return array|null clanky uzivatele rozdelene na schvalene("approved") a neschvalene("not_approved") nebo null
     */
    /*
    public function getLoggedUserArticles()
    {
        // je uzivatel prihlasen?
        if (!$this->userDB->isUserLoggedIn())
            return null;

        // zjisti id uzivatele
        $userID = $this->userDB->getLoggedUserData()["id_uzivatel"];
        if ($userID == null)
            return null;

        // schvalene clanky
        $approvedUserArticles = $this->getUserArticles($userID, "schvalen = 1");
        // neschvalene clanky
        $notApprovedUserArticles = $this->getUserArticles($userID, "schvalen = 0");

        return array(
            "approved" => $approvedUserArticles,
            "not_approved" => $notApprovedUserArticles
        );
    }
    */

    /*
    public function updateSelectedArticle(string $date, string $filePath, string $heading, string $abstract)
    {
        if (!($this->userDB->isUserLoggedIn()))
            return false;

        // zjisti user id
        $userID = $this->userDB->getLoggedUserData()["id_uzivatel"];
        if ($userID == null)
            return false;

        $whereStatement = "datum='{$date}' AND soubor='{$filePath}'";
        $articleID = $this->getUserArticles($userID, $whereStatement)[0]["id_clanek"];

        // jen 1 vrati VZDY
        return $this->editArticle($articleID, $heading, $abstract);
    }
    */

    public function deleteSelectedArticle()
    {
        $this->deleteArticle(22);
    }

    /**
     * Prida novy clanek do tabulky databaze
     *
     * @param int $userID id uzivatele
     * @param string $filePath cesta ke clanku (v adresari serveru)
     * @param string $heading titulek clanku
     * @param string $abstract abstrakt clanku
     * @param string $date datum vlozeni clanku
     *
     * @return bool true -> pokud se podařilo přidat novy clanke
     */
    /*
    private function addNewArticle(int $userID, string $filePath, string $heading, string $abstract, string $date): bool
    {
        $insertStatement = "id_uzivatel, soubor, nazev, abstrakt, datum, schvalen";
        $insertValues = "{$userID}, '{$filePath}', '{$heading}', '{$abstract}', '{$date}', 0";

        return $this->insertIntoTable($this->table_articles, $insertStatement, $insertValues);
    }
    */

    /**
     * Vrati stranky zadaneho uzivatele nebo null pokud uzivatel neexistuje
     *
     * @param int $userID id uzivatele
     * @param string $whereStatement filtrovani podle
     * @param string $orderByStatement serazeni podle
     * @return array neprazdne pole pokud byly nalezen clakny jinak prazdne pole
     */
    /*
    private function getUserArticles(int $userID, string $whereStatement = "", string $orderByStatement = ""): array
    {
        $userWhereStatement = "id_uzivatel='{$userID}'";
        if ($whereStatement != "")
            $userWhereStatement .= " AND " . $whereStatement;

        return $this->selectFromTable($this->table_articles, $userWhereStatement, $orderByStatement);
    }
    */

    /**
     * Zmeni titulek a abstrakt clanku
     *
     * @param int $articleID id clanku
     * @param string $heading titulek
     * @param string $abstract abstrakt
     * @return bool true-> pokud se podarilo zmeni radek tabulky
     */
    /*
    private function editArticle(int $articleID, string $heading, string $abstract)
    {
        $updateStatementWithValues = "abstrakt='{$abstract}', nazev='{$heading}'";
        $whereStatement = "id_clanek='{$articleID}'";

        return $this->updateInTable($this->table_articles, $updateStatementWithValues, $whereStatement);
    }
    */

    private function deleteArticle(int $articleID)
    {
        return $this->deleteFromTable($this->table_articles, "id_clanek={$articleID}");
    }

    // PUBLIC 2.0 stejně budu předělávat... (ffs)
    public function getNotAssignedArticles()
    {
        //vsechny clanky ve stavu 0
        $articles = $this->selectFromTable($this->table_articles, "schvalen=0");
        if (empty($articles))
            return [];

        $res = [];
        foreach ($articles as $row) {
            if ($this->reviewDB->existReview($row["id_clanek"]))
                continue;
            array_push($res, $row);
        }

        return $res;
    }

    public function changeStatus(int $id, int $newStatus)
    {
        return $this->updateInTable($this->table_articles, "schvalen={$newStatus}", "id_clanek='{$id}'");
    }

    public function getDeniedAssignedArticles()
    {
        //vsechny clanky ve stavu 0
        $articles = $this->selectFromTable($this->table_articles, "schvalen=2");
        if (empty($articles))
            return [];

        $res = [];
        foreach ($articles as $row) {
            if ($this->reviewDB->existReview($row["id_clanek"]))
                continue;
            array_push($res, $row);
        }

        return $res;
    }

    public function getAssignedButNoValidArticles()
    {
        //vsechny clanky ve stavu 0
        $articles = $this->selectFromTable($this->table_articles, "schvalen=0");
        if (empty($articles))
            return [];

        $res = [];
        foreach ($articles as $row) {
            if ($this->reviewDB->existReview($row["id_clanek"]) && $this->reviewDB->existValidReview($row["id_clanek"]))
                continue;
            array_push($res, $row);
        }

        return $res;
    }

    public function getAssignedBAndValidArticles()
    {
        //vsechny clanky ve stavu 0
        $articles = $this->selectFromTable($this->table_articles, "schvalen=0");
        if (empty($articles))
            return [];

        $res = [];
        foreach ($articles as $row) {
            print_r($row);
            $boo = $this->reviewDB->existValidReview($row["id_clanek"]);
            if ($boo)
                echo "ANO";
            if ($this->reviewDB->existReview($row["id_clanek"]) && !$this->reviewDB->existValidReview($row["id_clanek"]))
                continue;
            array_push($res, $row);
        }

        return $res;
    }

    public function getArticlesForRev()
    {
        $userID = $this->userDB->getLoggedUserData()["id_uzivatel"];

    }
}