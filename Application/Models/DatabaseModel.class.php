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
     *
     * @param $pdo PDO databaze
     */
    protected function __construct(PDO $pdo)
    {
        //$this->pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->pdo = $pdo;
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
     * @param array $values hodnoty kde
     * @param string $whereStatement kde
     * @param string $orderByStatement razeni
     * @param string $whatStatement co vybiram
     * @return array radky tabulky nebo null
     */
    protected function selectFromTable(string $tableName, array $values, string $whereStatement = "", string $orderByStatement = "", string $whatStatement = "*"): array
    {
        $q = "SELECT {$whatStatement} FROM {$tableName}"
            . (($whereStatement == "") ? "" : " WHERE {$whereStatement}")
            . (($orderByStatement) == "" ? "" : " ORDER BY $orderByStatement");

        $query = $this->pdo->prepare($q);
        if (!$query->execute($values)) {
            return [];
        }
        return $query->fetchAll();

        /*
        $sql = "SELECT * FROM mjakubas_uzivatel WHERE jmeno=:jmeno";
        $dotaz = $this->pdo->prepare($sql);

        $id = "Martin";
        $params = array(":jmeno" => $id);

        $dotaz->execute($params);
        echo $dotaz->queryString;

        return $dotaz->fetchAll();
        */
    }
    /*
    protected function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = "", string $whatStatement = "*"): array
    {
        $q = "SELECT {$whatStatement} FROM {$tableName}"
            . (($whereStatement == "") ? "" : " WHERE {$whereStatement}")
            . (($orderByStatement) == "" ? "" : " ORDER BY $orderByStatement");

        $res = $this->executeQuery($q); // prázdné pole nebo řádky tabulky

        if ($res == null)
            return [];

        return $res->fetchAll();
    }
    */

    /**
     * Vymaze radky z prislusne tabulky
     *
     * @param string $tableName nazev tabulky
     * @param array $values hodnoty
     * @param string $whereStatement kde
     * @return bool true pokud se podarilo vymazat radky
     */
    protected function deleteFromTable(string $tableName, array $values, string $whereStatement): bool
    {
        $q = "DELETE FROM {$tableName} WHERE {$whereStatement}";

        $query = $this->pdo->prepare($q);
        if (!$query->execute($values)) {
            return false;
        }
        return true;
    }
    /*
    protected function deleteFromTable(string $tableName, string $whereStatement): bool
    {
        $q = "DELETE FROM {$tableName} WHERE {$whereStatement}";

        $res = $this->executeQuery($q);

        if ($res == null)
            return false;

        return true;
    }
    */

    /**
     * Vlozi radek do tabulky
     *
     * @param string $tableName nazev tabulky
     * @param string $insertStatement insert
     * @param string $insertValues hodnoty insertu
     * @return bool true pokud se podarilo rádek vlozit
     */
    protected function insertIntoTable(string $tableName, string $insertStatement, array $values, string $insertValues): bool
    {
        $q = "INSERT INTO {$tableName}({$insertStatement}) VALUES ({$insertValues})";

        $query = $this->pdo->prepare($q);
        if (!$query->execute($values)) {
            return false;
        }
        return true;
    }
    /*
    protected function insertIntoTable(string $tableName, string $insertStatement, string $insertValues): bool
    {
        $q = "INSERT INTO {$tableName}({$insertStatement}) VALUES ({$insertValues})";

        $res = $this->executeQuery($q);

        if ($res == null)
            return false;

        return true;
    }
    */

    /**
     * aktualizuje radek v tabulce
     *
     * @param string $tableName nazev tabulky
     * @param string $updateStatementWithValues nove hodnoty
     * @param string $whereStatement kde
     * @return bool true pokud se podarilo radek aktualizovat
     */
    protected function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement, array $values): bool
    {
        $q = "UPDATE {$tableName} SET {$updateStatementWithValues} WHERE {$whereStatement}";

        $query = $this->pdo->prepare($q);
        if (!$query->execute($values)) {
            return false;
        }
        return true;
    }
    /*
    protected function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement): bool
    {
        $q = "UPDATE {$tableName} SET {$updateStatementWithValues} WHERE {$whereStatement}";
        $res = $this->executeQuery($q);

        if ($res == null)
            return false;

        return true;
    }
    */


    /**
     * Vrati PDO
     *
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    // TEST
}