<?php

class Order extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }    
    

    public function getRouters($user,$allRouters) {
        try {                      
            
            $table = "tbls_routers";
            
            if ($allRouters)
                $idName = "ROUTER.idseller";            
            else
                $idName = "ROUTER.idrouter";
            $newCustomerFilter = " AND CUSTOMER.idtypecustomer = 2 AND CUSTOMER.idstate = 0";
            $where = " AND CUSTOMER.idstate = '1' AND CUSTOMER.idtypecustomer = 1 AND ";               
            
            $tables[0] = "tbls_routers";
            $tables[1] = "tbls_customers";
            $tables[2] = "tbls_formspaymentsdetails";
            $tables[3] = "tbls_formspayments";                       

            $alias[0]= "ROUTER";
            $alias[1] = "CUSTOMER";
            $alias[2] = "FormPayDetail";
            $alias[3] = "FormPay";            

            $primary[0] = "";
            $foreign[0] = "";
            $primary[1] = "idcustomer";
            $foreign[1] = "idcustomer";
            $primary[2] = "idformpaymentdetail";
            $foreign[2] = "idformpaymentdetail";
            $primary[3] = "idformpayment";
            $foreign[3] = "idformpayment";   
            $condition = "";
            if ($allRouters)
                $result = $this->getRouterBySeller($user,$table ,$idName, $where, $tables, $alias, $primary, $foreign, $condition, $newCustomerFilter);
            else 
                $result = $this->getRouterById($user,$table ,$idName, $where, $tables, $alias, $primary, $foreign, $condition, $newCustomerFilter);            
               
            $sellerId = $this->getSellerIdByUser($user);
            $this->DeleteTempRegisters($sellerId);
            
            return $result;            
            
        } catch (Exception $exc) {
            $this->createLog('Order', 'getAllRouters', $exc);
        }
    }
    
    public function getRouterBySeller($user, $table ,$idName, $where, $tables, $alias, $primary, $foreign, $condition, $newCustomerFilter){
        
        try {
            Yii::import('application.components.Cryptography');            
            $cryptography = new Cryptography();            
            $result = array();    
            $result['companies'] = $this->excecuteDatabaseStoredProcedures("`sp_getsellercompany`('$user');");            
            $idValues = ($result['companies'][0]['Seller']);            
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);                           

            $where.= $idName. " = ".$idValues;   
            // Se incluye para traer también los clientes nuevos
            $where .= " OR " . $idName. " = ".$idValues . $newCustomerFilter ;

            $JoinCounter= true;        

            $result['datas'] = $this->ExecuteInnerJoin($tables, $alias, $primary, $foreign, $condition, $columns, $where, $table_encrypted, $JoinCounter);        
            $result['config'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");
            $result['privileges'] = $this->excecuteDatabaseStoredProcedures("`sp_getprivilegesbymodule`('$user','$item');");
            $result['priceList'] = $this->excecuteDatabaseStoredProcedures("`sp_getallpricelist`();");
            $result['formPayments'] = $this->excecuteDatabaseStoredProcedures("`sp_getformpayments`();");    


            return json_encode($result);
        } catch (Exception $exc) {
            $this->createLog('Order', 'getRouterBySeller', $exc);
        }
    }
    
    public function getRouterById($IdValues, $table ,$idName, $where, $tables, $alias, $primary, $foreign, $condition, $newCustomerFilter){
        
        try {
            Yii::import('application.components.Cryptography');            
            $cryptography = new Cryptography();            
            $result = array();        

            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);                                   


            for ($i=0; $i<count($IdValues);$i++){                
                $where.= $idName. " = ".$IdValues[$i];
                // Se incluye para traer también los clientes nuevos
                $where.= " OR " . $idName. " = ".$IdValues[$i] . $newCustomerFilter;
                
                if ($i<count($IdValues)-1)
                $where.= " OR ";
            }              
            
            $JoinCounter= true;                
            $result = $this->ExecuteInnerJoin($tables, $alias, $primary, $foreign, $condition, $columns, $where, $table_encrypted, $JoinCounter);                

            return $result;
            
            } catch (Exception $exc) {
            $this->createLog('Order', 'getRouterById', $exc);
        }    
    }
    
    public function getOrderDetails($collections, $references, $priceList, $customers, $company) {
        try {             
            
            
            Yii::import('application.components.Cryptography');            
            $cryptography = new Cryptography();    
            
            $customers = json_decode($customers);                    
            $collectionsId = json_decode($collections);                           
            $referencesId = json_decode($references);                
            $priceListId = json_decode($priceList);       
            
            $response = array();           
            
            $table = 'tbls_collections';
            $idName = "idcollection"; 
            $createWhere = false;
            $response['collections'] = $this->getTablesData($collectionsId, $table, $idName, $createWhere);            
            
            $table = 'tbls_references';
            $idName = "idreference";            
            $createWhere = false;            
            $response['references'] = $this->getReferences($company, $table, $idName, $createWhere);                
            
            $idReferenceArray = Array();
            $i=0;
            
            foreach($response['references'] as $item)
            {
                $idReferenceArray[$i] = $item['Id'];
                $i++;
            }                     
                        
            $table = 'tbls_referencespricelist';
            $idName = "PRICE.idpricelist";            
            $response['priceList'] = $this->getPriceList($priceListId, $table, $idName);                             
            
            
            $table = 'tbls_plus';
            $idName = "PLUS.idreference"; 
            $createWhere = true;
            $response['plus'] = $this->getPlus($idReferenceArray, $table, $idName, $createWhere);                             
            
            $response['address'] = $this->getSalePoints($customers);  
            
            return json_encode($response);
            
            
        } catch (Exception $exc) {
            $this->createLog('Order', 'getOrderDetails', $exc);
        }
    }
    
    public function ExecuteInnerJoin($tables, $alias, $primary, $foreign, $condition, $columns, $where, $table_encrypted, $JoinCounter){        
        
        try {
            $tablesArray = array();
            for ($i=0; $i<count($tables); $i++)
            {
                $tablesArray[$i]['table'] = $tables[$i];
                $tablesArray[$i]['alias'] = $alias[$i];
                $tablesArray[$i]['references']['type']['primary'] = $alias[$i].".".$primary[$i];
                if($JoinCounter)                
                    $tablesArray[$i]['references']['type']['foreign'] = $alias[$i-1].".".$foreign[$i];
                else
                {
                    if ($tablesArray[$i]['alias'] == "COLLECTION_PLUS")
                        $tablesArray[$i]['references']['type']['foreign'] = $alias[1].".".$foreign[$i];
                    else
                        $tablesArray[$i]['references']['type']['foreign'] = $alias[0].".".$foreign[$i];
                }
                $tablesArray[$i]['references']['type']['condition'] = $condition;     
            }          

            $innerJoin = $this->createInnerJoinMultiple($tablesArray);  
            $query = $this->createBasicSelectWithWhere($columns, $innerJoin, $where);          
            $result['datas'] = $this->excecuteQueryAll($query);

            return $result['datas'];     
        }  catch (Exception $exc) {
            $this->createLog('Order', 'ExecuteInnerJoin', $exc);
        }
        
    }
    
    public function getReferences($company, $table, $idName){
        
        try {            
            
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $result = array();
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");            
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);                             
            
            
            $where = " AND REFERENCE.idstate = '1' AND COLLECTIONS.idstate = 1 AND COLLECTIONS.idstateavailable = 1 AND REFERENCE.idcompany = " .$company;             
            
            
            $tables[0] = "tbls_references";
            $tables[1] = "tbls_collections";    

            $alias[0]= "REFERENCE";
            $alias[1] = "COLLECTIONS";            

            $primary[0] = "";
            $foreign[0] = "";
            $primary[1] = "idcollection";
            $foreign[1] = "idcollection";

            $condition = "";            
            $JoinCounter = true;            
            
            $result['datas'] = $this->ExecuteInnerJoin($tables, $alias, $primary, $foreign, $condition, $columns, $where, $table_encrypted, $JoinCounter);                          
            return $result['datas'];            
            
        }  catch (Exception $exc) {
            $this->createLog('Order', 'getReferences', $exc);
        }
                           
    }   
    
    public function getTablesData($IdValues, $table, $idName, $createWhere){   
        
        try {
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $result = array();
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
                
            }
            $columns = substr($columns, 0, -2);            
            
            $where = " AND `idstate` = '1'";       

            if ($createWhere == true)
            {            
                $where .= " AND ";       
                for ($i=0; $i<count($IdValues);$i++){                
                    $where.= $idName. " = ".$IdValues[$i];
                    if ($i<count($IdValues)-1)
                    $where.= " OR ";
                }                                    
            }                        
            
            $query = $this->createBasicSelectWithWhere($columns, $table, $where); 
            
            $result['datas'] = $this->excecuteQueryAll($query);            
            
            return $result;        
        }   
            catch (Exception $exc) {
            $this->createLog('Order', 'getTablesData', $exc);
        }
    }
    
    public function getPlus($IdValues, $table, $idName, $createWhere){
        
        try {
        
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $result = array();
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);        
            
            $where = " AND PLUS.idstate = '1' ";       

            if ($createWhere == true)
            {    
                $where .= "  AND ";       
                for ($i=0; $i<count($IdValues);$i++){                
                    $where.= $idName. " = ".$IdValues[$i];
                    if ($i<count($IdValues)-1)
                    $where.= " OR ";
                }     
            }            
            
            $where.= " ORDER BY COLLECTION_PLUS.idcollection DESC, REF.referencecode ASC";            
            
            $tables[0] = "tbls_plus";
            $tables[1] = "tbls_references";
            $tables[2] = "tbls_sizes";
            $tables[3] = "tbls_colors";
            $tables[4] = "tbls_stock";            
            $tables[5] = "tbls_collections";            

            $alias[0]= "PLUS";
            $alias[1] = "REF";
            $alias[2] = "SIZE";
            $alias[3] = "COLOR";
            $alias[4] = "STOCK";            
            $alias[5] = "COLLECTION_PLUS";

            $primary[0] = "";
            $foreign[0] = "";
            $primary[1] = "idreference";
            $foreign[1] = "idreference";
            $primary[2] = "idsize";
            $foreign[2] = "idsize";
            $primary[3] = "idcolors";
            $foreign[3] = "idcolor";  
            $primary[4] = "idplu";
            $foreign[4] = "idplus";             
            $primary[5] = "idcollection";
            $foreign[5] = "idcollection"; 

            $condition = "";
            
            $JoinCounter = false;            
            
            $result['datas'] = $this->ExecuteInnerJoin($tables, $alias, $primary, $foreign, $condition, $columns, $where, $table_encrypted, $JoinCounter);                       
            
            return $result;
        }   
            catch (Exception $exc) {
            $this->createLog('Order', 'getPlus', $exc);
        }
                           
    }
    
     public function getPriceList($IdValues, $table, $idName){
        
        try {            
            
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            $result = array();
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");            
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);                             
            
            
            $where = " AND PRICE.idstate = '1' AND ";       

            for ($i=0; $i<count($IdValues);$i++){                
                $where.= $idName. " = ".$IdValues[$i];
                if ($i<count($IdValues)-1)
                $where.= " OR ";
            }     

            $tables[0] = "tbls_referencespricelist";
            $tables[1] = "tbls_priceslists";    

            $alias[0]= "RefPrice";
            $alias[1] = "PRICE";            

            $primary[0] = "";
            $foreign[0] = "";
            $primary[1] = "idpricelist";
            $foreign[1] = "idpricelist";
            
            $condition = "";
            
            $JoinCounter = true;
            
            $result['datas'] = $this->ExecuteInnerJoin($tables, $alias, $primary, $foreign, $condition, $columns, $where, $table_encrypted, $JoinCounter);                                           
            
            return $result;
        }   
            catch (Exception $exc) {
            $this->createLog('Order', 'getPriceList', $exc);
        }                           
    }
    
    public function getSalePoints($customers){   
        
        try { 
            
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();            
            
            $table = "tbls_salespoints";            
            $idName = "ROUTER.idrouter";
            $where = "";
               
            $tables[0] = "tbls_salespoints";
            $tables[1] = "tbls_customers";       
            $tables[2] = "tbls_routers";       

            $alias[0]= "SALEPOINT";
            $alias[1] = "CUSTOMER";        
            $alias[2] = "ROUTER";        

            $primary[0] = "";
            $foreign[0] = "";
            $primary[1] = "idcustomer";
            $foreign[1] = "idcustomer";
            $primary[2] = "idcustomer";
            $foreign[2] = "idcustomer";
            
            $condition = "";                             
                        
            $idValues = ($result['companies'][0]['Seller']);            
            $table_encrypted =  $cryptography->Encrypting($table);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`('$table_encrypted');");
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['innerjoin'] . "." . $value['columnname'] . ' AS \'' . $value['columndescription'] . '\', ';
            }
            $columns = substr($columns, 0, -2);                           

            
            $i=0;
            for($i=0;$i<count($customers);$i++)                        
            {
                if($i==0)                    
                    $where.= " AND ";
                    
                else
                    $where.= " OR ";                                                          
                $where.= " ROUTER.idrouter = " . $customers[$i];                
            }
            
            $JoinCounter = true;
            
            $response = $this->ExecuteInnerJoin($tables, $alias, $primary, $foreign, $condition, $columns, $where, $table_encrypted, $JoinCounter);              
            
            return $response;
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'getSalePoints', $exc);
        }
    }
    public function CreateDataForInsert($table, $data){   
        
        try {                                                    
            
            $columns = "";
            $values;
            $columnsUpdate;
            
            $counter = 0;
            $counterColumns = 0;
            $columnsFlag = false;
            
            foreach ($data as $item){                
                $values[$counter] = "";
                foreach ($item as $key => $value){                                                    
                    if(!$columnsFlag)
                    {
                        $columns .= $key.",";
                        if ($value != 'CURDATE()' && $value != 'CURTIME()')
                        {
                            $columnsUpdate[$counterColumns] = $key;
                            $counterColumns++;
                        }
                    }
                    $values[$counter] .= ($value == 'CURDATE()') || ($value == 'CURTIME()') ? $value."," : "'$value',";                    
                }
                $columnsFlag = true;
                $values[$counter] = substr($values[$counter], 0, -1);
                $counter++;
            }   
            
            $columns = substr($columns, 0, -1);     
            $response = $this->insertblock($table, $columns, $values, $columnsUpdate);
            $this->updateAutoIncrement($table);      
            return $response;   
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'CreateDataForInsert', $exc);
        }
    } 
    
    public function SaveOrderTemp($user, $orderDetailJson, $routers, $priceLists, $formPays, $company, $viewDataJson){   
        
        try {              
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();                             
            
            $datetime = new DateTime('tomorrow');
            $tomorrow = $datetime->format('Y-m-d');            
            
            $sellerId = $this->getSellerIdByUser($user);            
            $response = $this->DeleteTempRegisters($sellerId);
            
            $data = $this->getDataForCreateOrderTempJson($user, $orderDetailJson, $routers, $priceLists, $formPays, $company, $sellerId);                        
            
            $orderTemp = $this->CreateOrderTempJson($data, $sellerId);         
            
            $table = "tbls_temp_orders";               
            $this->CreateDataForInsert($table, $orderTemp);            
            $orderTemp =  $this->saveOrderDetailTemp($orderDetailJson, $sellerId, $priceLists);                                                    
            
            $totals = $this->excecuteDatabaseStoredProcedures("`sp_getorderdetailsummatory`();");                                     
            
            $counter = 0;
            foreach ($orderTemp as $item)
            {  
                unset($orderTemp[$counter]['idcurrency']);
                foreach ($totals as $subitem)
                {                    
                    if($item['idtemporder'] == $subitem['idtemporder'])
                    {                               
                        $orderTemp[$counter]['totalquantity'] = $subitem['TotalQuantity'];
                        $orderTemp[$counter]['discounttotalvalue'] = $subitem['DiscountTotalValue'];
                        $orderTemp[$counter]['ivatotalvalue'] = $subitem['IvaTotalValue'];
                        $orderTemp[$counter]['subtotal'] = $subitem['SubTotal'];
                        $orderTemp[$counter]['grosstotalvalue'] = $subitem['GrossTotalValue'];
                        $orderTemp[$counter]['nettotalvalue'] = $subitem['NetTotalValue'];  
                        $orderTemp[$counter]['datedelivery'] = $tomorrow;                         
                    }
                }
                $counter++;                
            }         
            
            $this->CreateDataForInsert($table, $orderTemp);                           
            $response['orderTemp'] = $this->excecuteDatabaseStoredProcedures("`sp_getordertempforview`('$sellerId');");                 
            $response['orderTempTotals'] = $this->excecuteDatabaseStoredProcedures("`sp_getordertemptotalsforview`('$sellerId');");                 
            $response['orderTempSummary'] = $this->excecuteDatabaseStoredProcedures("`sp_getordertempsummary`('$sellerId');");                             
            $table_encrypted =  $cryptography->Encrypting($table);            
            $response['orderTempConfig'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");            
            $response['orderTempConfigForm'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableconfig`('$table_encrypted');");            
            
            $alias = "DETAIL.idtemporder = ";
            $where = "";
            $firstTime = true;
            foreach ($response['orderTemp'] as $item)
            {                
                if (!$firstTime)
                    $where .= " OR ";
                $firstTime = false;
                $where .= $alias . $item['Id'] . " ";
            }   
            
            $response['orderDetailTemp'] = $this->excecuteDatabaseStoredProcedures("`sp_getorderdetailtempforview`('$where');");                 
            $table = 'tbls_temp_ordersdetails';
            $table_encrypted =  $cryptography->Encrypting($table);            
            $response['orderDetailTempConfig'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableviewconfig`('$table_encrypted');");            
                        
            $this->insertViewDataJson($viewDataJson, $sellerId);
                        
            return json_encode($response);   
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'SaveOrderTemp', $exc);
        }
    }
    
     public function getDataForCreateOrderTempJson($user, $orderDetailJson, $routers, $priceLists, $formPays, $company, $sellerId){   
        
        try {                                                                            
            
            $orderDetailJson = json_decode($orderDetailJson, true);                        
            $routers = json_decode($routers);
            $formPays = json_decode($formPays, true);
            $priceLists = json_decode($priceLists, true);           
            
            $companyArray = $this->excecuteDatabaseStoredProcedures("`sp_getcompanydescriptionbyid`('$company');");            
            
            $formPayDetails = $this->excecuteDatabaseStoredProcedures("`sp_getformspaymentsdetails`();");   
            $priceListDescriptions = $this->excecuteDatabaseStoredProcedures("`sp_getallpricelist`();");               
            
            $address = $this->getSalePoints($routers);               
            $routersArray =  $this->getRouters($routers, false);              
            $addressArray = Array();
            
            foreach ($orderDetailJson as $item)
            {
                if (!in_array($item['idAddress'], $addressArray))
                {                    
                    array_push($addressArray,$item['idAddress']);
                }       
            }                      
            
            $selectedOptions = Array();
            
            for ($counter=0;$counter<count($routers);$counter++)
            {                
                foreach ($routersArray as $item)
                {
                    if ($item['Id'] == $routers[$counter])
                        $selectedOptions[$counter]['idCustomer'] = $item['Id del Cliente'];
                }
                $selectedOptions[$counter]['idRouter'] = $routers[$counter];
                $selectedOptions[$counter]['idFormPay'] = $formPays[$counter];
                $selectedOptions[$counter]['idPriceList'] = $priceLists[$counter];                   
                foreach ($priceListDescriptions as $item)
                {             
                    if ($item['Id'] == $priceLists[$counter])
                        $selectedOptions[$counter]['priceListDescriptions'] = $item['Lista de Precio'];
                }       
            }    
            
            foreach ($companyArray as $item)
            {
                $companyDescription = $item['companydescription'];
            }             

            $result['address'] = $address;
            $result['sellerId'] = $sellerId;            
            $result['selectedOptions'] = $selectedOptions;         
            $result['addressArray'] = $addressArray;
            $result['formPayDetails'] = $formPayDetails;
            $result['company'] = $company;
            $result['companyDescription'] = $companyDescription;            
          
            return $result;              
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'getDataForCreateOrderTempJson', $exc);
        }
    }

    public function CreateOrderTempJson($data){   
        
        try {                                         
            $orderTemp = Array();            
            $counter = 0;               
            
            foreach ($data['address'] as $item)
            { 
                if (in_array($item['Id Punto de Venta'], $data['addressArray']))
                {        
                    foreach ($data['selectedOptions'] as $option)
                    {
                        if($option['idCustomer'] == $item['Id Cliente'])
                        {
                            //Código forma de pago para el pago de contado.            
                            foreach ($data['formPayDetails'] as $subitem)
                            {

                                if(empty($cashPayment))
                                {
                                    if($option['idFormPay'] == 1)                                
                                        $cashPayment = $subitem['formpaymentsdetailcode'];
                                }

                                if($subitem['idformpaymentdetail']==$item['Forma de Pago'])
                                {                                       
                                    $orderTemp[$counter]["idcompany"] = $data['company'];
                                    $orderTemp[$counter]["companydescription"] = $data['companyDescription'];
                                    $orderTemp[$counter]["idseller"] = $data['sellerId'];
                                    $orderTemp[$counter]["idcustomer"] = $item['Id Cliente'];
                                    $orderTemp[$counter]["accountcustomer"] = $item['Cuenta Cliente'];
                                    $orderTemp[$counter]["address"] = $item['Direcci&#243;n'];
                                    $orderTemp[$counter]["idformpayment"] = $option['idFormPay'];
                                    $orderTemp[$counter]["dateorder"] = "CURDATE()";                          
                                    $orderTemp[$counter]["starttime"] = "CURTIME()";                          
                                    $orderTemp[$counter]["idplatformtype"] = 1;                          
                                    $orderTemp[$counter]["consecutivecellphone"] = 0;                          
                                    $orderTemp[$counter]["idstate"] = 1;                                                              
                                    
                                    // Si seleccionó la opción de pago de contado
                                    if($option['idFormPay']==1)    
                                    {                                
                                        $orderTemp[$counter]["idformpaymentdetail"] = 1;
                                        $orderTemp[$counter]["formpaymentsdetailcode"] = $cashPayment;
                                        
                                    }
                                    else
                                    {                                
                                        $orderTemp[$counter]["idformpaymentdetail"] = $subitem['idformpaymentdetail'];
                                        $orderTemp[$counter]["formpaymentsdetailcode"] = $subitem['formpaymentsdetailcode'];
                                        
                                    }
                                    
                                    $orderTemp[$counter]["idpricelist"] = $option['idPriceList'];
                                    $orderTemp[$counter]["descriptionpricelist"] = $option['priceListDescriptions'];                                                              
                                    $orderTemp[$counter]["idaddress"] = $item['Id Punto de Venta'];
                                }               
                            }
                        }
                    }
                    $counter++;
                }                                
            }            

            return $orderTemp;              
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'CreateOrderTempJson', $exc);
        }
    }
    
    public function saveOrderDetailTemp($orderDetailJson, $sellerId, $priceLists){   
        
        try {                       
            $data = $this->getDataForCreateOrderDetailTempJson($orderDetailJson, $sellerId, $priceLists);                     
            $orderDetailTemp = $this->CreateOrderTempDetailJson($orderDetailJson, $data);             
            $table = "tbls_temp_ordersdetails"; 
            $this->CreateDataForInsert($table, $orderDetailTemp);
            
            return $data['orderTemp'];
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'saveOrderDetailTemp', $exc);
        }
    }
    
     public function getDataForCreateOrderDetailTempJson($orderDetailJson,$sellerId, $priceLists){   
        
        try {                                   
            
            $result = $this->getPlusForCreatOrderDetailTemp($orderDetailJson);                
            $references = $result['references'];            
            $data['plus'] = $result['plus'];            
            $data['orderTemp'] = $this->excecuteDatabaseStoredProcedures("`sp_getordertempinfobyseller`('$sellerId');");                
            $data['price'] = $this->getPriceForCreatOrderDetailTemp($priceLists,$references);                
            
            
            return $data;   
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'getDataForCreateOrderDetailTempJson', $exc);
        }
    }
    
    public function CreateOrderTempDetailJson($orderDetailJson, $data){   
        
        try {                                
            
            $orderDetailJson = json_decode($orderDetailJson, true); 
            $today = date("Y-m-d");      
            
            $counter = 0;
            foreach ($orderDetailJson as $item)
            {
                foreach ($data['orderTemp'] as $subitem)
                {              
                    if($item['idAddress'] == $subitem['idaddress']) 
                    {              
                        foreach ($data['plus'] as $plus)
                        {
                            if($item['idReference'] == $plus['idreference'] && $item['idSize'] == $plus['idsize'] && $item['idColor'] == $plus['idcolor'])
                            {
                                $orderDetailTemp[$counter]['idtemporder'] =  $subitem['idtemporder'];
                                $orderDetailTemp[$counter]['idplu'] =  $plus['idplus'];                                
                                
                                //------------------------------------------
                                $orderDetailTemp[$counter]['configuration'] = $plus['configuration'];                                                                
                                
                                $orderDetailTemp[$counter]['referencecode'] =  $item['referenceCode'];                                
                                $orderDetailTemp[$counter]['size'] =  $item['Size'];                                    
                                $orderDetailTemp[$counter]['color'] =  $item['colorCode'];                                    
                                $orderDetailTemp[$counter]['quantity'] =  $item['quantity'];                                    
                                $orderDetailTemp[$counter]['custom'] =  $item['custom']; 
                                
                                $noColor = true;
                                $noColorPrice = 0;
                                $ColorPrice = 0;
                                foreach($data['price'] as $price)
                                {                                     
                                    if($item['idReference'] == $price['idreference'] && $subitem['idpricelist'] == $price['idpricelist'] && $price['idcurrency'] == $subitem['idcurrency'])
                                    {                                        
                                        if ($price['startdate'] <= $today && $price['enddate'] >= $today)
                                        {                                      
                                            //Precio sin color especifico
                                            if ($price['idcolor'] == 0)
                                            {
                                                $noColorPrice = $price['price'];
                                            }

                                            if ($price['idcolor'] == $item['idColor'])
                                            {                                            
                                                $Colorprice = $price['price'];
                                                $noColor = false;
                                            }
                                        }
                                    }
                                }
                                
                                if ($noColor)
                                {
                                    $orderDetailTemp[$counter]['groosunitvalue'] =  $noColorPrice; 
                                }
                                else
                                {                                    
                                    $orderDetailTemp[$counter]['groosunitvalue'] =  $Colorprice;                                     
                                }
                                
                                $values = $this->OrderDetailTempCalculations($orderDetailTemp[$counter]['groosunitvalue'], $orderDetailTemp[$counter]['quantity'], $item['idReference']);                         
                                
                                $orderDetailTemp[$counter]['percentagediscount'] = $values['percentageDiscount'];
                                $orderDetailTemp[$counter]['groosunitvalue'] = $values['grossUnitValue'];                                
                                $orderDetailTemp[$counter]['percentageiva'] = $values['percentageIVA'];            
                                $orderDetailTemp[$counter]['discountunitvalue'] = $values['discountUnitValue'];
                                $orderDetailTemp[$counter]['subtotalunit'] = $values['subTotalUnit'];
                                $orderDetailTemp[$counter]['ivaunitvalue'] = $values['IVAUnitValue'];
                                $orderDetailTemp[$counter]['netunitvalue'] = $values['netUnitValue'];
                                $orderDetailTemp[$counter]['discounttotalvalue'] = $values['discountTotalValue'];
                                $orderDetailTemp[$counter]['ivatotalvalue'] = $values['IVATotalvalue'];
                                $orderDetailTemp[$counter]['subtotal'] = $values['subTotal'];
                                $orderDetailTemp[$counter]['grosstotalvalue'] = $values['grossTotalValue'];
                                $orderDetailTemp[$counter]['nettotalvalue'] = $values['netValue'];                                                                                    
                                $orderDetailTemp[$counter]['nettotalvalue'] = $values['netValue'];                                     
                                
                                $counter++;
                            }
                        }
                    }
                }
            }
            return $orderDetailTemp;
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'CreateOrderTempDetailJson', $exc);
        }
    }
    
    public function insertViewDataJson($viewDataJson, $sellerId){   
        
        try {  
            $table = "tbls_temp_ordersview";
            $data['data']['idseller'] =  $sellerId;
            $data['data']['json'] =  $viewDataJson;            
            $response = $this->CreateDataForInsert($table, $data);
            return $response;
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'insertViewDataJson', $exc);
        }
    }
    
    
    public function getPlusForCreatOrderDetailTemp($orderDetailJson){   
        
        try {              
            
            $orderDetailJson = json_decode($orderDetailJson, true);                        
            $table = "tbls_plus";            
            $config = $this->excecuteDatabaseStoredProcedures("`sp_selecttablestructurewithprimary`('$table');");
            $columns = "";            
            foreach ($config as $value) 
            {                                
                $columns .= $value['COLUMN_NAME'] .', ';
            }
            
            $columns = substr($columns, 0, -2);            
            
            $firstTime = true;
            $where = " AND "; 
            $referenceArray = Array();
            
            foreach ($orderDetailJson as $item)
            {
                if (!in_array($item['idReference'], $referenceArray))
                        array_push($referenceArray,$item['idReference']);
                if(!$firstTime)
                {                
                    $where .= " OR ";                    
                } 
                
                $where .= " idreference = " . $item['idReference'] .  " AND  idcolor = " . $item['idColor'] .  " AND idsize = " .  $item['idSize'];
                $firstTime =false;
            }    
            
            $query = $this->createBasicSelectWithWhere($columns, $table, $where);
            $data['plus'] = $this->excecuteQueryAll($query);                                    
            $data['references'] = $referenceArray;
            
            return $data;
            
           
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'CreateOrderTempDetailJson', $exc);
        }
    }
    
    public function getPriceForCreatOrderDetailTemp($priceLists, $references){   
        
        try {        
            
            $priceLists = json_decode($priceLists);
            $priceListArray = Array();
            
            for ($counter=0;$counter<count($priceLists);$counter++)  
            {
                if (!in_array($priceLists[$counter], $priceListArray))
                        array_push($priceListArray,$priceLists[$counter]);
            }
            
            $table = "tbls_referencespricelist";
            $config = $this->excecuteDatabaseStoredProcedures("`sp_selecttablestructurewithprimary`('$table');");
            
            $columns = "";            
            foreach ($config as $value) 
            {                                
                $columns .= $value['COLUMN_NAME'] .', ';
            }
            
            $columns = substr($columns, 0, -2);      
            
            $firstPrice = true;            
            $where = " AND ";           
            
            for ($counterPrice=0;$counterPrice<count($priceListArray);$counterPrice++)
            {
                if(!$firstPrice)
                    {                
                        $where .= " OR ";                    
                    } 
                    $firstPrice =false;                                    
                    $firstReference = true;
                for ($counterReferences=0;$counterReferences<count($references);$counterReferences++)
                {
                    if(!$firstReference)
                    {                
                        $where .= " OR ";                    
                    } 
                    $firstReference = false;
                    $where .= " idpricelist = " . $priceListArray[$counterPrice] . " AND idreference= " . $references[$counterReferences];
                }                
            }            
            
            $query = $this->createBasicSelectWithWhere($columns, $table, $where);         
            $result = $this->excecuteQueryAll($query);              
            return $result;                                      
           
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'getPriceForCreatOrderDetailTemp', $exc);
        }
    }
    
    public function OrderDetailTempCalculations($grossUnitValue, $quantity, $reference){   
        
        try {                  
            $iva = $this->excecuteDatabaseStoredProcedures("`sp_getpercentageivabyreference`('$reference');");  
            
            foreach ($iva as $item)
            {
                $percentageIva = $item['IVA'];
            }            
            
            $result['percentageDiscount'] = 0;
            $result['grossUnitValue'] = $grossUnitValue;
            $result['percentageIVA'] = $percentageIva;            
            $result['discountUnitValue'] = $grossUnitValue*($result['percentageDiscount']/100);
            $result['subTotalUnit'] = $grossUnitValue - $result['discountUnitValue'];
            $result['IVAUnitValue'] = $result['subTotalUnit']*($result['percentageIVA']/100);
            $result['netUnitValue'] = $result['subTotalUnit']+$result['IVAUnitValue'];
            $result['discountTotalValue'] = $result['discountUnitValue']*$quantity;
            $result['IVATotalvalue'] = $result['IVAUnitValue']*$quantity;
            $result['subTotal'] = $result['subTotalUnit']*$quantity;
            $result['grossTotalValue'] = $grossUnitValue*$quantity;
            $result['netValue'] = $result['subTotal']+$result['IVATotalvalue'];
            
            return $result;     
           
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'getPriceForCreatOrderDetailTemp', $exc);
        }
    }
    
    public function GetOrderObservations($idTempOrder){   
        
        try {                             
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();                          
            $table = "tbls_temp_orders";
            $table_encrypted =  $cryptography->Encrypting($table);
            $response['orderTempConfigForm'] = $this->excecuteDatabaseStoredProcedures("`sp_gettableconfig`('$table_encrypted');"); 
            $response['observations'] = $this->excecuteDatabaseStoredProcedures("`sp_getordertempobservationsbyid`('$idTempOrder');"); 
            
            return json_encode($response);
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'GetOrderObservations', $exc);
        }
    }  
    
    public function SaveOrderObservations($idTempOrder, $observation, $column){   
        
        try {                             
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();                          
            $table = "tbls_temp_orders";
            $column =  $cryptography->Unencrypting($column);      
            $set = $column . " = " . "'" . $observation . "'";
            $where = " AND idtemporder = " . $idTempOrder;
            $response = $this->updatebasic($table, $set, $where);              
            if (json_encode($response) == '{}') 
                    return "OK";
                else
                    return "";                  
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'SaveOrderObservations', $exc);
        }
    }  
    
    public function SaveOrder($user, $company){   
        
        try {            
            
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();  
            $table = "tbls_orders";
            $table_encrypted =  $cryptography->Encrypting($table);            
            $sellerId = $this->getSellerIdByUser($user);    
            $this->excecuteDatabaseStoredProcedures("`sp_insertordervaluesbyseller`('$sellerId');");              
            $order = $this->SaveOrderDetail($sellerId, $table, $table_encrypted);                
            $data = $this->UpdateStock($order['orderDetail'], $order['idPlus'], $company);         
            $result = $this->updateTransactions($data['custom'], $data['idOrder'], $company); 
            /////revisar
            $response = $this->DeleteTempRegisters($sellerId);
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'SaveOrder', $exc);
        }
    }     
    
    public function SaveOrderDetail($sellerId, $table, $table_encrypted){   
        
        try {                
            $data = $this->getDataForCreateOrderDetailJson($sellerId, $table, $table_encrypted);     
            $orderDetail = $this->CreateOrderDetailJson($data);                        
            $table = "tbls_ordersdetails"; 
            $this->CreateDataForInsert($table, $orderDetail['orderDetail']); 
            
            return $orderDetail;            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'SaveOrderDetail', $exc);
        }
    }    
    
    public function getDataForCreateOrderDetailJson($sellerId, $table, $table_encrypted){   
        
        try {               
            Yii::import('application.components.Cryptography'); 
            $cryptography = new Cryptography();  
            $orderTemp = $this->excecuteDatabaseStoredProcedures("`sp_getordertempinfobyseller`('$sellerId');");   
            $orderDetailTemp = $this->excecuteDatabaseStoredProcedures("`sp_getalltemporderdetails`();");   
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselectwithjoin`($table_encrypted);");   
            
            $columns = "";
            
            foreach ($config as $item)
            {         
                $columnName =  $cryptography->Unencrypting($item['columnname']);
                $columns .= $columnName . ', ';      
                $columnsArray[$item['columndescription']] = $columnName;
            }                                   
            
            $columns = substr($columns, 0, -2);                                                               
            
            $where = " AND ";
            $firstTime = true;
            foreach ($orderTemp as $item)
            {          
                if(!$firstTime)
                    $where .= " OR ";
                // Comparo id del cliente, id del vendedor, dirección, fecha y hora para identificar el pedido
                $where .= $columnsArray['Id Cliente'] . " = " . $item['idcustomer'];                
                $where .= " AND ";                
                $where .= $columnsArray['Vendedor'] . " = " . $item['idseller'];
                $where .= " AND ";                
                $where .=  $columnsArray['Direcci&#243;n'] . " = " . "'" . $item['address'] . "'";
                $where .= " AND ";                
                $where .= $columnsArray['Fecha'] . " = " . "'" . $item['dateorder'] . "'";
                $where .= " AND ";                
                $where .= $columnsArray['Hora'] . " = " . "'" . $item['starttime'] . "'";                                  
                $firstTime = false;                
            }
            
            $query = $this->createBasicSelectWithWhere($columns, $table, $where); 
            $result['Order'] = $this->excecuteQueryAll($query);    
            $result['orderTemp'] = $orderTemp;
            $result['orderDetailTemp'] = $orderDetailTemp;
            
            return $result;
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'getDataForCreateOrderDetailJson', $exc);
        }
    }    
    
    public function CreateOrderDetailJson($data){   
        
        try {               
            
            $idOrderArray = Array();
            $idPlusArray = Array();
            $orderDetailCounter = 0;
            
            foreach ($data['Order'] as $item)
            {                
                foreach ($data['orderTemp'] as $subItem)
                {
                    if (!in_array($subItem['idtemporder'], $idOrderArray))
                    {
                        // Capturo los identificadores del encabezado del pedido
                        array_push($idOrderArray, $subItem['idtemporder']);
                    }
                    if($subItem['idcustomer'] == $item['idcustomer'] && $subItem['address'] == $item['address'])
                    {                           
                     $detailCounter = 0;
                     foreach ($data['orderDetailTemp'] as $detail)
                     {  
                         if (!in_array($detail['idplu'], $idPlusArray))
                        {
                        // Capturo los identificadores de los plus.
                        array_push($idPlusArray, $detail['idplu']);
                        }
                         if($detail['idtemporder'] == $subItem['idtemporder'])
                         {                          
                            // Asigno el id del pedido al Json del detalle
                            $data['orderDetailTemp'][$detailCounter]['idorder'] = $item['idorder'];     
                            // Elimino del Json el id temporal del pedido y el id del detalle temporal
                            unset($data['orderDetailTemp'][$detailCounter]['idtemporderdetail']);
                            unset($data['orderDetailTemp'][$detailCounter]['idtemporder']);
                          
                            $orderDetail[$orderDetailCounter] = $data['orderDetailTemp'][$detailCounter];
                            $orderDetailCounter++;
                            
                         }                         
                         $detailCounter++;
                     }
                    }
                }                
            }                                    
            $result['orderDetail'] = $orderDetail;            
            $result['idOrder'] = $idOrderArray;
            $result['idPlus'] = $idPlusArray;            
            return $result;
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'CreateOrderDetailJson', $exc);
        }
    }    
    
     public function DeleteTempRegisters($sellerId){   
        
        try {                             
            $orderTemp = $this->excecuteDatabaseStoredProcedures("`sp_getordertempinfobyseller`('$sellerId');"); 
            
            $orderCounter = 0;
            foreach ($orderTemp as $item)
            {
                $idOrder[$orderCounter] = $item['idtemporder'];
                $orderCounter++;
            }
            
            $table = "tbls_temp_ordersdetails";
            $where = " AND ";
            $firstTime = true;
            $column = "idtemporder";            
            $length = count($idOrder);                        
            
            if($length>0)            
            {
                for($counter=0;$counter<$length;$counter++)
                {
                    if (!$firstTime)
                        $where .= " OR ";
                    $where .= $column . " = " . $idOrder[$counter] . " ";                
                    $firstTime = false;
                }
                $where .= ";";
                $sql = $this->deletebasic($table, $where);                                                 
                $this->excecuteQuery($sql);                        
            }
            
            $this->excecuteDatabaseStoredProcedures("`sp_deletetempordersbyseller`('$sellerId');"); 
            return $this->excecuteDatabaseStoredProcedures("`sp_deletetemporderviewbyseller`('$sellerId');"); 
            
           
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'DeleteTempRegisters', $exc);
        }
    }       
    
    public function updateStock($orderDetail, $idPlus, $company){   
        
        try {                          
            $table = "tbls_stock";
            $stock = $this->getStockData($idPlus, $table);                                      
            $data = $this->createStockJsonforUpdate($stock, $orderDetail, $company);                   
            $response = $this->CreateDataForInsert($table, $data['stock']);                
            return $data;           
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'updateStock', $exc);
        }
    }   
    
    public function getStockData($idPlus, $table){           
        try {    
            Yii::import('application.components.Cryptography');
            $cryptography = new Cryptography();              
            $table_encrypted =  $cryptography->Encrypting($table);        
            $config = $this->excecuteDatabaseStoredProcedures("`sp_gettableselect`('$table_encrypted');");
            $columns = "";            
            foreach ($config as $value) {                
                $value['columnname'] = $cryptography->Unencrypting($value['columnname']);
                $columns .= $value['columnname'] . ', ';
                
            }
            $columns = substr($columns, 0, -2);
            
            $where = " AND ";
            $firstTime = true;
            $idName = "idplu";
            for($counter=0;$counter<count($idPlus);$counter++)            
            {
                if(!$firstTime)
                    $where .= " OR ";
                $where .= $idName . " = " . $idPlus[$counter] . " ";
                $firstTime = false;
            }
            
            $query = $this->createBasicSelectWithWhere($columns, $table, $where);                       
            $stock = $this->excecuteQueryAll($query);            
            
            return $stock;
           
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'getStockData', $exc);
        }
    }   
    
    public function createStockJsonforUpdate($stock, $orderDetail, $company){           
        try {   
            $idCustom = Array();  
            $idOrder = Array();                    
            $newStockIndex = count($stock);            
            foreach ($orderDetail as $item)
            {
                if (!in_array($item['idorder'], $idOrder))
                    {
                        // Capturo los identificadores de los pedidos que tienen pedido por encargo.
                        array_push($idOrder, $item['idorder']);
                    }
                
                //Identifico si hay pedidos por encargo.
                if($item['custom'] == '1')
                {
                    if (!in_array($item['idorder'], $idCustom))
                    {
                        // Capturo los identificadores de los pedidos que tienen pedido por encargo.
                        array_push($idCustom, $item['idorder']);
                    }
                }
                $stockCounter = 0;
                
                $stockFound = false;
                
                foreach ($stock as $subitem)
                {                       
                    if($item['idplu'] == $subitem['idplu'])
                    {
                        // Sumo al valor actual de reserva la cantidad pedida.
                        $stock[$stockCounter]['reservation'] = $subitem['reservation'] +  $item['quantity'];    
                        $stock[$stockCounter]['idcompany'] = $company;
                        $stockFound = true;
                    }           
                    
                    $stockCounter++;
                }   
                
                if (!$stockFound)
                {
                    // Si no se encontró el plus en la tabla de inventario, creo un nuevo item para insertarlo.
                    $stock[$newStockIndex]['idstock'] = "";
                    $stock[$newStockIndex]['idplu'] = $item['idplu'];                    
                    $stock[$newStockIndex]['quantitystock'] = "0";
                    $stock[$newStockIndex]['reservation'] = $item['quantity'];
                    $stock[$newStockIndex]['idcompany'] = $company;
                    $newStockIndex++;
                }                
            }       
            
            $data['custom'] = $idCustom;            
            $data['idOrder'] = $idOrder;            
            $data['stock'] = $stock;            
            return $data;
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'createStockJsonforUpdate', $exc);
        }
    } 
    
    public function updateTransactions($custom, $idOrder, $company){           
        try {   
            $table = "tbls_transactions";
            $transactions = $this->createTransactionJson($custom, $idOrder, $company);            
            $response = $this->CreateDataForInsert($table, $transactions);     
            return $response;
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'updateTransactions', $exc);
        }
    } 
    
       public function createTransactionJson($custom, $idOrder, $company){           
        try {   
            
            //Valores definidos en la historia de usuario.
            $idtypetransaction = 1; 
            $customTransaction = 3; 
            $idstatetransactions = 0;
            $creationdate = "CURDATE()";   
            $creationtime = "CURTIME()";
            $idstateemail = 0;            
            
            $counter = 0;
            for($orderCounter=0;$orderCounter<count($idOrder);$orderCounter++)
            {
                $transaction[$counter]['idtypetransaction'] = $idtypetransaction;
                $transaction[$counter]['idstatetransaction'] = $idstatetransactions;
                $transaction[$counter]['creationdate'] = $creationdate;
                $transaction[$counter]['creationtime'] = $creationtime;                
                $transaction[$counter]['idstateemail'] = $idstateemail;
                $transaction[$counter]['idcompany'] = $company;
                $transaction[$counter]['number'] = $idOrder[$orderCounter];
                
               // Si el pedido tiene un encargo .
               if(in_array($idOrder[$orderCounter], $custom))
               {
                    $counter++;
                    $transaction[$counter]['idtypetransaction'] = $customTransaction;
                    $transaction[$counter]['idstatetransaction'] = $idstatetransactions;
                    $transaction[$counter]['creationdate'] = $creationdate;
                    $transaction[$counter]['creationtime'] = $creationtime;                
                    $transaction[$counter]['idstateemail'] = $idstateemail;
                    $transaction[$counter]['idcompany'] = $company;
                    $transaction[$counter]['number'] = $idOrder[$orderCounter];                   
               }                       
                
              $counter++;  
            }            
            
            return $transaction;
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'createTransactionJson', $exc);
        }
    } 
    
    public function SelectUncompletedOrder($user){           
        try {               
            $sellerId = $this->getSellerIdByUser($user);            
            $uncompletedOrder = $this->excecuteDatabaseStoredProcedures("`sp_gettemporderviewbyseller`('$sellerId');");  
            
            if (empty($uncompletedOrder))
                return json_encode($uncompletedOrder);
            else 
            {
                foreach ($uncompletedOrder as $item)
                {
                $uncompletedOrderData = $item['json'];
                }
            return ($uncompletedOrderData);            
            }
            
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'SelectUncompletedOrder', $exc);
        }
    }
    
     public function getSellerIdByUser($user){           
        try {               
            $sellerArray = $this->excecuteDatabaseStoredProcedures("`sp_getselleridbyusername`('$user');");   
            foreach ($sellerArray as $item)
            {
                $sellerId = $item['idseller'];
            }
            return $sellerId;
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'getSellerByUser', $exc);
        }
    }
    
    public function SelectCustomersRouters($routers){           
        try {                           
            $table = "tbls_routers";
            $routers = json_decode($routers);
            $config = $this->excecuteDatabaseStoredProcedures("`sp_selecttablestructure`('$table');");                  
            
            $columns = "";
            foreach ($config as $item)
            {
                $columns .= $item['COLUMN_NAME'] . ", ";
                $counter++;
            }
            
            $columns = substr($columns, 0, -2);
            
            $where = " AND ";
            $value = "idrouter= ";
            $firstTime = true;
            
            for($i=0;$i<count($routers);$i++)
            {
                if(!$firstTime)
                    $where .= " OR ";
                else 
                    $firstTime = false;
                
                $where .= $value . $routers[$i];
            }                                    
            
            $query = $this->createBasicSelectWithWhere($columns, $table, $where); 
            
            $result = $this->excecuteQueryAll($query);      
            
            $counter = 0;
            foreach ($result as $item)
            {
                $customers[$counter] = $item['idcustomer'];
                $counter++;
            }
            
            
            return json_encode($customers);
        }   
        catch (Exception $exc) {
            $this->createLog('Order', 'SelectCustomersRouters', $exc);
        }
    } 
    
    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}