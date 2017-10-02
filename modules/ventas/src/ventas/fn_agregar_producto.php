<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 30/05/2017
 * Time: 05:06 PM
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

include "../../controller/ClassControllerCarritoVentas.php";
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

$connect = new \core\seguridad();
$connect->valida_session_id();

$carrito = new ClassControllerCarritoVentas();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$idEmpresa = $_SESSION['data_home']['idempresa'];
$idAlmacen = $_SESSION['data_home']['almacen'];
$_POST = $connect->get_sanatiza($_POST);
$idArticulo = $_POST['idproducto'];
$Descripcion = $_POST['descripcion'];
$Cantidad = $_POST['idcantidad'];
$TipoProducto  = $_POST['tipo_producto'];

$connect->_query = "
        select a.idarticulo,b.nombre_articulo,b.codigo,b.precio_venta,b.precio_mayoreo,b.cantidad_mayoreo,a.existencias FROM almacen_articulos as a
        LEFT JOIN articulos as b
        ON a.idarticulo = b.idarticulo
        WHERE  a.idarticulo = $idArticulo AND a.idempresa = $idEmpresa AND a.idalmacen = $idAlmacen AND a.existencias > $Cantidad ;
        ";
$connect->get_result_query();

$data_producto = $connect->_rows[0];
$Existencias = $connect->_rows[6];

header("Content-type:application/json");

$ListaCarrito = $carrito->imprime_carrito();




echo json_encode($_POST);


/*
if($_POST['opc'] != 2){
    if($Cantidad > $data_producto[6]){
        echo json_encode(array(
            "result"=>"error",
            "mensaje"=>"No cuenta con suficiente stock, aun asi quiere crear la orden de trabajo ?",
            "data"=>array("opc"=>2)
        ));
    }
}

$carrito->introduce_producto(
    $_POST['idproducto'],
    $TipoProducto,
    $_POST['nombre_producto'],
    $data_producto[3],
    $Cantidad,
    $Descripcion
);

echo json_encode(array(
    "result"=>"ok",
    "test"=>$data_producto,
    "data"=>array("opc"=>2)
));*/