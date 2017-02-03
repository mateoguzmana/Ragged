<?php

/*
 * Create by Activity Technology S.A.S.
 */

class SectorController extends Controller {

    public function getSector($json, $startDate, $startTime, $idCompany) {
        try {
            $result = DatabaseUtilities::model()->callStoredProcedure("sp_executionmethods_insert('getSectors','$startDate','$startTime','$idCompany')");
            if (!empty($result)) {
                foreach ($result as $row) {
                    $id = $row['IdExec'];
                }
                if (Sector::model()->insertSectors($json, $idCompany)) {
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
            $this->createLog('SectorController', 'getSector', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>