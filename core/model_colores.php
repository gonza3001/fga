<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 16/04/2017
 * Time: 03:25 PM
 */

namespace core;
include_once "seguridad.class.php";

class model_colores extends seguridad
{
    protected $idempresa;
    protected $opc_catalogo;
    protected $nombre_catalogo;
    protected $descripcion_catalogo;
    protected $idestado;
    protected $fecha_alta;
    protected $idusuario_alta;
    protected $fecha_um;
    protected $idusuario_um;


    public function getCodigoColor(){return $this->nombre_catalogo;}
    public function getNombreColor(){return $this->descripcion_catalogo;}
    public function getUsuarioAlta(){return $this->idusuario_alta;}
    public function getUsuarioUM(){return $this->idusuario_um;}
    public function getFechaAlta(){return $this->fecha_alta;}
    public function getFechaUM(){return $this->fecha_um;}
    public function getNoEstado(){return $this->idestado;}


    public function set_color($data_array = array()){

        if( array_key_exists('idempresa',$data_array) || array_key_exists('codigo_color',$data_array) || array_key_exists('nombre_color',$data_array)){

            //validar que no exista ya la categoria
            $this->_query = "SELECT descripcion_catalogo FROM catalogo_general where descripcion_catalogo = '$data_array[nombre_color]' AND idcatalogo = 4 AND idempresa = '$data_array[idempresa]' ";
            $this->get_result_query();

            if(count($this->_rows) >= 1 ){

                $this->_confirm = false;
                $this->_message = "El nombre del color ya existe";

            }else{

                $this->_query = "SELECT count(opc_catalogo) + 1 FROM catalogo_general where idcatalogo = 4 AND idempresa = '$data_array[idempresa]' ";
                $this->get_result_query();

                $idColor = $this->_rows[0][0];

                $this->_query = "
                INSERT INTO catalogo_general VALUES 
                (
                '4',
                '$data_array[idempresa]',
                '$idColor',
                '0',
                '0',
                '$data_array[codigo_color]',
                '$data_array[nombre_color]',
                '0',
                '0',
                '0',
                '1',
                '$data_array[idusuario_alta]',
                '$data_array[idusuario_alta]',
                '$data_array[fecha_alta]',
                '$data_array[fecha_alta]'
                )
                ";
                $this->execute_query();
                $this->_confirm = true;

            }

        }else{
            $this->_confirm = false;
            $this->_message = "Error al recibir los parametros";
        }

    }

    public function get_color($idTalla,$idEmpresa){

        //funcion para  traer la informacion de las tallas;

        if($idTalla != '' || $idEmpresa != ''){

            $this->_query = "
                    SELECT a.idempresa,a.opc_catalogo,a.nombre_catalogo,a.descripcion_catalogo,a.idestado,a.fecha_alta,b.nick_name as idusuario_alta,a.fecha_um,c.nick_name as idusuario_um 
                    FROM catalogo_general as a 
                    LEFT JOIN perfil_usuarios as b 
                    ON a.idusuario_alta = b.idusuario 
                     LEFT JOIN perfil_usuarios as c 
                    ON a.idusuario_um = c.idusuario 
                    WHERE  a.idcatalogo = 4 AND a.idempresa = '$idEmpresa' AND a.opc_catalogo = '$idTalla' ";

            $this->get_result_query();

        }

        if(count($this->_rows) == 1){

            foreach ($this->_rows[0] as $campo => $valor){
                $this->$campo = $valor ;
            }

            $this->_confirm = true ;
            $this->_message = "Se encontro la talla";

        }else{
            $this->_confirm = false ;
            $this->_message = "No se encontro la talla" ;
        }

    }

    public function edit_color($data_array = array()){

        if(array_key_exists('idempresa',$data_array) || array_key_exists('idcolor',$data_array) || array_key_exists('codigo_color',$data_array) ){
            //Validar que el color no exista

            $this->_query = "SELECT descripcion_catalogo FROM catalogo_general WHERE  descripcion_catalogo = '$data_array[nombre_color]' AND opc_catalogo != '$data_array[idcolor]'  AND idempresa = '$data_array[idempresa]' AND idcatalogo = 4 ";
            $this->get_result_query();

            if(count($this->_rows) >= 1 ){

                $this->_confirm = false ;
                $this->_message = "El color ya existe";

            }else{
                //editar el color
                unset($this->_rows) ;
                $FechaAlta = date("Y-m-d H:i:s");

                $this->_query =
                    "UPDATE catalogo_general 
                      SET 
                        nombre_catalogo = '$data_array[codigo_color]',
                        descripcion_catalogo = '$data_array[nombre_color]',
                        idestado = '$data_array[idestado]',
                        idusuario_um = '$data_array[idusuario_um]',
                        fecha_um = '$FechaAlta'
                      WHERE 
                        opc_catalogo = '$data_array[idcolor]' AND 
                        idempresa = '$data_array[idempresa]' AND 
                        idcatalogo = 4 AND 
                        opc_catalogo2 = 0 AND opc_catalogo3 = 0
                    ";


                $this->execute_query();
                $this->_confirm = true;
                $this->_message = "Color editado correctamente";
            }
        }else{
            $this->_confirm = false ;
            $this->_message = "No se encontraron los datos para editar el color";
        }

    }

    public function get_lista_color($opcion,$idEmpresa){

        $this->_query = "select opc_catalogo,idempresa,nombre_catalogo,descripcion_catalogo,fecha_alta,fecha_um,idestado from catalogo_general WHERE idcatalogo = 4 AND idempresa = '$idEmpresa' AND idestado = 1 ORDER BY opc_catalogo DESC ";
        $this->get_result_query();

        return $this->_rows ;


    }

}