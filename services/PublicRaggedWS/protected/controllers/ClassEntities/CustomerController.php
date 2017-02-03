<?php

/*
 * Create by Activity Technology S.A.S.
 */

class CustomerController extends Controller {

    public function getCustomer($json, $startDate, $startTime, $idCompany, $update) {
        try {                                      
            if (Customer::model()->insertCustomers($json, $idCompany, $update)) {   
                if ($update)                
                    $response = DatabaseUtilities::model()->callStoredProcedure("`sp_getcustomerinfoforsync`($idCompany)");                                                    
                else
                    $response = DatabaseUtilities::model()->callStoredProcedure("`sp_getcustomersbydateandtime`('$startDate',' $startTime','$idCompany')");                                    
                if ($response)
                    return json_encode($response);                
                else
                    return "[]";
            } else {
                return "[]";
            }        
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'getCustomer', $e);
            return "[]";

        }
    }
    
    public function getRelatedCustomerTables($identificationTypes, $currencies, $saleDistricts, $taxGroups) {
        try { 
            $identificationTypesValues = json_decode($identificationTypes);
            $currenciesValues = json_decode($currencies);
            $saleDistrictsValues = json_decode($saleDistricts);              
            $taxGroupsValues = json_decode($taxGroups);              
            
            $tableValues = array();
            
            $table = "tbls_typesidentitys";            
            $tableValues['identificationTypes'] = Customer::model()->updateAndGetTables($table, $identificationTypesValues);               
            
            $table = "tbls_currencies";            
            $tableValues['currencies'] = Customer::model()->updateAndGetTables($table, $currenciesValues);   
            
            $table = "tbls_salesgroups";            
            $tableValues['saleDistricts'] = Customer::model()->updateAndGetTables($table, $saleDistrictsValues);              
            
            $table = "tbls_formspaymentsdetails";            
            $tableValues['formspayments'] = Customer::model()->updateAndGetTables($table, null);              
            
            $table = "tbls_marks";            
            $tableValues['marks'] = Customer::model()->updateAndGetTables($table, null);              
            
            $table = "tbls_taxgroups";            
            Customer::model()->updateCustomersInfo($taxGroups, $table);                          
            $tableValues['taxgroup'] = Customer::model()->updateAndGetTables($table, null);                                     
            
            return json_encode($tableValues);
            
            
        } catch (Exception $e) {
        $this->createLog('CustomerController', 'getCustomers', $e);
        return "NO";
        }  
        
    }
    
    public function setCustomersInfo($json,$tablename) {
        try {                 
            $response = Customer::model()->updateCustomersInfo($json, $tablename); 
            if($response != null){
                $response = "OK";
            }
            else {
                $response = "NO";
            }
            return $response;            
            
        } catch (Exception $e) {
        $this->createLog('CustomerController', 'getCustomers', $e);
        return "NO";
        }  
        
    } 
    
    public function getUsersSellers($json) {
        try {  
            
            $response = Customer::model()->insertUserSellers($json);              
            return $response;
            
            
        } catch (Exception $e) {
        $this->createLog('CustomerController', 'getCustomers', $e);
        return "NO";
        }  
        
    }  
    
      public function getSellersInfo() {
        try {  
            
            $response = DatabaseUtilities::model()->callStoredProcedure("sp_getsellerusersid()");
            return json_encode($response);
            
            
        } catch (Exception $e) {
        $this->createLog('CustomerController', 'getCustomers', $e);
        return "NO";
        }  
        
    }  
    
    
    
     public function getFilters() {
        try {               
            $response = Customer::model()->getFilters();              
            return $response;            
            
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