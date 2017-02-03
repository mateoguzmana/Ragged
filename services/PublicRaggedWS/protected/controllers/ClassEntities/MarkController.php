<?php

/*
 * Create by Activity Technology S.A.S.
 */

class MarkController extends Controller {

    public function getMark($json, $startDate, $startTime, $idCompany) {
        try {
            $result = DatabaseUtilities::model()->callStoredProcedure("sp_executionmethods_insert('GetMarks','$startDate','$startTime','$idCompany')");
            if (!empty($result)) {
                foreach ($result as $row) {
                    $id = $row['IdExec'];
                }
                if (Mark::model()->insertMarks($json, $idCompany)) {
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
            $this->createLog('MarkController', 'getMark', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>