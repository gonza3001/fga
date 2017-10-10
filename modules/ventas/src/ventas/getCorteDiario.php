<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 09/10/2017
 * Time: 10:48 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

$connect = new \core\seguridad();
$connect->valida_session_id();

$idUsuario = $_SESSION['data_login']['idusuario'];
$idDepartamento = $_SESSION['data_home']['iddepartamento'];
$idEmpresa = $_SESSION['data_home']['idempresa'];
$idPerfil = $_SESSION['data_login']['idperfil'];

header("ContentType:application/json");

if($_SERVER['REQUEST_METHOD'] == "GET"){

    //Movimientos Caja (Pagos y Notas)
    $connect->_query = "
    SELECT 123
    ";

    //Movimientos de Aportaciones y Salidas
    $connect->_query = "
    call sp_CorteDiario(1,'$idEmpresa','$idDepartamento');
    ";
    $connect->get_result_query();

    $data =array(
        "Entradas"=>array(
            "Entradas"=>$connect->_rows[0][0],
            "Salidas"=>$connect->_rows[0][1],
            "Cancelacion"=>$connect->_rows[0][2],
            "Total"=>($connect->_rows[0][0] - $connect->_rows[0][1] ) - $connect->_rows[0][2]
        ),
        "Aportaciones"=>array(
            "Aportacion"=>$connect->_rows[0][3],
            "Retiro"=>$connect->_rows[0][4],
            "Cancelacion"=>$connect->_rows[0][5],
            "Total"=>($connect->_rows[0][3] - $connect->_rows[0][4] ) - $connect->_rows[0][5]
        )
    );

    echo json_encode(array("result"=>true,"message"=>"Consulta Exitosa","data"=>$data));
}else{
    echo json_encode(array("result"=>false,"message"=>"Metodo no soportado","data"=>array()));
}