<?php

/*
 * Create by Activity Technology SAS
 */

class ControllerUtilities extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();   
        $utility->createLog($class, $function, $e);
    }
    
    

}
