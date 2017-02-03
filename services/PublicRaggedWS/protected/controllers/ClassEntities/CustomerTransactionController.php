<?php

/*
 * Create by Activity Technology S.A.S.
 */

class CustomerTransactionController extends Controller {

    public function getCustomerTransaction($json, $startDate, $startTime, $idCompany) {
        try {                            
            if (CustomerTransaction::model()->insertCustomerTransaction($json, $idCompany)) {
                return "OK";
            } else {
                return "NO";
        }
        } catch (Exception $e) {
            $this->createLog('CustomerTransactionController', 'getOnHandProd', $e);
        }
    }
    
     public function getTableInfo($tablename) {
        try {                                                
            
            $info = CustomerTransaction::model()->getTableInfo($tablename);      
            return json_encode($info);
            
            
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