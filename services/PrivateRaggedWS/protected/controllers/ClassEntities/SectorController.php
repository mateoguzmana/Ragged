<?php

/*
 * Create by Activity Technology SAS
 */

class SectorController extends Controller {

    public function getAllSectors($user, $item) {
        try {
            $response = Sector::model()->getAllSectors($user, $item);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('SectorController', 'getAllSectors', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}
