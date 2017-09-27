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

$Background = "#F3F3F3";

$RowsTraspaso = "";

$DomicilioSucursal = "Calle avenida sendero division norte # 135 Local 123";
$TelefonoSucursal = "81 2132-356 - 044 81 2134-4567";

ob_start();
?>

    <page backtop="20mm" backbottom="10mm" backleft="2mm" backright="2mm">
        <page_header>
            <table style="width: 100%; border-bottom: solid 1px black;">
                <tr>
                    <td style="text-align: left;width: 25%">
                        <img width="140" src="../../../site_design/img/logos/logo_200px.jpg">
                    </td>
                    <td style="text-align: center;    width: 50%"></td>
                    <td style="text-align: right;    width: 25%"> Traspasos </td>
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
        <table style="width: 100%;border: solid 1px #000; border-collapse: collapse" align="center">
            <tr>
                <td style="width: 100%;text-align: left">
                    <b><?=$_SESSION['data_home']['nombre_empresa']?></b><br><br>
                    <b>Folio: </b> 0001<br>
                    <b>Fecha Registro: </b> 25/09/2017<br>
                    <b>Fecha Actual: </b> 25/09/2017<br>
                    <b>Solicitante: </b>Pedro Luis<br>
                    <b>Autorizador: </b>Alejandro Gomez<br>

                </td>
            </tr>
        </table>

        <br>
        <table style="width: 100%;border: solid 1px #000; border-collapse: collapse" align="center">
            <tr>
                <td style="width: 17%;border: solid 1px #000;background: <?=$Background?> ;padding: 5px; text-align: left;"><b>Almacen Origen:</b></td>
                <td style="width: 25%;border: solid 1px #000;padding: 5px; text-align: left;"><?=$RowsTraspaso?></td>
                <td style="width: 16%;border-right: solid 1px #000;padding: 5px; text-align: left;"></td>
                <td style="width: 17%;border: solid 1px #000;background: <?=$Background?>;padding: 5px; text-align: left;"><b>Almacen Destino:</b></td>
                <td style="width: 25%;border: solid 1px #000;padding: 5px; text-align: center;"><b><?=$RowsTraspaso?></b></td>
            </tr>
        </table>
        <br>
        <table style="width: 100%;border: solid 1px #000; border-collapse: collapse" align="center">
            <thead>
            <tr>
                <th colspan="4" style="width: 100%;padding: 8px; text-align: center; border: solid 1px #000; background: <?=$Background?>">
                    Detalle
                </th>
            </tr>
            <tr>
                <th style="width: 15%;padding: 8px; text-align: center; border: solid 1px #000; background: <?=$Background?>">Codigo</th>
                <th style="width: 60%;padding: 8px; text-align: center; border: solid 1px #000; background: <?=$Background?>">Descripción</th>
                <th style="width: 15%;padding: 8px; text-align: center; border: solid 1px #000; background: <?=$Background?>">Tip</th>
                <th style="width: 10%;padding: 8px; text-align: left; border: solid 1px #000; background: <?=$Background?>">Cantidad</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(count($RowsCompras)>0){

                for($i=0;$i<count($RowsCompras);$i++){

                    echo "
                    <tr>
                    <td style='width: 15%;padding: 5px; text-align: center; border: solid 1px #000;'>".$RowsTraspaso."</td>
                    <td style='width: 60%;padding: 5px; text-align: left; border: solid 1px #000;'>".$RowsTraspaso."</td>
                    <td style='width: 15%;padding: 5px; text-align: center; border: solid 1px #000;'>".$RowsTraspaso."</td>
                    <td style='width: 10%;padding: 5px; text-align: left; border: solid 1px #000;'>".$RowsTraspaso."</td>
                    </tr>
                    ";
                }
            }

            if(count($RowsTraspaso) <= 5){

                for($i=0;$i<(7 -count($RowsTraspaso)) ;$i++){


                    echo "
                    <tr>
                    <td style='width: 15%;padding: 5px; text-align: left; border: solid 1px #000;'>&nbsp;</td>
                    <td style='width: 60%;padding: 5px; text-align: left; border: solid 1px #000;'>&nbsp;</td>
                    <td style='width: 15%;padding: 5px; text-align: left; border: solid 1px #000;'>&nbsp;</td>
                    <td style='width: 10%;padding: 5px; text-align: left; border: solid 1px #000;'>&nbsp;</td>
                    </tr>
                    ";
                }
            }

            echo "
                    <tr>
                    <td colspan='2' style='width: 75%;padding: 5px; text-align: left; border-right: solid 1px #000;'>&nbsp;</td>
                    <td style='width: 15%;padding: 5px; text-align: right; border: solid 1px #000;background: ".$Background." '>Total</td>
                    <td style='width: 10%;padding: 5px; text-align: left; border: solid 1px #000;'>00</td>
                    </tr>
                    ";

            ?>
            </tbody>
        </table>
        <br>
        <table style="width: 100%;border: solid 1px #000; border-collapse: collapse" align="center">
            <tr>
                <td  style='width: 100%;padding: 5px; text-align: left; border: solid 1px #000;background: <?=$Background?>'>Observaciones</td>
            </tr>
            <tr>
                <td style='width: 100%;padding: 5px; text-align: left; border: solid 1px #000;'>
                    &nbsp;<br><br><br><br><br><br>
                </td>
            </tr>
        </table>


    </page>
<?php
$content = ob_get_clean();
$pdf = new HTML2PDF('P','A4','es','UTF-8');
$pdf->writeHTML($content);
$pdf->pdf->IncludeJS('print(TRUE)');
$pdf->output('ReporteTraspaso_'.date("YmdHis").'.pdf');

?>