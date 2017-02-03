<?php

/*
 * Create by Activity Technology SAS
 */

class AdminUsersController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function QueryAllUsers($user) {
        try {
            return json_encode(AdminUsers::model()->getQueryAllUsers($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'QueryAllUsers', $e);
        }
    }

    public function DocumentsTypes() {
        try {
            return AdminUsers::model()->getDocumentsTypes();
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'DocumentsTypes', $e);
        }
    }

    public function ProfilesTypes() {
        try {
            return json_encode(AdminUsers::model()->getProfilesTypes());
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'ProfilesTypes', $e);
        }
    }

    public function PrivilegesTypes() {
        try {
            return json_encode(AdminUsers::model()->getPrivilegesTypes());
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'PrivilegesTypes', $e);
        }
    }

    public function setChangeUserStatus($userid) {
        try {
            return json_encode(AdminUsers::model()->setChangeUserStatus($userid));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'PrivilegesTypes', $e);
        }
    }

    public function DataforCreateUser() {
        try {
            return json_encode(AdminUsers::model()->getDataforCreateUser());
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'SaveUser', $e);
        }
    }

    public function SaveUser($user) {
        try {
            return json_encode(AdminUsers::model()->setnewuser($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'SaveUser', $e);
        }
    }

    public function DataforEditUser($user) {
        try {
            return json_encode(AdminUsers::model()->getDataforEditUser($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'SaveUser', $e);
        }
    }

    public function EditUser($user) {
        try {
            return json_encode(AdminUsers::model()->setnewuser($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'SaveUser', $e);
        }
    }

    public function SellerFields($profile) {
        try {
            return json_encode(AdminUsers::model()->getSellerFields($profile));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'SellerFields', $e);
        }
    }

    public function DeleteUser($user) {
        try {
            return json_encode(AdminUsers::model()->setDeleteUser($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'DeleteUser', $e);
        }
    }

    public function QueryUserExistence($user) {
        try {
            return json_encode(AdminUsers::model()->getUserExistence($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'DeleteUser', $e);
        }
    }

    public function QueryUserExistenceEdit($user) {
        try {
            return json_encode(AdminUsers::model()->getUserExistenceEdit($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'DeleteUser', $e);
        }
    }

    public function SaveUserEdit($user) {
        try {
            return json_encode(AdminUsers::model()->setSaveUserEdit($user));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'DeleteUser', $e);
        }
    }

    public function getUserCompany($company) {
        try {
            return json_encode(AdminUsers::model()->getUserCompany($company));
        } catch (Exception $e) {
            $this->createLog('AdminUsersController', 'getUserCompany', $e);
        }
    }

}
