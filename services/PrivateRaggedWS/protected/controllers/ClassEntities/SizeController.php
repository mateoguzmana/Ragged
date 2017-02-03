<?php

/*
 * Create by Activity Technology SAS
 */

class SizeController extends Controller {

    public function getAllSizes ($user, $item) {
        try {
            
            $response = Size::model()->getAllSizes($user, $item);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('SizeController', 'getAllSizes', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}
