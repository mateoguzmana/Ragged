<?php

class EmailsConfiguration extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function getAllCompaniesforEmails($user) {
        try {
            $user = json_decode($user);
            $json['permissions'] = $this->excecuteDatabaseStoredProcedures("`sp_getpermissionsforemailsviewtable`('" . $user->user . "');");
            $json['Companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getcompaniesforselect`();");
            $json['SendEmails'] = $this->excecuteDatabaseStoredProcedures("`sp_getallsendemails`();");            
            return $json;
        } catch (Exception $e) {
            $this->createLog('EmailsConfiguration', 'getAllCompaniesforEmails', $e);
        }
    }

    /* public function getAllEmailsforCompany($data) {
      try {
      $data = json_decode($data);
      if ($data->company > 0) {
      return $this->excecuteDatabaseStoredProcedures("`sp_getallemailsforcompany`('$data->company');");
      } else {
      return $this->excecuteDatabaseStoredProcedures("`sp_getallsendemails`();");
      }
      } catch (Exception $e) {
      $this->createLog('EmailsConfiguration', 'getAllEmailsforCompany', $e);
      }
      } */

    public function setChangeSendEmailStatus($SendEmail) {
        try {
            $SendEmail = json_decode($SendEmail);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_setchangesendemailstatus`($SendEmail->SendEmailId)");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('EmailsConfiguration', 'setChangeSendEmailStatus', $e);
        }
    }

    public function getDataforCreateEmail() {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $createuserv = array();
            $createuserv['data'] = $this->excecuteDatabaseStoredProcedures("`sp_getdataforcreateview`(27);");
            foreach ($createuserv['data'] as $key => $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $unencrypted = array($key => $item);
                $createuserv['data'] = array_replace($createuserv['data'], $unencrypted);
            }
            foreach ($createuserv['data'] as $item) {
                if ($item['storagemethod'] != null) {
                    $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                }
            }
            $createuserv['storagemethod'] = $profilestypes;
            return $createuserv;
        } catch (Exception $e) {
            $this->createLog('EmailsConfiguration', 'getDataforCreateEmail', $e);
        }
    }

    public function getEmailConfigurationExistence($Email) {
        try {
            $Email = json_decode($Email);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_queryemailconfigurationexistence`($Email->Company,'$Email->Email',$Email->Document)");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('EmailsConfiguration', 'getEmailConfigurationExistence', $e);
        }
    }

    public function setNewEmailConfiguration($email) {
        try {
            $email = json_decode($email);
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $Columns = "";
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(27)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            $respcolmns = $this->excecuteDatabaseStoredProcedures("`sp_getcolumnsnameforcreate`(27);");
            foreach ($respcolmns as $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ',';
            }
            $Columns = substr($Columns, 0, -1);
            $values = str_replace("|~|", ",", $email->data);
            $Columns.=",idstate";
            $values.=",1";
            $query = $this->insertbasic($resptable[0]['result'], $Columns, $values);
            return "OK";
        } catch (Exception $e) {
            $this->createLog('EmailsConfiguration', 'setNewEmailConfiguration', $e);
        }
    }

    public function getDataforEditEmail($Email) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $Columns = "";
            $ColumnsforEmailEdit['data'] = array();
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getcolumnsforedit`(27);");
            foreach ($objcolumn as $key => $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $unencrypted = array($key => $item);
                $objcolumn = array_replace($objcolumn, $unencrypted);
            }
            array_push($ColumnsforEmailEdit['data'], $objcolumn);
            $Email = json_decode($Email);
            foreach ($objcolumn as $item) {
                if ($item['storagemethod'] != null) {
                    /* if ($item['columnname'] == "idpricelist") {
                      $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`(" . $Email->email . ");");
                      } else { */
                    $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                    //}
                }
                $Columns.=$item['columnname'] . ',';
            }
            $Columns = substr($Columns, 0, -1);
            $where = "AND idsendmail=" . $Email->email;
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(27)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            $query = $this->createBasicSelectWithWhere($Columns, $resptable[0]['result'], $where);
            $companyinfo = $this->excecuteQueryAll($query);
            foreach ($objcolumn as $key => $item) {
                foreach ($companyinfo as $value) {
                    if (($value[$item['columnname']] != null) || ($value[$item['columnname']] != "")) {
                        $ColumnsforEmailEdit['data'][$cont][$key]['value'] = $value[$item['columnname']];
                    }
                }
            }
            $ColumnsforEmailEdit['storagemethod'] = $profilestypes;
            return $ColumnsforEmailEdit;
        } catch (Exception $e) {
            $this->createLog('EmailsConfiguration', 'getDataforEditEmail', $e);
        }
    }

    public function getEmailConfigurationExistenceEdit($Email) {
        try {
            $Email = json_decode($Email);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_queryemailconfigurationexistenceedit`($Email->EmailId,$Email->Company,'$Email->Email',$Email->Document)");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('EmailsConfiguration', 'getEmailConfigurationExistenceEdit', $e);
        }
    }

    public function setEmailConfigurationEdit($data) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $Columnsdata = "";
            $cont = 0;
            $data = json_decode($data);
            $datauser = explode('|~|', $data->data);
            $respcolmns = $this->excecuteDatabaseStoredProcedures("`sp_getcolumnsforedit`(27);");
            foreach ($respcolmns as $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columnsdata.=$item['columnname'] . '=' . $datauser[$cont] . ',';
                $cont++;
            }
            $Columnsdata = substr($Columnsdata, 0, -1);
            $where = "AND idsendmail=" . $data->EmailId;
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(27)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            $query = $this->updatebasic($resptable[0]['result'], $Columnsdata, $where);
            return "OK";
        } catch (Exception $e) {
            $this->createLog('EmailsConfiguration', 'setEmailConfigurationEdit', $e);
        }
    }

}
