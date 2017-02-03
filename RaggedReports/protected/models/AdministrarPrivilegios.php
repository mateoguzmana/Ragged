<?php

class AdministrarPrivilegios extends CActiveRecord {
    /* Function para obtener todos los perfiles registrados en la base de datos
     * @return array de perfiles
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getPerfiles() {
        $sql = "SELECT IdPerfil,Descripcion FROM Perfil";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /* Function para obtener todos los modulos registrados en la base de datos
     * @return array de modulos
     */

    public function getModulosPrivilegios() {
        $sql = "SELECT * FROM modules";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /* Function para obtener todos los submodulos registrados en la base de datos
     * @return array de submodulos
     */

    public function getSubmodulosPrivilegios() {
        $sql = "SELECT * FROM submodules";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /* Function para obtener todos las opciones registrados en la base de datos
     * @return array de opciones
     */

    public function getOptions() {
        $sql = "SELECT * FROM options ";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /* Function para obtener todos los privilegios registrados en la base de datos dependiendo del perfil
     * @return array de modulos
     */

    public function getPerfilPrivilegios() {
        $cedula = Yii::app()->user->_cedula;
        $sql = "SELECT perfil FROM administradores WHERE cedula='$cedula'";
        $idPerfil = Yii::app()->db->createCommand($sql)->queryRow();
        $idPerfil = $idPerfil['perfil'];
        $sql2 = "SELECT * FROM options o INNER JOIN privileges p on p.idoption=o.idoption WHERE p.idprofile='$idPerfil'";
        return Yii::app()->db->createCommand($sql2)->queryAll();
    }

    /* Function para guardar un perfil
     * @return array de modulos
     */

    public function guardarPerfil($Perfil) {
        $sql = "INSERT INTO Perfil (Descripcion) VALUES ('$Perfil');";
        Yii::app()->db->createCommand($sql)->query();
    }

    /* Function para obtener todos los modulos registrados en la base de datos
     * @return array de modulos
     */

    public function getModulesPrivilegiosPerfil() {
        $sql = "SELECT idmodule,moduledescription FROM modules";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /* Function para obtener un conteo de las opciones que hay para checkear la opcion
     * @param $idOpcion
     * @param $idPerfil
     * @return conteo
     */

    public function getOptionPrivilegios($idOption, $idPerfil) {
        $sql = "SELECT COUNT(*)as conteo FROM privileges WHERE idoption='$idOption' AND idprofile='$idPerfil'";
        $conteo = Yii::app()->db->createCommand($sql)->queryRow();
        return $conteo['conteo'];
    }

    /* Function para obtener un conteo de los submodulos que hay para checkear la opcion del submodulo
     * @param $idSubmodule
     * @param $idPerfil
     * @return conteo
     */

    public function getSubmoduloCheckPrivilegios($idSubmodule, $idProfile) {
        $sql = "SELECT COUNT(*) as conteo FROM privileges WHERE idsubmodule='$idSubmodule' AND idoption='0' AND idprofile='$idProfile'";
        $conteo = Yii::app()->db->createCommand($sql)->queryRow();
        return $conteo['conteo'];
    }

    /* Function para obtener informacion de los submodulos
     * @param $Modulo
     * @return array $submodulo
     */

    public function getSubmodulesPrivilegiosPerfil($Modulo) {
        $Modulo = $Modulo . '_';
        $sql = "SELECT idsubmodule,submoduledescription FROM  submodules WHERE idsubmodule like '$Modulo%'";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /* Function para obtener informacion de las opciones
     * @param $idSubmodulo
     * @return array $Options
     */

    public function getOpcionesPrivilegiosPerfil($idSubmodulo) {
        $sql = "SELECT idoption,optiondescription,idsourcecode FROM  options WHERE idsubmodule like '$idSubmodulo'";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /* Function para obtener el conteo de opciones de un submodulo
     * @param $idSubmodulo
     * @return $conteo
     */

    public function getCantidadOpcionesSubmodulo($idSubmodulo) {
        $sql = "SELECT COUNT(*) as conteo FROM options WHERE idsubmodule='$idSubmodulo'";
        $conteo = Yii::app()->db->createCommand($sql)->queryRow();
        return $conteo['conteo'];
    }

    /* Function para obtener informacion de un submodulo
     * @param $idOption
     * @return array $idSubmodule
     */

    public function getSubmoduloPrivilegioInsertar($idOption) {
        $sql = "SELECT idsubmodule FROM options WHERE idoption='$idOption'";
        $idSubmodule = Yii::app()->db->createCommand($sql)->queryRow();
        return $idSubmodule['idsubmodule'];
    }

    /* Function para insertar en la tabla privilegios
     * @param $sql
     * 
     */

    public function insertarPrivilegiosPerfil($sql) {
        Yii::app()->db->createCommand($sql)->query();
    }

    /* Function para eliminar privilegios
     * @param $idPerfil
     * 
     */

    public function eliminarPrivilegiosPerfil($idPerfil) {
        $sql = "DELETE FROM privileges WHERE idprofile='$idPerfil'";
        Yii::app()->db->createCommand($sql)->query();
    }

    /* Function para eliminar un perfil
     * @param $idPerfil
     * 
     */

    public function eliminarPerfil($idPerfil) {
        $sql = "DELETE FROM Perfil WHERE IdPerfil='$idPerfil'";
        return Yii::app()->db->createCommand($sql)->query();
    }

}
