<?php

/*
 * Create by Activity Technology SAS
 */

class DashboardController extends Controller {

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function actionAjaxLoad() {

        try {
            $user = $_POST['user'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $AllDashboard = $service->DataforDashboard($user);
            $this->renderPartial('/dashboard/viewdashboard', array('alldashboard' => $AllDashboard));
        } catch (Exception $ex) {
            $this->createLog('DashboardController', 'actionAjaxLoad', $ex);
        }
    }

    public function actionAjaxLoadReport() {

        try {
            $date    = $_POST['date'];
            $company = $_POST['company'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $DataDashboardReport = $service->DataforDashboardReport($date, $company);
            $this->renderPartial('/dashboard/dashboardreport', array('DataDashboardReport' => $DataDashboardReport));
        } catch (Exception $ex) {
            $this->createLog('DashboardController', 'actionAjaxLoad', $ex);
        }
    }

}
