<?php

class Size extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAllSizes($user, $item) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $sizes = array();
            $table = 'tbls_sizes';
            $table_encrypted =  $cryptography->Encrypting($table);    

            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $columns = "";
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);
            $query = $this->createBasicSelectWithWhere($columns, $table, " AND `idstate` = '1'");            
            $sizes['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $sizes['datas'] = $this->excecuteQueryAll($query);
            $sizes['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $sizes['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");

            return json_encode($sizes);
        } catch (Exception $exc) {
            $this->createLog('Size', 'getAllSizes', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}