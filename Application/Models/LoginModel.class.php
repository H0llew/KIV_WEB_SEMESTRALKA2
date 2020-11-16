<?php

require_once "DatabaseModel.class.php";
require_once "SessionsModel.class.php";

/**
 * Obsahuje vse potrebne pro prihlase/odhlaseni a vytvoreni uzivatele a rozpoznani zda je uzivatel prihlasen
 */
class LoginModel extends DatabaseModel
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

    // info o prihlaseni

    /**
     * Je uzivatel prihlasen
     *
     * @return bool true -> pokud ano
     */
    public function isUserLoggedIn(): bool
    {
        return isset($_SESSION[SESSION_USER_KEY]);
    }

    // pro prihlaseni

    /**
     * Prihlasi uzivatele
     *
     * @param string $email email
     * @param string $password heslo
     * @return bool true -> pokud se uzivatele podarilo prihlasit
     */
    public function loginUser(string $email, string $password): bool
    {
        $whereStatement = "email='{$email}'";
        $user = $this->selectFromTable(TABLE_UZIVATEL, $whereStatement);

        if (empty($user))
            return false;

        if (!(password_verify($password, $user[0]['heslo'])))
            return false;

        $_SESSION[SESSION_USER_KEY] = $user[0]['id_uzivatel'];
        return true;
    }

    /**
     * Odhlasi uzivatele
     */
    public function logoutUser()
    {
        unset($_SESSION[SESSION_USER_KEY]);
    }

    // pro registraci

    /**
     * Existuje zadany email v databazi?
     *
     * @param string $email email
     * @return bool true -> pokud exituje zadany email v databazi
     */
    public function emailExists(string $email): bool
    {
        $whereStatement = "email='{$email}'";
        $res = $this->selectFromTable(TABLE_UZIVATEL, $whereStatement);

        if (empty($res))
            return false;

        return true;
    }

    /**
     * Metoda zaregistruje nového uživatele
     *
     * @param string $email email
     * @param string $password heslo
     * @param string $name jméno
     * @param string $surname přijmení
     * @return bool true pokud se bovedlo registrovat nového uživatele
     */
    public function registerNewUser(string $email, string $password, string $name, string $surname): bool
    {
        return $this->addNewUser($email, $password, $name, $surname);
    }

    /**
     * Prida noveho uzivatele do tabulky databaze
     *
     * @param string $email email
     * @param string $password heslo
     * @param string $name jmeno
     * @param string $surname prijmeni
     * @param int $role prava
     * @return bool true -> pokud se podařilo přidat noveho uzivatele
     */
    private function addNewUser(string $email, string $password, string $name, string $surname, int $role = 4): bool
    {
        $password = password_hash($password, PASSWORD_BCRYPT);

        $insertStatement = "email, heslo, jmeno, prijmeni, id_pravo, isBanned";
        $insertValues = "'{$email}', '{$password}', '{$name}', '{$surname}', $role, '0'";

        return $this->insertIntoTable(TABLE_UZIVATEL, $insertStatement, $insertValues);
    }
}