<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 05/06/2017
 * Time: 10:44 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

$connect  = new \core\seguridad();
$connect->valida_session_id();

$DateTIME = date("Y-m-d H:i:s");
$idEmpresa = $_SESSION['data_home']['idempresa'];
$idDepartamento = $_SESSION['data_home']['iddepartamento'];

$connect->_query = "SELECT fecha_apertura FROM apertura WHERE idempresa = $idEmpresa AND iddepartamento = $idDepartamento AND date(fecha_apertura) = date('".$DateTIME."') ";
$connect->get_result_query();

if(count($connect->_rows) > 0){
    \core\core::MyAlert("La apertura ya se realizo","alert");
    exit();
}

?>
<script src="<?=\core\core::ROOT_APP()?>site_design/js/js_formato_moneda.js"></script>
<script>
    setOpenModal("mdl_apertura_caja");
    $('.currency').numeric({prefix:'$ ', cents: true});
    $("input").focus(function(){$(this).select();});

</script>
<div class="modal fade" id="mdl_apertura_caja" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                Apertura
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            Saldo Inicial
                            <input id="saldo_inicial" class="form-control text-center text-bold currency input-lg" placeholder="Saldo Inicial de la caja" />
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-sm btn-primary" id="btnApertura" onclick="fnVentaAperturaCaja(2)" ><i class="fa fa-money"></i> Realizar apertura</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>