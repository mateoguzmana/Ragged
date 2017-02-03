<?php

/*
 * Create by Activity Technology SAS
 */

class ProfileTypesController extends Controller {

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
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $AllProfilesTypes = $service->QueryAllProfilesTypes();
            $this->renderPartial('/profilestypes/viewprofiletypes', array('allprofilestypes' => $AllProfilesTypes));
        } catch (Exception $ex) {
            $this->createLog('ProfileTypesController', 'actionAjaxLoad', $ex);
        }
    }

    public function actionEditProfileType() {
        try {
            echo $this->renderPartial('/profilestypes/_editprofiletype');
        } catch (Exception $e) {
            $this->createLog('ProfileTypesController', 'actionEditProfileType', $e);
        }
    }

    public function actionLoadCreateProfileTypeView() {
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $allprofilestypes = $service->QueryAllProfilesTypes();
            echo $this->renderPartial('/profilestypes/_createprofiletype', array('allprofilestypes' => $allprofilestypes));
        } catch (Exception $e) {
            $this->createLog('ProfileTypesController', 'actionLoadCreateProfileTypeView', $e);
        }
    }

    public function actionDeleteProfileType() {
        try {
            $profiletype = json_encode($_POST['profiletype']);
            /* print_r($profile);
              exit(); */
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->DeleteProfileType($profiletype);
            //echo $responseModSubOpt;
        } catch (Exception $e) {
            $this->createLog('ProfileTypesController', 'actionDeleteProfileType', $e);
        }
    }

    public function actionSaveProfileType() {
        try {
            $arrjson = json_encode($_POST['profiletype']);
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->SaveProfileType($arrjson);
            //echo $responseModSubOpt;
        } catch (Exception $e) {
            $this->createLog('ProfileTypesController', 'actionSaveProfileType', $e);
        }
    }

    public function actionSaveProfileTypeEdit() {
        try {
            $arrjson = json_encode($_POST['profiletype']);
           ini_set("soap.wsdl_cache_enabled", "0");
           $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
           echo $service->SaveProfileTypeEdit($arrjson);
           //echo $responseModSubOpt;
        } catch (Exception $e) {
            $this->createLog('ProfileTypesController', 'actionSaveProfileTypeEdit', $e);
        }
    }

}
