<?php

/*
 * Create by Activity Technology SAS
 */

class OrderController extends Controller {

    public function getRouters($user) {
        try {
            $allRouters = true;         
            $response = Order::model()->getRouters($user,$allRouters);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'getAllRouters', $exc);
        }
    }       
    
    public function getAllOrderDetails($collections, $references, $priceList, $customers, $company) {
        try {
            $response = Order::model()->getOrderDetails($collections, $references, $priceList, $customers, $company);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'getAllOrderDetails', $exc);
        }
    }
    
    public function SaveOrderTemp($user, $orderDetailJson, $routers, $priceLists, $formPays, $company, $viewDataJson) {
        try {
            $response = Order::model()->SaveOrderTemp($user, $orderDetailJson, $routers, $priceLists, $formPays, $company, $viewDataJson);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'SaveOrderTemp', $exc);
        }
    }
    
    public function GetOrderObservations($idTempOrder) {
        try {
            $response = Order::model()->GetOrderObservations($idTempOrder);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'GetOrderObservations', $exc);
        }
    }
    
    public function SaveOrderObservations($idTempOrder, $observation, $column) {
        try {
            $response = Order::model()->SaveOrderObservations($idTempOrder, $observation, $column);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'SaveOrderObservations', $exc);
        }
    }   
    
    public function SaveOrder($user, $company) {
        try {
            $response = Order::model()->SaveOrder($user, $company);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'SaveOrder', $exc);
        }
    }   
    
    public function SelectUncompletedOrder($user) {
        try {
            $response = Order::model()->SelectUncompletedOrder($user);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'SaveOrder', $exc);
        }
    }
    
    
    public function SelectCustomersRouters($routers) {
        try {
            $response = Order::model()->SelectCustomersRouters($routers);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('OrderController', 'SelectCustomersRouters', $exc);
        }
    }
    
    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }
}
