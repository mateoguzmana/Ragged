<?php

/**
 * Created by Activity Technology SAS.
 */
class ActivityLog
{
    public function createLog($className, $functionName, $e){
        $excep['message'] = $e->getMessage();
		$excep['line'] = $e->getLine();
		$excep['code'] = $e->getCode();
		$excep['trace'] = $e->getTrace();    		
		$data['exception'] = $excep;
        $data = json_encode($data);
    	$randomValue = (string)date('FjY-g:ia') . '-' . rand(0,5000000);
    	$urlFileToProcessName = (string)$className . '-' . (string)$functionName . '-' . $randomValue;
    	$fileUrl = (string)'protected/logs/' . $urlFileToProcessName . '.json';
        file_put_contents($fileUrl, $data);
    }

}