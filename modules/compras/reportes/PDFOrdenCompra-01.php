<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 25/09/2017
 * Time: 11:28 AM
 */

include "../../../core/core.class.php";
include "../../../core/sesiones.class.php";
include "../controllers/ControllerCompras.php";

require_once '../../../plugins/html2pdf/html2pdf.class.php';

$connect = new ControllerCompras();

$Background = "#F3F3F3";

$RowsCompras = $connect->getListaCompra(array("idcompra"=>$_REQUEST['id'],"idempresa"=>$_SESSION['data_home']['idempresa']));


ob_start();
?>

    <page backtop="20mm" backbottom="10mm" backleft="2mm" backright="2mm">
        <page_header>
            <table style="width: 100%; border-bottom: solid 1px black;">
                <tr>
                    <td style="text-align: left;width: 25%">
                        <img width="140" src="../../../site_design/img/logos/logo_200px.jpg">
                    </td>
                    <td style="text-align: center;    width: 50%"><h3>Orden de compra</h3></td>
                    <td style="text-align: right;    width: 25%"> <?=date("d/m/Y")?> </td>
                </tr>
            </table>
        </page_header>
        <page_footer>
            <table style="width: 100%; border-bottom: solid 1px black;">
                <tr>
                    <td style="text-align: left;    width: 50%"><?=$_SESSION['data_home']['nombre_empresa']?></td>
                    <td style="text-align: right;    width: 50%">Pagina [[page_cu]] de [[page_nb]]</td>
                </tr>
            </table>
        </page_footer>

        <br>
        <table style="width: 100%;border: solid 1px #000; border-collapse: collapse" align="center">
            <tr>
                <td style="width: 15%;border: solid 1px #000;background: <?=$Background?> ;padding: 5px; text-align: left;"><b>Proveedor:</b></td>
                <td style="width: 15%;border: solid 1px #000;padding: 5px; text-align: left;"><?=$RowsCompras[0][11]?></td>
                <td style="width: 45%;border-right: solid 1px #000;padding: 5px; text-align: left;"></td>
                <td style="width: 10%;border: solid 1px #000;background: <?=$Background?>;padding: 5px; text-align: left;"><b>Folio:</b></td>
                <td style="width: 15%;border: solid 1px #000;padding: 5px; text-align: left;"><?=$RowsCompras[0][3]?></td>
            </tr>
            <tr>
                <td style="width: 15%;border: solid 1px #000;background: <?=$Background?> ;padding: 5px; text-align: left;"><b>Telefono:</b></td>
                <td style="width: 15%;border: solid 1px #000;padding: 5px; text-align: left;"><?=$RowsCompras[0][12]?></td>
                <td style="width: 45%;border-right: solid 1px #000;padding: 5px; text-align: left;"></td>
                <td style="width: 10%;border: solid 1px #000;background: <?=$Background?> ;padding: 5px; text-align: left;"><b>Fecha:</b></td>
                <td style="width: 15%;border: solid 1px #000;padding: 5px; text-align: left;"><?=$RowsCompras[0][13]?></td>
            </tr>
        </table>

        <br>
        <table style="width: 100%;border: solid 1px #000; border-collapse: collapse" align="center">
            <thead>
            <tr>
                <th colspan="5" style="width: 100%;padding: 8px; text-align: center; border: solid 1px #000; background: <?=$Background?>">
                    Detalle
                </th>
            </tr>
            <tr>
                <th style="width: 15%;padding: 8px; text-align: left; border: solid 1px #000; background: <?=$Background?>">Codigo</th>
                <th style="width: 10%;padding: 8px; text-align: left; border: solid 1px #000; background: <?=$Background?>">Cantidad</th>
                <th style="width: 45%;padding: 8px; text-align: left; border: solid 1px #000; background: <?=$Background?>">Descripción</th>
                <th style="width: 15%;padding: 8px; text-align: center; border: solid 1px #000; background: <?=$Background?>">Precio U</th>
                <th style="width: 15%;padding: 8px; text-align: center; border: solid 1px #000; background: <?=$Background?>">Importe</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(count($RowsCompras)>0){

                for($i=0;$i<count($RowsCompras);$i++){

                    $importe = ($RowsCompras[$i][7] * $RowsCompras[$i][8]);
                    $TotalImporte = ( $TotalImporte + $importe );
                    echo "
                    <tr>
                    <td style='width: 15%;padding: 5px; text-align: left; border: solid 1px #000;'>".$RowsCompras[$i][0]."</td>
                    <td style='width: 10%;padding: 5px; text-align: left; border: solid 1px #000;'>".$RowsCompras[$i][7]."</td>
                    <td style='width: 45%;padding: 5px; text-align: left; border: solid 1px #000;'>".$RowsCompras[$i][6]."</td>
                    <td style='width: 15%;padding: 5px; text-align: right; border: solid 1px #000;'>$ ".$RowsCompras[$i][8]."</td>
                    <td style='width: 15%;padding: 5px; text-align: right; border: solid 1px #000;'>$ ".$importe."</td>
                    </tr>
                    ";
                }
                echo "<tr><td style='width: 70%;padding: 5px; text-align: right; border-right: solid 1px #000;' colspan='3'></td>
                        <td style='width: 15%;padding: 5px; text-align: right; border: solid 1px #000;background: ".$Background.";'><b>Total:</b></td>
                        <td style='width: 15%;padding: 5px; text-align: right; border: solid 1px #000;'>$ ".$TotalImporte."</td></tr>";
            }
            ?>
            </tbody>
        </table>


    </page>
<?php
$content = ob_get_clean();
$pdf = new HTML2PDF('P','A4','es','UTF-8');
$pdf->writeHTML($content);
$pdf->pdf->IncludeJS('print(TRUE)');
$pdf->output('Reporte.pdf');

?>