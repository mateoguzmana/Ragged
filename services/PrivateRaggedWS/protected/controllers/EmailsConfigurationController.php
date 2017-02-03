<?php

/*
 * Create by Activity Technology SAS
 */

class EmailsConfigurationController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function QueryAllCompaniesforEmails($user) {
        try {
            return json_encode(EmailsConfiguration::model()->getAllCompaniesforEmails($user));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'QueryAllCompaniesforEmails', $e);
        }
    }

    public function QueryDataforCreateEmail() {
        try {
            return json_encode(EmailsConfiguration::model()->getDataforCreateEmail());
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'QueryDataforCreateEmail', $e);
        }
    }

    public function QueryAllEmailsforCompany($data) {
        try {
            return json_encode(EmailsConfiguration::model()->getAllEmailsforCompany($data));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'QueryAllEmailsforCompany', $e);
        }
    }

    public function ChangeSendEmailStatus($SendEmail) {
        try {
            return json_encode(EmailsConfiguration::model()->setChangeSendEmailStatus($SendEmail));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'ChangeSendEmailStatus', $e);
        }
    }

    public function QueryEmailConfigurationExistence($Email) {
        try {
            return json_encode(EmailsConfiguration::model()->getEmailConfigurationExistence($Email));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'QueryEmailConfigurationExistence', $e);
        }
    }

    public function SaveNewEmailConfiguration($Email) {
        try {
            return json_encode(EmailsConfiguration::model()->setNewEmailConfiguration($Email));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'SaveNewEmailConfiguration', $e);
        }
    }

    public function DataforEditEmail($email) {
        try {
            return json_encode(EmailsConfiguration::model()->getDataforEditEmail($email));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'DataforEditEmail', $e);
        }
    }

    public function QueryEmailConfigurationExistenceEdit($Email) {
        try {
            return json_encode(EmailsConfiguration::model()->getEmailConfigurationExistenceEdit($Email));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'QueryEmailConfigurationExistence', $e);
        }
    }

    public function SaveEmailConfigurationEdit($data) {
        try {
            return json_encode(EmailsConfiguration::model()->setEmailConfigurationEdit($data));
        } catch (Exception $e) {
            $this->createLog('EmailsConfigurationController', 'SaveEmailConfigurationEdit', $e);
        }
    }

}
