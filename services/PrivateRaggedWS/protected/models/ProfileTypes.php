<?php

class ProfileTypes extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function setSaveProfileType($profiletype) {
        try {
            $arr = json_decode($profiletype);
            $responsename = $this->excecuteDatabaseStoredFunctions("`fn_savenewnameprofiletype`('$arr')");
            return $responsename[0]['result'] == "OK";
        } catch (Exception $e) {
            $this->createLog('ProfileTypes', 'setSaveProfileType', $e);
            return false;
        }
    }

//Guardamos el perfil que se ha editado
    public function setSaveProfileTypeEdit($profiletype) {
        try {
            $saveprofile = json_decode($profiletype);
            if ($saveprofile->profiletypename !== "") {
                $this->excecuteDatabaseStoredProcedures("`sp_updatenameprofiletype`('" . $saveprofile->profiletypename . "','" . $saveprofile->profiletypeid . "');");
                return true;
            }
            return false;
        } catch (Exception $e) {
            $this->createLog('ProfileTypes', 'setSaveProfileTypeEdit', $e);
            return false;
        }
    }

//Borramos el perfil
    public function setDeleteProfileType($profiletype) {
        try {
            $deleteprofile = json_decode($profiletype);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_deleteprofiletype`('$deleteprofile')");
            return $response[0]['result'] == "OK";
        } catch (Exception $e) {
            $this->createLog('ProfileTypes', 'setDeleteProfileType', $e);
            return false;
        }
    }

    //Validamos si el tipo de perfil está asociado a un perfil
    public function validateProfileType($profiletype) {
        try {
            $idprofiletype = json_encode($profiletype);
            $response =  $this->excecuteDatabaseStoredFunctions("`fn_countprofilestypes`('$idprofiletype')");
            return  $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('ProfileTypes', 'setDeleteProfileType', $e);
            return 'NO';
        }
    }

}
