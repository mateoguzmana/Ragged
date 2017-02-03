<?php

/*
 * Create by Activity Technology SAS
 */

class CategoryController extends Controller {

    public function getAllCategories($user, $item) {
        try {
            $response = Category::model()->getAllCategories($user, $item);
            return $response;
        } catch (Exception $exc) {
            $this->createLog('CategoryController', 'getAllCategories', $exc);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
