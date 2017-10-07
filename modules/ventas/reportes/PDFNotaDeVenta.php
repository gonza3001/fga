<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 25/09/2017
 * Time: 11:28 AM
 */
include "../../../core/core.class.php";
include "../../../core/sesiones.class.php";
include "../../../core/seguridad.class.php";

require_once '../../../plugins/html2pdf/html2pdf.class.php';

$connect = new \core\seguridad();
$FolioVenta = $_REQUEST['id'];
$connect->_query = "SELECT 
	b.idventa,
	lpad(b.idventa,4,'0'),
	g.nombre_departamento,
	e.nick_name,
	f.nombre_completo,
	d.nombre_articulo,
    b.cantidad,
    b.precio_compra,
    b.tipo_articulo,
    b.descripcion,
    a.costo_trabajo_cp,
    a.idcliente,
    a.iddepartamento,
    a.idusuario,
    a.descripcion_general,
    a.idtipo_venta,
    date(a.fecha_venta)
FROM detalle_venta as b 
left join venta as a 
on b.idventa = a.idventa
left join articulos as d 
on b.idarticulo= d.idarticulo
left join perfil_usuarios as e 
on a.idusuario = e.idusuario 
left join clientes as f 
on a.idcliente = f.idcliente 
left join departamentos as g 
on a.iddepartamento = g.iddepartamento
where b.idventa = $FolioVenta";

$connect->get_result_query();
$ListaVenta = $connect->_rows;

$connect->_query = "SELECT 
	a.fecha_registro,
    a.importe_pagado,
    a.idestatus,
    a.NoPago
FROM movimientos_caja as a
where a.idventa = $FolioVenta";

$connect->get_result_query();
$ListaPagos = $connect->_rows;

$FolioVenta = $ListaVenta[0][1];
$NombreSucursal = $ListaVenta[0][2];
$FechaVenta = date("dd/mmm/Y",$ListaVenta[0][16]);
$Vendedor = $ListaVenta[0][3];
$ClienteVenta = $ListaVenta[0][4];

$Background = "#F3F3F3";

$DomicilioSucursal = "Calle avenida sendero division norte # 135 Local 123";
$TelefonoSucursal = "81 2132-356 - 044 81 2134-4567";

ob_start();
?>
    <style type="text/css">
        <!--
        table {
            font-size: 9px;
        }
        table thead th{
            border: 1px solid #919191;
            background: #F3F3F3 ;padding: 5px;
        }
        table tbody td{
            border: 1px solid #919191;
        }
        -->
    </style>
    <page format="120x200" orientation="L"  backtop="20mm" backbottom="10mm" backleft="2mm" backright="2mm">

        <page_header>
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;width: 28%">
                        <img width="180" src="../../../site_design/img/logos/logo_200px.jpg">
                    </td>
                    <td style="text-align: center;    width: 48%">
                        <h3 style="margin-bottom: -0px;"><?=$_SESSION['data_home']['nombre_empresa']?></h3>
                        <p style="font-size: 8px;">
                            <?=$DomicilioSucursal?><br>
                            <?=$TelefonoSucursal?>
                        </p>
                    </td>
                    <td style="text-align: center;font-size: 13px;width: 20%">
                        <b>Nota de Venta</b><br>
                        <b><?=$connect->getFormatFolio($FolioVenta,4)?></b>
                    </td>
                </tr>
            </table>
        </page_header>

        <table style="width: 100%;border-collapse: collapse;">
            <tr>
                <td style="width: 10%;border: 1px solid #fafafa;font-weight: bold;background: #F3F3F3;padding: 4px;">Sucursal:</td>
                <td style="width: 25%;border: 1px solid #fafafa;padding: 4px;"><?=$NombreSucursal?></td>
                <td style="width: 40%"></td>
                <td style="width: 10%;border: 1px solid #fafafa;padding: 4px;;font-weight: bold;background: #F3F3F3">Fecha:</td>
                <td style="width: 15%;border: 1px solid #fafafa;padding: 4px;"><?=$FechaVenta?></td>
            </tr>
            <tr >
                <td style="width: 10%;border: 1px solid #fafafa;font-weight: bold;padding: 4px;;background: #F3F3F3">Agente:</td>
                <td style="width: 25%;border: 1px solid #fafafa;padding: 4px;"><?=$Vendedor?></td>
                <td style="width: 40%"></td>
                <td style="width: 10%;border: 1px solid #fafafa;font-weight: bold;background: #F3F3F3;padding: 4px;">Estatus:</td>
                <td style="width: 15%;border: 1px solid #fafafa;padding: 4px;"></td>
            </tr>
            <tr><td colspan="5"></td></tr><tr><td colspan="5"></td></tr>
        </table>

        <table style="width: 100%;border-collapse: collapse">
            <tr>
                <td style="width: 60%;padding: 0px; vertical-align: top">

                    <table style="width: 100%;border: solid 1px #000; border-collapse: collapse">
                        <thead>
                        <tr>
                            <th style="width: 10px;text-align: center">#</th>
                            <th style="width: 15%;text-align: center">Cantidad</th>
                            <th style="width: 75%">Descripción</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        if(count($ListaVenta)>0){
                            $No=0;
                            for($i=0;$i<count($ListaVenta);$i++){

                                $No++;

                                if($ListaVenta[$i][8] == "ART"){
                                    echo "<tr>
                                <td style='text-align: center'>$No</td>
                                <td style='text-align: center'>".(int)$ListaVenta[$i][6]."</td>
                                <td>".$ListaVenta[$i][5]."</td>
                                </tr>";
                                }
                                $TotalImporte = ($ListaVenta[$i][7] * $ListaVenta[$i][6]) + $TotalImporte;
                            }
                            $TotalImporte = $TotalImporte + $ListaVenta[0][10];
                        }

                        if(count($ListaVenta) <= 7){

                            for($i=0;$i<(7 -count($ListaVenta)) ;$i++){

                                echo "
                                <tr>
                                <td>&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                </tr>
                                ";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </td>
                <td style="width: 40%;padding: 0px;vertical-align: top;">

                    <table style="width: 100%;border: solid 1px #000; border-collapse: collapse" >
                        <thead>
                        <tr>
                            <th style="width: 10%;text-align: center">#</th>
                            <th style="width: 40%">Fecha</th>
                            <th style="width: 20%">Estatus</th>
                            <th style="width: 30%;text-align: center">Pago</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $No=0;
                        if(count($ListaPagos)>0){
                            $TotalPagos = 0;
                            for($i=0;$i < count($ListaPagos); $i++){


                                $SignoPago = "-";
                                if($ListaPagos[$i][2] == "A"){
                                    $TotalPagos = $TotalPagos + $ListaPagos[$i][1] ;
                                    $SignoPago = "+";
                                }

                                echo "<tr>
                                <td style='text-align: center'>".$ListaPagos[$i][3]."</td>
                                <td >".$ListaPagos[$i][0]."</td>
                                <td style='text-align: center'>".$ListaPagos[$i][2]."</td>
                                <td style='text-align: center'> ".$ListaPagos[$i][1]."</td>
                                </tr>";

                            }
                        }

                        if(count($ListaPagos) <= 7){

                            for($i=0;$i<(7 -count($ListaPagos)) ;$i++){
                                echo "
                                <tr>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                </tr>
                                ";
                            }
                            echo "<tr><td colspan='3' style='text-align: right;background: #F3F3F3'>Importe</td><td style='text-align: center'>".$TotalImporte."</td></tr>";
                            echo "<tr><td colspan='3' style='text-align: right;background: #F3F3F3'>Saldo Pendiente</td><td style='text-align: center'>".($TotalImporte - $TotalPagos)."</td></tr>";
                        }

                        ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table style="width: 100% ;border-collapse: collapse">
            <thead>
            <tr>
                <th style="width: 100%;">Descripción General</th>
            </tr>
            </thead>
            <?php
            if(count($ListaVenta)>0){
                $No=0;
                for($i=0;$i<count($ListaVenta);$i++){

                    $No++;
                    if($ListaVenta[$i][9] != ""){
                        echo "<tr>
                    <td style='font-size: 8px;width: 100%'> # ".$No." - ".$ListaVenta[$i][9]."</td>
                    </tr>";
                    }
                }
            }
            ?>
            <tr>
                <td style="width: 100%;font-size: 8px">
                    <?=$ListaVenta[0][14]?>
                </td>
            </tr>
        </table>

    </page>
<?php
$content = ob_get_clean();
$pdf = new HTML2PDF('P','Letter','es','UTF-8');
$pdf->writeHTML($content);
$pdf->pdf->IncludeJS('print(TRUE)');
$pdf->output('NotaDeVenta.pdf');
?>