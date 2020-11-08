<?php

require_once "DatabaseModel.class.php";

class RoleModel extends DatabaseModel
{

    private $table_roles = TABLE_PRAVO;

    private $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionsModel();
    }

    public function getRoleWeight(int $roleID)
    {
        $role = $this->selectFromTable($this->table_roles, "id_pravo='{$roleID}'");
        if (empty($role))
            return -1;

        return $role[0]["vaha"];
    }
}