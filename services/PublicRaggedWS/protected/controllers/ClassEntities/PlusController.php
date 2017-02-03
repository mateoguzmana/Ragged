<?php

/*
 * Create by Activity Technology S.A.S.
 */

class PlusController extends Controller {

    public function getPlus($json, $startDate, $startTime, $idCompany) {
        try {           
            if (Plus::model()->insertPlus($json, $idCompany)) {                    
                    return "OK";
            } else {
                    return "NO";
                }                
        } catch (Exception $e) {
            $this->createLog('ReferencePlus', 'getPlus', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>