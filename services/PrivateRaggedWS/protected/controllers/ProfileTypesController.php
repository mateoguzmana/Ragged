<?php

/*
  Created by Activity Technology S.A.S.
 */

class ProfileTypesController extends Controller {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function SaveProfileType($profiletype) {
        try {
            return json_encode(ProfileTypes::model()->setSaveProfileType($profiletype));
            //return json_encode($responsenewprofile);
        } catch (Exception $e) {
            $this->createLog('ProfileTypesController', 'SaveProfileType', $e);
        }
    }

    public function SaveProfileTypeEdit($profiletype) {
        try {
            return json_encode(ProfileTypes::model()->setSaveProfileTypeEdit($profiletype));
            //return json_encode($responsenewprofile);
        } catch (Exception $e) {
            $this->createLog('ProfileTypesController', 'SaveProfileTypeEdit', $e);
        }
    }

    /*

     * Método para eliminar un tipo de perfil
     * retorna:
     * 1 - Si se eliminar correctamente
     * 2 - Si no se puede eliminar porque ya está asigando a un perfil
     * 3 - Error 
     *      */

    public function DeleteProfileType($profiletype) {
        try {
            $response = ProfileTypes::model()->validateProfileType($profiletype);
            //return $response;
            if ($response === 'OK') {
                $result = ProfileTypes::model()->setDeleteProfileType($profiletype);
                //return json_encode(ProfileTypes::model()->setDeleteProfileType($profiletype));
                if ($result === true) {
                    return '1';
                }
            } else {
                return '2';
            }
            //return json_encode($responsenewprofile);
        } catch (Exception $e) {
            $this->createLog('ProfileTypesController', 'DeleteProfileType', $e);
            return '3';
        }
    }

}
