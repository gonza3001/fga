<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 05/06/2017
 * Time: 06:39 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php o modelo ( ej: model_aparatos.php)
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 *
 * Ejemplo:
 * Si se requiere cambiar de servidor de base de datos
 * $data_server = array(
 *   'bdHost'=>'192.168.2.5',
 *   'bdUser'=>'sa',
 *   'bdPass'=>'pasword',
 *   'port'=>'3306',
 *   'bdData'=>'dataBase'
 *);
 *
 * Si no es requerdio se puede dejar en null
 *
 * con @data_server
 * @@$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos'],$data_server);
 *
 * Sin @data_server
 * @@$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 *
 * @@$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/

$connect = new \core\seguridad();
$connect->valida_session_id();
$idEmpresa = $_SESSION['data_home']['idempresa'];
$idDepartamento = $_SESSION['data_home']['iddepartamento'];
$NoUsuarioAlta = $_SESSION['data_login']['idusuario'];
header("Content-type:application/json");


switch($_POST['opc']){
    case 1:

        //Validar si ya se realizo la apertura
        $DateTIME = date("Y-m-d H:i:s");

        $connect->_query = "SELECT fecha_apertura FROM apertura WHERE idempresa = $idEmpresa AND iddepartamento = $idDepartamento AND date(fecha_apertura) = date('".$DateTIME."') ";
        $connect->get_result_query();

        if(count($connect->_rows) > 0){
            echo json_encode(array("result"=>"ok"));
        }else{
            echo json_encode(array("result"=>"error","mensaje"=>"error no se encontro la apertura de la caja"));
        }
        break;
    case 2:

        $DateTIME = date("Y-m-d H:i:s");
        $SaldoInicial = $_POST['SaldoInicial'];

        if($SaldoInicial > 0){
            $connect->_query = "call sp_registra_apertura(
            '$idEmpresa',
            '$idDepartamento',
            '$SaldoInicial',
            '$NoUsuarioAlta',
            '$DateTIME',
            '$DateTIME'
            )";
            $connect->execute_query();

            echo json_encode(array("result"=>"ok"));
        }else{
            echo json_encode(array("result"=>"error","mensaje"=>"El saldo inicial es incorrecto"));
        }

        break;
    default:
        break;
}

