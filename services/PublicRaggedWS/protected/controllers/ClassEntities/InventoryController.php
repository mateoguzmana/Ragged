<?php

/*
 * Create by Activity Technology S.A.S.
 */

class InventoryController extends Controller {

    public function insertInventory($json, $startDate, $startTime, $idCompany, $tablename, $method, $type, $insertProcess, $setProcess) {
        try {                        
                
            if (Inventory::model()->insertInventory($json, $idCompany, $tablename, $method, $type, $insertProcess, $setProcess)) {                   
                    return "OK";
                } else {
                    return "NO";
                }               
        } catch (Exception $e) {
            $this->createLog('InventoryController', $method, $e);
        }
    }
    
     public function setInventoryInfo($json,$tablename) {
        try {                             
            $response = Inventory::model()->updateInventoryInfo($json, $tablename); 
            if($response != null){
                $response = "OK";
            }
            else {
                $response = "NO";
            }
            return $response;            
            
        } catch (Exception $e) {
        $this->createLog('InventoryController', 'getOnHandProd', $e);
        return "NO";
        }  
        
    }
    
    public function GetTableInfo($table) {        
        
        $data = Inventory::model()->GetTableInfo($table);                                   
        return json_encode($data);
    
    }
    
     public function getInventory($table, $idcompany) {        
        
        $data = Inventory::model()->getInventory($table, $idcompany);                                   
        return json_encode($data);
    
    }
    
    public function updateStock() {        
        
        $response = Inventory::model()->updateStock();                
        return json_encode($response);          
    
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>