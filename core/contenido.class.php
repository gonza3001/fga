<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/01/2017
 * Time: 04:27 PM
 */

namespace core;
include "bd.php";

class contenido extends bd
{

    //Metodo para Formatear el Folio Agregando ceros a la Izquierda
    public function getFormatFolio($NoFolio,$NoCeros){
        // Funcion para mostrar 4 ceros a la izquierda
        // Ejemplo: Numero de entrar 1, resultado: 0001
        $newNum = str_pad($NoFolio, $NoCeros,"0", STR_PAD_LEFT);
        return  $newNum;
    }

}