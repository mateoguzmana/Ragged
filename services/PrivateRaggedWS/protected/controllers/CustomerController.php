<?php

/*
 * Create by Activity Technology SAS
 */

    class CustomerController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function DataforCreateCustomer($userName) {
        try {
            return json_encode(Customer::model()->getDataforCreateCustomer($userName));            
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'DataforCreateCustomer', $e);
        }
    }
    
    public function getDepartmentCountry($country) {
        try {
            return json_encode(Customer::model()->getDepartmentCountry($country));            
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'getDepartmentCountry', $e);
        }
    }

}
