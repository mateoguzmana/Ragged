<?php

/*
 * Created By Activity Technology S.A.S.
 */

class ReferenceController extends Controller {

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
                //ini_set("soap.wsdl_cache_enabled", "1");
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $References = $service->SelectReferences($user['user'], $item);
                $ReferencesArray = json_decode($References);
                $Collections = $service->SelectCollection($user['user'], $item);
                $CollectionArray = json_decode($Collections);
                $PriceList = $service->SelectPriceLists($user['user'], $item);
                $PriceListArray = json_decode($PriceList);
                echo $this->renderPartial('/reference/viewreference', array('References' => json_encode($ReferencesArray), 'Collections' => json_encode($CollectionArray), 'PriceList' => json_encode($PriceListArray)));
            }
        } catch (Exception $exc) {
            $this->createLog('ReferenceController', 'actionAjaxLoad', $exc);
        }
    }

    public function actionGetReferenceDetail() {
        try {
            if ($_POST) {
                $collections = $_POST['collections'];
                $references = $_POST['references'];
                $priceList = $_POST['priceList'];
                ini_set("soap.wsdl_cache_enabled", "1");
                ini_set("soap.wsdl_cache_enabled", "0");
                $service = new SoapClient("https://ragged.amovil.net/development/services/PrivateRaggedWS/index.php?r=PrivateRaggedWS/quote");
                $response = $service->SelectReferenceDetail(json_encode($collections), json_encode($references), json_encode($priceList));
                $PlusArray = json_decode($response);
                echo $this->renderPartial('/reference/viewreferencedetail', array('Plus' => json_encode($PlusArray)));
            }
        } catch (Exception $exc) {
            $this->createLog('ReferenceController', 'actionAjaxLoad', $exc);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
