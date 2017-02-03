<?php

class Authentication extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function getValidateAuthentication($login) {
        try {
            $login = json_decode($login);
            return $this->excecuteDatabaseStoredFunctions("`fn_validateexistinguser`('" . $login->user . "','" . $login->pass . "')");
        } catch (Exception $e) {
            $this->createLog('Authentication', 'getValidateAuthentication', $e);
        }
    }

    public function GetModulesWebService($json) {
        try {
            $json = json_decode($json);
            $Modules = $this->excecuteDatabaseStoredProcedures("`sp_getuserspermissionsmodules`('$json->user','$json->pass');");
            $arrModulesSubmodules = array();
            $userMS = array();
            foreach ($Modules as $module) {                
                $arrayPermissions = array();
                $idmodule = $module['idmodule'];
                $arrModulesSubmodules = $this->excecuteDatabaseStoredProcedures("`sp_getuserspermissionssubmodules`('$json->user','$json->pass','$idmodule');");
                $objuserMS = new stdClass();                 
                $arrayPermissions['Modules'] = array('id'=>$module['idmodule'],'sourcecode'=>$module['idsourcecode'],'description'=>$module['moduledescription']);
                $arrayPermissions['Submodules'] = $arrModulesSubmodules;
                array_push($userMS, $arrayPermissions);
            }
            return $userMS;
        } catch (Exception $e) {
            $this->createLog('Authentication', 'GetModulesWebService', $e);
        }
    }

}
