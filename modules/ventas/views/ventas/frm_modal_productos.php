<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 29/05/2017
 * Time: 12:30 PM
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
include "../../../../core/model_categorias.php";
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


$connect = new \core\model_categorias();
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$idEmpresa = $_SESSION['data_home']['idempresa'];
$idAlmacen = $_SESSION['data_home']['almacen'];

$connect->_query = "
SELECT a.idarticulo,idalmacen,b.nombre_articulo,a.existencias,b.precio_venta
FROM almacen_articulos as a
LEFT JOIN articulos as b
ON a.idarticulo = b.idarticulo
LEFT JOIN catalogo_general as c
ON c.idcatalogo = 2 AND c.opc_catalogo = b.idsubcategoria AND c.idempresa = $idEmpresa
LEFT JOIN catalogo_general as d
ON d.idcatalogo = 3 AND d.opc_catalogo = b.idtalla AND d.idempresa = $idEmpresa
LEFT JOIN catalogo_general as e
ON e.idcatalogo = 4 AND e.opc_catalogo = b.idcolor AND e.idempresa = $idEmpresa
WHERE a.existencias > 0 AND a.idalmacen = $idAlmacen AND b.idempresa = $idEmpresa GROUP BY b.idcolor
";

$connect->get_result_query();
$lista = $connect->_rows;

?>
<script>
    setOpenModal("mdl_categorias");
    $("th").addClass("bg-bareylev");
</script>
<div class="modal fade" id="mdl_categorias">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Seleccione el Color
            </div>
            <div class="modal-body">

                <div class="row" id="content01">
                    <div class="col-md-12">
                       <div class="form-group">
                           <label>Buscar Producto:</label>
                           <input id="textSearch" type="text" autofocus onkeyup="fnVentaOpenModal({'opc':2})" class="form-control input-sm">
                       </div>
                    </div>

                    <div class="col-md-12 table-responsive"  style="max-height: 500px;overflow-y: scroll;" >
                        <table class="table table-condensed table-hover">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th class='text-center'>Existencias</th>
                                <th width="70">Precio</th>
                                <th>Agregar</th>
                            </tr>
                            </thead>
                            <tbody id="lista_busqueda_producto">

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row  hidden" id="content02">

                    <div class="col-md-12">

                        <div class="form-group ">
                            Producto:
                            <input class="form-control input-lg" id="producto" disabled placeholder="id del Producto" />
                        </div>

                        <div class="col-md-12 hidden">
                            <h3>Producto: <span class="text-success" id="txtNameProduc">Nombre</span></h3>
                        </div>

                        <div class="col-md-12">
                            <label>Agregar descripción</label>
                            <textarea id="descripcion_por_producto" class="form-control input-lg"></textarea>
                        </div>

                        <div class="col-md-4">
                            &nbsp;
                            <button id="btnregresar" class="btn btn-lg btn-block btn-primary" onclick="$('#textSearch').val('');$('#textSearch').focus();$('#lista_busqueda_producto').html('');$('#txtCantidad').val(1);$('#content02').addClass('hidden');$('#content01').removeClass('hidden');"><i class="fa fa-arrow-left"></i> Regresar</button>
                        </div>
                        <div class="col-md-4">
                            Cantidad
                            <input class="form-control input-lg text-center" id="txtCantidad" value="1" type="text">
                        </div>
                        <div class="col-md-4">
                            &nbsp;
                            <button class="btn btn-default btn-lg btn-block" onclick="fnVentaAddCartProducto(1);$('#btnregresar').click();" ><i class="fa fa-plus"></i> Agregar</button>
                        </div>


                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
