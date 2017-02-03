<?php

/*
 * Create by Activity Technology SAS
 */

class MarkController extends Controller {

    public function getAllMarks($user, $item) {
        try {
            $response = Mark::model()->getAllMarks($user, $item);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('MarkController', 'getAllMarks', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}