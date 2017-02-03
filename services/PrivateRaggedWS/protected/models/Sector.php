<?php

class Sector extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAllSectors($user, $item) {
        try {            
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $sectors = array();
            $table = 'tbls_sectors';             
            $table_encrypted =  $cryptography->Encrypting($table);    
            
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $columns = "";
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);            
            $query = $this->createBasicSelectWithWhere($columns, $table, " AND `idstate` = '1'");    
            
            $sectors['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $sectors['datas'] = $this->excecuteQueryAll($query);            
            $sectors['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $sectors['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");

            return json_encode($sectors);
        } catch (Exception $exc) {
            $this->createLog('Sector', 'getAllSectors', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }    
  

}
