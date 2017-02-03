<?php

/*
 * Create by Activity Technology SAS
 */

class CustomerController extends Controller {

    public function getAllCustomers($user, $item) {
        try {
            $response = Customer::model()->getAllCustomers($user, $item);
            $counter = 0;
            foreach ($response['datas'] as $key => $val) {
                foreach ($val as $ky => $vl) {
                    $columns[$counter] = $ky;
                    $counter++;
                }
                break;
            }
            $counter = 0;
            for ($i = 0; $i < count($columns); $i++) {
                for ($j = 0; $j < count($response['config']); $j++) {
                    if ($response['config'][$j]['columndescription'] == $columns[$i] && $response['config'][$j]['hide'] == "1") {
                        $hide[$counter] = $i + 1;
                        $counter++;
                    }
                }
            }
            $response['columns_hide'] = $hide;
            return json_encode($response);
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'getAllCustomers', $exc);
        }
    }

    public function getAllCustomersWallet($customers) {
        try {
            return Customer::model()->getAllCustomersWallet($customers);
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'getAllCustomersWallet', $exc);
        }
    }

    public function getAllCustomersSalePoints($customer) {
        try {
            return Customer::model()->getAllCustomersSalePoints($customer);
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'getAllCustomersSalePoints', $exc);
        }
    }

    public function getDepartmentCountry($company) {
        try {
            return Customer::model()->getDepartmentCountry($company);
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'getDepartmentCountry', $exc);
        }
    }

    public function getCitiesDepartment($department) {
        try {
            return Customer::model()->getCitiesDepartment($department);
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'getCitiesDepartment', $exc);
        }
    }

    public function getCompanyCustomerByUser($userName) {
        try {
            $response = Customer::model()->getCompanyCustomerByUser($userName);
            return json_encode($response);
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'getCompanyCustomerByUser', $exc);
        }
    }

    public function CheckCustomerExists($accountCustomer) {
        try {
            $response = Customer::model()->CheckCustomerExists($accountCustomer);
            return json_encode($response);
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'CheckCustomerExists', $exc);
        }
    }

    public function saveCustomer($jsonData, $userName) {
        try {

            $response = Customer::model()->saveCustomer($jsonData, $userName);
            return json_encode($response);
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'saveCustomer', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
