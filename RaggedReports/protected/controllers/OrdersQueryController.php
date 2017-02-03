<?php

/*
 * Created By Activity Technology S.A.S.
 */

class OrdersQueryController extends Controller {

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

    public function actionIndex() {
        try {
            if ($_POST) {
                $user = $_POST['user'];
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $AllOrders = json_decode($service->QueryAllOrders(json_encode($user)));
                /* echo '<pre>';
                  print_r($AllOrders);
                  exit(); */
                echo $this->renderPartial('index', array('AllOrders' => $AllOrders));
            }
        } catch (Exception $exc) {
            $this->createLog('OrdersQueryController', 'actionIndex', $exc);
        }
    }

    public function actionQueryOrders() {
        try {
            if ($_POST) {
                $userquery = $_POST['userquery'];
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                echo $service->QueryOrders(json_encode($userquery));
                /* $Orders = $service->QueryOrders(json_encode($userquery));
                  print_r($Orders);
                  exit(); */
            }
        } catch (Exception $exc) {
            $this->createLog('OrdersQueryController', 'actionQueryOrders', $exc);
        }
    }

    public function actionEditOrdersQuery() {
        try {
            echo $this->renderPartial('_editOrder');
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'actionEditOrdersQuery', $e);
        }
    }

    public function actionDataforEditOrders() {
        try {
            $Order = $_POST['id'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->DataforEditOrder(json_encode($Order));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'actionDataforEditOrder', $e);
        }
    }

    public function actionChangeOrderDetails() {
        try {
            $orderdetail = $_POST['orderdetail'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->ChangeOrderDetails(json_encode($orderdetail)));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'actionChangeOrderDetails', $e);
        }
    }

    public function actionQueryBusinessRulesValidation() {
        try {
            $Order = $_POST['order'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryBusinessRulesValidation(json_encode($Order));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'actionQueryBusinessRulesValidation', $e);
        }
    }

    public function actionChangeValidationBusinessRule() {
        try {
            $ValBusRule = $_POST['VBR'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->ChangeValidationBusinessRule(json_encode($ValBusRule)));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'actionChangeValidationBusinessRule', $e);
        }
    }

    public function actionOrdersQueryRender() {
        try {
            echo $this->renderPartial('index');
        } catch (Exception $exc) {
            $this->createLog('OrdersQueryController', 'actionIndex', $exc);
        }
    }

}
