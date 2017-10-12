<?php
/**
 * Created by PhpStorm.
 * User: agomez
 * Date: 02/06/2017
 * Time: 10:59 PM
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/seguridad.class.php";

$connect = new \core\seguridad();
sleep(1);

$idEmpresa = $_SESSION['data_home']['idempresa'];
$idAlmacen = $_SESSION['data_home']['almacen'];
$textSearch = $_POST['textSearch'];

$connect->_query = "
SELECT a.idarticulo,idalmacen,b.nombre_articulo,a.existencias,b.precio_venta
FROM almacen_articulos as a
LEFT JOIN articulos as b
ON a.idarticulo = b.idarticulo
LEFT JOIN catalogo_general as c
ON c.idcatalogo = 2 AND c.opc_catalogo = b.idsubcategoria AND c.idempresa = $idEmpresa
LEFT JOIN catalogo_general as d
ON d.idcatalogo = 3 AND d.opc_catalogo = b.idtalla AND d.idempresa = $idEmpresa
LEFT JOIN catalogo_general as e
ON e.idcatalogo = 4 AND e.opc_catalogo = b.idcolor AND e.idempresa = $idEmpresa
WHERE a.existencias > 0 AND a.idalmacen = $idAlmacen AND b.idempresa = $idEmpresa AND b.nombre_articulo LIKE '%$textSearch%'  GROUP BY b.idcolor
";

$connect->get_result_query();
$lista = $connect->_rows;

$a=0;
for($i=0;$i < count($lista);$i++){

    $valor = $lista[$i][0]."-".$lista[$i][2] ;
    $NombreProducto = $lista[$i][2];
    $precio_venta = $lista[$i][4];

    echo "<tr><td>".$lista[$i][2]."</td><td class='text-center'>".(int) ($lista[$i][3])."</td><td>".$lista[$i][4]."</td><td><button class='btn btn-sm btn-success' onclick='getCrearTrabajo(1,{valor:\"".$valor."\",NombreProducto:\"".$NombreProducto."\"})' ><i class='fa fa-plus'></i></button></td></tr>";

}