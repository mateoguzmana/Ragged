<?php

/*
 * Created By Activity Technology S.A.S.
 */

class RaggedProcessController extends Controller {

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
            $user = $_POST['user'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $AllProcess = json_decode($service->QueryAllProcess(json_encode($user)));
            echo $this->renderPartial('index', array('AllProcess' => $AllProcess));
        } catch (Exception $exc) {
            $this->createLog('RaggedProcessController', 'actionIndex', $exc);
        }
    }

    public function actionQueryListProcessExcecution() {
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryListProcessExcecution();
        } catch (Exception $exc) {
            $this->createLog('RaggedProcessController', 'actionQueryListProcessExcecution', $exc);
        }
    }

    public function actionQueryListProcessExcecutionUser() {
        try {
            $userquery = $_POST['userquery'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryListProcessExcecutionUser(json_encode($userquery));
        } catch (Exception $exc) {
            $this->createLog('RaggedProcessController', 'actionQueryListProcessExcecutionUser', $exc);
        }
    }

    public function actionQueryListProcessExcecutionDetail() {
        try {
            $Process = $_POST['process'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryListProcessExcecutionDetail(json_encode($Process));
        } catch (Exception $exc) {
            $this->createLog('RaggedProcessController', 'actionQueryListProcessExcecutionDetail', $exc);
        }
    }

    public function actionRunProcess() {
        try {
            $Process = $_POST['query'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->RunProcess(json_encode($Process));
        } catch (Exception $exc) {
            $this->createLog('RaggedProcessController', 'actionRunProcess', $exc);
        }
    }

    public function actionExcecuteCompleteProcess() {
        try {
            $Companies = $_POST['Companies'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->ExcecuteCompleteProcess(json_encode($Companies));
            /* $ListProcessExcecution = json_decode($service->ExcecuteCompleteProcess(json_encode($Companies)));
              print_r($ListProcessExcecution);
              exit(); */
        } catch (Exception $exc) {
            $this->createLog('RaggedProcessController', 'actionExcecuteCompleteProcess', $exc);
        }
    }

}
