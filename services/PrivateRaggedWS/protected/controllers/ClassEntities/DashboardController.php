<?php

class DashboardController extends Controller {

    public function getDataforDashboard($user) {
        try {
            return Dashboard::model()->getDataforDashboard($user);
        } catch (Exception $exc) {
            $this->createLog('DashboardController', 'getDataforDashboard', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}
