<?php

/*
 * Create by Activity Technology SAS
 */

class PriceController extends Controller {

    public function getAllPriceLists($user, $item) {
        try {            
            $response = Price::model()->getAllPriceLists($user, $item);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('PriceController', 'getAllPriceLists', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}
