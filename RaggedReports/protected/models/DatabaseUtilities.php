<?php

class DatabaseUtilities extends CActiveRecord {
    
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function excecuteDatabaseStoredFunctions($function) {
        try {
            $connection = YII::app()->db;
            $query = 'select $function as `result`';
            $command = $connection->createCommand($query);
            $dataReader = $command->queryAll();
            return $dataReader;
        } catch(Exception $e){            
    		$this->createLog('DatabaseUtilities', 'excecuteDatabaseStoredFunctions', $e);        
        }
    }
    
    public function excecuteDatabaseStoredProcedures($function) {
        try {
            $connection = YII::app()->db;
            //$query = 'CALL '.$function;
            $query = "CALL ".$function;
            $command = $connection->createCommand($query);
            $dataReader = $command->queryAll();
            return $dataReader;
        } catch(Exception $e){            
    		$this->createLog('DatabaseUtilities', 'excecuteDatabaseStoredProcedures', $e);        
        }
    }

}