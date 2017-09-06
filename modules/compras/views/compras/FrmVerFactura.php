<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 04/09/2017
 * Time: 04:03 PM
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

$connect = new \core\seguridad();
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

if( array_key_exists('idcompra',$_POST) &&  empty($_POST['idcompra'])){
    \core\core::MyAlert("No existe la orden de compra","alert");
    exit;
}else{

    $idCompra = $_POST['idcompra'];

    $connect->_query = "
    select 
      d.iddetalle_compra,
      a.idcompra,
      b.nombre_proveedor as Proveedor,
      concat_ws(' ',c.nombre,c.apaterno,c.amaterno)as UsuarioAlta,
      a.fecha_alta,
      d.tipo_articulo,
      d.idarticulo,
      d.cantidad,
      e.nombre_articulo,
      d.precio_compra
    from detalle_compra as d 
    left join compra as a
    on a.idcompra = d.idcompra 
    left join articulos as e 
    on d.idarticulo = e.idarticulo
    left join proveedores as b 
    on a.idproveedor = b.idproveedor
    left join perfil_usuarios as c 
    on a.idusuario_alta = c.idusuario 
    where d.idcompra = $idCompra ";

    $connect->get_result_query();
    $DataCompra = $connect->_rows;

}
?>
<script src="<?=\core\core::ROOT_APP()?>site_design/js/js_formato_moneda.js"></script>
<script>
    setOpenModal("mdlVerFacturaCompra");
    $('.currency').numeric({prefix:'$ ', cents: true});
</script>
<div id="mdlVerFacturaCompra" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                Orden de Compra: <?=$connect->getFormatFolio($_POST['idcompra'],4)?>

            </div>
            <div class="modal-body">

               <table class="table table-condensed  table-bordered">
                   <thead>
                   <tr>
                       <th>Proveedor</th>
                       <th>Usuario</th>
                       <th class="text-right">Fecha Registro</th>
                   </tr>
                   </thead>
                   <tbody>
                   <tr>
                       <td><?=$DataCompra[0][2]?></td>
                       <td><?=$DataCompra[0][3]?></td>
                       <td class="text-right"><?=$DataCompra[0][4]?></td>
                   </tr>
                   </tbody>
               </table>

                <!-- Codigo 	Cant. 	Descripcion 	Tipo 	Precio Unitario 	Precio Total -->
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">Cantidad</th>
                        <th>Descripción</th>
                        <th>Unitario</th>
                        <th>SubTotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    for($i=0;$i < count($DataCompra);$i++){
                        $Subtotal = ($DataCompra[$i][7] * $DataCompra[$i][9]);
                        echo "<tr><td class='text-center'>".$DataCompra[$i][7]."</td><td>".$DataCompra[$i][8]."</td><td class='text-right currency' >".$DataCompra[$i][9]."</td><td class='currency text-right'>".$Subtotal."</td></tr>";
                        $Total = $Total + $Subtotal;
                    }
                    ?>
                    <tr>
                        <td colspan="3" class="text-bold text-right">Total:</td>
                        <td class="text-right currency"><?=$Total?></td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">

                <button class="btn btn-success btn-sm" ><i class="fa fa-print"></i> Imprimir</button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>


            </div>
        </div>
    </div>
</div>