<?php

/*
 * Create by Activity Technology SAS
 */

class AdminUsersController extends Controller {

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
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
            $AllUsers = json_decode($service->QueryAllUsers(json_encode($user)));
            session_start();
            $_SESSION['sellers'] = $AllUsers;
            echo $this->renderPartial('index', array('tabla_usuarios' => $AllUsers));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionIndex', $e);
        }
    }

    public function actionCreateUser() {
        try {
            echo $this->renderPartial('_createuser');
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionCreateUser', $e);
        }
    }

    public function actionCreateUserdata() {
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->DataforCreateUser();
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionCreateUserdata', $e);
        }
    }

    public function actionChangeUserStatus() {
        try {
            $Id = $_POST['id'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $StatusResponse = json_decode($service->ChangeUserStatus($Id));
            echo $StatusResponse[0]->result;
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionChangeUserStatus', $e);
        }
    }

    public function actionSaveUser() {
        try {
            $newuser = $_POST['newuser'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->SaveUser(json_encode($newuser)));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionSaveUser', $e);
        }
    }

    public function actionEditUser() {
        try {
            echo $this->renderPartial('_edituser');
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionEditUser', $e);
        }
    }

    public function actionEditUserdata() {
        try {
            $user = $_POST['id'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->DataforEditUser(json_encode($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionEditUserdata', $e);
        }
    }

    public function actionSellerFields() {
        try {
            $profile = $_POST['id'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->SellerFields(json_encode($profile)));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionSellerFields', $e);
        }
    }

    public function actionDeleteUser() {
        try {
            $user = $_POST['user'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->DeleteUser(json_encode($user)));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionDeleteUser', $e);
        }
    }

    public function actionQueryUserExistence() {
        try {
            $user = $_POST['newuser'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->QueryUserExistence(json_encode($user)));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionQueryUserExistence', $e);
        }
    }

    public function actionQueryUserExistenceEdit() {
        try {
            $user = $_POST['useredit'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->QueryUserExistenceEdit(json_encode($user)));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionSellerFields', $e);
        }
    }

    public function actionSaveUserEdit() {
        try {
            $user = $_POST['user'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo json_decode($service->SaveUserEdit(json_encode($user)));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'actionSaveUserEdit', $e);
        }
    }

    public function actiongetAllSellers() {
        try {
            session_start();
            echo json_encode($_SESSION['sellers']);
        } catch (Exception $e) {
            $this->createLog('AuthenticationController', 'actiongetAllSellers', $e);
        }
    }

}
