<?php

/*
 * Create by Activity Technology S.A.S.
 */

class ReferenceController extends Controller {

    public function getReference($json, $startDate, $startTime, $idCompany) {
        try {               
            if (Reference::model()->insertReferences($json, $idCompany)) {             
                return "OK";
            } else {
                return "NO";
            }            
        } catch (Exception $e) {
            $this->createLog('ReferenceController', 'getReference', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}

?>