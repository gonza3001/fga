<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 30/05/2017
 * Time: 05:08 PM
 */


class ClassControllerCarritoVentas
{

    public function __construct()
    {
        $this->num_producto = 0;

    }

    public function introduce_producto($id_prod,$tipo_producto,$nombre_prod,$precio_venta,$cantidad,$descripcion){

        $_SESSION['cart_venta'][] = array(
            'id'=>$id_prod,
            'tipo'=>$tipo_producto,
            'nombre'=>$nombre_prod,
            'cantidad'=>$cantidad,
            'precio_venta'=>$precio_venta,
            'descripcion'=>$descripcion

        );

    }

    public function elimina_producto($linea){

        unset($_SESSION['cart_venta'][$linea]);

        array_splice($_SESSION['cart_venta'],$linea,1);



    }

    public function imprime_carrito(){

        $total_precio=0;

        for ($i=0;$i<count($_SESSION['cart_venta']);$i++){

            $total_precio = ($_SESSION['cart_venta'][$i]['precio_compra'] * $_SESSION['cart_venta'][$i]['cantidad']);

            $data[] = array(
                "idproducto"=>$_SESSION['cart_venta'][$i]['id'],
                "tipo_producto"=>$_SESSION['cart_venta'][$i]['tipo'],
                "nombre"=>$_SESSION['cart_venta'][$i]['nombre'],
                "descripcion"=>$_SESSION['cart_venta'][$i]['descripcion'],
                "precio_venta"=>$_SESSION['cart_venta'][$i]['precio_venta'],
                "cantidad"=>$_SESSION['cart_venta'][$i]['cantidad'],
                "total"=>$total_precio
            );
        }

        return $data;
    }

}