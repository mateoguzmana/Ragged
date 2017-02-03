<?php

class Dashboard extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getDataforDashboard($userName) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $createuserv = array();

            $data['companies']          = $this->excecuteDatabaseStoredProcedures("`sp_getcompaniesforselect`();");
            
            return $data;
        } catch (Exception $e) {
            $this->createLog('DataforDashboard', 'getDataforDashboard', $e);
        }
    }

    public function getDataforDashboardReport($date, $company) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $createuserv = array();

            $dateformat = explode("-", $date);
            $year = $dateformat[0];
            $month= $dateformat[1];
            $date3 = $year."-".$month."-01";
            $date4 = $year."-".$month."-31";

            $data['sellers']          = $this->excecuteDatabaseStoredProcedures("`sp_getsellersbycompanyanddate`('$company', '$date');");
            $data['activesellers']    = $this->excecuteDatabaseStoredProcedures("`sp_getsellersactivesbycompany`('$company');");
            $data['activecustomers']  = $this->excecuteDatabaseStoredProcedures("`sp_getcustomersactivesbycompany`('$company');");
            $data['customers']        = $this->excecuteDatabaseStoredProcedures("`sp_getcustomersbycompanyanddate`('$company', '$date');");

            $data['orders']           = $this->excecuteDatabaseStoredProcedures("`sp_getordersbycompanyanddate`('$company', '$date');");
            $data['ordersmonth']      = $this->excecuteDatabaseStoredProcedures("`sp_getordersbycompanyandmonth`('$company', '$date3', '$date4');");
            $data['toporders']        = $this->excecuteDatabaseStoredProcedures("`sp_gettopordersbycompanyandmonth`('$company', '$date3', '$date4');");

            return $data;
        } catch (Exception $e) {
            $this->createLog('DataforDashboardRepor', 'getDataforDashboardReport', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
