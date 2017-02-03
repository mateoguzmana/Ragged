<?php

/*
 * Create by Activity Technology S.A.S.
 */

class CollectionController extends Controller {

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
                /* Yii::app()->clientScript->registerScriptFile(
                  ///js/collections/collections.js
                  Yii::app()->baseUrl . '/js/collections/collections.js', CClientScript::POS_END
                  ); */
                $user = $_POST['user'];
                $item = $_POST['item'];
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $Collections = $service->SelectCollection($user['user'], $item);

                //echo $Collections;
                /* exit(); */

                echo $this->renderPartial('/collection/viewcollection', array('Collections' => $Collections));
            }
        } catch (Exception $e) {
            $this->createLog('CollectionController', 'actionLoad', $e);
        }
    }

    public function actionAjaxChangeStatusCollection() {
        try {

            if ($_POST) {
                $data = $_POST['data'];
                ini_set("soap.wsdl.cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");                
                $response = $service->UpdateStatusCollection(json_encode($data));
                echo $response;
            }
        } catch (Exception $e) {
            $this->createLog('CollectionController', 'actionAjaxChangeStatusCollection', $e);
        }
    }

    public function actionAjaxChangeAllStatusCollection() {
        try {
            ini_set("soap.wsdl_cache_enabled", 0);
            $company = $_POST['company'];
            $status = $_POST['status'];                   
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote", array('cache_wsdl' => WSDL_CACHE_NONE));                                    
            echo $service->UpdateAllStatusCollection($company,$status);             
        } catch (Exception $e) {            
            $this->createLog('CollectionController', 'actionAjaxChangeStatusCollection', $e);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
