<?php

/*
 * Create by Activity Technology S.A.S.
 */

class ColorController extends Controller {

    public function getColor($json, $startDate, $startTime, $idCompany) {
        try {
            $result = DatabaseUtilities::model()->callStoredProcedure("sp_executionmethods_insert('GetColors','$startDate','$startTime','$idCompany')");
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $id = $row['IdExec'];
                }       
                
                if (Color::model()->insertColors($json, $idCompany)) {
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
            $this->createLog('ColorController', 'getColor', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>