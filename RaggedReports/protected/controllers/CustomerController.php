<?php

/*
 * Created By Activity Technology S.A.S.
 */

class CustomerController extends Controller {

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
                $response = $service->SelectCustomers($user['user'], $item);

                /* echo "<pre>";
                  print_r($response);
                  exit(); */

                $response = json_decode($response, true);

                if (($response) && (count($response['datas']) > 0)) {

                    $flag = false;
                    // Creo encabezado de la tabla.
                    $tablaHTML = "";
                    $tablaHTML = '<table id="tblDinamicCustomer" class="display dataTable bordered centered" width="100%"><thead><tr>';
                    foreach ($response['datas'] as $key => $val) {
                        $tablaHTML .= "<th></th>";
                        foreach ($val as $ky => $vl) {
                            $tablaHTML .= "<th>" . $ky . "</th>";
                        }
                        break;
                    }

                    $tablaHTML = $tablaHTML . "</tr></thead><tbody>";
                    $tablaHTML .= count($response['datas']) > 0 ? '' : '<tr></tr>';
                    foreach ($response['datas'] as $key => $val) {

                        $tablaHTML = $tablaHTML . '<tr data-id="' . $val['Id'] . '">';
                        $tablaHTML = $tablaHTML . '<td>' . '<input type="checkbox" class="customercheckbox" id="answer" data-idCheck="' . $val['Id'] . '" name="" />' . '</td>';
                        foreach ($val as $ky => $vl) {
                            foreach ($response['config'] as $data => $row) {
                                if ($row['columndescription'] == $ky) {
                                    if ($row['class'] == "changeNumFormat") {
                                        $vl = str_replace(".", ",", $vl);
                                        $vl = number_format($vl, 2, ",", ".");
                                    }
                                    if ($vl == $row['value']) {
                                        $tablaHTML .= '<td><a data-idAddress="' . $val['Id'] . '" class="btn btn-default ' . $row['class'] . '" href="#"><span style="display:none">"' . $row['value'] . '"</span><span class="' . $row['showvalue'] . '"></span></a></td>';
                                        $flag = true;
                                        break;
                                    }
                                } else {
                                    $flag = false;
                                }
                            }
                            if (!$flag) {
                                $tablaHTML = $tablaHTML . '<td>' . $vl . '</td>';
                            }
                        }
                        $tablaHTML = $tablaHTML . "</tr>";
                    }
                    $tablaHTML .= "</tbody></table>";
                }

                //$response['datas'] = "";

                echo $this->renderPartial('/customer/viewcustomer', array('Customers' => json_encode($response), 'Table' => $tablaHTML));
            }
        } catch (Exception $exc) {
            $this->createLog('CustomerController', 'actionAjaxLoad', $exc);
        }
    }

    public function actionShowWallet() {
        try {

            $customers = $_POST['idCustomers'];
            ini_set("soap.wsdl_cache_enabled", "1");
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $response = $service->SelectCustomersWallet(json_encode($customers));
            echo $response;
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionShowWallet', $e);
        }
    }

    public function actionShowSalePoints() {
        try {
            $customers = $_POST['idCustomers'];
            ini_set("soap.wsdl_cache_enabled", "1");
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $response = $service->SelectCustomersSalePoints(json_encode($customers));
            echo $response;
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionShowSalePoints', $e);
        }
    }

    public function actionCreateCustomer() {
        try {
            echo $this->renderPartial('_createcustomer');
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionCreateCustomer', $e);
        }
    }

    public function actionCreateCustomerData() {
        try {
            $userName = $_POST['userName'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->DataforCreateCustomer($userName);
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionCreateCustomerData', $e);
        }
    }

    public function actionGetUserCompany() {
        try {
            $company = $_POST['company'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->getUserCompany($company);
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionGetUserCompany', $e);
        }
    }

    public function actionGetDepartments() {
        $country = $_POST['country'];
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->getDepartmentCountry($country);
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionGetDepartments', $e);
        }
    }

    public function actionGetCities() {
        $department = $_POST['department'];
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->getCitiesDepartment($department);
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionGetCities', $e);
        }
    }

    public function actionGetCompanyCustomerByUser() {
        try {
            $userName = $_POST['userName'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->getCompanyCustomerByUser($userName);
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionGetCompanyCustomerByUser', $e);
        }
    }

    public function actionCustomerExists() {
        try {
            $jsonData = $_POST['jsonData'];
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            $exists = json_decode($service->CheckCustomerExists($jsonData['accountcustomer']));
            foreach ($exists as $data) {
                if ($data->dataExist == '0') {
                    echo 'success';
                } else {
                    echo 'error';
                }
            }
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionCustomerExists', $e);
        }
    }

    public function actionSaveCustomer() {
        try {
            $jsonData = $_POST['jsonData'];
            $userName = $_POST['userName'];
            //Primero agregaremos las columnas que nos hagan falta para registrar el customer
            ini_set("soap.wsdl_cache_enabled", "0");
            $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
            echo $service->saveCustomer($jsonData, $userName);
        } catch (Exception $e) {
            $this->createLog('CustomerController', 'actionSaveCustomer', $e);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
