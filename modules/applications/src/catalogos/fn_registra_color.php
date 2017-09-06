<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/04/2017
 * Time: 03:48 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php o modelo ( ej: model_aparatos.php)
 */

include "../../../../core/core.class.php";
include "../../../../core/sesiones.class.php";
include "../../../../core/model_colores.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 *
 * Ejemplo:
 * Si se requiere cambiar de servidor de base de datos
 * $data_server = array(
 *   'bdHost'=>'192.168.2.5',
 *   'bdUser'=>'sa',
 *   'bdPass'=>'pasword',
 *   'port'=>'3306',
 *   'bdData'=>'dataBase'
 *);
 *
 * Si no es requerdio se puede dejar en null
 *
 * con @data_server
 * @@$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos'],$data_server);
 *
 * Sin @data_server
 * @@$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 *
 * @@$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$color = new \core\model_colores();
$color->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

if(array_key_exists('codigo_color',$_POST) || array_key_exists('nombre_color',$_POST)){

    $NombreCategoria = $color->get_sanatiza($_POST['codigo_color']);
    $Descripcion_categoria = $color->get_sanatiza($_POST['nombre_color']);

    $color->set_color(array(
        'idempresa'=>$_SESSION['data_home']['idempresa'],
        'codigo_color'=>$NombreCategoria,
        'nombre_color'=>$Descripcion_categoria,
        'idusuario_alta'=>$_SESSION['data_login']['idusuario'],
        'fecha_alta'=>date("Y-m-d H:i:s")
    ));

    if($color->_confirm){

        echo "<script>getMessage('Color registrador correctamente');menu_catalogos(5,5);$('#modalbtnclose').click();</script>";

    }else{
        \core\core::MyAlert($color->_message,"alert");
    }


}else{
    echo "error al recibir los parametros";
}