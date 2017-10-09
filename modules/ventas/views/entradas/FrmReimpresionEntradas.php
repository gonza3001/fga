<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 06/10/2017
 * Time: 07:00 PM
 */
include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

?>
<script src="<?=\core\core::ROOT_APP()?>site_design/js/js_formato_moneda.js"></script>
<script>

    setOpenModal("mdlReimpresionEntradas");
    $(".currency").numeric({prefix:'$ ',cents:true});
    $("input").focus(function () {
        $(this).select();
    });

    $("#FolioEntrada").on("keyup",function (e) {

        if(e.keyCode == 13){
            getEntradas(6);
        }
    })

</script>
<div id="mdlReimpresionEntradas" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa fa-dollar"></i> Reimpresion de Entrada
            </div>
            <div class="modal-body">

                <div id="box1" class="row row-sm">
                    <div class="col-md-12">
                        <div class="form-group">
                            Folio
                            <input id="FolioEntrada" class="form-control text-bold text-center"/>
                        </div>
                    </div>
                </div>
                <div id="box2" class="row hidden row-sm">
                    <div class="col-md-12">
                        <h4 id="tituloMovimiento"></h4>
                        <div class="form-group">
                            Importe
                            <input id="importe" readonly class="form-control " />
                        </div>
                        <div class="form-group">
                            Descripción
                            <textarea id="observacion"></textarea>
                        </div>
                        <div class="form-group">
                            <button onclick="WindowsOpenReport(1,{'idEntrada':$('#FolioEntrada').val()})" class="btn btn-block btn-default btn-sm"><i class="fa fa-print"></i> Imprimir</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" id="mdlBtnClose" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>
            </div>
        </div>
    </div>
</div>
