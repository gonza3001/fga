<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 28/06/2017
 * Time: 08:55 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

$connect = new \core\seguridad();
$connect->valida_session_id();

$FolioVenta = $_POST['folio_venta'];

$connect->_query = "SELECT 
	a.idventa,a.iddepartamento,a.idcliente,a.idusuario,b.idarticulo,b.tipo_articulo,
    a.costo_trabajo_cp,b.precio_compra,b.cantidad,b.descripcion,a.fecha_venta 
FROM 
	venta AS a 
LEFT JOIN detalle_venta as b 
ON a.idventa = b.idventa
WHERE a.idventa = $FolioVenta";

$connect->get_result_query();
$ListaVenta = $connect->_rows;

$NombreSucursal = $ListaVenta[0][1];
$FechaVenta = $ListaVenta[0]['fecha_venta'];
$Vendedor = $ListaVenta[0][3];
$ClienteVenta = $ListaVenta[0][2];

$Titulo = "FGA Servicios de Impresion";

$DomicilioSucursal = "Calle avenida sendero division norte # 135 Local 123";
$TelefonoSucursal = "81 2132-356 - 044 81 2134-4567";
?>
<script>


        // set portrait orientation
        jsPrintSetup.setOption('orientation', jsPrintSetup.kPortraitOrientation);
        // set top margins in millimeters
        jsPrintSetup.setOption('marginTop', 5);
        jsPrintSetup.setOption('marginBottom', 5);
        jsPrintSetup.setOption('marginLeft', 5);
        jsPrintSetup.setOption('marginRight', 5);

        jsPrintSetup.setPrinter('<?=$_SESSION['myPrint']?>');
        jsPrintSetup.setSilentPrint(1);

        // set page header
        jsPrintSetup.setOption('headerStrLeft', '');
        jsPrintSetup.setOption('headerStrCenter', '');
        jsPrintSetup.setOption('headerStrRight', '');
        // set empty page footer
        jsPrintSetup.setOption('footerStrLeft', '');
        jsPrintSetup.setOption('footerStrCenter', '');
        jsPrintSetup.setOption('footerStrRight', '');



        jsPrintSetup.setOption('printRange',jsPrintSetup.kRangeSpecifiedPageRange);
        jsPrintSetup.setOption('startPageRange', 1);
        jsPrintSetup.setOption('endPageRange', 1);



        setTimeout(function () {
            jsPrintSetup.printWindow(window);
            // print desired frame
            //    jsPrintSetup.printWindow(window.frames[0]);
            jsPrintSetup.setSilentPrint(0);
        },500);


</script>

<body>
<div class="row">
    <div class="col-md-12">

        <div style="width: 950px !important;">
            <table class="table table-bordered table-condensed no-margin" >
                <thead>
                <tr>
                    <th width="180" class="text-center" style="vertical-align: text-top;" rowspan="2"><img src="site_design/img/logos/logo_200px.jpg" class="img-responsive" ></th>
                    <th style="text-align: center"><h3><?=$Titulo?></h3></th>
                    <th width="180" class="text-center"  style="vertical-align: text-top;" rowspan="2">
                        Folio: <span class="text-red"><?=$FolioVenta?></span><br>
                        <span class="small" >Vendedor<br>
                            <?=$Vendedor?></span>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center">
                        <span class="small">
                            Sucursal: <?=$NombreSucursal?><br>
                            Domicilio: <?=$DomicilioSucursal?><br>
                            Telefonos: <?=$TelefonoSucursal?><br>
                        </span>
                    </th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <table class="table small table-bordered table-hover table-condensed">
                <thead>
                <tr>
                    <th width="100">Cantidad</th>
                    <th>Descripción</th>
                    <th width="100">Precio U.</th>
                    <th width="100">Importe</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center" style="vertical-align: text-top;" rowspan="2">1</td>
                    <td>Playera Blanca Talla ch </td>
                    <td class="text-center" style="vertical-align: text-top;" rowspan="2">$ 170.00</td>
                    <td class="text-center" style="vertical-align: text-top;" rowspan="2">$ 170.00</td>
                </tr>
                <tr>
                    <td>Descripcion de la imagen que va en la parte de abajo de la camisa Descripcion de la imagen que va en la parte de abajo de la camisa Descripcion de la imagen que va en la parte de abajo de la camisa Descripcion de la imagen que va en la parte de abajo de la camisa</td>
                </tr>

                <tr>
                    <td style="vertical-align: text-top;" rowspan="2">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="vertical-align: text-top;" rowspan="2">&nbsp;</td>
                    <td style="vertical-align: text-top;" rowspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>


                <tr>
                    <td colspan="4">
                        <b>Descripción General</b><br>
                        padpaspdpasdppsadpaspdsdp apdpadspasdpasd asd asdppasda pads sda aspdpadspapsdpasdp asdp  apsdp pp
                    </td>
                </tr>

                <tr>
                    <td colspan="3" class="text-right text-bold" >Total: </td>
                    <td class="text-center text-bold" >$ 1,234.00</td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td class="text-bold text-right" >Fecha: <?=$FechaVenta?></td>
                    <td class="text-bold text-right">Pago # 1:</td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="1"></td>
                    <td colspan="2" class="text-bold text-right">Saldo Pendiente:</td>
                    <td>$.0.00</td>
                </tr>
                </tbody>
            </table>
        </div>


    </div>
</div>

</body>