/**
 * Created by USUARIO on 10/05/2017.
 */

function CancelarFacturaCompra(opc,idcompra){

    bootbox.confirm({
        title:"Confirmación de Compra",
        message:"Esta seguro de Cancelar la compra",
        size:"small",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancelar'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Aceptar'
            }
        },
        callback:function(confirm){
            if(confirm){

                $.ajax({
                    url:"modules/compras/src/compras/fnCancelarFacturaCompra.php",
                    type:"post",
                    dataType:"json",
                    beforeSend:function () {
                        fnloadSpinner(1);
                    },
                    data:{opc:opc,idcompra:idcompra}
                }).done(function(response){

                    fnloadSpinner(2);

                    if(response.result){

                        gn_menu_principal(6,6)
                        getMessage(response.message,'Compras',"success",3000);

                    }else{
                        MyAlert(response.message,"error");
                    }


                }).fail(function (Jqh,textStatus,errno) {
                    fnloadSpinner(2);

                    if(console && console.log){
                        if(textEstatus == 'timeout')
                        {
                            MyAlert("Tiempo de Espera Agotado","alert");

                        }else{
                            MyAlert("Error al llamer el fnCancelarFacturaCompra: "+textStatus,"alert");
                        }
                    }

                });
            }
        }
    });


}

function AutorizarFacturaCompra(opc,idcompra){

    bootbox.confirm({
       title:"Confirmación de Compra",
       message:"Esta seguro de Autorizar la compra" + idcompra ,
       size:"small",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancelar'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Aceptar'
            }
        },
       callback:function(confirm){
           if(confirm){

               $.ajax({
                   url:"modules/compras/src/compras/fnAutorizarFacturaCompra.php",
                   type:"post",
                   dataType:"json",
                   beforeSend:function () {
                       fnloadSpinner(1);
                   },
                   data:{opc:opc,idcompra:idcompra}
               }).done(function(response){

                   fnloadSpinner(2);
                   console.log(response);

                   if(response.result){

                       if(opc == 2){
                           lista_compras_realizadas(1);
                       }else{
                           gn_menu_principal(6,6);
                       }
                       getMessage(response.message,'Compras',"success",3000);

                   }else{
                       MyAlert(response.message,"error");
                   }


               }).fail(function (Jqh,textStatus,errno) {
                   fnloadSpinner(2);

                   if(console && console.log){
                       if(textEstatus == 'timeout')
                       {
                           MyAlert("Tiempo de Espera Agotado","alert");

                       }else{
                           MyAlert("Error al llamer el fnAutorizarFacturaCompra: "+textStatus,"alert");
                       }
                   }

               });
           }
       }
    });


}

function VerFacturaDeCompra(opc,idcompra) {


   window.open("modules/compras/reportes/PDFOrdenCompra-01.php?id="+idcompra+"","","location=no,width=700,height=800,scrollbars=NO,menubar=NO,titlebar=NO,toolbar=NO");

   /*SenderAjax(
     "modules/compras/reportes/",
     "PDFOrdenCompra-01.php",
     null,
     "idgeneral",
     "post",
       {opc:opc,idcompra:idcompra}
   );*/

}

function ListarComprasPorAutorizar(opc) {

    $.ajax({
        url:"modules/compras/src/compras/fnListaComprasPorAutorizar.php",
        type:"post",
        dataType:"json",
        beforeSend:function () {
            fnloadSpinner(1);
        },
        data:{opc:opc}
    }).done(function(response){

        fnloadSpinner(2);

        if(response.result){


            var fila = "",i=0;

            for(i=0;i< response.data.length;i++){


                fila = fila  + '<li><a href="#">'+ response.data[i]['FolioCompra'] +' - '+ response.data[i]['nombre_proveedor'] +' <span class="pull-right"><button class="btn btn-sm btn-default" title="Imprimir" onclick="VerFacturaDeCompra(1,'+response.data[i]['idcompra']+')" ><i class="fa fa-print"></i></button> <button class="btn btn-sm btn-default" title="Editar"><i class="fa fa-edit"></i></button> <button class="btn btn-sm btn-success" onclick="AutorizarFacturaCompra(1,'+response.data[i]['idcompra']+')" title="Dar Entrada "><i class="fa fa-upload"></i></button> <button class="btn btn-sm btn-danger" onclick="CancelarFacturaCompra(1,'+response.data[i]['idcompra']+')"  title="Cancelar"><i class="fa fa-close"></i></button></span><span class="pull-right">'+response.data[i]['fecha_alta']+'&nbsp;&nbsp;&nbsp;</span></a></li>';

                $("#listaComprasPorAutorizar").html(fila);

            }

        }else{
            MyAlert(response.message,"error");
        }


    }).fail(function (Jqh,textStatus,errno) {
        fnloadSpinner(2);

        if(console && console.log){
            if(textEstatus == 'timeout')
            {
                MyAlert("Tiempo de Espera Agotado","alert");

            }else{
                MyAlert("Error al llamer el fnListaComprasPorAutorizar: "+textStatus,"alert");
            }
        }

    });

}

function CargarIndicadoresCompras(opc) {

    $.ajax({
        url:"modules/compras/src/compras/fnCargarIndicadoresCompras.php",
        type:"post",
        dataType:"json",
        beforeSend:function () {
            fnloadSpinner(1);
        },
        data:{opc:opc}
    }).done(function(response){

        fnloadSpinner(2);

        if(response.result){

            var autorizar,realizado,cancelado;

            for(i=0;i < response.data.length;i++){

                if(response.data[i]['Estado'] == "autorizar"){
                    autorizar = response.data[i]['Total'];
                    if(autorizar <= 0){autorizar = '0000';}
                }

                if(response.data[i]['Estado'] == "realizado"){
                    realizado = response.data[i]['Total'];
                    if(realizado <= 0){realizado = '0000';}
                }

                if(response.data[i]['Estado'] == "cancelado"){
                    cancelado = response.data[i]['Total'];
                    if(cancelado <= 0){cancelado = '0000';}
                }


            }

            $('#lblautorizar').text(autorizar);
            $('#lblrealizadas').text(realizado);
            $('#lblcanceladas').text(cancelado);

        }else{
            MyAlert(response.message,"error");
        }


    }).fail(function (Jqh,textStatus,errno) {
        fnloadSpinner(2);

        if(console && console.log){
            if(textEstatus == 'timeout')
            {
                MyAlert("Tiempo de Espera Agotado","alert");

            }else{
                MyAlert("Error al llamer el fnCargarIndicadoresCompras: "+textStatus,"alert");
            }
        }

    });


}

function lista_compras_realizadas(opc){

    SenderAjax(
        "modules/compras/views/compras/",
        "frm_lista_compras_realizadas.php",
        null,
        "data_list",
        "post",
        {
            opc:opc
        }
    );

}

//ELiminar producto del carrito de compras
function fnCompraEliminarProducto(idproducto){

    $.ajax({
        url:"modules/compras/src/ordenes_compra/fn_eliminar_producto.php",
        type:"post",
        data:{idproducto:idproducto,opc:2},
        dataType:"JSON"
    }).done(function(data){

        switch (data.result){
            case 'ok':

                SenderAjax(
                    "modules/compras/src/ordenes_compra/",
                    "fn_agregar_carrito.php",
                    null,"lista_productos","post",{
                        opc:2
                    }
                );

                break;
            case 'error':
                break;
        }

    }).fail(function(jqh,textStatus){
       MyAlert("Error al llamar el metodo delete","alert");
    });

}

function lista_compras_canceladas(){

}