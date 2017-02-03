<?php

/*
 * Create by Activity Technology SAS
 */

class OrdersQueryController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function QueryAllOrders($user) {
        try {
            return json_encode(OrdersQuery::model()->getAllOrders($user));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'QueryAllOrders', $e);
        }
    }

    public function QueryOrders($userquery) {
        try {
            return json_encode(OrdersQuery::model()->getOrders($userquery));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'QueryOrders', $e);
        }
    }

    public function DataforEditOrder($order) {
        try {
            return json_encode(OrdersQuery::model()->getDataforEditOrder($order));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'DataforEditOrder', $e);
        }
    }

    public function ChangeOrderDetails($orderdetail) {
        try {
            return json_encode(OrdersQuery::model()->setOrderDetails($orderdetail));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'ChangeOrderDetails', $e);
        }
    }

    public function QueryBusinessRulesValidation($Order) {
        try {
            return json_encode(OrdersQuery::model()->getBusinessRulesValidation($Order));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'QueryBusinessRulesValidation', $e);
        }
    }

    public function ChangeValidationBusinessRule($ValBusRule) {
        try {
            return json_encode(OrdersQuery::model()->setValidationBusinessRule($ValBusRule));
        } catch (Exception $e) {
            $this->createLog('OrdersQueryController', 'ChangeValidationBusinessRule', $e);
        }
    }

}
