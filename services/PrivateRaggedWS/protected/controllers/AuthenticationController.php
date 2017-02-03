<?php

/*
 * Create by Activity Technology SAS
 */

//Yii::import('application.protected.controllers.ControllerUtilities');
class AuthenticationController extends Controller {

    public function actions() {
        return array(
            'quote' => array(
                'class' => 'CWebServiceAction',
            ),
        );
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    /**
     * @return string
     * @soap
     */
    public function testService() {
        return "OK";
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function validateAuthentication($user) {
        try {
            $arrResponse = Authentication::model()->getValidateAuthentication($user);            
            return json_encode(array('status' => $arrResponse[0]['result'], 'values' => ($arrResponse[0]['result'] == "OK") ? $this->GetModulesWeb($user) : null));
            //return json_encode($loginArray);
        } catch (Exception $e) {
            $this->createLog('AuthenticationController', 'validateAuthentication', $e);
        }
    }

    public function GetModulesWeb($json) {
        try {
            return Authentication::model()->GetModulesWebService($json);
            //return $ModulesWeb;
        } catch (Exception $e) {
            $this->createLog('AuthenticationController', 'GetModulesWebService', $e);
        }
    }

}
