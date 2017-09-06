<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 02/05/2017
 * Time: 08:55 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

$connect = new \core\seguridad();
$connect->valida_session_id();
unset($_SESSION['cart']);
$idEmpresa = $_SESSION['data_home']['idempresa'];

?>
<script>
    $(".select2").select2();
</script>
<div class="row">

    <div class="col-md-5">
        <div class="form-group">
            <label>Proveedor</label>
            <select id="idproveedor" class="form-control select2" style="width: 100%">
                <option value="0">-- --</option>
                <?php

                $connect->_query = "SELECT idproveedor,nombre_proveedor FROM proveedores WHERE idestado = 1 AND idempresa = '$idEmpresa' ";
                $connect->get_result_query();

                for($i=0;$i <count($connect->_rows);$i++){
                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                }

                ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" disabled value="<?=date("d/m/Y")?>" />
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Orde de Compra Nº</label>
            <input class="form-control" disabled value="2" />
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Agregar Productos</label>
            <button class="btn btn-info btn-block" onclick="nueva_orden_compra(2,0)" ><i class="fa fa-search"></i> Buscar Productos</button>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-striped table-bordered ">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Cant.</th>
                    <th>Descripcion</th>
                    <th>Tipo</th>
                    <th width="300" class="text-right">Precio Unitario</th>
                    <th width="300" class="text-right">Precio Total</th>
                </tr>
            </thead>
            <tbody id="lista_productos">
            <tr>
                <td colspan="4"></td>
                <td class="text-right" >Neto $</td>
                <td class="text-right"> $0.00</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-right">Neto $</td>
                <td class="text-right"> $0.00</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-right">Neto $</td>
                <td class="text-right"> $0.00</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12 text-right ">
        <button class="btn btn-danger " onclick="nueva_orden_compra(1,2)" ><i class="fa fa-trash"></i> Cancelar</button>
        <button class="btn btn-success " onclick="nueva_orden_compra(4,0)" ><i class="fa fa-save"></i> Guardar</button>
    </div>
</div>
