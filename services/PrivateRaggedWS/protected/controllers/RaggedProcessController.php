<?php

/*
 * Created By Activity Technology S.A.S.
 */

class RaggedProcessController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function QueryAllProcess($user) {
        try {
            return json_encode(RaggedProcess::model()->getAllProcess($user));
        } catch (Exception $e) {
            $this->createLog('RaggedProcessController', 'QueryAllProcess', $e);
        }
    }

    public function QueryListProcessExcecution() {
        try {
            return json_encode(RaggedProcess::model()->getListProcessExcecution());
        } catch (Exception $e) {
            $this->createLog('RaggedProcessController', 'QueryListProcessExcecution', $e);
        }
    }

    public function QueryListProcessExcecutionUser($userquery) {
        try {
            return json_encode(RaggedProcess::model()->getListProcessExcecutionUser($userquery));
        } catch (Exception $e) {
            $this->createLog('RaggedProcessController', 'QueryListProcessExcecutionUser', $e);
        }
    }

    public function QueryListProcessExcecutionDetail($Process) {
        try {
            return json_encode(RaggedProcess::model()->getListProcessExcecutionDetail($Process));
        } catch (Exception $e) {
            $this->createLog('RaggedProcessController', 'QueryListProcessExcecutionDetail', $e);
        }
    }

    public function RunProcess($Process) {
        try {
            $validation = RaggedProcess::model()->getValidationProcess();
            if ($validation == "OK") {
                $time = RaggedProcess::model()->setRunProcess($Process);
                if ($time != "") {
                    //llama servicio
                    $response = $this->CallServiceProcess();
                    if ($response == "OK") {
                        $Process = json_decode($Process);
                        return $this->InsertUserLog($Process->user, $time, 0);
                    }
                }
                return "";
            }
            return $validation;
        } catch (Exception $e) {
            $this->createLog('RaggedProcessController', 'RunProcess', $e);
        }
    }

    public function ExcecuteCompleteProcess($Companies) {
        try {
            $validation = RaggedProcess::model()->getValidationProcess();
            if ($validation == "OK") {
                $time = RaggedProcess::model()->setExcecuteCompleteProcess($Companies);
                if ($time != "") {
                    //llama servicio
                    $response = $this->CallServiceProcess();
                    if ($response == "OK") {
                        $Companies = json_decode($Companies);
                        return $this->InsertUserLog($Companies->user, $time, 0);
                    }
                }
                return "";
            }
            return $validation;
        } catch (Exception $e) {
            $this->createLog('RaggedProcessController', 'ExcecuteCompleteProcess', $e);
        }
    }

    public function InsertUserLog($user, $nowTime, $countCall) {
        try {
            if ($countCall > 5) {
                return false;
            }
            $id = RaggedProcess::model()->getProcessId($nowTime);
            if ($id == False) {                
                sleep(2);
                return $this->InsertUserLog($user, $nowTime, $countCall + 1);
            } else {
                return RaggedProcess::model()->setExecutionProcessUsers($user, $id);
                //inserta log. Devuelve falso o verdadero. Se retorna la respuesta
            }
        } catch (Exception $e) {
            $this->createLog('RaggedProcessController', 'InsertUserLog', $e);
        }
    }

    public function CallServiceProcess() {
        try {
            $service = "http://209.133.196.89/RaggedTriggerVirtual/TriggerService.svc?wsdl";
            $client = new SoapClient($service);  
            return ($client->excecuteProcess() == True ) ? "OK" : "";
        } catch (Exception $ex) {
            $this->createLog('RaggedProcessController', 'CallServiceProcess', $ex);
        }
    }

}
