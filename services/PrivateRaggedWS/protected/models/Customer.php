<?php

class Customer extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAllCustomers($user, $itemlst) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            //$customers = array();
            $table = 'tbls_customers';
            $table_encrypted = $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");

            $Alias = array();
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $Alias[] = $value['columndescription'];
            }

            $customers['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies`('$user');");

            $profileType = $this->excecuteDatabaseStoredProcedures("`sp_getuserprofiletype`('$user');");

            foreach ($profileType as $item) {
                $idProfileType = $item['idprofiletype'];
            }

            if ($idProfileType == 5) {
                $seller = $this->excecuteDatabaseStoredProcedures("`sp_getselleridbyusername`('$user');");
                foreach ($seller as $item) {
                    $sellerId = $item['idseller'];
                }
                $customers['datas'] = $this->excecuteDatabaseStoredProcedures("`sp_getcustomersellerinfoforrview`('$sellerId');");
            } else {
                $customers['datas'] = $this->excecuteDatabaseStoredProcedures("`sp_getcustomersinforview`();");
            }

            for ($i = 0; $i < count($customers['datas']); $i++) {
                $j = 0;
                foreach ($customers['datas'][$i] as $k1 => $v1) {
                    $customers['datas'][$i][$Alias[$j]] = $customers['datas'][$i][$k1];
                    unset($customers['datas'][$i][$k1]);
                    $j++;
                }
            }

            $customers['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $customers['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$itemlst');");
            return $customers;
        } catch (Exception $exc) {
            $this->createLog('Customer', 'getAllCustomers', $exc);
        }
    }

    public function getAllCustomersWallet($customers, $routers) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $table = "tbls_customerstransactions";
            $table_encrypted = $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $Alias = array();
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $Alias[] = $value['columndescription'];
            }

            $values = json_decode($customers);
            $where = "";
            $i = 0;
            foreach ($values as $row) {
                if ($i == 0) {
                    $where .= "CTrans.idcustomer=" . $row . " ";
                } else {
                    $where .= "OR CTrans.idcustomer=" . $row . " ";
                }
                $i++;
            }
            $customersWallet = array();
            
            $endDate = date('Y-m-d', time());
            $date2 = '0001-00-00';
            $datetime1 = date_create($endDate);
            $datetime2 = date_create($date2);
            $interval = date_diff($datetime1, $datetime2);
            $differenceFormat = "%y-%m-%d";
            $startDate = $interval->format($differenceFormat);

            $startDate = "\'" . $startDate . "\'";
            $endDate = "\'" . $endDate . "\'";

            $customersWallet['datas'] = $this->excecuteDatabaseStoredProcedures("`sp_getcustomerwallet`('$where','$startDate','$endDate');");

            for ($i = 0; $i < count($customersWallet['datas']); $i++) {
                $j = 0;
                foreach ($customersWallet['datas'][$i] as $k1 => $v1) {
                    $customersWallet['datas'][$i][$Alias[$j]] = $customersWallet['datas'][$i][$k1];
                    unset($customersWallet['datas'][$i][$k1]);
                    $j++;
                }
            }
            $customersWallet['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            return json_encode($customersWallet);
        } catch (Exception $exc) {
            $this->createLog('Customer', 'getAllCustomersWallet', $exc);
        }
    }

    public function getAllCustomersSalePoints($customer) {
        try {

            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $table = "tbls_salespoints";
            $table_encrypted = $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");

            $columns = "";
            foreach ($config as $value) {
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);

            $tables[0] = "tbls_salespoints";
            $tables[1] = "tbls_customers";

            $alias[0] = "SALEPOINT";
            $alias[1] = "CUSTOMER";
            $alias[2] = "ROUTER";

            $primary[0] = "";
            $foreign[0] = "";
            $primary[1] = "idcustomer";
            $foreign[1] = "idcustomer";
            $primary[2] = "idcustomer";
            $foreign[2] = "idcustomer";

            $condition = "";

            $where = " AND CUSTOMER.idcustomer = " . $customer;

            $tablesArray = array();

            for ($i = 0; $i < count($tables); $i++) {
                $tablesArray[$i]['table'] = $tables[$i];
                $tablesArray[$i]['alias'] = $alias[$i];
                $tablesArray[$i]['references']['type']['primary'] = $alias[$i] . "." . $primary[$i];
                $tablesArray[$i]['references']['type']['foreign'] = $alias[$i - 1] . "." . $foreign[$i];
                $tablesArray[$i]['references']['type']['condition'] = $condition;
            }
            $innerJoin = $this->createInnerJoinMultiple($tablesArray);
            $query = $this->createBasicSelectWithWhere($columns, $innerJoin, $where);
            $customersSalePoints['datas'] = $this->excecuteQueryAll($query);
            $customersSalePoints['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            return json_encode($customersSalePoints);
        } catch (Exception $exc) {
            $this->createLog('Customer', 'getAllCustomersWallet', $exc);
        }
    }

    public function getDataforCreateCustomer($userName) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $createuserv = array();

            $table = 'tbls_customers';

            $createcustomer['data'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableconfig`('" . $cryptography->Encrypting($table) . "');");

            foreach ($createcustomer['data'] as $key => $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $unencrypted = array($key => $item);
                $createcustomer['data'] = array_replace($createcustomer['data'], $unencrypted);
            }

            foreach ($createcustomer['data'] as $item) {
                if ($item['storagemethod'] != null) {
                    $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                }
            }
            $createcustomer['storagemethod'] = $profilestypes;
            $createcustomer['departments'] = $this->excecuteDatabaseStoredProcedures("`sp_getalldepartments`();");
            $createcustomer['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$userName', 'itemlstCustomers');");

            return $createcustomer;
        } catch (Exception $e) {
            $this->createLog('Customer', 'getDataforCreateCustomer', $e);
        }
    }

    public function getDepartmentCountry($country) {
        try {
            return $this->excecuteDatabaseStoredProcedures("sp_getdepartmentbycountry($country);");
        } catch (Exception $e) {
            $this->createLog('Customer', 'getDepartmentCountry', $e);
        }
    }

    public function getCitiesDepartment($department) {
        try {
            return $this->excecuteDatabaseStoredProcedures("sp_getcitybydepartment($department);");
        } catch (Exception $e) {
            $this->createLog('Customer', 'getCitiesDepartment', $e);
        }
    }

    public function getCompanyCustomerByUser($userName) {
        try {
            return $this->excecuteDatabaseStoredProcedures("sp_getusercompanies('$userName');");
        } catch (Exception $e) {
            $this->createLog('Customer', 'getCompanyCustomerByUser', $e);
        }
    }

    public function CheckCustomerExists($accountCustomer) {
        try {
            return $this->excecuteDatabaseStoredProcedures("sp_customerexists ('$accountCustomer');");
        } catch (Exception $e) {
            $this->createLog('Customer', 'getCompanyCustomerByUser', $e);
        }
    }

    public function saveCustomer($jsonData, $userName) {
        try {

            $dataCustomer = json_decode($jsonData);

            $dataCustomer->reasonsocial = $dataCustomer->secondname != '' ? trim($dataCustomer->lastname . ' ' . $dataCustomer->secondname . ' ' . $dataCustomer->surname . ' ' . $dataCustomer->secondsurname) : trim($dataCustomer->lastname . ' ' . $dataCustomer->surname . ' ' . $dataCustomer->secondsurname);
            $dataCustomer->idsalezone = 7;
            $dataCustomer->idcustomerstate = 1;
            $dataCustomer->idcustomergroup = 1;
            $dataCustomer->creationdate = "CURDATE()";
            $dataCustomer->modificationdate = "CURDATE()";
            $dataCustomer->datelastupdate = "CURDATE()";
            $dataCustomer->timelastupdate = "CURTIME()";
            $dataCustomer->taxgroup = $dataCustomer->idcompany == '1' ? 11 : 13;
            $dataCustomer->idtypecustomer = 2;
            $dataCustomer->idformpaymentdetail = 8;

            $tableCustomer = 'tbls_customers';

            $columns = "";
            $values = "";

            foreach ($dataCustomer as $key => $value) {
                if (($key != 'address') && ($key != 'city')) {
                    $columns .= $key . ",";
                    $values .= ($value == 'CURDATE()') || ($value == 'CURTIME()') ? $value . "," : "'$value',";
                }
            }

            $columns = substr($columns, 0, -1);
            $values = substr($values, 0, -1);

            $this->insertbasic($tableCustomer, $columns, $values);

            $arrayIdcustomer = $this->excecuteDatabaseStoredProcedures("sp_getcustomerid ('$dataCustomer->accountcustomer');");
            $arraySeller = $this->excecuteDatabaseStoredProcedures("`sp_getselleridbyusername`('$userName');");

            foreach ($arrayIdcustomer as $data) {
                $idcustomer = $data['idcustomer'];
            }

            foreach ($arraySeller as $item) {
                $sellerId = $item['idseller'];
            }

            $this->excecuteDatabaseStoredProcedures("`sp_updatenewcustomeridstate`('$idcustomer');");

            //Insert sale point
            $tableSalePoint = 'tbls_salespoints';

            $columnsSalePoint = "idcustomer, nameaddress, address, city";
            $valuesSalePoint = "'$idcustomer', '" . $dataCustomer->reasonsocial . "', '" . $dataCustomer->address . "', '" . $dataCustomer->city . "'";

            $this->insertbasic($tableSalePoint, $columnsSalePoint, $valuesSalePoint);

            //Routers
            $tableRouters = 'tbls_routers';

            $columnsRouters = "idseller, idcustomer";
            $valuesRouters = "'$sellerId', '$idcustomer'";

            $this->insertbasic($tableRouters, $columnsRouters, $valuesRouters);

            //customer currencies
            $tableCurrencies = 'tbls_customerscurrencies';
            $currency = 8; // 8 = N/R
            $columnsCurrencies = "idcustomer, idcurrency";
            $valuesCurrencies = "'$idcustomer', '$currency'";

            $this->insertbasic($tableCurrencies, $columnsCurrencies, $valuesCurrencies);

            //tbls_transactions 
            $tableTransact = 'tbls_transactions';

            $columnsTransct = "number, creationdate, creationtime, idstatetransaction, idtypetransaction, idcompany, idstateemail";
            $valuesTransct = "'$idcustomer', CURDATE(), CURTIME(), 0, 2, '$dataCustomer->idcompany', 0";

            $this->insertbasic($tableTransact, $columnsTransct, $valuesTransct);

            return 'success';
        } catch (Exception $e) {
            $this->createLog('Customer', 'getCompanyCustomerByUser', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
