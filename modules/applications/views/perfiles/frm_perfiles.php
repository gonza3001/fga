<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 28/04/2017
 * Time: 06:13 PM
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
include "../../controllers/perfiles/ControllerPerfiles.php";

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

$connect = new ControllerPerfiles();
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<div class="box box-primary">
    <div class="box-header">
        <i class="fa fa-list"></i>Catalogo de perfiles<br>
        <button class="btn btn-xs btn-primary" onclick="menu_catalogos(12,12)"><i class="fa fa-list"></i> Listado de perfiles</button>
        <button class="btn btn-xs btn-default" onclick="nuevo_perfil(1,0)"><i class="fa fa-file"></i> Nuevo</button>
        <button class="btn btn-xs disabled btn-warning"><i class="fa fa-search"></i> Generar Reporte</button>
        <button class="btn btn-xs disabled btn-default"><i class="fa fa-file-excel-o"></i> Exportar</button>
    </div>
    <div id="DataList" class="box-body table-responsive no-padding" >

        <table id="example" class="table table-condensed table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th class="bg-bareylev" width="50">No</th>
                <th class="bg-bareylev">Nombre</th>
                <th class="bg-bareylev">Descripción</th>
                <th class="bg-bareylev" width="120">Fecha Alta</th>
                <th class="bg-bareylev" width="80">Funciones</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script>

    var listar = function(){
        var table = $('#example').DataTable( {
            "ajax":{
                "url":"modules/applications/src/perfiles/fn_lista_perfiles.php",
                "method":"post"
            },
            "order": [],
            "language": {
                "lengthMenu": "Mostrar  _MENU_ registros",
                "zeroRecords": "Nothing found - sorry",
                "info": " Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "sSearch":        "Buscar:",
                "paginate": {
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                }
            },
            "columns":[
                {"data":"idperfil"},
                {"data":"nombre"},
                {"data":"descripcion"},
                {"data":"fecha_alta"},
                {"data":"funciones"}
            ],
            "autoWidth": false
        });
    };

    listar();
</script>

