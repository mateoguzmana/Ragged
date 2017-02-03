<?php

class Line extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAllLines($user, $item) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $lines = array();
            $table = 'tbls_lines';
            $table_encrypted =  $cryptography->Encrypting($table);    

            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $columns = "";
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);
            $query = $this->createBasicSelectWithWhere($columns, $table, " AND `idstate` = '1'");
            
            $lines['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $lines['datas'] = $this->excecuteQueryAll($query);
            $lines['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $lines['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");

            return json_encode($lines);
        } catch (Exception $exc) {
            $this->createLog('Line', 'getAllLines', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}