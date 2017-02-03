<?php

class AdminUsers extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function getQueryAllUsers($user) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $json = array('users' => array('columns' => '',
                    'user' => array()
                )
                , 'sellers' => array('columns' => '',
                    'seller' => array()
                ), 'permissions' => array()
            );
            $user = json_decode($user);
            $json['permissions'] = $this->excecuteDatabaseStoredProcedures("`sp_getpermissionsforusersviewtable`('" . $user->user . "');");
            $tablesinfo = $this->excecuteDatabaseStoredProcedures("`sp_querytablesinfo`(2);");
            foreach ($tablesinfo as $itemtable) {
                $Columns = "";
                $ColumnsView = "";
                $info = "";
                $Alias = array();
                $InnerJ = array();
                $usersviewtable['users'] = array();
                $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsusersview`(" . $itemtable['idtable'] . ");");
                foreach ($objcolumn as $item) {
                    if ($item['inputtype'] == "select") {
                        $Alias[] = $item['columndescription'];
                    }
                    $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                    $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                    $ColumnsView.='<th>' . $item['columndescription'] . '</th>';
                    if ($item['innerjoin'] != "") {
                        $InnerJ[] = $item['innerjoin'];
                    }
                }
                $Columns = substr($Columns, 0, -2);
                if ($cont == 0) {
                    $json['users']['columns'] = $ColumnsView;
                    for ($r = 0; $r < count($InnerJ); $r++) {
                        $columns_users.=',' . $InnerJ[$r] . ' ';
                    }
                    $select = $this->excecuteDatabaseStoredProcedures("`sp_getusersprofilestates`('$columns_users');");
                    $i = 0;
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
                    $userinfo = array();
                    $itemtable['tablename'] = $cryptography->Unencrypting($itemtable['tablename']);
                    $query = $this->createBasicSelectWithWhere($Columns, $itemtable['tablename'], '');
                    $userinfo = $this->excecuteQueryAll($query);
                    foreach ($userinfo as $key1 => $value) {
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
                        array_push($json['users']['user'], array('userid' => $select[$contselect]['iduser'], 'info' => $info));
                        $contselect++;
                    }
                }
                if ($cont == 1) {
                    $json['sellers']['columns'] = $ColumnsView;
                    for ($r = 0; $r < count($InnerJ); $r++) {
                        $columns_sellers.=',' . $InnerJ[$r];
                    }
                    $select = $this->excecuteDatabaseStoredProcedures("`sp_getsellersinfo`('$columns_sellers');");
                    $i = 0;
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
                    foreach ($userinfo as $key1 => $value) {
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
                        array_push($json['sellers']['seller'], array('sellerid' => $select[$contselect]['iduser'], 'info' => $info));
                        $contselect++;
                    }
                }
                $cont++;
            }
            return $json;
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'getQueryAllUsers', $e);
        }
    }

    public function getDocumentsTypes() {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getdocumentstypes`();");
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'getDocumentsTypes', $e);
        }
    }

    public function getProfilesTypes() {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getallprofiles`();");
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'getProfilesTypes', $e);
        }
    }

    public function setChangeUserStatus($id) {
        try {
            return $this->excecuteDatabaseStoredFunctions("`fn_changeuserstatus`($id)");
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'setChangeUserStatus', $e);
        }
    }

    public function getDataforCreateUser() {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $createuserv = array();
            $createuserv['data'] = $this->excecuteDatabaseStoredProcedures("`sp_getdataforcreateview`(1);");
            foreach ($createuserv['data'] as $key => $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $unencrypted = array($key => $item);
                $createuserv['data'] = array_replace($createuserv['data'], $unencrypted);
            }
            $createuserv['tables'] = $this->excecuteDatabaseStoredProcedures("`sp_getalltablesfromdiferentusers`();");
            foreach ($createuserv['data'] as $item) {
                if ($item['storagemethod'] != null) {
                    $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                }
            }
            $createuserv['storagemethod'] = $profilestypes;
            $createuserv['departments'] = $this->excecuteDatabaseStoredProcedures("`sp_getalldepartments`();");
            return $createuserv;
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'getDataforCreateUser', $e);
        }
    }

    public function setnewuser($usern) {
        try {
            $user = json_decode($usern);
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            for ($i = 0; $i < count($user->tables); $i++) {
                $Columns = "";
                $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(" . $user->tables[$i]->tableid . ")");
                $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
                $respcolmns = $this->excecuteDatabaseStoredProcedures("`sp_getcolumnsnameforcreate`(" . $user->tables[$i]->tableid . ");");
                foreach ($respcolmns as $item) {
                    $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                    $Columns.=$item['columnname'] . ',';
                }
                $Columns = substr($Columns, 0, -1);
                $values = str_replace("|~|", ",", $user->tables[$i]->data);
                if ($i > 0) {
                    $userid = $this->excecuteDatabaseStoredFunctions("`fn_queryidusernewuser`(" . $user->idencard . ")");
                    $Columns.=",iduser";
                    $values.="," . $userid[0]['result'];
                }
                $query = $this->insertbasic($resptable[0]['result'], $Columns, $values);
            }
            return "OK";
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'setnewuser', $e);
        }
    }

    public function setSaveUserEdit($usern) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $user = json_decode($usern);
            $where = "AND iduser=" . $user->userid;
            for ($i = 0; $i < count($user->tables); $i++) {
                $Columnsdata = "";
                $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(" . $user->tables[$i]->tableid . ")");
                $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
                $respcolmns = $this->excecuteDatabaseStoredProcedures("`sp_getcolumnsforedit`(" . $user->tables[$i]->tableid . ");");
                $cont = 0;
                $value = $user->tables[$i]->data;
                $datauser = explode('|~|', $value);
                foreach ($respcolmns as $item) {
                    $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                    $Columnsdata.=$item['columnname'] . '=' . $datauser[$cont] . ',';
                    $cont++;
                }
                $Columnsdata = substr($Columnsdata, 0, -1);

                if ((count($user->tables)) == 1) {
                    $responsecont = $this->excecuteDatabaseStoredFunctions("`fn_queryexistenceusertblseller`(" . $user->userid . ")");
                    if ($responsecont[0]['result'] == "ON") {
                        $responsedel = $this->excecuteDatabaseStoredFunctions("`fn_deleteselleruseredit`(" . $user->userid . ")");
                    }
                    $query = $this->updatebasic($resptable[0]['result'], $Columnsdata, $where);
                } else if ($i == 1) {
                    $responsecont = $this->excecuteDatabaseStoredFunctions("`fn_queryexistenceusertblseller`(" . $user->userid . ")");
                    if ($responsecont[0]['result'] == "OFF") {
                        foreach ($respcolmns as $item) {
                            $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                            $Columns.=$item['columnname'] . ',';
                        }
                        $Columns.="iduser";
                        $values = str_replace("|~|", ",", $user->tables[$i]->data);
                        $values.="," . $user->userid;
                        $query = $this->insertbasic($resptable[0]['result'], $Columns, $values);
                    } else {
                        $query = $this->updatebasic($resptable[0]['result'], $Columnsdata, $where);
                    }
                } else {
                    $query = $this->updatebasic($resptable[0]['result'], $Columnsdata, $where);
                }
            }
            return "OK";
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'setSaveUserEdit', $e);
        }
    }

    public function getDataforEditUser($useredit) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $user = json_decode($useredit);
            $Columnsforuseredit['data'] = array();
            $typeuser = $this->excecuteDatabaseStoredFunctions("`fn_queryusertype`(" . $user->user . ")");
            $tablesinfo = $this->excecuteDatabaseStoredProcedures("`sp_querytablesinfo`(2);");
            $where = "AND iduser=" . $user->user;
            $cont = 0;
            $banuser = false;
            foreach ($tablesinfo as $itemtable) {
                $Columns = "";
                $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getcolumnsforedit`(" . $itemtable['idtable'] . ");");
                $itemtable['tablename'] = $cryptography->Unencrypting($itemtable['tablename']);
                foreach ($objcolumn as $key => $item) {
                    $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                    $unencrypted = array($key => $item);
                    $objcolumn = array_replace($objcolumn, $unencrypted);
                }
                array_push($Columnsforuseredit['data'], $objcolumn);
                foreach ($objcolumn as $item) {
                    if ($item['storagemethod'] != null) {

                        $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                    }
                    $Columns.=$item['columnname'] . ',';
                }
                $Columns = substr($Columns, 0, -1);
                if (($typeuser[0]['result'] == 1) && ($itemtable['idtable'] == 2)) {
                    
                } else {
                    $query = $this->createBasicSelectWithWhere($Columns, $itemtable['tablename'], $where);
                    $userinfo = $this->excecuteQueryAll($query);
                    foreach ($objcolumn as $key => $item) {
                        foreach ($userinfo as $value) {
                            if (($value[$item['columnname']] != null) || ($value[$item['columnname']] != "")) {
                                $Columnsforuseredit['data'][$cont][$key]['value'] = $value[$item['columnname']];
                            }
                        }
                    }
                }
                $cont++;
            }
            $Columnsforuseredit['typeuser'] = $typeuser[0]['result'];
            $Columnsforuseredit['departments'] = $this->excecuteDatabaseStoredProcedures("`sp_getalldepartments`();");
            $Columnsforuseredit['storagemethod'] = $profilestypes;
            return $Columnsforuseredit;
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'getDataforEditUser', $e);
        }
    }

    public function setedituser($user) {
        try {
            $user = json_decode($user);
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'setedituser', $e);
        }
    }

    public function getSellerFields($user) {
        try {
            $user = json_decode($user);
            $typeuser = $this->excecuteDatabaseStoredFunctions("`fn_getprofiletype`(" . $user->profiletype . ")");
            return $typeuser[0]['result'];
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'setnewuser', $e);
        }
    }

    public function setDeleteUser($userid) {
        try {
            $user = json_decode($userid);
            $deleteresponse = $this->excecuteDatabaseStoredFunctions("`fn_deleteuser`(" . $user->userid . ")");
            return $deleteresponse[0]['result'];
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'setDeleteUser', $e);
        }
    }

    public function getUserExistence($usern) {
        try {
            $user = json_decode($usern);
            $queryresponse = $this->excecuteDatabaseStoredFunctions("`fn_queryuserexistence`('" . $user->user . "'," . $user->idencard . ")");
            return $queryresponse[0]['result'];
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'setDeleteUser', $e);
        }
    }

    public function getUserExistenceEdit($usern) {
        try {
            $user = json_decode($usern);
            $queryresponse = $this->excecuteDatabaseStoredFunctions("`fn_queryuserforeditexistence`(" . $user->userid . ",'" . $user->user . "'," . $user->idencard . ")");
            return $queryresponse[0]['result'];
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'setDeleteUser', $e);
        }
    }

    public function getUserCompany($company) {
        try {
            return $this->excecuteDatabaseStoredProcedures("sp_getusercompanyforselect($company);");
        } catch (Exception $e) {
            $this->createLog('AdminUsers', 'getUserCompany', $e);
        }
    }

}
