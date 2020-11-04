<?php

/**
 * Trida poskytuje zakladni metody pro komunikaci s databazi
 */
class DatabaseModel
{
    /** PDO objekt pro praci s databazi */
    private $pdo;

    /**
     * Instance inicializuje připojení k databázi
     */
    protected function __construct()
    {
        $this->pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
    }

    /**
     * Provede dotaz a vrati ziskana data nebo null
     *
     * @param string $query SQL dotaz
     * @return PDOStatement|null vysledek dotazu
     */
    protected function executeQuery(string $query)
    {
        $res = $this->pdo->query($query);

        if ($res)
            return $res;

        $error = $this->pdo->errorInfo();
        echo $error[2];
        return null;
    }

    /**
     * Vybere prvky z databazove tabulky nebo null
     *
     * @param string $tableName nazev tabulky
     * @param string $whereStatement kde
     * @param string $orderByStatement razeni
     * @return array radky tabulky nebo null
     */
    protected function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = ""): array
    {
        $q = "SELECT * FROM {$tableName}"
            . (($whereStatement == "") ? "" : " WHERE {$whereStatement}")
            . (($orderByStatement) == "" ? "" : " ORDER BY $orderByStatement");

        $res = $this->executeQuery($q); // prázdné pole nebo řádky tabulky

        if ($res == null)
            return [];

        return $res->fetchAll();
    }

    /**
     * Vymaze radky z prislusne tabulky
     *
     * @param string $tableName nazev tabulky
     * @param string $whereStatement kde
     * @return bool true pokud se podarilo vymazat radky
     */
    protected function deleteFromTable(string $tableName, string $whereStatement): bool
    {
        $q = "DELETE FROM {$tableName} WHERE {$whereStatement}";

        $res = $this->executeQuery($q);

        if ($res == null)
            return false;

        return true;
    }

    /**
     * Vlozi radek do tabulky
     *
     * @param string $tableName nazev tabulky
     * @param string $insertStatement insert
     * @param string $insertValues hodnoty insertu
     * @return bool true pokud se podarilo rádek vlozit
     */
    protected function insertIntoTable(string $tableName, string $insertStatement, string $insertValues): bool
    {
        $q = "INSERT INTO {$tableName}({$insertStatement}) VALUES ({$insertValues})";

        $res = $this->executeQuery($q);

        if ($res == null)
            return false;

        return true;
    }

    /**
     * aktualizuje radek v tabulce
     *
     * @param string $tableName nazev tabulky
     * @param string $updateStatementWithValues nove hodnoty
     * @param string $whereStatement kde
     * @return bool true pokud se podarilo radek aktualizovat
     */
    protected function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement): bool
    {
        $q = "UPDATE {$tableName} SET {$updateStatementWithValues} WHERE {$whereStatement}";

        $res = $this->executeQuery($q);

        if ($res == null)
            return false;

        return true;
    }
}