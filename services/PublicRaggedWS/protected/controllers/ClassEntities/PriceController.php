<?php

/*
 * Create by Activity Technology S.A.S.
 */

class PriceController extends Controller {

    public function getPrice($json, $startDate, $startTime, $idCompany) {
        try {          
            if (Price::model()->insertPrice($json, $idCompany)) {                   
                    return "OK";

            } else {
                return "NO";
            }         
        } catch (Exception $e) {
            $this->createLog('PriceController', 'getPrice', $e);
            return "NO";
        }
    }
    
    public function setPriceInfo($json,$tablename) {
        try {                             
            $response = Price::model()->setPriceInfo($json, $tablename); 
            if($response != null){
                $response = "OK";
            }
            else {
                $response = "NO";
            }
            return $response;            
            
        } catch (Exception $e) {
        $this->createLog('PriceController', 'getPrice', $e);
        return "NO";
        }  
        
    }
    
     public function getTableInfo($table) {
        try {                     
            $response = Price::model()->getTableInfo($table);                         
            
            return json_encode($response);
            
            
        } catch (Exception $e) {
        $this->createLog('CustomerController', 'getCustomers', $e);
        return "NO";
        }  
        
    }    
    
    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}



?>