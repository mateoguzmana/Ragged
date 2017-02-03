<?php

/*
 * Created By Activity Technology S.A.S.
 */

class MarkController extends Controller {

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

    public function actionAjaxLoad() {
        try {
            if ($_POST) {
                $user = $_POST['user'];
                $item = $_POST['item'];
                //print_r($item . " - " .$user['user'] );
                ini_set("soap.wsdl_cache_enabled", "1");
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $Marks = $service->SelectMarks($user['user'], $item);                
                
                $aux = json_decode($Marks);                                
                echo $this->renderPartial('/mark/viewmark', array('Marks' => json_encode($aux)));
            }
        } catch (Exception $exc) {
            $this->createLog('MarkController', 'actionAjaxLoad', $exc);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
