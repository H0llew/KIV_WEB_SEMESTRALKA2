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
        $approvedUserArticles = $this->getUserArticles($userID, "schvalen = 1");
        // neschvalene clanky
        $waitingForApprovalArticles = $this->getUserArticles($userID, "schvalen = 0");
        // zamitnute clanky
        $dismissedArticles = $this->getUserArticles($userID, "schvalen = 2");

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
        $review = $this->selectFromTable(TABLE_RECENZE, "id_clanek={$id_clanek}");
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
        $review = $this->selectFromTable(TABLE_RECENZE, "id_clanek={$id_clanek}");
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

        $whereStatement = "datum='{$date}' AND soubor='{$filePath}'";
        $articleID = $this->getUserArticles($userID, $whereStatement)[0]["id_clanek"];

        // jen 1 vrati VZDY
        return $this->editArticle($articleID, $heading, $abstract);
    }

    public function getAllArticles(int $status)
    {
        //SELECT * FROM mjakubas_uzivatel, mjakubas_clanek WHERE mjakubas_clanek.schvalen=0 AND mjakubas_uzivatel.id_uzivatel=mjakubas_clanek.id_uzivatel

        $whereStatement = "schvalen={$status} AND " . TABLE_CLANEK . ".id_uzivatel=" . TABLE_UZIVATEL . ".id_uzivatel";
        return $this->selectFromTable(TABLE_CLANEK . ", " . TABLE_UZIVATEL, $whereStatement, "");
    }

    public function createNewEmptyReview(int $id_user, int $id_clanek)
    {
        $insertStatement = "id_uzivatel, id_clanek, hodnoceni, zprava";
        $insertValues = "{$id_user}, '{$id_clanek}', '-1', '-1'";

        return $this->insertIntoTable(TABLE_RECENZE, $insertStatement, $insertValues);
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
        $insertStatement = "id_uzivatel, soubor, nazev, abstrakt, datum, schvalen";
        $insertValues = "{$userID}, '{$filePath}', '{$heading}', '{$abstract}', '{$date}', 0";

        return $this->insertIntoTable(TABLE_CLANEK, $insertStatement, $insertValues);
    }

    /**
     * Vrati stranky zadaneho uzivatele nebo null pokud uzivatel neexistuje
     *
     * @param int $userID id uzivatele
     * @param string $whereStatement filtrovani podle
     * @param string $orderByStatement serazeni podle
     * @return array neprazdne pole pokud byly nalezen clakny jinak prazdne pole
     */
    private function getUserArticles(int $userID, string $whereStatement = "", string $orderByStatement = ""): array
    {
        $userWhereStatement = TABLE_CLANEK . ".id_uzivatel='{$userID}' AND " . TABLE_UZIVATEL . ".id_uzivatel='{$userID}'";
        if ($whereStatement != "")
            $userWhereStatement .= " and " . $whereStatement;

        return $this->selectFromTable(TABLE_CLANEK . ", " . TABLE_UZIVATEL, $userWhereStatement, $orderByStatement);
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
        $updateStatementWithValues = "abstrakt='{$abstract}', nazev='{$heading}'";
        $whereStatement = "id_clanek='{$articleID}'";

        return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement);
    }
}