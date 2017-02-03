<?php

/*
 * Create by Activity Technology S.A.S.
 */

class LineController extends Controller {

    public function getAllLines($user, $item) {
        try {
            return Line::model()->getAllLines($user, $item);
        } catch (Exception $exc) {
            $this->createLog('LineController', 'getAllLines', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}
