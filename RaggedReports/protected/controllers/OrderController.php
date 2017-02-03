<?php

/*
 * Created By Activity Technology S.A.S.
 */

class OrderController extends Controller {

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionAjaxLoad() {
        try {
            if ($_POST) {
                $user = $_POST['user'];
                $item = $_POST['item'];
                ini_set("soap.wsdl_cache_enabled", "1");
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $uncompletedOrder = $service->SelectUncompletedOrder($user['user']);
                $RoutersJson = json_decode($uncompletedOrder);
                echo json_encode($RoutersJson);
            }
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'actionAjaxLoad', $exc);
        }
    }

    public function actionGetRouters() {
        try {
            if ($_POST) {
                $user = $_POST['user'];
                $item = $_POST['item'];
                ini_set("soap.wsdl_cache_enabled", "1");
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $Routers = $service->SelectRouters($user['user'], $item);
                $RoutersJson = json_decode($Routers);          
                echo $this->renderPartial('/order/viewrouter', array('Routers' => json_encode($RoutersJson)));
            }
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'actionGetRouters', $exc);
        }
    }

    public function actionShowWallet() {
        try {
            $routers = $_POST['idRouters'];
            ini_set("soap.wsdl_cache_enabled", "1");
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $customers = $service->SelectCustomersRouters(json_encode($routers));            
            $response = $service->SelectCustomersWallet($customers);
            echo $response;
        } catch (Exception $e) {
            $this->createLog('OrderController', 'actionShowWallet', $e);
        }
    }

    public function ActionBackToRouters() {
        try {
            ini_set("soap.wsdl_cache_enabled", "1");
            ini_set("soap.wsdl_cache_enabled", "0");
            echo $this->renderPartial('/order/viewrouter');
        } catch (Exception $e) {
            $this->createLog('OrderController', 'ActionBackToRouters', $e);
        }
    }

    public function actionCreateOrderDetail() {
        try {
            if ($_POST) {
                $user = $_POST['user'];
                $item = $_POST['item'];
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $References = $service->SelectReferences($user['user'], $item);
                $ReferencesArray = json_decode($References);
                $Collections = $service->SelectCollection($user['user'], $item);
                $CollectionArray = json_decode($Collections);
                $PriceList = $service->SelectPriceLists($user['user'], $item);
                $PriceListArray = json_decode($PriceList);
                echo $this->renderPartial('/reference/viewreference', array('References' => json_encode($ReferencesArray), 'Collections' => json_encode($CollectionArray), 'PriceList' => json_encode($PriceListArray)));
                $priceList = $_POST['priceList'];
                $customers = $_POST['customers'];
                $company = $_POST['company'];
                $quantities = $_POST['quantities'];
                $checks = $_POST['checks'];
                $collections = "";
                $references = "";
                $response = $service->SelectOrderDetail(json_encode($collections), json_encode($references), json_encode($priceList), json_encode($customers), $company);
                $Plus = json_decode($response, true);                            
            
                //////////////////////////////////////        
                
                $colorIdTemp = 0;
                $colorCodeTemp = 0;
                $createAccordion = true;
                $tableInsideAccordion = '';
                $contIndex = 0;                
                $accordionChildCounter = 0;
                $accordionParentCounter = 0;                
                $customerIndex = 0;
                $accountTemp = '';
                $addressTemp = '';
                $addressIdTemp = '';
                $addressArray = array();
                $addressIdArray = array();
                $referenceIdTemp = 0;
                $referenceCodeTemp = 0;
                $referenceAux = '';
                $eferenceCodeArray = array();
                $referenceNameArray = array();
                $referenceIdArray = array();
                $plusArray = array();
                $sizeTemp = '';    
                
                
                $addressTemp = array();
                $addressIdTemp = array();
            
                foreach($Plus['address'] as $keyAddress => $valAddress) 
                {
                   if ($valAddress["Cuenta Cliente"] == $accountTemp)
                   {
                       array_push($addressTemp, $valAddress["Direcci&#243;n"]); 
                       array_push($addressIdTemp, $valAddress["Id Punto de Venta"]);                                               
                   }
                      
                   else
                    {
                        $accountTemp = $valAddress["Cuenta Cliente"];
                        $addressTemp = array();
                        $addressIdTemp = array();
                        array_push($addressTemp, $valAddress["Direcci&#243;n"]); 
                        array_push($addressIdTemp, $valAddress["Id Punto de Venta"]);                                                 
                    }
                    $addressArray[$accountTemp] = $addressTemp;
                    $addressIdArray[$accountTemp] = $addressIdTemp;
                }
                
                ///////////////////////////////    
                
                $referenceCodeArray = array();
                $referenceNameArray = array();
                $referenceIdArray = array();
                
                foreach ($Plus['plus']['datas'] as $keyReference => $valReference)
                {
                    if ($referenceAux != $valReference['C&#243;digo Referencia'])
                    {
                        $referenceAux = $valReference['C&#243;digo Referencia'];
                        array_push($referenceCodeArray, $valReference['C&#243;digo Referencia']);                                               
                        array_push($referenceNameArray, $valReference['C&#243;digo Referencia'] . ' - ' . $valReference['Descripci&#243;n Referencia']);                                               
                        array_push($referenceIdArray, $valReference['IdReferencia']);
                    }
                }
                
                $singleCustomer = count($customers) > 1 ? false : true;                
                $referenceAccordion = '';
                
                for ($referenceCounter = 0; $referenceCounter < count($referenceCodeArray); $referenceCounter++)
                {                    
                    $accordionParentCounter++;             
                    
                    // Identifico si seleccionó más de un cliente para la toma de pedido.
                    if (!$singleCustomer)
                    {                    
                        $referenceAccordion .= '<div class="panel-group accordion-reference" id="reference-' . $referenceIdArray[$referenceCounter] . '">';
                        $referenceAccordion .= '<div class="panel panel-default">';
                        $referenceAccordion .= '<div class="panel-heading">';
                        $referenceAccordion .= '<h4 class="panel-title">';
                        $referenceAccordion .= '<a data-toggle="collapse" class ="expandFind" data-inner="collapseInner' . $accordionParentCounter . '" data-parent="#reference-accordion' . $accordionParentCounter . '" href="#collapseInner' . $accordionParentCounter . '">' . $referenceNameArray[$referenceCounter] . ' </a></h4></div>';
                        $referenceAccordion .= '<div id="collapseInner' . $accordionParentCounter . '" class="panel-collapse collapse reference-collapse">';
                        $referenceAccordion .= '<div class="panel-body">';
                    }
                    
                    $customers = array();
                    foreach($Plus['address'] as $keyAddress => $valAddress) 
                    {
                        $addressLength = count($addressArray[$valAddress['Cuenta Cliente']]);                                    
                        
                        if (!in_array($valAddress['Cuenta Cliente'], $customers))	                  

                        {
                            $plusArray = array();                        
                            array_push($customers, $valAddress['Cuenta Cliente']);                                               
                            foreach ($Plus['plus']['datas'] as $key => $val) 
                                {
                                $accordionChildCounter++;
                                $sizeHeader = array();
                                $sizeName = array();
                                if ($referenceIdTemp == $val['IdReferencia'])
                                    $createAccordion = false;
                                else
                                {
                                    $createAccordion = true;
                                    $referenceIdTemp = $val['IdReferencia'];
                                    $referenceCodeTemp = $val['C&#243;digo Referencia'];
                                }
                                if ($createAccordion)
                                {                                    
                                    if ($val['C&#243;digo Referencia'] == $referenceCodeArray[$referenceCounter])
                                    {                                        
                                        if (!$singleCustomer)
                                        {
                                            $referenceAccordion .= '<div class="panel-group" id="accordion-' . $val['Id'] . '-' . $accordionChildCounter . '">';
                                            $referenceAccordion .= '<div class="panel panel-default"><div class="panel-heading">';
                                            $referenceAccordion .= '<h4 class="panel-title"><a data-toggle="collapse" data-inner="collapseInner' . $accordionParentCounter . '" data-parent="#accordion-' . $val['Id'] . '-' . $accordionChildCounter . '" href="#collapse-' . $val['Id'] . '-' . $accordionChildCounter . '">';
                                            $referenceAccordion .= $valAddress['Cuenta Cliente'] . ' - ' . $valAddress['Raz&#243;n Social'];
                                            $referenceAccordion .= '</a></h4></div>';
                                            $referenceAccordion .= '<div id="collapse-' . $val['Id'] . '-' . $accordionChildCounter . '" class="panel-collapse collapse">';
                                        }
                                        else
                                        {                                            
                                            $referenceAccordion .= '<div class="panel-group accordion-reference" id="reference-' . $referenceIdArray[$referenceCounter] . '">';
                                            $referenceAccordion .= '<div class="panel panel-default"><div class="panel-heading">';
                                            $referenceAccordion .= '<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-' . $val['Id'] . '-' . $accordionChildCounter . '" href="#collapse-' . $val['Id'] . '-' . $accordionChildCounter . '">';
                                            $referenceAccordion .= $referenceNameArray[$referenceCounter];
                                            $referenceAccordion .= '</a></h4></div>';
                                            $referenceAccordion .= '<div id="collapse-' . $val['Id'] . '-' . $accordionChildCounter . '" class="panel-collapse collapse in">';
                                        }
                                        $referenceAccordion .= '<div style="overflow-x: scroll" class="panel-body">';
                                        $tableInsideAccordion .= '<table id="table-' . $accordionChildCounter . '" class="display dataTable bordered centered table-plus" width="100%">';                                                                     
                                        $tableInsideAccordion .= '<thead><tr>';
                                        $tableInsideAccordion .= '<th>' . 'Dirección' . '</th>';
                                        $tableInsideAccordion .= '<th>' . 'Color' . '</th>';

                                        foreach ($Plus['plus']['datas'] as $keyTableHead => $valTableHead) {
                                            foreach($val as $kyTableHead => $vlTableHead) {
                                                if ($referenceIdTemp == $valTableHead['IdReferencia'] && $kyTableHead == 'Tallas')
                                                {    
                                                    
                                                    if (!in_array($valTableHead['IdTalla'], $sizeHeader))	                                                
                                                    {
                                                        array_push($sizeHeader, $valTableHead['IdTalla']);                                                                                                   
                                                        array_push($sizeName, $valTableHead['Tallas']);                                                                                                                                                       
                                                        $tableInsideAccordion .= '<th>' . $valTableHead['Tallas'] . '</th>';
                                                    }
                                                }
                                            }
                                        }
                                        $tableInsideAccordion .= '<th>' . 'Encargo' . '</th>';
                                        $tableInsideAccordion .= '</tr></thead>';
                                        $tableInsideAccordion .= '<tbody>';                                        
                                       
                                        
                                        $addressCounter = 0;                                                                                                                        
                                       

                                        for ($addressCounter = 0; $addressCounter < $addressLength; $addressCounter++)
                                        {                                            
                                            $colorIdTemp = '';
                                            foreach ($Plus['plus']['datas'] as $keyTable => $valTable) 
                                            {                                        
                                                if ($valTable['IdColor'] != $colorIdTemp)
                                                {                                                
                                                    if ($referenceIdTemp == $valTable['IdReferencia'])
                                                    {
                                                        if (!in_array($referenceIdTemp . '*' . $valTable['IdColor'] . '*' . $addressArray[$valAddress['Cuenta Cliente']][$addressCounter], $plusArray))
                                                        {
                                                            $colorIdTemp = $valTable['IdColor'];
                                                            $colorCodeTemp = $valTable['C&#243;digo Color'];                                                   
                                                            array_push($plusArray, $referenceIdTemp . '*' . $colorIdTemp . '*' . $addressArray[$valAddress['Cuenta Cliente']][$addressCounter]);                                                                                                   
                                                           
                                                            $tableInsideAccordion .= '<tr data-id="' . $val['Id'] . '">';
                                                            $tableInsideAccordion .= '<td>' . $addressArray[$valAddress['Cuenta Cliente']][$addressCounter] . '</td>';
                                                            $tableInsideAccordion .= '<td style="cursor:pointer" class="tdColor" data-idColor=' . $valTable['Id'] . ' >' . $valTable['C&#243;digo Color'] . ' - ' . $valTable['Colores'] . '</td>';
                                                            $sizeFound = false;
                                                            $contIndex = 0;
                                                            for ($contIndex = 0; $contIndex < count($sizeHeader); $contIndex++)
                                                            {
                                                                $sizeFound = false;
                                                                $idSize = "";
                                                                foreach($Plus['plus']['datas'] as $keyTableQuant => $valTableQuant)
                                                                {
                                                                    $idSize = $sizeHeader[$contIndex];
                                                                    $sizeTemp = $sizeName[$contIndex];
                                                                    if ($referenceIdTemp == $valTableQuant['IdReferencia'])
                                                                    {
                                                                        if ($valTableQuant['IdColor'] == $colorIdTemp)
                                                                        {
                                                                            if ($sizeHeader[$contIndex] == $valTableQuant['IdTalla'])
                                                                            {
                                                                                $totalQuantity = $valTableQuant['Cantidad'] - $valTableQuant['Reserva'];
                                                                                // Asigno un data-id con la siguiente estructura: Id Dirección del cliente*código de la referencia*Id de la referencia*Código del color*Id del color*Talla*Id de la talla
                                                                                $tableInsideAccordion .= '<td><input data-toggle="tooltip" title="' . $totalQuantity . '" data-Quantity="' . $addressIdArray[$valAddress['Cuenta Cliente']][$addressCounter] . '*' . $referenceCodeTemp . '*' . $referenceIdTemp . '*' . $colorCodeTemp . '*' . $colorIdTemp . '*' . $sizeTemp . '*' . $idSize . '" class="form-control nums input-quantity" style="width:60px; text-align: center; color:blue" type="text" name="fname" placeholder=' . $totalQuantity . '></td>';
                                                                                $sizeFound = true;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                // En caso de que no se haya encontrado inventario de la referencia en la talla actual.
                                                                if ($sizeFound == false)
                                                                {
                                                                    // Asigno un data-id con la siguiente estructura: Id Dirección del cliente*código de la referencia*Id de la referencia*Código del color*Id del color*Talla*Id de la talla
                                                                    $tableInsideAccordion .= '<td> <input data-toggle="tooltip" title="0" data-Quantity="' . $addressIdArray[$valAddress['Cuenta Cliente']][$addressCounter] . '*' . $referenceCodeTemp . '*' . $referenceIdTemp . '*' . $colorCodeTemp . '*' . $colorIdTemp . '*' . $sizeTemp . '*' . $idSize . '" class="form-control nums input-quantity" style="width:60px; text-align: center; color:blue" type="text" name="fname" placeholder="0"> </td>';
                                                                }
                                                            }
                                                            //Asigno un data-id con la estructura: Id Dirección del cliente*Id del color*Id de la referencia.
                                                            //$tableInsideAccordion = $tableInsideAccordion . '<td>' . '<input type="checkbox" data-idCheck="' . addressIdArray[valAddress['Cuenta Cliente']][addressCounter] . '*' . colorIdTemp . '*' . referenceIdTemp . '" class="customer-checkbox">' . '</td>';
                                                            $tableInsideAccordion .= '<td>' . '<input type="checkbox" data-idCheck="' . $addressIdArray[$valAddress['Cuenta Cliente']][$addressCounter] . '*' . $colorIdTemp . '*' . $referenceIdTemp . '" class="customer-checkbox" id="answer" name=""/>' . '</td>';
                                                            $tableInsideAccordion .= '</tr>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        $tableInsideAccordion .= '</tbody>';
                                        $tableInsideAccordion .= '</table>';                                                                                                                        
                                        $referenceAccordion .= $tableInsideAccordion;
                                        $tableInsideAccordion = '';
                                        $referenceAccordion .= '</div></div></div></div>';
                                    }
                                }
                            }
                        }
                    }
                    if (!$singleCustomer)
                    {
                        $referenceAccordion .= '</div></div></div></div>';
                    }      
                }                              
                
                if (isset($quantities))
                    echo $this->renderPartial('/order/vieworderdetail', array('Plus' => json_encode($Plus), 'SingleCustomer' => $singleCustomer, 'Quantities' => json_encode($quantities), 'Checks' => json_encode($checks), 'Table' => $referenceAccordion));
                else
                    echo $this->renderPartial('/order/vieworderdetail', array('Plus' => json_encode($Plus), 'SingleCustomer' => $singleCustomer, 'Table' => $referenceAccordion));
            }
        } catch (Exception $exc) {
            
            $this->createLog('OrderController', 'actionCreateOrderDetail', $exc);
        }
    }

    public function actionSaveOrderTemp() {
        try {
            if ($_POST) {
                $user = $_POST['user'];
                $orderDetailJson = $_POST['OrderDetailJson'];                
                $routers = $_POST['routers'];                
                $priceLists = $_POST['priceLists'];                
                $formPays = $_POST['formPays'];                
                $company = $_POST['company'];
                $viewDataJson = $_POST['viewDataJson'];        
                ini_set("soap.wsdl_cache_enabled", "0");        
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $response = $service->SaveOrderTemp($user['user'], $orderDetailJson, $routers, $priceLists, $formPays, $company, $viewDataJson);
                echo $response;
            }
        } catch (Exception $exc) {            
            $this->createLog('OrderController', 'actionSaveOrderTemp', $exc);
        }
    }

    public function actionGetOrderObservations() {
        try {
            if ($_POST) {
                $idTempOrder = $_POST['idTempOrder'];
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $response = $service->GetOrderObservations($idTempOrder);
                echo $response;
            }
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'actionGetOrderObservations', $exc);
        }
    }

    public function actionSaveOrderObservations() {
        try {
            if ($_POST) {
                $idTempOrder = $_POST['idTempOrder'];
                $observation = $_POST['observation'];
                $column = $_POST['column'];
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $response = $service->SaveOrderObservations($idTempOrder, $observation, $column);
                echo $response;
            }
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'actionSaveOrderObservations', $exc);
        }
    }

    public function actionSaveOrder() {
        try {
            if ($_POST) {
                $user = $_POST['user'];
                $company = $_POST['company'];
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $response = $service->SaveOrder($user['user'], $company);
                echo $response;
            }
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'actionSaveOrder', $exc);
        }
    }

    
    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
