<?php

class Collection extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAllCollections($user, $item) {
        try {
            
            
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $collections = array();
            $table = 'tbls_collections';
            $table_encrypted =  $cryptography->Encrypting($table);    

            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $columns = "";
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);
            $query = $this->createBasicSelectWithWhere($columns, $table, " AND `idstate` = '1'");

            $collections['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $collections['datas'] = $this->excecuteQueryAll($query);
            $collections['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $collections['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");

            return json_encode($collections);
        } catch (Exception $e) {
            $this->createLog('Collection', 'getAllCollection', $e);
        }
    }

    public function setStatusCollection($data) {
        try {
            $json = json_decode($data);
            $id = $json->id;
            $status = ((string)$json->status == "1") ? "0" : "1";
            //return $status;
            if ($this->excecuteDatabaseStoredProcedures("`sp_setstatuscollection`('$id','$status');")) {
                return "NO";
            } else {
                return "YES";
            }
        } catch (Exception $e) {
            $this->createLog('Collection', 'setStatusCollection', $e);
        }
    }
    
    public function setAllStatusCollection($company, $status) {
        try {               
            
            // Si seleccionó todas las compañías
            if ($company == 0)
            {
                $companies = $this->excecuteDatabaseStoredProcedures("`sp_getcompaniesforselect`();");                
                $success = True;
                foreach ($companies  as $company_item)
                {
                    $id = $company_item['id'];                    
                    if($this->excecuteDatabaseStoredProcedures("`sp_setallstatuscollection`('$id','$status');"))
                    {
                        $success = False;
                    }                    
                }
                if ($success)
                    return "YES";
                else return "NO";                
            }
            
            else
            {            
                if ($this->excecuteDatabaseStoredProcedures("`sp_setallstatuscollection`('$company','$status');")) {
                    return "NO";
                } else {
                    return "YES";
                }
            }
        } catch (Exception $e) {
            $this->createLog('Collection', 'setAllStatusCollection', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
