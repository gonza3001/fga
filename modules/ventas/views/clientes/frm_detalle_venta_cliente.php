<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 16/06/2017
 * Time: 11:27 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";

include "../../controller/ClassControllerVentas.php";

$connect = new ClassControllerVentas();
$connect->valida_session_id();

if(!array_key_exists('idcliente',$_POST) && !array_key_exists('folio_venta',$_POST) && !empty($_POST['idcliente']) && !empty($_POST['folio_venta'])){
    \core\core::MyAlert("No se encontro el id del cliente o el folio de venta","alert");
    exit();
}

$FolioVenta = $_POST['folio_venta'];
$idCliente = $_POST['idcliente'];

$connect->_query = "SELECT 
                      a.iddetalle_venta,a.idalmacen,a.idventa,a.tipo_articulo,b.nombre_articulo,a.cantidad,a.precio_compra,a.descripcion 
                    FROM 
                      detalle_venta as a
                      LEFT JOIN articulos as b
                      ON a.idarticulo = b.idarticulo
                    where a.idventa = $FolioVenta AND a.tipo_articulo = 'ART' ";
$connect->get_result_query();
$DetalleART = $connect->_rows;

$connect->_query = "SELECT 
                      a.iddetalle_venta,a.idalmacen,a.idventa,a.tipo_articulo,b.nombre_material,a.cantidad,a.precio_compra,a.descripcion 
                    FROM 
                      detalle_venta as a 
                      LEFT JOIN materiales as b
                      ON a.idarticulo = b.idmateriales
                    where a.idventa = $FolioVenta AND a.tipo_articulo = 'MAT' ";
$connect->get_result_query();
$DetalleMAT = $connect->_rows;


//Numeros de pagos
$connect->_query = "
SELECT idmovimiento,NoPago,importe_venta,importe_pagado,fecha_movimiento FROM movimientos_caja WHERE idventa = $FolioVenta ORDER BY NoPago DESC
";
$connect->get_result_query();
$DetallePagos = $connect->_rows;
sleep(3);
$a=1;
?>
<script src="<?=\core\core::ROOT_APP()?>site_design/js/js_formato_moneda.js"></script>
<script>
    $("th").addClass("bg-bareylev");
    $('.currency').numeric({prefix:'$ ', cents: true});
</script>
<div class="col-md-7">
    <table class="table table-striped table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio U</th>
            <th>Sub Total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0;$i < count($DetalleART);$i++){

            $PrecioSubtotal = $DetalleART[$i][6] * $DetalleART[$i][5];
            echo "<tr><td>".$a++."</td>
                    <td>".$DetalleART[$i][4]."</td>
                    <td>".$DetalleART[$i][5]."</td>
                    <td class='currency'>".$DetalleART[$i][6]."</td>
                    <td class='currency text-right'>".$PrecioSubtotal."</td>
                    </tr>";
            $SubTotalART = $SubTotalART + $DetalleART[$i][6];
        }
        $Total = $PrecioSubtotal;
        $PrecioSubtotal=0;
        for($i=0;$i < count($DetalleMAT);$i++){

            $PrecioSubtotal = $DetalleMAT[$i][6] * $DetalleMAT[$i][5];
            echo "<tr><td>".$a++."</td>
                    <td>".$DetalleMAT[$i][4]."</td>
                    <td>".$DetalleMAT[$i][5]."</td>
                    <td class='currency'>".$DetalleMAT[$i][6]."</td>
                    <td class='currency text-right'>".$PrecioSubtotal."</td>
                    </tr>";
            $SubTotalMat = $SubTotalMat + $DetalleMAT[$i][6];
        }
        $Total = $Total + $PrecioSubtotal + $_SESSION['sys_config']['costo_trabajo_cp'];
        ?>
        <tr><td><?=$a++?></td><td>Costo trabajo</td><td>0</td><td class="currency"><?=$_SESSION['sys_config']['costo_trabajo_cp']?></td><td class="currency text-right"><?=$_SESSION['sys_config']['costo_trabajo_cp']?></td></tr>
        </tbody>
        <tbody>
        <tr>
            <td colspan="3" class="text-right text-bold">Total: </td>
            <td colspan="3" class="text-right text-bold currency"><?=$Total?></td>
        </tr>
        </tbody>
    </table>


</div>

<div class="col-md-5">
    <table class="table table-striped table-condensed table-hover">
        <thead>
        <tr>
            <th>No Pago</th>
            <th>Importe</th>
            <th>Pago</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $a=1;

        for($i=0;$i < count($DetallePagos);$i++){
            echo "<tr>
                    <td>".$DetallePagos[$i][1]."</td>
                    <td class='currency' >".$DetallePagos[$i][2]."</td>
                    <td class='currency' >".$DetallePagos[$i][3]."</td>
                    <td>".$DetallePagos[$i][4]."</td>
                    </tr>";
            $Total = $Total + $DetallePagos[$i][3];
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-bold">Total: </td>
            <td class="text-bold currency"><?=$DetallePagos[0][2]?></td>
            <td class="text-bold currency"><?=$Total?></td>
            <td>
                <?php
                if($Total <  $DetallePagos[0][2]){
                    echo "<span class='label label-warning'> Nota Pendiente</span>";
                }else{
                    echo "<span class='label label-success'> Nota Terminada</span>";
                }
                ?>
            </td>
        </tr>
        </tfoot>
    </table>
</div>