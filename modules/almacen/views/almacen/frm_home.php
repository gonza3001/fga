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

$connect = new \core\seguridad();
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<script src="<?=\core\core::ROOT_APP()?>site_design/js/jsAlmacen.js"></script>
<script>
    $(".info-box").css('box-shadow','3px 4px 4px #a3a3a3');
</script>
<div class="box box-success animated fadeInDown">
    <div class="box-header">
        <i class="fa fa-database"></i> Almacen y Suministros
    </div>
    <div class="toolbars">

        <button class="btn btn-sm btn-primary" onclick="gn_menu_principal(5,5)"> <i class="fa fa-refresh"></i> Actualizar</button>

        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle"
                    type="button" data-toggle="dropdown">
                <i class="fa fa-list-ol"></i> Inventario <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#" onclick="listar_inventarios(1,'ART',1,0)">Almacen General</a></li>
                <?php
                $connect->_query = "SELECT idalmacen,nombre_almacen from almacen WHERE idalmacen <> ".$_SESSION['sys_config']['almacen']." AND idempresa = ".$_SESSION['data_home']['idempresa']." ";
                $connect->get_result_query();
                for($i=0;$i < count($connect->_rows);$i++){
                    echo "<li><a href='#' onclick='listar_inventarios(1,\"ART\",".$connect->_rows[$i][0].",0)' >".$connect->_rows[$i][1]."</a></li>";
                }
                ?>
                <li class="divider"></li>
                <li><a href="#" onclick="listar_inventarios(1,'ART',999,0)">Todos</a></li>
            </ul>
        </div>

        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle"
                    type="button" data-toggle="dropdown">
                <i class="fa fa-truck"></i> Traspasos <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Autorizar traspaso</a></li>
                <li><a href="#">Almacen Sendero</a></li>
                <li><a href="#">Almacen Interplaza</a></li>
                <li class="divider"></li>
                <li><a href="#">Solicitar Traspaso</a></li>
                <li><a href="#" onclick="compras_nuevo_traspaso(1)" >Nuevo Traspaso</a></li>
            </ul>
        </div>


        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle"
                    type="button" data-toggle="dropdown">
                <i class="fa fa-print"></i> Reportes <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Inventario</a></li>
                <li><a href="#">Traspasos</a></li>
            </ul>
        </div>


    </div>
    <div id="lista_traspasos" class="box-body">

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-list-ol"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Inventario general</span>
                        <span class="info-box-number">0<small>%</small></span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box shadow" >
                    <span class="info-box-icon bg-aqua"><i class="fa fa-truck"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Traspasos</span>
                        <span class="info-box-number">0<small>%</small></span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="fa fa-minus"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Existencias Bajas</span>
                        <span class="info-box-number">0<small>%</small></span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-angle-double-left"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sin Existencias</span>
                        <span class="info-box-number">0<small>%</small></span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Total productos</h3>
                    </div>
                    <div class="box-body">

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Total materiales</h3>
                    </div>
                    <div class="box-body">

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Productos con stock minimo</h3>
                    </div>
                    <div class="box-body">

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Productos sin stock</h3>
                    </div>
                    <div class="box-body">

                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
