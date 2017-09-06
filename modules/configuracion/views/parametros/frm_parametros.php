<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 03/06/2017
 * Time: 09:25 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

$connect = new \core\seguridad();
$connect->valida_session_id();



?>
<script src="<?=\core\core::ROOT_APP()?>site_design/js/js_formato_moneda.js"></script>
<script>
    $('.currency').numeric({prefix:'$ ', cents: true});
    $("input").focus(function(){
        this.select();
    });
</script>
<div class="box box-warning animated fadeIn">
    <div class="box-header">
        <i class="fa fa-gears fa-spin"></i>Configuración del Sistema
    </div>
    <div class="toolbars">
        <button class="btn btn-sm btn-default" onclick="GuardarParametros()" ><i class="fa fa-save"></i> Guardar</button>
    </div>
    <div class="box-body">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Datos Generales</a></li>
                <li><a href="#tab_2" data-toggle="tab">Servicios</a></li>
                <li><a href="#tab_3" data-toggle="tab">Credito y Costos</a></li>
                <li><a href="#tab_8" data-toggle="tab">Iva</a></li>
                <li><a href="#tab_4" data-toggle="tab">Costos por trabajo</a></li>
                <li><a href="#tab_9" data-toggle="tab">Impresoras</a></li>
                <li><a href="#tab_5" data-toggle="tab">Notificaciones</a></li>
                <li><a href="#tab_6" data-toggle="tab">Alertas</a></li>
                <li><a href="#tab_7" data-toggle="tab">Correos</a></li>
                <li class="pull-right"><a href="#" onclick="gn_menu_principal(14,14)"><i class="fa fa-refresh"></i></a></li>
            </ul>
            <div class="tab-content">

                <!--## IVA -->
                <div class="tab-pane " id="tab_8">

                </div>

                <!-- Impresoras -->
                <!--## IVA -->
                <div class="tab-pane " id="tab_9">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Localidad</label>
                                <select class="form-control input-sm">
                                    <option value="0">Seleccione una Localidad</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Alias de la Impresora</label>
                                <input class="form-control input-sm" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre de la Impresora</label>
                                <input class="form-control input-sm" />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Agregar</label><br>
                                <button class="btn btn-block btn-primary btn-sm"><i class="fa fa-check"></i></button>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="tab-pane active" id="tab_1">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        Nombre Empresa
                                        <input class="form-control input-sm" placeholder="Nombre de la empresa">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        Descripción
                                        <input class="form-control input-sm" placeholder="Nombre de la empresa">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        Tema
                                        <select class="form-control">
                                            <option value="1">Blancl and Cian</option>
                                            <option value="1">Blancl and Cian</option>
                                            <option value="1">Blancl and Cian</option>
                                            <option value="1">Blancl and Cian</option>
                                            <option value="1">Blancl and Cian</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-md-2">
                                    <div class="title-parametros">Medios de pagos</div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="1" checked disabled value="">
                                            Pagos en efectivo
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="2" > Pago con tarjeta
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="3"> Pago Combinado
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="title-parametros">Horarios de Acceso</div>

                                    <div class="form-group">
                                        Hora inicial
                                        <input class="form-control input-sm"/>
                                    </div>
                                    <div class="form-group">
                                        Hora Final
                                        <input class="form-control input-sm"/>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="title-parametros">Horarios de Venta</div>

                                    <div class="form-group">
                                        Hora inicial
                                        <input class="form-control input-sm"/>
                                    </div>
                                    <div class="form-group">
                                        Hora Final
                                        <input class="form-control input-sm"/>
                                    </div>
                                </div>

                                <!-- CREDITOS -->
                                <div class="col-md-3">
                                    <div class="title-parametros">Credito</div>
                                    <div class="form-group">
                                        % Pago inicial:
                                        <div class="input-group">
                                            <span class="input-group-addon">%</span>
                                            <input type="number" id="pago_minimo_credito" value="<?=$_SESSION['sys_config']['pago_inicial']?>" class="form-control input-sm">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        Monto Maximo:
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" id="credito_maximo" value="" class="form-control currency input-sm">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        Aplicar a:
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <select id="tipo_cliente_credito" class="form-control input-sm">
                                                <option>Todos los clientes</option>
                                                <option>Clientes frecuentes</option>
                                                <option>Ninguno</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <!-- END CREDITOS -->
                            </div>

                        </div>
                    </div>

                </div>

                <div class="tab-pane" id="tab_2">
                    <h3>Servicio no contratado</h3>
                </div>

                <div class="tab-pane" id="tab_3">

                    <div class="row">
                        <!-- PORCENTAJES IVA % -->
                        <div class="col-md-4">
                            <ul class="list-group">
                                <li class="list-group-item text-bold">% Agregar IVA</li>
                                <li class="list-group-item"><input id="ivaCompra" class="hidden" value="0"> Activar IVA en Compras <button class="btn btn-default pull-right btn-toggle" onc><i class="fa fa-toggle-on fa-2x text-green"></i></button></li>
                                <li class="list-group-item"><input id="ivaVenta" class="hidden" value="0"> Activar IVA en Ventas <button class="btn btn-default pull-right btn-toggle"><i class="fa fa-toggle-off fa-2x" ></i></button></li>
                                <li class="list-group-item text-bold"><i class="fa fa-cc-visa"></i> Medios de Pago</li>
                                <li class="list-group-item"><input id="medioPagoEfectivo" class="hidden" value="0"> Pago en Efectivo <button class="btn btn-default pull-right btn-toggle" onc><i class="fa fa-toggle-on fa-2x text-green"></i></button></li>
                                <li class="list-group-item"><input id="medioPagoTarjeta" class="hidden" value="0"> Pago con Tarjeta <button class="btn btn-default pull-right btn-toggle"><i class="fa fa-toggle-off fa-2x" ></i></button></li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="tab-pane" id="tab_4">
                    <!-- Porcentajes -->
                    <div class="row">

                        <div class="col-md-4">
                            <div class="title-parametros">Costos con Productos</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        Costo por trabajo
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input id="costo_trabajo_cp" value="<?=$_SESSION['sys_config']['costo_trabajo_cp']?>" type="text" class="form-control currency " aria-label="...">
                                        </div><!-- /input-group -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        Cantidad Mayoreo
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">#</span>
                                            <input type="text" id="cantidad_mayoreo_costo_trabajo_cp" value="<?=$_SESSION['sys_config']['cantidad_mayoreo_cp']?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        Costo por trabajo mayoreo
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" id="costo_trabajo_mayoreo_cp" value="<?=$_SESSION['sys_config']['costo_trabajo_mayoreo_cp']?>" class="form-control currency" aria-label="...">
                                        </div><!-- /input-group -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="title-parametros">Costos sin Productos</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        Costo por trabajo
                                        <div class="input-group">
                                      <span class="input-group-addon">$</span>
                                            <input type="text" value="<?=$_SESSION['sys_config']['costo_trabajo_sp']?>" id="costo_trabajo_sp" class="form-control currency" aria-label="...">
                                        </div><!-- /input-group -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        Cantidad Mayoreo
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">#</span>
                                            <input type="text" value="<?=$_SESSION['sys_config']['cantidad_mayoreo_sp']?>" id="cantidad_mayoreo_costo_trabajo_sp" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        Costo por trabajo mayoreo
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" value="<?=$_SESSION['sys_config']['costo_trabajo_mayoreo_sp']?>" id="costo_trabajo_mayoreo_sp" class="form-control currency" aria-label="...">
                                        </div><!-- /input-group -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Porcentajes -->
                </div>
                <div class="tab-pane" id="tab_5"></div>
                <div class="tab-pane" id="tab_6"></div>
                <div class="tab-pane" id="tab_7"></div>
            </div>
        </div>

    </div>
</div>