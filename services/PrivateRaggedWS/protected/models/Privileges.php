<?php

class Privileges extends DatabaseUtilities {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

//Log de errores
    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

//Obtenemos el perfil para editarlo
    public function getProfileEdit($profile) {
        try {
            $AllMSOs = array();
            $AllMSO = json_encode($this->GetallModSubOpt());
            $AllMSO = json_decode($AllMSO);
            $profileprivileges = $this->excecuteDatabaseStoredProcedures("`sp_getallprivilegesprofile`($profile);");
            for ($i = 0; $i < count($AllMSO); $i++) {
                for ($j = 0; $j < count($AllMSO[$i]->submodules); $j++) {
                    for ($k = 0; $k < count($AllMSO[$i]->submodules[$j]->submodule->options); $k++) {
                        foreach ($profileprivileges as $itemprofileprivilege) {
                            if (($itemprofileprivilege['idmodule'] == $AllMSO[$i]->idmodule) && ($itemprofileprivilege['idsubmodule'] == $AllMSO[$i]->submodules[$j]->submodule->idsubmodule) && ($itemprofileprivilege['idoption'] == $AllMSO[$i]->submodules[$j]->submodule->options[$k]->option->idoption)) {
                                $AllMSO[$i]->submodules[$j]->submodule->options[$k]->option->privilegeid = $itemprofileprivilege['idprivilege'];
                                $AllMSO[$i]->submodules[$j]->submodule->options[$k]->option->active = $itemprofileprivilege['active'];
                                $AllMSO[$i]->submodules[$j]->submodule->options[$k]->option->privilegetypeid = $itemprofileprivilege['idprivilegetype'];
                            }
                        }
                    }
                }
            }
            $AllMSOs['AllMSO'] = $AllMSO;
            $userprofiletype = $this->excecuteDatabaseStoredFunctions("`fn_getprofiletype`($profile)");
            $AllMSOs['userprofiletypeid'] = $userprofiletype[0]['result'];
            //array_push($AllMSO, $profiletypeid);
            $AllMSOs['profilestypes'] = $this->excecuteDatabaseStoredProcedures("`sp_getallprofilestypes`();");
            //array_push($AllMSO, $profilestypes);
            return $AllMSOs;
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getProfileEdit', $e);
        }
    }

//Obtenemos todos los modulos
    public function getAllModules() {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getallmodules`();");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getAllModules', $e);
        }
    }

//Obtenemos las acciones (botones)
    public function getAllActions($user) {
        try {
            $user = json_decode($user);
            $submodules = $this->excecuteDatabaseStoredProcedures("`sp_getsubmodulesbyuser`('" . $user->user . "');");
            foreach ($submodules as $submodule) {
                $Options[$submodule['idsourcecode']] = $this->excecuteDatabaseStoredProcedures("`sp_getallactionsbysubmodule`('" . $user->user . "'," . $submodule['idsubmodule'] . ");");
            }
            return $Options;
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getAllActions', $e);
        }
    }

//Obtenemos las acciones (botones)
    public function getAllActionsSubmodule($user) {
        try {
            $user = json_decode($user);
            return $this->excecuteDatabaseStoredProcedures("`sp_getallactionsbysubmodulename`('$user->user','$user->submodule');");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getAllActionsSubmodule', $e);
        }
    }

//Obtenemos todos los submodulos
    public function getAllSubmodules() {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getallsubmodules`();");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getAllSubmodules', $e);
        }
    }

//Obtenemos todas las opciones 
    public function getAllOptions() {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getalloptions`();");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getAllOptions', $e);
        }
    }

//Obtenemos los submodulos por modulo
    public function GetQuerySubmodule($module) {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getallsubmodulebymodule`($module);");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'GetQuerySubmodule', $e);
        }
    }

//Obtnemos las opciones por submodulos
    public function GetQueryOptions($submodule) {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getalloptionsbysubmodule`($submodule);");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'GetQueryOptions', $e);
        }
    }

//Obtenemos todas las opciones de cada submodulo y cada submodulos de cada modulo
    public function GetallModSubOpt() {
        try {
            $allModSubOpt = array();
            $module = array();
            $Modules = $this->excecuteDatabaseStoredProcedures("`sp_getallmodules`();");
            $Submodules = $this->excecuteDatabaseStoredProcedures("`sp_getallsubmodules`();");
            $Options = $this->excecuteDatabaseStoredProcedures("`sp_getalloptions`();");
            foreach ($Modules as $Module) {
                $submodulearr = array();
                foreach ($Submodules as $Submodule) {
                    $optionarr = array();
                    foreach ($Options as $Option) {
                        if (($Option['idsubmodule'] == $Submodule['idsubmodule']) && ($Submodule['idmodule'] == $Module['idmodule'])) {
                            $objoption = new stdClass();
                            $objoption->option = array('idoption' => $Option['idoption'], 'optiondescription' => $Option['optiondescription']);
                            array_push($optionarr, $objoption);
                        }
                    }
                    if ($Submodule['idmodule'] == $Module['idmodule']) {
                        $objsubmodule = new stdClass();
                        $objsubmodule->submodule = array('idsubmodule' => $Submodule['idsubmodule'], 'submoduledescription' => $Submodule['submoduledescription'], 'options' => $optionarr);
                        array_push($submodulearr, $objsubmodule);
                        unset($optionarr);
                    }
                }
                $module = array('idmodule' => $Module['idmodule'], 'moduledescription' => $Module['moduledescription'], 'privilegetypeid' => $Module['idprivilegetype'], 'submodules' => $submodulearr);
                array_push($allModSubOpt, $module);
                unset($submodulearr);
            }
            return $allModSubOpt;
        } catch (Exception $e) {
            $this->createLog('Privileges', 'GetallModSubOpt', $e);
        }
    }

//Obtenemos todos los tipos de privilegios
    public function getAllPrivilegesTypes() {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getallprivilegestypes`();");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getAllPrivilegesTypes', $e);
        }
    }

//Obtnemos todos los tipos de perfiles
    public function getAllProfilesTypes() {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getallprofilestypes`();");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getAllProfilesTypes', $e);
        }
    }

//Guardamos el perfil que se ha creado
    public function setSaveProfile($profile) {
        try {
            $arr = json_decode($profile);
            $responsename = $this->excecuteDatabaseStoredFunctions("`fn_savenewnameprofile`('$arr->name',$arr->profiletype)");
            if ($responsename[0]['result'] == "OK") {
                $responseid = $this->excecuteDatabaseStoredFunctions("`fn_getidnewnameprofile`('$arr->name')");
                foreach ($arr->data as $itemarr) {
                    $responsesaved = $this->excecuteDatabaseStoredFunctions("`fn_savenewprofileinprivileges`('" . $responseid[0]['result'] . "','$itemarr->module','$itemarr->submodule','$itemarr->option','$itemarr->typeprivilege','$itemarr->active')");
                }
                return $responsesaved[0]['result'];
            } else {
                return "Hubo un error insertanto el nombre!";
            }
        } catch (Exception $e) {
            $this->createLog('Privileges', 'setSaveProfile', $e);
        }
    }

//Guardamos el perfil que se ha editado
    public function setSaveProfileEdit($profile) {
        try {
            $saveprofile = json_decode($profile);
            if ($saveprofile->name != "") {
                $this->excecuteDatabaseStoredProcedures("`sp_updatenewnameprofile`($saveprofile->profileid,'$saveprofile->name');");
            }
            if ($saveprofile->profiletype != "") {
                $this->excecuteDatabaseStoredProcedures("`sp_updatetypeprofile`($saveprofile->profileid,'$saveprofile->profiletype');");
            }
            if (count($saveprofile->data) > 0) {
                foreach ($saveprofile->data as $itemarr) {
                    if ($itemarr->privilegeid == "undefined") {
                        $this->excecuteDatabaseStoredFunctions("`fn_createprivilegesfileprivileges`($itemarr->Module,$itemarr->SubModule,$itemarr->Option,$saveprofile->profileid)");
                    } else {
                        $this->excecuteDatabaseStoredFunctions("`fn_changeprofileprivileges`($itemarr->privilegeid)");
                    }
                }
            }
            /* if (count($saveprofile->datae) > 0) {
              foreach ($saveprofile->datae as $itemarre) {
              $this->excecuteDatabaseStoredFunctions("`fn_changeprofileprivileges`($itemarre->privilegeid)");
              }
              } */
            return "OK";
        } catch (Exception $e) {
            $this->createLog('Privileges', 'setSaveProfileEdit', $e);
        }
    }

//Borramos el perfil
    public function setDeleteProfile($profile) {
        try {
            $deleteprofile = json_decode($profile);
            $response = $this->excecuteDatabaseStoredFunctions("`fn_deleteprofile`($deleteprofile->profileid)");
            return $response[0]['result'];
        } catch (Exception $e) {
            $this->createLog('Privileges', 'setDeleteProfile', $e);
        }
    }

//Obtenemos todos los tipos de perfil
    public function getProfilesTypes() {
        try {
            return $this->excecuteDatabaseStoredProcedures("`sp_getallprofiles`();");
        } catch (Exception $e) {
            $this->createLog('Privileges', 'getProfilesTypes', $e);
        }
    }

}
