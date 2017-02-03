<?php

/*
 * Create by Activity Technology SAS
 */

class ReferenceController extends Controller {

    public function getAllReferences($user, $item) {
        try {
            $response = Reference::model()->getAllReferences($user, $item);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('ReferenceController', 'getAllReferences', $exc);
        }
    }
    
    public function getAllReferenceDetails($collections, $references, $priceList) {
        try {
            $response = Reference::model()->getAllReferenceDetails($collections, $references, $priceList);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('ReferenceController', 'getAllReferenceDetails', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}
