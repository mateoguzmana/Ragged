<?php

/*
 * Create by Activity Technology SAS
 */

class PrivilegesController extends Controller {

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
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $AllProfiles = $service->QueryAllProfiles();
            $AllPrivileges = $service->QueryAllPrivilegesTypes();
            echo $this->renderPartial('index', array('allprofiles' => $AllProfiles, 'allprivileges' => $AllPrivileges));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actionIndex', $e);
        }
    }

//Carga los permisos para toda la sesion
    public function actionLoadActions() {
        try {
            $user = $_POST['user'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryAllActions(json_encode($user));
            //$response=json_decode($response);
            //echo $AllActions;
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actionLoadActions', $e);
        }
    }

    public function actionLoadActionsSubmodule() {
        try {
            $user = $_POST['user'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryAllActionsSubmodule(json_encode($user));
            //$response=json_decode($response);
            //echo $AllActions;
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actionLoadActionsSubmodule', $e);
        }
    }

    public function actionEditProfile() {
        try {
            echo $this->renderPartial('_editprofile');
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actionEditProfile', $e);
        }
    }

    public function actionLoadCreateProfileView() {
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $allprofilestypes = $service->QueryAllProfilesTypes();
            echo $this->renderPartial('_createprofile', array('allprofilestypes' => $allprofilestypes));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actionLoadCreateProfileView', $e);
        }
    }

    public function actionCreateProfileQueryModules() {
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryAllModules();
            //echo $Modules;
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actionCreateProfileQueryModules', $e);
        }
    }

    public function actionCreateProfileQuerySubmodules() {
        try {
            $module = $_POST['module'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QuerySubmodule($module);
            //echo $SubModule;
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actionCreateProfileQuerySubmodules', $e);
        }
    }

    public function actionCreateProfileQueryOptions() {
        try {
            $submodule = $_POST['submodule'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryOptions($submodule);
            //echo $options;
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actionCreateProfileQueryOptions', $e);
        }
    }

    public function actiongetModSubOptPri() {
        try {
            session_start();
            //print_r($_SESSION['data']);
            echo $_SESSION['data'];
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'actiongetModSubOptPri', $e);
        }
    }

    public function actionallModSubOpt() {
        try {
            //$privilegeid = $_POST['privilege'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->allModSubOpt();
            //echo $service->allModSubOpt();
        } catch (Exception $e) {
            $this->createLog('AuthenticationController', 'actionallModSubOpt', $e);
        }
    }

    public function actionQueryProfileEdit() {
        try {
            $idprofile = $_POST['profile'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryProfileEdit($idprofile);
            //echo $arrayresp;
        } catch (Exception $e) {
            $this->createLog('AuthenticationController', 'actionallModSubOptEdit', $e);
        }
    }

    public function actionSaveProfile() {
        try {
            $arrjson = json_encode($_POST['profile']);
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->SaveProfile($arrjson);
            //echo $responseModSubOpt;
        } catch (Exception $e) {
            $this->createLog('AuthenticationController', 'actionSaveProfile', $e);
        }
    }

    public function actionSaveProfileEdit() {
        try {
            $arrjson = json_encode($_POST['profile']);
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->SaveProfileEdit($arrjson);
            //echo $responseModSubOpt;
        } catch (Exception $e) {
            $this->createLog('AuthenticationController', 'actionSaveProfileEdit', $e);
        }
    }

    public function actionDeleteProfile() {
        try {
            $profile = json_encode($_POST['profile']);
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->DeleteProfile($profile);
            //echo $responseModSubOpt;
        } catch (Exception $e) {
            $this->createLog('AuthenticationController', 'actionDeleteProfile', $e);
        }
    }

}
