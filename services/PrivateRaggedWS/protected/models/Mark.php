<?php

class Mark extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAllMarks($user, $item) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $marks = array();
            $table = 'tbls_marks';
            $table_encrypted =  $cryptography->Encrypting($table);    

            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $columns = "";
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);
            $query = $this->createBasicSelectWithWhere($columns, $table, " AND `idstate` = '1'");            

            $marks['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $marks['datas'] = $this->excecuteQueryAll($query);
            $marks['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $marks['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");

            return json_encode($marks);            
            
        } catch (Exception $exc) {
            $this->createLog('Mark', 'getAllMarks', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
