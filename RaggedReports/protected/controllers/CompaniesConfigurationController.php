<?php

/*
 * Create by Activity Technology SAS
 */

class CompaniesConfigurationController extends Controller {

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
            $AllCompanies = json_decode($service->QueryAllCompanies(json_encode($user)));
            /*echo '<pre>';
              print_r($AllCompanies);
              exit();*/
            echo $this->renderPartial('index', array('AllCompanies' => $AllCompanies));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'actionIndex', $e);
        }
    }

    public function actionChangeCompanyStatus() {
        try {
            $Company = $_POST['Company'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->ChangeCompanyStatus(json_encode($Company)));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'actionChangeCompanyStatus', $e);
        }
    }

    public function actionEditCompany() {
        try {
            echo $this->renderPartial('_editCompany');
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'actionEditCompany', $e);
        }
    }

    public function actionDataforEditCompany() {
        try {
            $company = $_POST['id'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->DataforEditCompany(json_encode($company));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'actionDataforEditCompany', $e);
        }
    }

    public function actionSaveCompanyEdit() {
        try {
            $data = $_POST['data'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->SaveCompanyEdit(json_encode($data)));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'actionChangeCompanyStatus', $e);
        }
    }

    public function actionLoadBusinessRules() {
        try {
            $Company = $_POST['Company'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->LoadBusinessRules(json_encode($Company));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'actionChangeCompanyStatus', $e);
        }
    }

    public function actionChangeBusinessStatus() {
        try {
            $BusinessRule = $_POST['BusinessRule'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->ChangeBusinessStatus(json_encode($BusinessRule)));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'actionChangeCompanyStatus', $e);
        }
    }

}
