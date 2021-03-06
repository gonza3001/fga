<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 03/05/2017
 * Time: 06:51 PM
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
include "../../../../core/model_articulos.php";

include "../../controller/ControllerCartTraspasos.php";

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
$articulos = new \core\model_articulos();
$articulos->valida_session_id();
/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$carrito = new ControllerCartTraspasos();

$idarticulo = $_POST['idarticulo'];
$tipo_articulo = $_POST['tipo_articulo'];
$nombre_articulo = $_POST['nombre_articulo'];
$cantidad = $_POST['cantidad'];
$existencias = $_POST['existencias'];
$idalmacen_origen = $_POST['datos']['idalmacen_origen'];
$idalmacen_destino = $_POST['datos']['idalmacen_destino'];
$idusuario_solicita = $_POST['datos']['idusuario_solicita'];



if($_POST['opc'] == 1){

    $carrito->introduce_producto(
        $idarticulo,
        $nombre_articulo,
        $tipo_articulo,
        $cantidad,
        $idalmacen_origen,
        $idalmacen_destino,
        $idusuario_solicita
    );
}

$data =  $carrito->imprime_carrito();
$total_neto = 0;

for($i=0; $i < count($data);$i++){
    $total_neto = $total_neto + $data[$i]['total'];
    echo
        "<tr>
    <td>".$data[$i]['idarticulo']."</td>
    <td>".$data[$i]['nombre']."</td>
    <td>".$data[$i]['tipo']."</td>
    <td><span class='badge'>".$data[$i]['cantidad']."</span></td>
    <td><a href='#' onclick='eliminar_carrito_traspasos(".$i.")'><span class='fa fa-trash'></span></a></td>
    </tr>";
}

?>
<script>
    getMessage("Producto Agregado correctamente","Traspas","success",1500);
</script>

