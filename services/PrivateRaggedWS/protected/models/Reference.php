<?php

class Reference extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAllReferences($user, $item) {
        try {
           
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();                        
            $table = 'tbls_references';
            $table_encrypted =  $cryptography->Encrypting($table);    
            $references = array();
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");            
            $columns = "";
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);
            $query = $this->createBasicSelectWithWhere($columns, $table, " AND `idstate` = '1'");               
            $references['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $references['datas'] = $this->excecuteQueryAll($query);
            //$references['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table');");
            $references['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");

            return json_encode($references);
        } catch (Exception $exc) {
            $this->createLog('Reference', 'getAllReferences', $exc);
        }
    }
    
    public function getAllReferenceDetails($collections, $references, $priceList) {
        try {                       
            
            $collectionsId = json_decode($collections);                           
            $referencesId = json_decode($references);                
            $priceListId = json_decode($priceList);       
            
            $response = array();           
            
            $table = 'tbls_collections';
            $idName = "idcollection";            
            $response['collections'] = $this->getTablesData($collectionsId, $table, $idName);
            
            $table = 'tbls_references';
            $idName = "idreference";            
            $response['references'] = $this->getTablesData($referencesId, $table, $idName);
            
            $table = 'tbls_referencespricelist';
            $idName = "PRICE.idpricelist";            
            $response['priceList'] = $this->getPriceList($priceListId, $table, $idName);                 
            
            $table = 'tbls_plus';
            $idName = "PLUS.idreference";            
            $response['plus'] = $this->getPlus($referencesId, $table, $idName);             
            
            return json_encode($response);
            
            
        } catch (Exception $exc) {
            $this->createLog('Reference', 'getAllReferenceDetails', $exc);
        }
    }
    
    public function getTablesData($IdValues, $table, $idName){   
        
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $result = array();
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
                
            }
            $columns = substr($columns, 0, -2);            
            
            $where = " AND `idstate` = '1' AND ";       

            for ($i=0; $i<count($IdValues);$i++){                
                $where.= $idName. " = ".$IdValues[$i];
                if ($i<count($IdValues)-1)
                $where.= " OR ";
            }                        

            $query = $this->createBasicSelectWithWhere($columns, $table, $where);   
            

            $result['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $result['datas'] = $this->excecuteQueryAll($query);
            $result['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $result['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");
            return $result;        
        }   
            catch (Exception $exc) {
            $this->createLog('Reference', 'getAllReferenceDetails', $exc);
        }
    }
    
    public function getPlus($IdValues, $table, $idName){
        
        try {
        
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $result = array();
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);                 

            
            
            $where = " AND PLUS.idstate = '1' AND ";       

            for ($i=0; $i<count($IdValues);$i++){                
                $where.= $idName. " = ".$IdValues[$i];
                if ($i<count($IdValues)-1)
                $where.= " OR ";
            }     

            $tables[0] = "tbls_plus";
            $tables[1] = "tbls_references";
            $tables[2] = "tbls_sizes";
            $tables[3] = "tbls_colors";
            $tables[4] = "tbls_stock";
            

            $alias[0]= "PLUS";
            $alias[1] = "REF";
            $alias[2] = "SIZE";
            $alias[3] = "COLOR";
            $alias[4] = "STOCK";

            $primary[0] = "";
            $foreign[0] = "";
            $primary[1] = "idreference";
            $foreign[1] = "idreference";
            $primary[2] = "idsize";
            $foreign[2] = "idsize";
            $primary[3] = "idcolors";
            $foreign[3] = "idcolor";  
            $primary[4] = "idplu";
            $foreign[4] = "idplus";  

            $condition = "";

            $tablesArray = array();

            for ($i=0; $i<count($tables); $i++)
            {
                $tablesArray[$i]['table'] = $tables[$i];
                $tablesArray[$i]['alias'] = $alias[$i];
                $tablesArray[$i]['references']['type']['primary'] = $alias[$i].".".$primary[$i];
                $tablesArray[$i]['references']['type']['foreign'] = $alias[0].".".$foreign[$i];
                $tablesArray[$i]['references']['type']['condition'] = $condition;     
            }        
            $innerJoin = $this->createInnerJoinMultiple($tablesArray);  

            $query = $this->createBasicSelectWithWhere($columns, $innerJoin, $where);            
            
            $result['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $result['datas'] = $this->excecuteQueryAll($query);
            $result['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $result['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");    
            
            return $result;
        }   
            catch (Exception $exc) {
            $this->createLog('Reference', 'getPlus', $exc);
        }
                           
    }
    
     public function getPriceList($IdValues, $table, $idName){
        
        try {            
            
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $result = array();
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");            
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);                             
            
            
            $where = " AND PRICE.idstate = '1' AND ";       

            for ($i=0; $i<count($IdValues);$i++){                
                $where.= $idName. " = ".$IdValues[$i];
                if ($i<count($IdValues)-1)
                $where.= " OR ";
            }     

            $tables[0] = "tbls_referencespricelist";
            $tables[1] = "tbls_priceslists";    

            $alias[0]= "RefPrice";
            $alias[1] = "PRICE";
            

            $primary[0] = "";
            $foreign[0] = "";
            $primary[1] = "idpricelist";
            $foreign[1] = "idpricelist";


            $condition = "";

            $tablesArray = array();

            for ($i=0; $i<count($tables); $i++)
            {
                $tablesArray[$i]['table'] = $tables[$i];
                $tablesArray[$i]['alias'] = $alias[$i];
                $tablesArray[$i]['references']['type']['primary'] = $alias[$i].".".$primary[$i];
                $tablesArray[$i]['references']['type']['foreign'] = $alias[0].".".$foreign[$i];
                $tablesArray[$i]['references']['type']['condition'] = $condition;     
            }        
            $innerJoin = $this->createInnerJoinMultiple($tablesArray);  
            
            
            
            $query = $this->createBasicSelectWithWhere($columns, $innerJoin, $where);                        
            $result['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");
            $result['datas'] = $this->excecuteQueryAll($query);
            $result['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $result['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");    
            
            return $result;
        }   
            catch (Exception $exc) {
            $this->createLog('Reference', 'getPlus', $exc);
        }
                           
    }
    

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}