<?php

class OrdersQuery extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function getAllOrders($user) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $Columns = "";
            $ColumnsView = "";
            $info = "";
            $Alias = array();
            $Methods = array();
            $user = json_decode($user);
            $Methods['Compania'] = $this->excecuteDatabaseStoredProcedures("`sp_getcompaniesforuser`('$user->user');");
            $Methods['Customers'] = $this->excecuteDatabaseStoredProcedures("`sp_getallaccountcustomerfororders`('$user->user');");
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsforview3`(21);");
            foreach ($objcolumn as $item) {
                //if ($item['inputtype'] == "select") {
                $Alias[] = $item['columndescription'];
                //}
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th class="text-center">' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $columns_orders.= "," . $item['innerjoin'];
                }
                if ($item['storagemethod'] != null) {
                    if ($item['columndescription'] == "Estado") {
                        $Methods[$item['columndescription']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                    } else {
                        $Methods[$item['columndescription']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`('$user->user');");
                    }
                }
            }
            //$columns_orders = substr($columns_orders, 0, -1);
            $select = $this->excecuteDatabaseStoredProcedures("`sp_getvaluesforordersquery`('$columns_orders','$user->user');");
            for ($i = 0; $i < count($select); $i++) {
                $j = 0;
                foreach ($select[$i] as $k1 => $v1) {
                    $select[$i][$Alias[$j - 1]] = $select[$i][$k1];
                    unset($select[$i][$k1]);
                    $j++;
                }
            }
            $contselect = 0;
            $ban = true;
            $ordersinfo = array();
            $Columns = substr($Columns, 0, -2);
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(21)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            $where = 'AND iduser=(SELECT iduser FROM `tbls_users` WHERE username="' . $user->user . '")';
            $queryCont = $this->createBasicSelectWithWhere('COUNT(idseller)', 'tbls_sellers', $where);
            $cont = $this->excecuteQueryScalar($queryCont);
            if ($cont > 0) {
                $query = $this->createBasicSelectWithWhere($Columns, $resptable[0]['result'], 'AND idstate=1 AND dateorder=CURDATE() AND idseller=(SELECT idseller FROM `tbls_sellers` WHERE iduser=(SELECT iduser FROM `tbls_users` WHERE username=' . $user->user . '))');
            } else {
                $query = $this->createBasicSelectWithWhere($Columns, $resptable[0]['result'], 'AND idstate=1 AND dateorder=CURDATE()');
            }
            $json = array('Orders' => array('columns' => '',
                    'Order' => array()
                ), 'permissions' => array()
            );
            $ordersinfo = $this->excecuteQueryAll($query);
            foreach ($ordersinfo as $key1 => $value) {
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
                array_push($json['Orders']['Order'], array('orderid' => $select[$contselect][''], 'info' => $info));
                $contselect++;
            }
            $json['Orders']['columns'] = $ColumnsView;
            $json['storagemethod'] = $Methods;
            $json['permissions'] = $this->excecuteDatabaseStoredProcedures("`sp_getpermissionsforordersquery`('$user->user');");
            return $json;
        } catch (Exception $e) {
            $this->createLog('OrdersQuery', 'getAllOrders', $e);
        }
    }

    public function getOrders($userquery) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $Columns = "";
            $ColumnsView = "";
            $info = "";
            $Methods = array();
            $Alias = array();
            $InnerJ = array();
            $userquery = json_decode($userquery);
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getallcolumnsforview3`(21);");
            foreach ($objcolumn as $item) {
                $Alias[] = $item['columndescription'];
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                $ColumnsView.='<th class="text-center">' . $item['columndescription'] . '</th>';
                if ($item['innerjoin'] != "") {
                    $InnerJ[] = $item['innerjoin'];
                }
                if ($item['storagemethod'] != null) {
                    if ($item['columndescription'] == "Estado") {
                        $Methods[$item['columndescription']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                    } else {
                        $Methods[$item['columndescription']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`('$userquery->user');");
                    }
                }
            }
            for ($r = 0; $r < count($InnerJ); $r++) {
                $columns_orders.=$InnerJ[$r] . ",";
            }
            $columns_orders = substr($columns_orders, 0, -1);
            $where1 = "";
            $where2 = "";
            if (count($userquery->Companias) > 0) {
                $where1.= " AND idcompany IN($userquery->Companias)";
                $where2.= " AND O.idcompany IN($userquery->Companias)";
            }
            if (count($userquery->NumeroOrdenes) > 0) {
                $where1.= " AND idorder IN($userquery->NumeroOrdenes)";
                $where2.= " AND O.idorder IN($userquery->NumeroOrdenes)";
            }
            if (count($userquery->CuentaClientes) > 0) {
                $where1.= " AND accountcustomer IN($userquery->CuentaClientes)";
                $where2.= " AND O.accountcustomer IN($userquery->CuentaClientes)";
            }
            if (count($userquery->Estados) > 0) {
                $where1.= " AND idstate IN($userquery->Estados)";
                $where2.= " AND O.idstate IN($userquery->Estados)";
            }
            $where = 'AND iduser=(SELECT iduser FROM `tbls_users` WHERE username="' . $userquery->user . '")';
            $queryCont = $this->createBasicSelectWithWhere('COUNT(idseller)', 'tbls_sellers', $where);
            $cont = $this->excecuteQueryScalar($queryCont);
            if ($cont == 0) {
                if (count($userquery->Vendedores) > 0) {
                    $where1.= " AND idseller IN($userquery->Vendedores)";
                    $where2.= " AND O.idseller IN($userquery->Vendedores)";
                }
            }
            $where2.=" ORDER BY O.`idorder` ASC";
            if ($cont > 0) {
                $Where = " AND CO.idstate=1 AND O.idseller=(SELECT idseller FROM `tbls_sellers` WHERE iduser=(SELECT iduser FROM `tbls_users` WHERE username='" . $userquery->user . "')) AND O.dateorder BETWEEN '$userquery->Fechaini' AND '$userquery->Fechafin'";
            } else {
                $Where = " AND CO.idstate=1 AND O.dateorder BETWEEN '$userquery->Fechaini' AND '$userquery->Fechafin'";
            }
            $Table = "`tbls_orders` O INNER JOIN `tbls_sellers` S ON S.idseller=O.idseller INNER JOIN `tbls_users` U ON U.iduser=S.iduser INNER JOIN `tbls_customers` C ON C.idcustomer=O.idcustomer INNER JOIN `tbls_states` ST ON ST.idstate=O.idstate INNER JOIN `tbls_companies` CO ON CO.idcompany=O.idcompany";
            $query = $this->createBasicSelectWithWhere($columns_orders, $Table, $Where . $where2);
            $select = $this->excecuteQueryAll($query);
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
            $ordersinfo = array();
            $Columns = substr($Columns, 0, -2);
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(21)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            if ($cont > 0) {
                $Where = "AND idcompany IN(SELECT idcompany FROM `tbls_companies` WHERE idstate=1) AND dateorder BETWEEN '$userquery->Fechaini' AND '$userquery->Fechafin' AND idseller=(SELECT idseller FROM `tbls_sellers` WHERE iduser=(SELECT iduser FROM `tbls_users` WHERE username='$userquery->user'))";
            } else {
                $Where = "AND idcompany IN(SELECT idcompany FROM `tbls_companies` WHERE idstate=1) AND dateorder BETWEEN '$userquery->Fechaini' AND '$userquery->Fechafin' ";
            }
            $where1.=" ORDER BY `idorder` ASC";
            $json = array('Orders' => array('columns' => '',
                    'Order' => array()
                ), 'permissions' => array()
            );
            $query = $this->createBasicSelectWithWhere($Columns, $resptable[0]['result'], $Where . $where1);
            $ordersinfo = $this->excecuteQueryAll($query);
            foreach ($ordersinfo as $key1 => $value) {
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
                        $info.='<td class="text-center">' . $va . '</td>';
                    }
                }
                array_push($json['Orders']['Order'], array('orderid' => $select[$contselect]['Numero Pedido'], 'info' => $info));
                $contselect++;
            }
            $Methods['Customers'] = $this->excecuteDatabaseStoredProcedures("`sp_getallaccountcustomerfororders`('$userquery->user');");
            $json['Orders']['columns'] = $ColumnsView;
            $json['storagemethod'] = $Methods;
            $json['permissions'] = $this->excecuteDatabaseStoredProcedures("`sp_getpermissionsforordersquery`('$userquery->user');");
            return $json;
        } catch (Exception $e) {
            $this->createLog('OrdersQuery', 'getOrders', $e);
        }
    }

    public function getDataforEditOrder($Order) {
        try {
            //Orders
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $columns_orders = "";
            $Columns = "";
            $ColumnsforOrderEdit['data'] = array();
            $Order = json_decode($Order);
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getcolumnsforedit3`(21);");
            foreach ($objcolumn as $key => $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $unencrypted = array($key => $item);
                $objcolumn = array_replace($objcolumn, $unencrypted);
                if ($item['storagemethod'] != null) {
                    if (($item['columnname'] == "idcustomer") || ($item['columnname'] == "address")) {
                        $profilestypes[$item['columnname']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`('$Order->order');");
                    }
                }
                if ($item['innerjoin'] != "") {
                    $columns_orders.= $item['innerjoin'] . ",";
                }
            }
            array_push($ColumnsforOrderEdit['data'], $objcolumn);
            $columns_orders = substr($columns_orders, 0, -1);
            $where = "AND idorder=" . $Order->order;
            $resptable = $this->excecuteDatabaseStoredFunctions("`fn_gettablename`(21)");
            $resptable[0]['result'] = $cryptography->Unencrypting($resptable[0]['result']);
            $table = $resptable[0]['result'] . " O 
                INNER JOIN `tbls_sellers` S ON S.idseller=O.idseller 
                INNER JOIN `tbls_users` U ON U.iduser=S.iduser 
                INNER JOIN `tbls_customers` C ON C.idcustomer=O.idcustomer 
                INNER JOIN `tbls_states` ST ON ST.idstate=O.idstate 
                INNER JOIN `tbls_priceslists` P ON P.idpricelist=O.idpricelist 
                INNER JOIN `tbls_formspayments` F ON F.idformpayment=O.idformpayment";
            $query = $this->createBasicSelectWithWhere($columns_orders, $table, $where);
            $orderinfo = $this->excecuteQueryAll($query);
            foreach ($objcolumn as $key => $item) {
                foreach ($orderinfo as $value) {
                    if (($value[$item['columnname']] != null) || ($value[$item['columnname']] != "")) {
                        $ColumnsforOrderEdit['data'][$cont][$key]['value'] = $value[$item['columnname']];
                    }
                }
            }
            $ColumnsforOrderEdit['storagemethod'] = $profilestypes;
            //orderdetails
            $cont = 0;
            $columns_ordersdetails = "";
            $Columns = "";
            $ColumnsforOrderEdit['dataod'] = array();
            $ColumnsforOrderEdit['dataodcolumns'] = array();
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getdataforview`(31);");
            foreach ($objcolumn as $key => $item) {
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $unencrypted = array($key => $item);
                $objcolumn = array_replace($objcolumn, $unencrypted);
                if ($item['innerjoin'] != "") {
                    $columns_ordersdetails.= $item['innerjoin'] . ",";
                }
            }
            $columns_ordersdetails = substr($columns_ordersdetails, 0, -1);
            $ColumnsforOrderEdit['dataodcolumns'] = $objcolumn;
            $ColumnsforOrderEdit['dataod'] = $this->excecuteDatabaseStoredProcedures("`sp_getordersdetailsbyorder`('$columns_ordersdetails',$Order->order);");
            $ColumnsforOrderEdit['totals'] = $this->excecuteDatabaseStoredProcedures("`sp_gettotalvaluesfororderdetails`($Order->order);");
            return $ColumnsforOrderEdit;
        } catch (Exception $e) {
            $this->createLog('OrdersQuery', 'getDataforEditOrder', $e);
        }
    }

    public function setOrderDetails($orderdetail) {
        try {
            $orderdetail = json_decode($orderdetail);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_updateorderdetailsaddrecustom`($orderdetail->order,'$orderdetail->addr',$orderdetail->cust)");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('OrdersQuery', 'setOrderDetails', $e);
        }
    }

    public function getBusinessRulesValidation($Order) {
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();
            $cont = 0;
            $Columns = "";
            $ColumnsView = "";
            $columns_BusinessRulesVal = "";
            $info = "";
            $contMethod = 0;
            $info = "";
            $contnum = 1;
            $Alias = array();
            $Methods = array();
            $objcolumn = $this->excecuteDatabaseStoredProcedures("`sp_getdataforview`(32);");
            foreach ($objcolumn as $item) {
                $Alias[] = $item['columndescription'];
                $item['columnname'] = $cryptography->Unencrypting($item['columnname']);
                $Columns.=$item['columnname'] . ' AS \'' . $item['columndescription'] . '\', ';
                if (($item['columndescription'] != "Regla de Negocio") && ($item['columndescription'] != "Estado")) {
                    $ColumnsView.='<th class="text-center">' . $item['columndescription'] . '</th>';
                }
                if ($item['innerjoin'] != "") {
                    $columns_BusinessRulesVal.= "," . $item['innerjoin'];
                }
                if ($item['storagemethod'] != null) {
                    $Methods[$item['columndescription']] = $this->excecuteDatabaseStoredProcedures("`" . $item['storagemethod'] . "`();");
                }
            }

            foreach ($Methods as $Method) {
                foreach ($Method as $item) {
                    $ColumnsView.='<th class="text-center">' . $item['descriptionbusinessrule'] . '</th>';
                    $contMethod++;
                }
            }

            $Order = json_decode($Order);
            $select = $this->excecuteDatabaseStoredProcedures("`sp_getbusinessrulesvalidationbyorder`('$columns_BusinessRulesVal',$Order->order);");
            for ($i = 0; $i < count($select); $i++) {
                if ($i != 0) {
                    if (($select[$i]['date']) == ($select[$i - 1]['date']) && ($select[$i]['time']) == ($select[$i - 1]['time'])) {
                        for ($j = 1; j < $select[$i - 1]['idbusinessrules'] - $select[$i]['idbusinessrules']; $j++) {
                            $info.='<td></td>';
                        }
                        if ($select[$i]['idstate'] == "1") {
                            $info.='<td>' . "<a class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a>" . '</td>';
                        } else {
                            $info.='<td>' . "<a class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a>" . '</td>';
                        }
                    } else {
                        $contnum++;
                        for ($j = 1; $j <= ($contMethod - $select[$i - 1]['idbusinessrules']); $j++) {
                            $info.='<td></td>';
                        }
                        $info.='</tr>';
                        $info.='<tr class="text-center"><td>' . $contnum . '</td>';
                        $info.='<td>' . $select[$i]['date'] . '</td>';
                        $info.='<td>' . $select[$i]['time'] . '</td>';
                        for ($j = 1; j <= $select[$i]['idbusinessrules']; $j++) {
                            $info.='<td></td>';
                        }
                        if ($select[$i]['idstate'] == "1") {
                            $info.='<td>' . "<a class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a>" . '</td>';
                        } else {
                            $info.='<td>' . "<a class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a>" . '</td>';
                        }
                    }
                } else {
                    $info.='<tr class="text-center"><td>' . $contnum . '</td>';
                    $info.='<td>' . $select[$i]['date'] . '</td>';
                    $info.='<td>' . $select[$i]['time'] . '</td>';
                    for ($j = 1; j <= $select[$i]['idbusinessrules']; $j++) {
                        $info.='<td></td>';
                    }
                    if ($select[$i]['idstate'] == "1") {
                        $info.='<td>' . "<a class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a>" . '</td>';
                    } else {
                        $info.='<td>' . "<a class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a>" . '</td>';
                    }
                }
                if ($i == (count($select)) - 1) {
                    for ($j = 1; $j <= ($contMethod - $select[$i]['idbusinessrules']); $j++) {
                        $info.='<td></td>';
                    }
                    $info.='</tr>';
                }
            }
            $json = array('BusRulVals' => array('columns' => '',
                    'BusRulVal' => array()
                )
            );
            $json['BusRulVals']['columns'] = $ColumnsView;
            $json['BusRulVals']['BusRulVal'] = $info;
            return $json;
        } catch (Exception $e) {
            $this->createLog('OrdersQuery', 'getBusinessRulesValidation', $e);
        }
    }

    public function setValidationBusinessRule($ValBusRule) {
        try {
            $ValBusRule = json_decode($ValBusRule);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_updatevalidationbusinessrulestate`($ValBusRule->VBRid)");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('OrdersQuery', 'setValidationBusinessRule', $e);
        }
    }

}
