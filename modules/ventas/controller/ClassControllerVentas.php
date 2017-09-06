<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/06/2017
 * Time: 01:27 PM
 */

include "../../../../core/seguridad.class.php";

class ClassControllerVentas extends \core\seguridad
{


    public function putRealizarPago($idFolio = ""){

        if($idFolio != ""){

            $this->_query = "
            SELECT idmovimiento,tipo_venta,idventa,NoPago,importe_venta,importe_pagado,importe_recibido,tipo_pago,pago_efectivo,pago_voucher,idestatus,fecha_registro
            FROM movimientos_caja WHERE idventa = $idFolio ORDER BY NoPago ASC;
            ";

            $this->get_result_query();

            if($this->_rows[0][0] != null){
                $this->_confirm = true;
                $this->_message = "Se encontro el folio: $idFolio";
            }else{
                $this->_message = "No se encontro el folio de venta";
                $this->_confirm = false;

            }



        }else{
            $this->_confirm = false;
            $this->_message = "No se encontro el folio para realizar el pago";
        }

    }

}