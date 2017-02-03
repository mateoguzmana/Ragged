<?php

/*
 * Create by Activity Technology SAS
 */

class CollectionController extends Controller {

    public function getAllCollections($user, $item) {
        try {
            $Response = Collection::model()->getAllCollections($user, $item);
            return $Response;
        } catch (Exception $e) {
            $this->createLog('CollectionController', 'getAllCollections', $e);
        }
    }

    public function setStatusCollection($data) {
        try {
            return json_encode(Collection::model()->setStatusCollection($data));
        } catch (Exception $e) {
            $this->createLog('CollectionController', 'setStatusCollection', $e);
        }
    }
    
    public function setAllStatusCollection($company, $status) {
        try {
            return json_encode(Collection::model()->setAllStatusCollection($company, $status));
        } catch (Exception $e) {
            $this->createLog('CollectionController', 'setAllStatusCollection', $e);
        }
    }

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

}
