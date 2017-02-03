<?php
    class DashboardController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function DataforDashboard($userName) {
        try {
            return json_encode(Dashboard::model()->getDataforDashboard($userName));            
        } catch (Exception $e) {
            $this->createLog('DashboardController', 'DataforDashboard', $e);
        }
    }

    public function DataforDashboardReport($date, $company) {
        try {
            return json_encode(Dashboard::model()->getDataforDashboardReport($date, $company));            
        } catch (Exception $e) {
            $this->createLog('DashboardController', 'DataforDashboardReport', $e);
        }
    }
}
