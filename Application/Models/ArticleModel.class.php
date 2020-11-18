<?php

require_once "DatabaseModel.class.php";
require_once "UserModel.class.php";
require_once "SessionsModel.class.php";

/**
 * Obsahuje fce pro databazove operace tykajici se uzivatele
 */
class ArticleModel extends DatabaseModel
{
    private $session;

    private $userModel;

    public function __construct(PDO $pdo = null)
    {
        $conn = $pdo;
        if ($pdo == null)
            $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        parent::__construct($conn);

        $this->userModel = new UserModel($conn);

        $this->session = new SessionsModel();
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
    public function createNewArticle(string $filePath, string $heading, string $abstract, string $date): bool
    {
        // je uzivatel prihlasen?
        if (!$this->userModel->isUserLoggedIn())
            return false;

        // zjisti id uzivatele
        $userID = $this->userModel->getUserID();
        if ($userID == -1)
            return false;

        // existuje clanek na serveru ?
        if (!file_exists($filePath))
            return false;

        // vytvor clanek
        return $this->addNewArticle($userID, $filePath, $heading, $abstract, $date);
    }

    /**
     * Vrati clanky prihlaseneho uzivatele
     *
     * @return array|null clanky uzivatele rozdelene na schvalene("approved") a neschvalene("not_approved") nebo null
     */
    public function getLoggedUserArticles()
    {
        // je uzivatel prihlasen?
        if (!$this->userModel->isUserLoggedIn())
            return [];

        // zjisti id uzivatele
        $userID = $this->userModel->getUserID();
        if ($userID == -1)
            return [];

        // schvalene clanky
        //$approvedUserArticles = $this->getUserArticles($userID, "schvalen = 1");
        // neschvalene clanky
        //$waitingForApprovalArticles = $this->getUserArticles($userID, "schvalen = 0");
        // zamitnute clanky
        //$dismissedArticles = $this->getUserArticles($userID, "schvalen = 2");

        // schvalene clanky
        $approvedUserArticles = $this->getUserArticles($userID, array(1), "schvalen=?");
        // neschvalene clanky
        $waitingForApprovalArticles = $this->getUserArticles($userID, array(0), "schvalen=?");
        // zamitnute clanky
        $dismissedArticles = $this->getUserArticles($userID, array(2), "schvalen=?");

        return array(
            "approved" => $approvedUserArticles,
            "waiting" => $waitingForApprovalArticles,
            "dissmised" => $dismissedArticles
        );
    }

    /**
     * Existuje recenze prispevku?
     *
     * @param int $id_clanek id clanku
     * @return bool true-> pokud existuje recenze pro prispevek
     */
    public function existArticleReview(int $id_clanek)
    {
        //$review = $this->selectFromTable(TABLE_RECENZE, "id_clanek={$id_clanek}");
        $review = $this->selectFromTable(TABLE_RECENZE, array($id_clanek), "id_clanek=?");
        if (empty($review))
            return false;
        return true;
    }

    /**
     * Existuji 3 recenzenti na prispevek?
     *
     * @param int $id_clanek id clanku
     * @return bool true-> pokud ano
     */
    public function exist3ArticleReview(int $id_clanek)
    {
        //$review = $this->selectFromTable(TABLE_RECENZE, "id_clanek={$id_clanek}");
        $review = $this->selectFromTable(TABLE_RECENZE, array($id_clanek), "id_clanek=?");
        if (empty($review))
            return false;
        if (count($review) < 3)
            return false;
        return true;
    }

    /**
     * Aktualizuje udaje prispevku v databazi
     *
     * @param string $date datum
     * @param string $filePath cesta noveho souboru
     * @param string $heading titulek
     * @param string $abstract abstrakt
     * @return bool true -> pokud se aktualizace udaju povedla
     */
    public function updateSelectedArticle(string $date, string $filePath, string $heading, string $abstract)
    {
        if (!($this->userModel->isUserLoggedIn()))
            return false;

        // zjisti user id
        $userID = $this->userModel->getUserID();
        if ($userID == -1)
            return false;

        //$whereStatement = "datum='{$date}' AND soubor='{$filePath}'";
        //$articleID = $this->getUserArticles($userID, $whereStatement)[0]["id_clanek"];

        $whereStatement = "datum=? AND soubor=?";
        $articleID = $this->getUserArticles($userID, array($date, $filePath), $whereStatement)[0]["id_clanek"];

        // jen 1 vrati VZDY
        return $this->editArticle($articleID, $heading, $abstract);
    }

    /**
     * Vrati clanek podle id
     *
     * @param int $id_clanek id clanku
     * @return array clanek
     */
    public function getArticle(int $id_clanek)
    {
        //return $this->selectFromTable(TABLE_CLANEK . ", " . TABLE_UZIVATEL, "id_clanek={$id_clanek} AND " . TABLE_CLANEK . ".id_uzivatel=" . TABLE_UZIVATEL . ".id_uzivatel");
        return $this->selectFromTable(TABLE_CLANEK . ", " . TABLE_UZIVATEL, array($id_clanek), "id_clanek=? AND " . TABLE_CLANEK . ".id_uzivatel=" . TABLE_UZIVATEL . ".id_uzivatel");
    }

    /**
     * Zmeni prvky prispevku v databazi
     *
     * @param int $id_clanek id clanku
     * @param int $status status
     * @return bool true-> zmena se podarila
     */
    public function updateArticleStatus(int $id_clanek, int $status)
    {
        //return $this->updateInTable(TABLE_CLANEK, "schvalen={$status}", "id_clanek={$id_clanek}");
        return $this->updateInTable(TABLE_CLANEK, "schvalen=?", "id_clanek=?", array($status, $id_clanek));
    }

    /**
     * Vymaze prispevek z db
     *
     * @param int $id_clanek id prispevku
     * @return bool true->pokuid se podarilo prispevek vymatat
     */
    public function deleteArticle(int $id_clanek)
    {
        $revs = $this->getArticleReviews($id_clanek);
        if (!empty($revs))
            return false;

        //return $this->deleteFromTable(TABLE_CLANEK, "id_clanek={$id_clanek}");
        return $this->deleteFromTable(TABLE_CLANEK, array($id_clanek), "id_clanek=?");
    }

    // filtrovani prispevku

    /**
     * Vrati vsechny prispevky se statusem
     *
     * @param int $status status
     * @return array pole prispevku
     */
    public function getAllArticles(int $status)
    {
        //SELECT * FROM mjakubas_uzivatel, mjakubas_clanek WHERE mjakubas_clanek.schvalen=0 AND mjakubas_uzivatel.id_uzivatel=mjakubas_clanek.id_uzivatel

        //$whereStatement = "schvalen={$status} AND " . TABLE_CLANEK . ".id_uzivatel=" . TABLE_UZIVATEL . ".id_uzivatel";
        $whereStatement = "schvalen=? AND " . TABLE_CLANEK . ".id_uzivatel=" . TABLE_UZIVATEL . ".id_uzivatel";
        return $this->selectFromTable(TABLE_CLANEK . ", " . TABLE_UZIVATEL, array($status), $whereStatement, "");
    }

    /**
     * Vytvori novou prazdnou recenzi
     *
     * @param int $id_user id uzivatel
     * @param int $id_clanek id clanek
     * @return bool true-> pokud se vytvorila nova prazdna recenze
     */
    public function createNewEmptyReview(int $id_user, int $id_clanek)
    {
        //$insertStatement = "id_uzivatel, id_clanek, hodnoceni1, hodnoceni2, hodnoceni3, zprava";
        //$insertValues = "{$id_user}, '{$id_clanek}', '-1', '-1', '-1', '-1'";

        $insertStatement = "id_uzivatel, id_clanek, hodnoceni1, hodnoceni2, hodnoceni3, zprava";
        $insertValues = "?, ?, ?, ?, ?, ?";

        return $this->insertIntoTable(TABLE_RECENZE, $insertStatement, array($id_user, $id_clanek, -1, -1, -1, -1), $insertValues);
    }

    /**
     * Zepta se zda recenze prispevku je validni
     *
     * @param int $id_clanek id prispevek
     * @return bool true->pokud ano
     */
    public function existsAllValidReviews(int $id_clanek)
    {
        //$review = $this->selectFromTable(TABLE_RECENZE, "id_clanek={$id_clanek}");
        $review = $this->selectFromTable(TABLE_RECENZE, array($id_clanek), "id_clanek=?");
        if (empty($review))
            return false;
        if (count($review) < 3)
            return false;

        $revs = $this->getArticleReviews($id_clanek);
        foreach ($revs as $row) {
            if (!($row["hodnoceni1"] != -1 && $row["hodnoceni2"] != -1 && $row["hodnoceni3"] != -1 && $row["zprava"] != -1))
                return false;
        }

        return true;
    }

    // recenze

    /**
     * Vrati recenze prispevku pokud existuji
     *
     * @param int $id_clanek id prispevku
     * @return array recenze
     */
    public function getArticleReviews(int $id_clanek)
    {
        //SELECT * FROM mjakubas_recenze, mjakubas_uzivatel WHERE mjakubas_recenze.id_clanek=1 AND mjakubas_uzivatel.id_uzivatel=mjakubas_recenze.id_uzivatel

        $tableName = TABLE_RECENZE . ", " . TABLE_UZIVATEL;
        //$whereStatement = TABLE_RECENZE . ".id_uzivatel=" . TABLE_UZIVATEL . ".id_uzivatel AND " . "id_clanek={$id_clanek}";
        $whereStatement = TABLE_RECENZE . ".id_uzivatel=" . TABLE_UZIVATEL . ".id_uzivatel AND " . "id_clanek=?";

        return $this->selectFromTable($tableName, array($id_clanek), $whereStatement);
    }

    /**
     * Vrati recenze prihlaseneho uzivatele
     *
     * @return array
     */
    public function getLoggedUserReviews()
    {
        // je uzivatel prihlasen?
        if (!$this->userModel->isUserLoggedIn())
            return [];

        // zjisti id uzivatele
        $userID = $this->userModel->getUserID();
        if ($userID == -1)
            return [];

        //$nonValid = $this->getUserReviews($userID, "(hodnoceni1 = -1 OR hodnoceni2 = -1 OR hodnoceni3 = -1 OR zprava = '-1')");
        //$valid = $this->getUserReviews($userID, "(hodnoceni1 <> -1 AND hodnoceni2 <> -1 AND hodnoceni3 <> -1 AND zprava <> '-1')");

        $nonValid = $this->getUserReviews($userID, array(-1, -1, -1, -1), "(hodnoceni1=? OR hodnoceni2=? OR hodnoceni3=? OR zprava=?)");
        $valid = $this->getUserReviews($userID, array(-1, -1, -1, -1), "(hodnoceni1 <> ? AND hodnoceni2 <> ? AND hodnoceni3 <> ? AND zprava <> ?)");

        return array(
            "nonValid" => $nonValid,
            "valid" => $valid
        );
    }

    /**
     * Zmeni recenzi
     *
     * @param int $rev_id id recenze
     * @param int $fh1 hodnoceni 1
     * @param int $fh2 hodnoceni 2
     * @param int $fh3 hodnoceni 3
     * @param string $zprava zprava
     * @return bool true-> pokud se podarilo zmenit recenzi
     */
    public function updateReview(int $rev_id, int $fh1, int $fh2, int $fh3, string $zprava)
    {
        //$updateStatementWithValues = "hodnoceni1='{$fh1}', hodnoceni2='{$fh2}', hodnoceni3='{$fh3}', zprava='{$zprava}'";
        //$whereStatement = "id_recenze='{$rev_id}'";

        $updateStatementWithValues = "hodnoceni1=?, hodnoceni2=?, hodnoceni3=?, zprava=?";
        $whereStatement = "id_recenze=?";

        return $this->updateInTable(TABLE_RECENZE, $updateStatementWithValues, $whereStatement, array($fh1, $fh2, $fh3, $zprava, $rev_id));
    }

    // private

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
    private function addNewArticle(int $userID, string $filePath, string $heading, string $abstract, string $date): bool
    {
        //$insertStatement = "id_uzivatel, soubor, nazev, abstrakt, datum, schvalen";
        //$insertValues = "{$userID}, '{$filePath}', '{$heading}', '{$abstract}', '{$date}', 0";

        $insertStatement = "id_uzivatel, soubor, nazev, abstrakt, datum, schvalen";
        $insertValues = "?, ?, ?, ?, ?, ?";

        return $this->insertIntoTable(TABLE_CLANEK, $insertStatement, array($userID, $filePath, $heading, $abstract, $date, 0), $insertValues);
    }

    /**
     * Vrati stranky zadaneho uzivatele nebo null pokud uzivatel neexistuje
     *
     * @param int $userID id uzivatele
     * @param string $whereStatement filtrovani podle
     * @param string $orderByStatement serazeni podle
     * @return array neprazdne pole pokud byly nalezen clakny jinak prazdne pole
     */
    private function getUserArticles(int $userID, array $values, string $whereStatement = "", string $orderByStatement = ""): array
    {
        /*
        $userWhereStatement = TABLE_CLANEK . ".id_uzivatel='{$userID}' AND " . TABLE_UZIVATEL . ".id_uzivatel='{$userID}'";
        if ($whereStatement != "")
            $userWhereStatement .= " and " . $whereStatement;

        return $this->selectFromTable(TABLE_CLANEK . ", " . TABLE_UZIVATEL, $userWhereStatement, $orderByStatement);
        */

        $userWhereStatement = TABLE_CLANEK . ".id_uzivatel=? AND " . TABLE_UZIVATEL . ".id_uzivatel=?";
        $whereValues = array($userID, $userID);
        if ($whereStatement != "")
            $userWhereStatement .= " and " . $whereStatement;

        //array_push($whereValues, $values);
        foreach ($values as $value) {
            array_push($whereValues, $value);
        }

        return $this->selectFromTable(TABLE_CLANEK . ", " . TABLE_UZIVATEL, $whereValues, $userWhereStatement, $orderByStatement);
    }

    /**
     * Zmeni titulek a abstrakt clanku
     *
     * @param int $articleID id clanku
     * @param string $heading titulek
     * @param string $abstract abstrakt
     * @return bool true-> pokud se podarilo zmeni radek tabulky
     */
    private function editArticle(int $articleID, string $heading, string $abstract)
    {
        //$updateStatementWithValues = "abstrakt='{$abstract}', nazev='{$heading}'";
        //$whereStatement = "id_clanek='{$articleID}'";

        $updateStatementWithValues = "abstrakt=?, nazev=?";
        $whereStatement = "id_clanek=?";

        return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, array($abstract, $heading, $articleID));
    }

    /**
     * Vrati recenze uzivatele
     *
     * @param int $userID id uzivatele
     * @param string $whereStatement kde
     * @return array recenze
     */
    private function getUserReviews(int $userID, array $values, string $whereStatement = "")
    {
        /*
        $userWhereStatement = TABLE_RECENZE . ".id_uzivatel='{$userID}' AND " . TABLE_UZIVATEL . ".id_uzivatel='{$userID}'";
        if ($whereStatement != "")
            $userWhereStatement .= " AND " . $whereStatement;

        return $this->selectFromTable(TABLE_RECENZE . ", " . TABLE_UZIVATEL, $userWhereStatement);
        */

        $userWhereStatement = TABLE_RECENZE . ".id_uzivatel=? AND " . TABLE_UZIVATEL . ".id_uzivatel=?";
        $whereValues = array($userID, $userID);
        if ($whereStatement != "")
            $userWhereStatement .= " AND " . $whereStatement;

        //array_push($whereValues, $values);
        foreach ($values as $value) {
            array_push($whereValues, $value);
        }

        return $this->selectFromTable(TABLE_RECENZE . ", " . TABLE_UZIVATEL, $whereValues, $userWhereStatement);
    }
}