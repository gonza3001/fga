/**
 * Created by alejandro.gomez on 22/05/2017.
 */

function fnImprimirTraspaso(idtraspaso) {

    window.open("modules/almacen/reportes/PDFReporteTraspasos-01.php?id="+idtraspaso+"","","location=no,width=700,height=800,scrollbars=NO,menubar=NO,titlebar=NO,toolbar=NO");

}

function fnListarTraspasos(opc,idalmacen,nombre_almacen){

    SenderAjax(
        "modules/almacen/views/traspasos/",
        "FrmListarTraspasos.php",
        null,
        "lista_traspasos",
        "post",
        {opc:opc,idalmacen:idalmacen,nombre_almacen:nombre_almacen}
    );

}

function CargarTableroAlmacen(opc,idempresa,idalmacen){

    $.ajax({
        url:"modules/almacen/src/almacen/fnCargarTableroAlmacen.php",
        type:"post",
        dataType:"json",
        data:{opc:opc,idempresa:idempresa,idalmacen:idalmacen},
        beforeSend:function () {
            fnloadSpinner(1);
        }
    }).done(function (response) {
        fnloadSpinner(2);

        console.log(response);

        if(response.result){

            $('#IndicadorInventario').html('<span class="info-box-text">'+response.data.indicadores.Inventario.titulo+'</span><span class="info-box-number">'+response.data.indicadores.Inventario.total+'</span>');
            $('#IndicadorTraspasos').html('<span class="info-box-text">'+response.data.indicadores.Traspasos.titulo+'</span><span class="info-box-number">'+response.data.indicadores.Traspasos.total+'</span>')
            $('#IndicadorExisBajas').html('<span class="info-box-text">'+response.data.indicadores.Existencias.titulo+'</span><span class="info-box-number">'+response.data.indicadores.Existencias.total+'</span>')
            $('#IndicadorSinExistencias').html('<span class="info-box-text">'+response.data.indicadores.SinExistencias.titulo+'</span><span class="info-box-number">'+response.data.indicadores.SinExistencias.total+'</span>')


            //Lista para los ultimos 10 Traspasos
            if( response.data.datalist.traspasos.length > 0 ){

                var listaTraspasos = "";

                for(i=0;i < response.data.datalist.traspasos.length;i++){

                    listaTraspasos = listaTraspasos + "<tr><td>"+response.data.datalist.traspasos[i]['FolioTraspaso']+"</td><td>"+response.data.datalist.traspasos[i]['almacen_origen']+"</td><td>"+response.data.datalist.traspasos[i]['almacen_destino']+"</td><td>"+response.data.datalist.traspasos[i]['fecha_alta']+"</td></tr>"

                }

                $("#listaTraspasos").html(listaTraspasos);

            }


            //Lista para el Stock Minimo

            if(response.data.datalist.existenciasBajas.length > 0){

                var listaStockMinimo = '';

                for(i=0;i< response.data.datalist.existenciasBajas.length;i++){

                    listaStockMinimo = listaStockMinimo + "<tr><td>"+response.data.datalist.existenciasBajas[i]['nombre_articulo']+"</td><td>"+response.data.datalist.existenciasBajas[i]['Existencias']+"</td></tr>"
                }

                $("#listaStockMinimo").html(listaStockMinimo);

            }


        }else{
            MyAlert(response.message,"alert");
        }

    }).fail(function (jqH,textStatus,errno) {
        fnloadSpinner(2);

        if(console && console.log){
            if(textStatus == 'timeout')
            {
                MyAlert("Tiempo de Espera Agotado","alert");

            }else{
                MyAlert("Error al llamer el fnCargarTableroAlmacen: "+textStatus,"alert");
            }
        }

    });
}

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

function agregar_carrito_traspasos(idarticulo,nombre_articulo,tipo_articulo,cantidad,existencias,datos,e) {

    //alert(idarticulo + "-" + nombre_articulo + "-" +tipo_articulo +"-"+cantidad +"-" + existencias +"--"+ datos);

    if(existencias < cantidad){
        MyAlert("No cuenta con suficientes articulos en el almacen origen","alert");
    }else{

        if(cantidad > 0 ){
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
            $(e).closest("tr").remove();
        }else{
            MyAlert("La cantidad debe ser mayor a 0","error");
        }
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