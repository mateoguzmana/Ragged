<?php

/*
 * Create by Activity Technology S.A.S.
 */

class CategoryController extends Controller {

    public function getCategory($json, $startDate, $startTime, $idCompany) {
        try {
            $result = DatabaseUtilities::model()->callStoredProcedure("sp_executionmethods_insert('GetCategory','$startDate','$startTime','$idCompany')");
            if (!empty($result)) {
                foreach ($result as $row) {
                    $id = $row['IdExec'];
                }                
                
                
                if (Category::model()->insertCategories($json, $idCompany)) {
                    if (DatabaseUtilities::model()->callStoredProcedure("sp_executionmethods_update('$id')")) {
                        return "OK";
                    } else {
                        return "NO";
                    }
                } else {
                    return "NO";
                }
            } else {
                return "NO";
            }
        } catch (Exception $e) {
            $this->createLog('CategoryController', 'getCategory', $e);
            return "NO";

        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>