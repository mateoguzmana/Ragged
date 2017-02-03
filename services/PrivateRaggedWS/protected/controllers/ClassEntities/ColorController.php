<?php

/*
 * Create by Activity Technology SAS
 */

class ColorController extends Controller {

    public function getAllColors($user, $item) {
        try {
            return Color::model()->getAllColors($user, $item);
        } catch (Exception $exc) {
            $this->createLog('ColorController', 'getAllColors', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}
