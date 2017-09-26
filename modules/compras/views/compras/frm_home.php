<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/04/2017
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

$seguridad = new \core\seguridad();
$seguridad->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<script src="<?=\core\core::ROOT_APP()?>site_design/js/jsCompras.js"></script>
<script>
    //setPush("Prueba de mensaje Push");
    //Script Lista de Compras por Autorizar
    ListarComprasPorAutorizar(1);
    //Script Indicadores de compras
    CargarIndicadoresCompras(1);

</script>
<div class="box box-danger animated fadeInDown" xmlns="http://www.w3.org/1999/html">
    <div class="box-header">
        <i class="fa fa-truck"></i> Administracion y Compras
    </div>
    <div class="toolbars">

        <button class="btn btn-sm btn-primary" onclick="gn_menu_principal(6,6)"> <i class="fa fa-home"></i> Inicio</button>

        <button class="btn btn-default btn-sm " onclick="nueva_orden_compra(1,2)" type="button"><i class="fa fa-dollar"></i> Nueva Comprar </button>

        <div class="btn-group">
            <button class="btn btn-info btn-sm dropdown-toggle"
                    type="button" data-toggle="dropdown">
                <i class="fa fa-list"></i> Lista de compras <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#" onclick="lista_compras_realizadas(1)" >Compra por dar Entrada</a></li>
                <li><a href="#" onclick="lista_compras_realizadas(2)" >Compras Realizadas</a></li>
                <li><a href="#" onclick="lista_compras_realizadas(3)" >Compras Canceladas</a></li>
            </ul>
        </div>

        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle"
                    type="button" data-toggle="dropdown">
                <i class="fa fa-line-chart"></i> Reportes <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Reportes </a></li>
                <li><a href="#">Indicadores</a></li>
            </ul>
        </div>

    </div>
    <div id="data_list" class="box-body">

        <div class="row">
            <div class="col-md-7">
                <div class="box box-warning ">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="box-header bg-warning">
                        Compras por Autorizar
                    </div>
                    <div class="box-footer no-padding">
                        <ul id="listaComprasPorAutorizar" class="nav nav-stacked">

                        </ul>
                    </div>
                </div><!-- /.widget-user -->
            </div>

            <div class="col-md-5">
                <div class="box box-info">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="box-header bg-info">
                        Indicador de Compras
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li><a href="#">Por Autorizar <span id="lblautorizar" class="pull-right badge bg-blue">0000</span></a></li>
                            <li><a href="#">Realizadas <span id="lblrealizadas" class="pull-right badge bg-green">0000</span></a></li>
                            <li><a href="#">Canceladas <span id="lblcanceladas" class="pull-right badge bg-red">0000</span></a></li>
                        </ul>
                    </div>
                </div><!-- /.widget-user -->
            </div>
        </div>

    </div>
</div>
