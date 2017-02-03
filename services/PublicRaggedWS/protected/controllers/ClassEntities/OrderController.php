<?php

/*
 * Create by Activity Technology S.A.S.
 */

class OrderController extends Controller {

    public function getOrders($idCompany) {
        try {                     
            $response = Order::model()->getOrders($idCompany);
            if ($response) {             
                return json_encode($response);
            } else {
                return "";
            }            
        } catch (Exception $e) {
            $this->createLog('OrderController', 'getOrders', $e);
        }
    }
    
    public function getOrderState($descriptionState) {
        try {                    
            $response = Order::model()->getOrderState($descriptionState);           
            
            if ($response) {             
                return $response[0]['Id'];
            } else {
                return "";
            }            
        } catch (Exception $e) {
            $this->createLog('OrderController', 'getOrderState', $e);
        }
    }
    
    public function updateReservation($idOrder) {
        try {       
            
            $response = Order::model()->updateReservation($idOrder);                   
            return json_encode($response);
            if ($response) {             
                return $response[0]['Id'];
            } else {
                return "";
            }            
        } catch (Exception $e) {
            $this->createLog('OrderController', 'updateReservation', $e);
        }
    }
    
    public function updateOrderState($Order) {
        try {       
            
            $response = Order::model()->updateOrderState($Order);                   
            
            return $response;
            
            return json_encode($response);
            if ($response) {             
                return $response[0]['Id'];
            } else {
                return "";
            }            
        } catch (Exception $e) {
            $this->createLog('OrderController', 'updateOrderState', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>