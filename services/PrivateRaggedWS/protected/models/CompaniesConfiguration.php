<?php

class CompaniesConfiguration extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function getAllCompanies($user) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $json = array('companies' => array('columns' => '',
                    'company' => array()
                ), 'permissions' => array()
            );
            $user = json_decode($user);
            $json['permissions'] = $this->excecuteDatabaseStoredProcedures("`sp_getpermissionsforuserscompaniestable`('$user->user');");
            $Columns = "";
            $ColumnsView = "";
            $info = "";
            $Alias = array();
            $columns_companies = "";
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsusersview`(24);");
            foreach ($objcolumn as $item) {
                if ($item['inputtype'] == "select") {
                    $Alias[] = $item['columndescription'];
                }
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th>' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $columns_companies.= "," . $item['innerjoin'];
                }
            }
            $json['companies']['columns'] = $ColumnsView;
            $select = $this->excecuteDatabaseStoredProcedures("`sp_getcompaniesstates`('$columns_companies');");
            for ($i = 0; $i < count($select); $i++) {
                $j = 0;
                foreach ($select[$i] as $k1 => $v1) {
                    if ($j > 0) {
                        $select[$i][$Alias[$j - 1]] = $select[$i][$k1];
                        unset($select[$i][$k1]);
                    }
                    $j++;
                }
            }
            $contselect = 0;
            $ban = true;
            $companiesinfo = array();
            $Columns = substr($Columns, 0, -2);
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(24)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            $query = $this->createBasicSelectWithWhere($Columns, $resptable[0]['result'], '');
            $companiesinfo = $this->excecuteQueryAll($query);
            foreach ($companiesinfo as $key1 => $value) {
                $info = "";
                foreach ($value as $ky => $va) {
                    $ban = true;
                    foreach ($select[$contselect] as $k1 => $v1) {
                        if ($ky == $k1) {
                            if ($ky == "Estado") {
                                if ($v1 == "ACTIVO") {
                                    $info.='<td>' . "<a onclick='ChangeCompanyStatus(" . $select[$contselect]['idcompany'] . ")' class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a>" . '</td>';
                                } else {
                                    $info.='<td>' . "<a onclick='ChangeCompanyStatus(" . $select[$contselect]['idcompany'] . ")' class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a>" . '</td>';
                                }
                            } else {
                                $info.='<td>' . $v1 . '</td>';
                            }
                            $ban = false;
                        }
                    }
                    if ($ban) {
                        $info.='<td>' . $va . '</td>';
                    }
                }
                array_push($json['companies']['company'], array('idcompany' => $select[$contselect]['idcompany'], 'pricelistid' => $select[$contselect]['Precio Lista'], 'stateid' => $select[$contselect]['Estado'], 'info' => $info));
                $contselect++;
            }
            return $json;
        } catch (Exception $e) {
            $this->createLog('CompaniesConfiguration', 'getAllCompanies', $e);
        }
    }

    public function setChangeCompanyStatus($company) {
        try {
            $company = json_decode($company);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_setchangecompanystatus`(" . $company->CompanyId . ")");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('CompaniesConfiguration', 'setChangeCompanyStatus', $e);
        }
    }

    public function getDataforEditCompany($company) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $Columns = "";
            $ColumnsforCompaniesEdit['data'] = array();
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getcolumnsforedit`(24);");
            foreach ($objcolumn as $key => $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $unencrypted = array($key => $item);
                $objcolumn = array_replace($objcolumn, $unencrypted);
            }
            array_push($ColumnsforCompaniesEdit['data'], $objcolumn);
            $company = json_decode($company);
            foreach ($objcolumn as $item) {
                if ($item['storagemethod'] != null) {
                    if ($item['columnname'] == "idpricelist") {
                        $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`(" . $company->company . ");");
                    } else {
                        $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                    }
                }
                $Columns.=$item['columnname'] . ',';
            }
            $Columns = substr($Columns, 0, -1);
            $where = "AND idcompany=" . $company->company;
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(24)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            $query = $this->createBasicSelectWithWhere($Columns, $resptable[0]['result'], $where);
            $companyinfo = $this->excecuteQueryAll($query);
            foreach ($objcolumn as $key => $item) {
                foreach ($companyinfo as $value) {
                    if (($value[$item['columnname']] != null) || ($value[$item['columnname']] != "")) {
                        $ColumnsforCompaniesEdit['data'][$cont][$key]['value'] = $value[$item['columnname']];
                    }
                }
            }
            $ColumnsforCompaniesEdit['storagemethod'] = $profilestypes;
            return $ColumnsforCompaniesEdit;
        } catch (Exception $e) {
            $this->createLog('CompaniesConfiguration', 'getDataforEditCompany', $e);
        }
    }

    public function setCompanyEdit($data) {
        try {
            $data = json_decode($data);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_updatecompanyconfiguration`(" . $data->company . "," . $data->pricelist . "," . $data->state . "," . $data->dayscancellation . ")");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('CompaniesConfiguration', 'setCompanyEdit', $e);
        }
    }

    public function setChangeBusinessStatus($BusinessRule) {
        try {
            $BusinessRule = json_decode($BusinessRule);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_updatecompanyconfigurationstatus`($BusinessRule->busrulid)");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('CompaniesConfiguration', 'setCompanyEdit', $e);
        }
    }

    public function getBusinessRules($Company) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $json = array('BusinessRules' => array('columns' => '',
                    'BusinessRule' => array()
                ), 'permissions' => array()
            );
            $Company = json_decode($Company);
            $Columns = "";
            $ColumnsView = "";
            $columns_companies = "";
            $info = "";
            //$Alias = array();
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsusersview`(25);");
            foreach ($objcolumn as $item) {
                /*if ($item['inputtype'] == "select") {
                    $Alias[] = $item['columndescription'];
                }*/
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th>' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $columns_companies.= "," . $item['innerjoin'];
                }
            }
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsusersview`(26);");
            foreach ($objcolumn as $item) {
                /*if ($item['inputtype'] == "select") {
                    $Alias[] = $item['columndescription'];
                }*/
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th>' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $InnerJ[] = $item['innerjoin'];
                }
            }
            $json['companies']['columns'] = $ColumnsView;
            return $this->excecuteDatabaseStoredProcedures("`sp_getbusinessrulesforcompany`('" . $columns_companies . "'," . $Company->company . ");");
        } catch (Exception $e) {
            $this->createLog('CompaniesConfiguration', 'getAllCompanies', $e);
        }
    }

}
