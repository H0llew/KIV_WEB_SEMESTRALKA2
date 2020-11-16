<?php

require_once "DatabaseModel.class.php";
require_once "SessionsModel.class.php";

/**
 * Obsahuje fce pro databazove operace tykajici se uzivatele
 */
class UserModel extends DatabaseModel
{
    private $session;

    public function __construct(PDO $pdo = null)
    {
        $conn = $pdo;
        if ($pdo == null)
            $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        parent::__construct($conn);

        $this->session = new SessionsModel();
    }

    // obecne

    /**
     * Vrati klic prihlaseneho uzivatele
     *
     * @return int klic
     */
    public function getUserID(): int
    {
        if (!$this->isUserLoggedIn())
            return -1;

        return $_SESSION[SESSION_USER_KEY];
    }

    // stav uzivatele

    /**
     * Je uzivatel prihlasen?
     *
     * @return bool true -> pokud ano
     */
    public function isUserLoggedIn(): bool
    {
        return isset($_SESSION[SESSION_USER_KEY]);
    }

    /**
     * Je uzivatel admin?
     *
     * @return bool ano je ,ne není
     */
    public function isUserAdmin(): bool
    {
        $weight = $this->getUserRoleWeight();
        if ($weight >= 10)
            return true;
        return false;
    }

    /**
     * Je uzivatel recenzent
     *
     * @return bool ano je, ne není
     */
    public function isUserReviewer(): bool
    {
        $weight = $this->getUserRoleWeight();
        if ($weight >= 5)
            return true;
        return false;
    }

    /**
     * Vrati vahu uzivatelske role
     *
     * @return mixed
     */
    public function getUserRoleWeight()
    {
        //SELECT vaha FROM mjakubas_uzivatel, mjakubas_pravo WHERE mjakubas_uzivatel.id_uzivatel=1 AND mjakubas_uzivatel.id_pravo=mjakubas_pravo.id_pravo

        $userID = $this->getUserID();
        if ($userID == -1)
            return -1;
        $whatStatement = "vaha";
        $tableStatement = TABLE_UZIVATEL . ", " . TABLE_PRAVO;
        $whereStatement = TABLE_UZIVATEL . ".id_uzivatel={$userID} AND " . TABLE_UZIVATEL . ".id_pravo=" . TABLE_PRAVO . ".id_pravo";

        $res = $this->selectFromTable($tableStatement, $whereStatement, "", $whatStatement);
        return $res[0][0];
    }

    // data uzivatele

    /**
     * Vrati data prihlaseneho uzivatele
     *
     * @return mixed|null data uzivatele nebo null
     */
    public function getLoggedUserData()
    {
        if (!$this->isUserLoggedIn())
            return [];

        $user_id = $this->getUserID();
        if ($user_id == -1) {
            unset($_SESSION[SESSION_USER_KEY]);
            return [];
        }

        $whereStatement = "id_uzivatel='{$user_id}'";
        $userData = $this->selectFromTable(TABLE_UZIVATEL, $whereStatement);
        if (empty($userData))
            return [];

        $whatStatement = "nazev";
        $tableStatement = TABLE_UZIVATEL . ", " . TABLE_PRAVO;
        $whereStatement = TABLE_UZIVATEL . ".id_uzivatel={$user_id} AND " . TABLE_PRAVO . ".id_pravo={$userData[0]["id_pravo"]}";

        $res = $this->selectFromTable($tableStatement, $whereStatement, "", $whatStatement);

        $userData[0]["role"] = $res[0][0];
        return $userData[0];
    }

    /**
     * Vrati data prihlasenego uzivatele
     *
     * @param string $email email
     * @param string $jmeno jmeno
     * @param string $prijmeni prihlaseni
     * @param string $heslo heslo
     * @return bool true -> pokud se povedlo zmenit data
     */
    public function updateUserData(string $email, string $jmeno, string $prijmeni, string $heslo): bool
    {
        if (!$this->isUserLoggedIn())
            return false;

        // zjisti user id
        $userID = $this->getUserID();
        if ($userID == -1)
            return false;

        $heslo = password_hash($heslo, PASSWORD_DEFAULT);

        $whereStatement = "id_uzivatel={$userID}";
        $updateStatementWithValues = "email='{$email}', jmeno='{$jmeno}', prijmeni='{$prijmeni}', heslo='{$heslo}'";

        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Zmeni roli uzivatele
     *
     * @param int $userID id uzivatele
     * @param int $newRole nova role uzivatelel
     * @return bool true-> poku se zmne povedla
     */
    public function changeUserRole(int $userID, int $newRole)
    {
        $whereStatement = "id_uzivatel={$userID}";
        $updateStatementWithValues = "id_pravo='{$newRole}'";

        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Zmeni uzivateluv ban status
     *
     * @param int $userID id uzivatele
     * @param int $banStatus status banu
     * @return bool true-> pokud se podarilo zabanovat uzivatele
     */
    public function changeUserBanStatus(int $userID, int $banStatus)
    {
        $whereStatement = "id_uzivatel={$userID}";
        $updateStatementWithValues = "isBanned='{$banStatus}'";

        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Vrati vsechny uzivatele splnujici minimalni vahu
     *
     * @param int $minWeight min vaha
     * @return array
     */
    public function getAllUsers(int $minWeight = 0, string $sortBy = "", int $maxWeight = 100)
    {
        //SELECT id_uzivatel, mjakubas_uzivatel.id_pravo, email, jmeno, prijmeni, nazev, vaha FROM mjakubas_uzivatel, mjakubas_pravo
        // WHERE mjakubas_uzivatel.id_pravo=mjakubas_pravo.id_pravo

        $whatStatement = "id_uzivatel, mjakubas_uzivatel.id_pravo, email, jmeno, prijmeni, nazev, vaha, isBanned, heslo";
        $tableStatement = TABLE_UZIVATEL . ", " . TABLE_PRAVO;
        $whereStatement = "mjakubas_uzivatel.id_pravo=mjakubas_pravo.id_pravo AND vaha>={$minWeight} AND vaha<={$maxWeight}";

        return $this->selectFromTable($tableStatement, $whereStatement, $sortBy, $whatStatement);
    }

    // vymaz

    /**
     * "Vymaze" z tabulky vybraneho uzivatele
     *
     * @param int $id
     */
    public function deleteUser(int $id)
    {
        $updateStatement = "email='uzivatel odstraneň', heslo='0'";
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatement, "id_uzivatel={$id}");
    }
}