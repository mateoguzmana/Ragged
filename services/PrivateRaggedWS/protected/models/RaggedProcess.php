<?php

class RaggedProcess extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function getAllProcess($user) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $Columns = "";
            $ColumnsView = "";
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsusersview`(33);");
            foreach ($objcolumn as $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                //$Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th class="text-center">' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $columns_process.= "," . $item['innerjoin'];
                }
            }
            $json = array('Processes' => array('columns' => '',
                    'Process' => array()
                ),
                'companies' => array(),
                'dependencies' => array()
            );
            $user=  json_decode($user);
            $json['Processes']['columns'] = $ColumnsView;
            $json['Processes']['Process'] = $this->excecuteDatabaseStoredProcedures("`sp_getvaluesforprocessragged`('$columns_process');");
            $json['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getusercompanies2`('$user->user');");
            $json['dependencies'] = $this->excecuteDatabaseStoredProcedures("`sp_getalldependencies`();");
            return $json;
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'getAllProcess', $e);
        }
    }

    public function getListProcessExcecution() {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $json = array('Processes' => array('columns' => '',
                    'Process' => ''
                ), 'companies' => '',
                'users' => ''
            );
            $Columns = "";
            $ColumnsView = "";
            $info = "";
            $Alias = array();
            $columns_process = "";
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsusersview`(34);");
            foreach ($objcolumn as $item) {
                //if ($item['inputtype'] == "select") {
                $Alias[] = $item['columndescription'];
                //}
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th>' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $columns_process.= "," . $item['innerjoin'];
                }
            }
            $json['Processes']['Process'] = $this->excecuteDatabaseStoredProcedures("`sp_getprocessheaderactives`('$columns_process');");
            $json['Processes']['columns'] = $ColumnsView;
            $json['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getallcompaniesactives`();");
            $json['users'] = $this->excecuteDatabaseStoredProcedures("`sp_getallusersforprocess`();");
            return $json;
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'getListProcessExcecution', $e);
        }
    }

    public function getListProcessExcecutionUser($userquery) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $where2 = "";
            $userquery = json_decode($userquery);
            if (count($userquery->Companies) > 0) {
                //$where1.= " AND idcompany IN($userquery->Companies)";
                $where2.= ' AND EP.id IN(SELECT DISTINCT(idprocessexecution) FROM `tblwspu_historicalexecutionmethods` WHERE idcompany IN(' . $userquery->Companies . ') AND startdate BETWEEN "' . $userquery->Fechaini . '" AND "' . $userquery->Fechafin . '")';
            }
            if (count($userquery->Users) > 0) {
                //$where1.= " AND iduser IN($userquery->Users)";
                $where2.= " AND EU.iduser IN($userquery->Users)";
            }
            $where2.= ' AND EP.startdate BETWEEN "' . $userquery->Fechaini . '" AND "' . $userquery->Fechafin . '"';
            $Columns = "";
            $ColumnsView = "";
            //$info = "";
            //$Alias = array();
            $columns_process = "";
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsusersview`(34);");
            foreach ($objcolumn as $item) {
                //if ($item['inputtype'] == "select") {
                //$Alias[] = $item['columndescription'];
                //}
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                //$Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th>' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $columns_process.= "," . $item['innerjoin'];
                }
            }
            $json = array('Processes' => array('columns' => '',
                    'Process' => ''
                ), 'companies' => '',
                'users' => ''
            );
            $json['Processes']['Process'] = $this->excecuteDatabaseStoredProcedures("`sp_getprocessheaderactivesquery`('$columns_process','$where2');");
            $json['Processes']['columns'] = $ColumnsView;
            //$json['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getallcompaniesactives`();");
            //$json['users'] = $this->excecuteDatabaseStoredProcedures("`sp_getallusersforprocess`();");
            return $json;
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'getListProcessExcecution', $e);
        }
    }

    public function getListProcessExcecutionDetail($Process) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $Process = json_decode($Process);
            $Columns = "";
            $ColumnsView = "";
            $info = "";
            $Alias = array();
            $columns_process = "";
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsusersview`(35);");
            foreach ($objcolumn as $item) {
                $Alias[] = $item['columndescription'];
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th>' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $columns_process.= $item['innerjoin'] . ",";
                }
            }
            $columns_process = substr($columns_process, 0, -1);
            $companies = $Process->Companies == "" ? "EMPTY" : $Process->Companies;
            $select = $this->excecuteDatabaseStoredProcedures("`sp_getprocessdetailsbyheader`('$columns_process',$Process->processid,'$companies');");
            for ($i = 0; $i < count($select); $i++) {
                $j = 1;
                foreach ($select[$i] as $k1 => $v1) {
                    $select[$i][$Alias[$j - 1]] = $select[$i][$k1];
                    unset($select[$i][$k1]);
                    $j++;
                }
            }
            $contselect = 0;
            $ban = true;
            $companiesinfo = array();
            $Columns = substr($Columns, 0, -2);
            $json = array('Processes' => array('columns' => '',
                    'Process' => array()
                )
            );
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(35)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            $wherecompanies = $companies == "EMPTY" ? "" : "AND FIND_IN_SET(idcompany,'" . $Process->Companies . "')";
            $where = "AND idprocessexecution=$Process->processid $wherecompanies ORDER BY id ASC";
            $query = $this->createBasicSelectWithWhere($Columns, $resptable[0]['result'], $where);
            $companiesinfo = $this->excecuteQueryAll($query);
            foreach ($companiesinfo as $key1 => $value) {
                $info = "";
                foreach ($value as $ky => $va) {
                    $ban = true;
                    foreach ($select[$contselect] as $k1 => $v1) {
                        if ($ky == $k1) {
                            $info.='<td>' . $v1 . '</td>';
                            $ban = false;
                        }
                    }
                    if ($ban) {
                        $info.='<td>' . $va . '</td>';
                    }
                }
                array_push($json['Processes']['Process'], array('info' => $info));
                $contselect++;
            }
            $json['Processes']['columns'] = $ColumnsView;            
            return $json;
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'getListProcessExcecution', $e);
        }
    }

    public function setRunProcess($Process) {
        try {
            $Process = json_decode($Process);
            if (count($Process->Companies) > 0) {
                $CompaniesStatus = $this->excecuteDatabaseStoredFunctions("`fn_updatestateprocesscompanies`('$Process->Companies')");
                if ($CompaniesStatus[0]['result'] == "OK") {
                    $this->excecuteDatabaseStoredProceduresQuery("`sp_changestatusprocesstocero`();");
                    $response = $this->excecuteDatabaseStoredFunctions("`fn_updateindividualstateprocess`('$Process->Processes')");
                    if ($response[0]['result'] == "OK") {
                        return $this->excecuteDatabaseStoredProceduresQueryScalar("`sp_getcurtime`();");
                    }
                }
            }
            return "";
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'setRunProcess', $e);
        }
    }

    public function setExcecuteCompleteProcess($Companies) {
        try {
            $Companies = json_decode($Companies);
            if (count($Companies->Companies) > 0) {
                $CompaniesStatus = $this->excecuteDatabaseStoredFunctions("`fn_updatestateprocesscompanies`('$Companies->Companies')");
                if ($CompaniesStatus[0]['result'] == "OK") {
                    $response = $this->excecuteDatabaseStoredFunctions("`fn_changestatusforcompleteexcecute`()");
                    if ($response[0]['result'] == "OK") {
                        return $this->excecuteDatabaseStoredProceduresQueryScalar("`sp_getcurtime`();");
                    }
                }
            }
            return "";
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'setExcecuteCompleteProcess', $e);
        }
    }

    public function getValidationProcess() {
        try {
            $validation = $this->excecuteDatabaseStoredFunctions("`fn_validateprocessexecution`()");
            return $validation[0]['result'];
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'getValidationProcess', $e);
        }
    }

    public function getProcessId($nowTime) {
        try {
            $response = $this->excecuteDatabaseStoredProceduresQueryScalar("`sp_getlastidexecutionprocess`('$nowTime');");
            return $response = "" ? false : $response;
            //llama el maximo id de la fecha actual (curdate), que la hora sea mayor o igual a $nowTime y que esl estado sea 0
            //si no existe un id, retorna false
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'getProcessId', $e);
        }
    }

    public function setExecutionProcessUsers($user, $id) {
        try {
            $response = $this->excecuteDatabaseStoredFunctions("`fn_setexecutionprocessusers`($id,'$user')");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('ProcessRagged', 'setExecutionProcessUsers', $e);
        }
    }

}
