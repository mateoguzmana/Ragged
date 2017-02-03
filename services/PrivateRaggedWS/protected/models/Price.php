<?php

class Price extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAllPriceLists($user, $item) {
        try {
            
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();                        
            $table = 'tbls_priceslists';
            $table_encrypted =  $cryptography->Encrypting($table);    
            $priceLists = array();
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");            
            $columns = "";
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);
            $query = $this->createBasicSelectWithWhere($columns, $table, " AND `idstate` = '1'");               
            //$priceLists['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $priceLists['datas'] = $this->excecuteQueryAll($query);
            //$priceLists['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table');");
            //$priceLists['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");

            return json_encode($priceLists);
        } catch (Exception $exc) {
            $this->createLog('Reference', 'getAllReferences', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}