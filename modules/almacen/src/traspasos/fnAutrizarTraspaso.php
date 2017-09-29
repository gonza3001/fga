<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 28/09/2017
 * Time: 08:20 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

$connect = new \core\seguridad();
$connect->valida_session_id();

$idEmpresa = $_SESSION['data_home']['idempresa'];
$NoUsuario = $_SESSION['data_login']['idusuario'];

header("ContentType:application/json");

if($_SERVER['REQUEST_METHOD']=="POST"){

    if(isset($_POST['idTraspaso'])){

        $idTraspaso = $_POST['idTraspaso'];

        $connect->_query = "
        call sp_AutorizarTraspaso('$idTraspaso','$NoUsuario','$idEmpresa');
        ";
        $connect->get_result_query();

        $Estado = $connect->_rows[0][0];
        $Mensaje =$connect->_rows[0][1];

        if($Estado == 2){

            $AlmacenDestino =$connect->_rows[0][2];


            $connect->_query = "SELECT idarticulo,cantidad FROM detalle_traspasos WHERE idtraspaso = $idTraspaso ";
            $connect->get_result_query();

            if(count($connect->_rows)>0){

                for($i=0;$i<count($connect->_rows);$i++){

                $idArticulo = $connect->_rows[$i][0];
                $Cantidad = $connect->_rows[$i][1];

                $connect->_query = "
                call sp_execute_traspaso(3,'$idTraspaso','$idEmpresa','$NoUsuario','$AlmacenDestino','$AlmacenDestino','$Cantidad','$idArticulo',null);
                ";

                $connect->execute_query();

                }
            }
            echo json_encode(array("result"=>true, "message"=>"Traspaso exitoso","data"=>array()));

        }else{
            echo json_encode(array("result"=>false, "message"=>$Mensaje,"data"=>array()));
        }

    }else{
        echo json_encode(array("result"=>false, "message"=>"Parametros incorrectos","data"=>array()));
    }

}else{
    echo json_encode(array("result"=>false, "message"=>"Metodo no soportado","data"=>array()));
}