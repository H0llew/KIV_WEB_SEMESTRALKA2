<?php

require_once "DatabaseModel.class.php";
require_once "SessionsModel.class.php";
require_once "RoleModel.class.php";

/**
 * Obsahuje funkce pro praci s tabulkou obsahujici uzivatele aplikace
 */
class UserModel extends DatabaseModel
{
    private $table_users = TABLE_UZIVATEL;

    private $roleDB;

    private $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionsModel();

        $this->roleDB = new RoleModel();
    }

    // public

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
        if ($this->emailExists($email))
            return false;

        return $this->addNewUser($email, $password, $name, $surname);
    }

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
        $user = $this->selectFromTable($this->table_users, $whereStatement);

        if (empty($user))
            return false;

        if (!(password_verify($password, $user[0]['heslo'])))
            return false;

        $_SESSION[SESSION_USER_KEY] = $user[0]['id_uzivatel'];
        return true;
    }

    /**
     * Prihlasi uzivatele
     *
     * @return bool true -> pokud se podarilo uzivatele prihlasit
     */
    public function isUserLoggedIn(): bool
    {
        return isset($_SESSION[SESSION_USER_KEY]);
    }

    public function isUserAdmin(): bool
    {
        $user = $this->getLoggedUserData();
        if ($user == null)
            return false;

        if ($this->roleDB->getRoleWeight($user["id_pravo"] >= 10))
            return true;

        return false;
    }


    /**
     * Odhlasi uzivatele
     */
    public function logoutUser()
    {
        unset($_SESSION[SESSION_USER_KEY]);
    }

    /**
     * Vrati data prihlaseneho uzivatele
     *
     * @return mixed|null data uzivatele nebo null
     */
    public function getLoggedUserData()
    {
        if (!$this->isUserLoggedIn())
            return null;

        $user_id = $_SESSION[SESSION_USER_KEY];
        if ($user_id == null) {
            $this->logoutUser();
            return null;
        }

        $whereStatement = "id_uzivatel='{$user_id}'";
        $userData = $this->selectFromTable($this->table_users, $whereStatement);
        if (empty($userData))
            return null;

        return $userData[0];
    }

    /**
     * Existuje zadany email v databazi?
     *
     * @param string $email email
     * @return bool true -> pokud exituje zadany email v databazi
     */
    public function emailExists(string $email): bool
    {
        $whereStatement = "email='{$email}'";
        $res = $this->selectFromTable($this->table_users, $whereStatement);

        if (empty($res))
            return false;

        return true;
    }

    public function getAllUsers()
    {
        if (!$this->isUserAdmin())
            return null;

        return $this->selectFromTable($this->table_users, "", "");
    }

    public function getAllReviewers()
    {
        if (!$this->isUserAdmin())
            return null;

        $users = $this->selectFromTable($this->table_users, "", "");
        if (empty($users))
            return $users;

        print_r($users);

        $res = [];
        foreach ($users as $row) {
            if ($this->roleDB->getRoleWeight($row["id_pravo"]) >= 5) {
                array_push($res, $row);
            }
        }

        return $res;
    }

    // private

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

        $insertStatement = "email, heslo, jmeno, prijmeni, id_pravo";
        $insertValues = "'{$email}', '{$password}', '{$name}', '{$surname}', $role";

        return $this->insertIntoTable($this->table_users, $insertStatement, $insertValues);
    }

    // testovací metody

    /*
    private function getAllUsers()
    {
        return $this->selectFromTable($this->table_users, "", "id_uzivatel");
    }

    public function dumpAllUsers()
    {
        var_dump($this->getAllUsers());
    }

    public function addTestUser()
    {
        echo $this->addNewUser("testA@testA.testA", "test", "testJmenoA", "TestPrijmeniA", 2);
        echo $this->addNewUser("test@test.test", "test", "testJmeno", "TestPrijmeni");
    }

    public function checkEmail()
    {
        echo $this->emailExists("test@test.test");
        echo $this->emailExists("nope");
    }

    public function testLogin()
    {
        return $this->loginUser("test@test.test", "test");
    }
    */
}