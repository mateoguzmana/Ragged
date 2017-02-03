<?php

/*
 * Create by Activity Technology S.A.S.
 */

class CollectionController extends Controller {

    public function getCollection($json, $startDate, $startTime, $idCompany) {
        try {
            $result = DatabaseUtilities::model()->callStoredProcedure("sp_executionmethods_insert('GetCollection','$startDate','$startTime','$idCompany')");
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $id = $row['IdExec'];
                }
                if (Collection::model()->insertCollentions($json, $idCompany)) {
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
            $this->createLog('CollectionController', 'getCollection', $e);
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