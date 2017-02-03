<?php

/*
 * Create by Activity Technology SAS
 */

class LoginController extends Controller {

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

    public function actionLogin() {
        try {
            $userlogin = $_POST['client'];
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=Authentication/quote");
            echo $service->validateAuthentication(json_encode($userlogin));
        } catch (Exception $e) {
            $this->createLog('LoginController', 'actionLogin', $e);
        }
    }

    public function actionIndex() {
        try {
            session_start();
            if ($_SESSION['data'] != '') {
                echo $this->render('index');
            } else {
                $this->redirect(Yii::app()->homeUrl);
            }
        } catch (Exception $e) {
            $this->createLog('LoginController', 'actionIndex', $e);
        }
    }

    public function actionLoad() {
        try {
            session_start();
            $_SESSION['data'] = json_encode($_POST['data']);
        } catch (Exception $e) {
            $this->createLog('LoginController', 'actionLoad', $e);
        }
    }

    public function actionUserOptions() {
        try {
            session_start();
            $_SESSION['data'] = json_encode($_POST['data']);
        } catch (Exception $e) {
            $this->createLog('LoginController', 'actionUserOptions', $e);
        }
    }

}
