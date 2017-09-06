/**
 * Created by alejandro.gomez on 22/05/2017.
 */



function setRealizarTraspaso(opc,openReport,idestado){

    $.ajax({
        url:"modules/almacen/src/traspasos/fn_realizar_traspaso.php",
        type:"GET",
        dataType:"JSON",
        beforeSend:function(){
            fnloadSpinner(1);
        },
        data:{
            opc:opc,idestado:idestado,
            openReport:openReport
        }
    }).done(function(dataResult){

        console.log(dataResult);

        fnloadSpinner(2);
        if(dataResult.result == "ok"){

            getMessage(dataResult.mensaje,'Traspaso realizado correctamente','success',5000);
            gn_menu_principal(5,5);
        }


    }).fail(function(jqXHR,textStatus,errorThrown){

        fnloadSpinner(2);
        console.log(textStatus + errorThrown);

    });


}


function eliminar_carrito_traspasos(idarticulo){

    console.log(idarticulo);

    $.ajax({
        url:"modules/almacen/src/traspasos/fn_elimina_producto.php",
        type:"POST",
        dataType:"JSON",
        data:{idarticulo:idarticulo,opc:2}
    }).done(function(response){

        switch (response.result){
            case 'ok':

                SenderAjax(
                    "modules/almacen/src/traspasos/",
                    "fn_agregar_carrito_traspaso.php",
                    null,"list_cart_traspasos","post",
                    {
                        opc:2
                    }
                );

                break;

            case 'error':
                MyAlert(response.mensaje,"alert");
                break;
        }

    }).fail(function (jqh,textStatus) {
        MyAlert("Errro al llamar el metodo delete","error");
    })

}
function agregar_carrito_traspasos(idarticulo,nombre_articulo,tipo_articulo,cantidad,existencias,datos) {

    //alert(idarticulo + "-" + nombre_articulo + "-" +tipo_articulo +"-"+cantidad +"-" + existencias +"--"+ datos);

    if(existencias < cantidad){
        MyAlert("No cuenta con suficientes articulos en el almacen origen","alert");
    }else{

        SenderAjax(
            "modules/almacen/src/traspasos/",
            "fn_agregar_carrito_traspaso.php",
            null,"list_cart_traspasos","post",
            {
                opc:1,
                idarticulo:idarticulo,
                tipo_articulo:tipo_articulo,
                nombre_articulo:nombre_articulo,
                cantidad:cantidad,
                existencias:existencias,
                datos:datos
            }
        );

    }


}

function agregar_producto_traspaso(opc,datos) {

    $("#list_producto_cart").html('');
    SenderAjax(
        "modules/almacen/src/traspasos/",
        "fn_buscar_producto.php",
        null,"list_producto_cart","post",{
            opc:3,parametro:2,nombre_producto:$("#nombre_producto").val(),
            tipo_producto:$("#tipo_producto").val(),datos:datos
        }
    );

    $("#idusuario_solicita").attr('disabled','disabled'),
    $("#idalmacen_origen").attr('disabled','disabled'),
    $("#idalmacen_destino").attr('disabled','disabled');
}


function buscar_producto_almacen(){

    var idusuario_solicita = $("#idusuario_solicita").val(),
        idalmacen_origen = $("#idalmacen_origen").val(),
        idalmacen_destino = $("#idalmacen_destino").val();

    if(idusuario_solicita == 0 ){
        MyAlert("Seleccione el usuario solicitante","error");
    }else if(idalmacen_origen == 0){
        MyAlert("Seleccione el almacen de origen","alert");
    }else if(idalmacen_destino == 0){
        MyAlert("Selecione el almacen destino","alert");
    }else{


        //Abrir modal para seleccionar los articulos a traspasar
        SenderAjax(
            "modules/almacen/views/traspasos/",
            "frm_agregar_productos.php",
            null,
            "show_modal",
            "post",
            {
                opc:1,
                idalmacen_origen:idalmacen_origen,
                idusuario_solicita:idusuario_solicita,
                idalmacen_destino:idalmacen_destino
            }
        );


    }

}

function compras_nuevo_traspaso(opc){

    switch (opc){
        //mostrar formulario para nuevo traspaso
        case 1:

            SenderAjax(
                "modules/almacen/views/traspasos/",
                "frm_nuevo_traspaso.php",
                null,
                "lista_traspasos",
                "post",
                {
                    opc:opc
                }
            );

            break;
        default:
            MyAlert("Error no se encontro la opcion","error");
            break;
    }



}

function listar_inventarios(opc,tipo_producto,idalmacen,str){

    switch (opc){
        //Opcion para mostrar lista de almacen general
        case 1:

            SenderAjax(
                "modules/almacen/views/inventario/",
                "frm_almacen_general.php",
                null,
                "lista_traspasos",
                "post",
                {
                    opc:opc,
                    tipo_producto:tipo_producto,
                    idalmacen:idalmacen,
                    str:str
                }
            );

            break;
        case 2:

            SenderAjax(
                "modules/almacen/views/inventario/",
                "frm_almacen_general.php",
                null,
                "lista_traspasos",
                "post",
                {
                    opc:opc,
                    tipo_producto:tipo_producto,
                    idalmacen:idalmacen,
                    str:str
                }
            );

            break;
        default:
            MyAlert("La opcio no existe","error");
    }
}


var listar_inventario3 = function(tipo_articulo,idalmacen,idempresa){

    SenderAjax(
        "modules/almacen/src/inventario/",
        "fn_listar_inventario.php",
        null,
        "tabla_lista_inventarios",
        "post",
        {tipo_articulo:tipo_articulo,idalmacen:idalmacen,idempresa:idempresa}
    );


}