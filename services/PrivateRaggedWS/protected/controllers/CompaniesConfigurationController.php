<?php

/*
 * Create by Activity Technology SAS
 */

class CompaniesConfigurationController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function QueryAllCompanies($user) {
        try {
            return json_encode(CompaniesConfiguration::model()->getAllCompanies($user));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'QueryAllCompanies', $e);
        }
    }

    public function ChangeCompanyStatus($company) {
        try {
            return json_encode(CompaniesConfiguration::model()->setChangeCompanyStatus($company));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'ChangeCompanyStatus', $e);
        }
    }

    public function DataforEditCompany($company) {
        try {
            return json_encode(CompaniesConfiguration::model()->getDataforEditCompany($company));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'DataforEditCompany', $e);
        }
    }

    public function SaveCompanyEdit($data) {
        try {
            return json_encode(CompaniesConfiguration::model()->setCompanyEdit($data));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'SaveCompanyEdit', $e);
        }
    }

    public function LoadBusinessRules($Company) {
        try {
            return json_encode(CompaniesConfiguration::model()->getBusinessRules($Company));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'LoadBusinessRules', $e);
        }
    }

    public function ChangeBusinessStatus($BusinessRule) {
        try {
            return json_encode(CompaniesConfiguration::model()->setChangeBusinessStatus($BusinessRule));
        } catch (Exception $e) {
            $this->createLog('CompaniesConfigurationController', 'ChangeBusinessStatus', $e);
        }
    }

}
