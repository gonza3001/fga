<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 06/10/2017
 * Time: 09:39 AM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";
?>
<script>
    $("table th").addClass("bg-bareylev");
    fnListarTrabajos(<?=$_POST['opc']?>);

</script>

<div class="row row-sm">
    <div class="col-md-5 table-responsive scroll-auto">
        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-list"></i> Lista de Trabajos Pendientes
            </div>
            <div class="box-body">
                <table class="table table-bordered table-condensed table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">Folio</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Fecha Promesa</th>
                    </tr>
                    </thead>
                    <tbody id="tblListaTrabajos">
                    <?php
                    //Lista de trabajos Pendientes
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="box box-success">
            <div class="box-header">
                <i class="fa fa-folder-open"></i> Orden de Trabajo: <span id="getTituloDetalle"></span>
            </div>
            <div class="box-body scroll-auto no-padding" style="min-height: 55vh;max-height: 55vh;">

                <div id="getDetalleOrden" class="row row-sm">

                    <h2 class="text-center text-gray">Seleccione un Folio</h2>

                </div>

            </div>
        </div>
    </div>

</div>