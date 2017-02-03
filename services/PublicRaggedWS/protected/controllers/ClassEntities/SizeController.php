<?php

/*
 * Create by Activity Technology S.A.S.
 */

class SizeController extends Controller {

    public function getSize($json, $startDate, $startTime, $idCompany) {
        try {
            $result = DatabaseUtilities::model()->callStoredProcedure("sp_executionmethods_insert('GetSizes','$startDate','$startTime','$idCompany')"); 
            if (!empty($result)) {
                foreach ($result as $row) {
                    $id = $row['IdExec'];
                }
                if (Size::model()->insertSizes($json, $idCompany)) {
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
            $this->createLog('SizeController', 'getSize', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>