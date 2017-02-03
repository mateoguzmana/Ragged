<?php

/*
 * Create by Activity Technology SAS
 */

class EmailsConfigurationController extends Controller {

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
            $data = json_decode($service->QueryAllCompaniesforEmails(json_encode($user)));
            /*echo '<pre>';
            print_r($data);
            exit();*/
            echo $this->renderPartial('index', array('data' => $data));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionIndex', $e);
        }
    }

    /*public function actionQueryAllEmailsforCompany() {
        try {
            $data = $_POST['data'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryAllEmailsforCompany(json_encode($data));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionQueryAllEmailsforCompany', $e);
        }
    }*/

    public function actionChangeSendEmailStatus() {
        try {
            $SendEmail = $_POST['SendEmail'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->ChangeSendEmailStatus(json_encode($SendEmail)));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionChangeSendEmailStatus', $e);
        }
    }

    public function actionCreateEmail() {
        try {
            echo $this->renderPartial('_createEmail');
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionCreateEmail', $e);
        }
    }

    public function actionCreateEmailData() {
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->QueryDataforCreateEmail();
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionCreateEmailData', $e);
        }
    }    
    
    public function actionQueryEmailConfigurationExistence() {
        try {
            $Email = $_POST['Email'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->QueryEmailConfigurationExistence(json_encode($Email)));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionQueryEmailConfigurationExistence', $e);
        }
    }
    
    public function actionSaveNewEmailConfiguration() {
        try {
            $Email = $_POST['Email'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->SaveNewEmailConfiguration(json_encode($Email)));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionSaveNewEmailConfiguration', $e);
        }
    }
    
    public function actionEditEmailsConfiguration() {
        try {
            echo $this->renderPartial('_editEmail');
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionEditEmailsConfiguration', $e);
        }
    }

    public function actionDataforEditEmail() {
        try {
            $email = $_POST['id'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->DataforEditEmail(json_encode($email));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionDataforEditEmail', $e);
        }
    }
    
        
    public function actionQueryEmailConfigurationExistenceEdit() {
        try {
            $Email = $_POST['Email'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->QueryEmailConfigurationExistenceEdit(json_encode($Email)));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionQueryEmailConfigurationExistenceEdit', $e);
        }
    }
    

    public function actionSaveEmailConfigurationEdit() {
        try {
            $data = $_POST['data'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->SaveEmailConfigurationEdit(json_encode($data)));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'actionSaveEmailConfigurationEdit', $e);
        }
    }

}
