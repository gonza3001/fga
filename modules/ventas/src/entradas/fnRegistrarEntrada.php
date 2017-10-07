<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 06/10/2017
 * Time: 07:59 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

$connect = new \core\seguridad();
$connect->valida_session_id();

$idEmpresa = $_SESSION['data_home']['idempresa'];
$idUsuario = $_SESSION['data_login']['idusuario'];
$idDepartamento = $_SESSION['data_home']['iddepartamento'];
$FechaAcutal = date("Y-m-d H:i:s");
header("ContentType:application/json");

if($_SERVER['REQUEST_METHOD'] == "POST"){

    if(array_key_exists('idconcepto',$_POST) && array_key_exists('importe',$_POST) && array_key_exists('observaciones',$_POST)){

        $_POST = $connect->get_sanatiza($_POST);

        $connect->_query = "
        call sp_registrar_entrada('0','$idEmpresa','$idDepartamento','1','$_POST[idconcepto]','$_POST[importe]','$_POST[observaciones]','$idUsuario','$idUsuario','A','$FechaAcutal')
        ";
        $connect->get_result_query(true);
        $Folio = $connect->getFormatFolio($connect->_rows[0]['id'],4);

        echo json_encode(array("result"=>true,"message"=>"Exito","data"=>array("Folio"=>$Folio)));

    }else{
        echo json_encode(array("result"=>false,"message"=>"error en parametros","data"=>array()));
    }

}else{
    echo json_encode(array("result"=>false,"message"=>"Metodo no soportado","data"=>array()));
}