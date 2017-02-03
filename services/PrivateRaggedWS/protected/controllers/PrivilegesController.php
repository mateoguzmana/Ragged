<?php

/*
 * Create by Activity Technology SAS
 */

class PrivilegesController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function QueryAllProfiles() {
        try {
            return json_encode(Privileges::model()->getProfilesTypes()); //procedimiento ya esta en adminusers
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryAllProfiles', $e);
        }
    }

    public function QueryAllActions($user) {
        try {
            return json_encode(Privileges::model()->getAllActions($user));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryAllActions', $e);
        }
    }

    public function QueryAllActionsSubmodule($user) {
        try {
            return json_encode(Privileges::model()->getAllActionsSubmodule($user));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryAllActionsSubmodule', $e);
        }
    }

    public function QueryProfileEdit($idprofile) {
        try {
            return json_encode(Privileges::model()->getProfileEdit($idprofile));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryProfileEdit', $e);
        }
    }

    public function QueryAllModules() {
        try {
            return json_encode(Privileges::model()->getAllModules());
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryAllModules', $e);
        }
    }

    public function QueryAllSubmodules() {
        try {
            return json_encode(Privileges::model()->getAllSubmodules());
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryAllSubmodules', $e);
        }
    }

    public function QueryAllOptions() {
        try {
            return json_encode(Privileges::model()->getAllOptions());
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryAllOptions', $e);
        }
    }

    public function GetModulesWeb($json) {
        try {
            return Privileges::model()->GetModulesWeb($json);
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'GetModulesWeb', $e);
        }
    }

    public function QuerySubmodule($module) {
        try {
            return json_encode(Privileges::model()->GetQuerySubmodule($module));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QuerySubmodule', $e);
        }
    }

    public function QueryOptions($submodule) {
        try {
            return json_encode(Privileges::model()->GetQueryOptions($submodule));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryOptions', $e);
        }
    }

    public function allModSubOpt() {
        try {
            return json_encode(Privileges::model()->GetallModSubOpt());
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'allModSubOpt', $e);
        }
    }

    public function QueryAllPrivilegesTypes() {
        try {
            return json_encode(Privileges::model()->getAllPrivilegesTypes());
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryAllPrivilegesTypes', $e);
        }
    }

    public function QueryAllProfilesTypes() {
        try {
            return json_encode(Privileges::model()->getAllProfilesTypes());
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'QueryAllProfilesTypes', $e);
        }
    }

    public function SaveProfile($profile) {
        try {
            return json_encode(Privileges::model()->setSaveProfile($profile));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'SaveProfile', $e);
        }
    }

    public function SaveProfileEdit($profile) {
        try {
            return json_encode(Privileges::model()->setSaveProfileEdit($profile));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'SaveProfileEdit', $e);
        }
    }

    public function DeleteProfile($profile) {
        try {
            return json_encode(Privileges::model()->setDeleteProfile($profile));
        } catch (Exception $e) {
            $this->createLog('PrivilegesController', 'DeleteProfile', $e);
        }
    }

}