/**
 * Created by alejandro.gomez on 29/05/2017.
 */

var viewContentVenta = false;
var gPagoInicial = false,gPagoEfectivo = true,gPagoTerjeta = false,gPagoCombinado = false;

function fngetDetaleTrabajo(idventa,data) {

    console.log(data);
    var divDetalle = $("#getDetalleOrden");

    divDetalle.html("");

    var TempleteART = '',
        TempleteMAT = '',NombreArticulo='';


    for(i=0;i<data.length;i++){

        if(idventa == data[i].idventa){

            $("#getTituloDetalle").text(data[i].Folio+" - "+ data[i].NombreCliente);

            if(data[i].tipo_articulo == "ART"){

                NombreArticulo = data[i].nombre_articulo;

                TempleteART = '<div class="col-md-6 invoice-col "><address class="well well-sm"><strong>Datos del cliente</strong><br>Nombre: '+data[i].NombreCliente+'<br>Fecha Venta: '+data[i].FechaVenta+'</address></div><div class="col-md-6 invoice-col"><address><strong>Datos de Venta</strong><br>Sucursal: '+data[i].nombre_departamento+'<br>Sucursal: '+data[i].nombre_departamento+'<br></address></div><div class="col-md-6 ">\n' +
                    '                        <div class="panel panel-info">\n' +
                    '                            <div class="panel-heading padding-x3"><b>Articulo</b>: '+NombreArticulo +'</div>\n' +
                    '                            <div class="panel-body scroll-auto" style="min-height: 25vh;">\n' +
                            '                       <strong><i class="fa fa-book margin-r-5"></i>  Producto</strong><p class="text-muted">'+ NombreArticulo  +'</p>' +
                            '                       <strong><i class="fa fa-bookmark margin-r-5"></i> Descripción</strong><p class="text-muted">'+data[i].descripcion+'</p>' +
                            '                       <strong><i class="fa fa-paper-plane"></i> Material</strong><p id="textMat" class="text-muted">'+TempleteMAT+'</p>'+
                            '                    </div>' +
                        '                    </div>' +
                    '                    </div>';

                divDetalle.html(divDetalle.html()+TempleteART);
            }

            if(data[i].tipo_articulo=="MAT"){
                TempleteMAT = "Material: " +data[i].nombre_articulo+"<br>Cantidad: "+data[i].cantidad;
                $("#textMat").html($("#textMat").text()+TempleteMAT);
            }


        }
    }

}

function fnListarTrabajos(opc) {

    $.ajax({
        url:"modules/ventas/src/trabajos/fnListarTrabajos.php",
        type:"get",
        dataType:"json",
        data:{opc:opc}
    }).done(function (response) {

        if(response.result){

            var i,trs='',trabajos = response.detalle;
            var vdata,idVenta,FolioVenta,NombreCliente,FechaVenta,FechaPromesa;

            if(trabajos.length > 0){

                for(i=0;i<trabajos.length;i++){

                    if(response.data[i] == trabajos[i].idventa){

                        idVenta = trabajos[i].idventa;
                        FolioVenta = trabajos[i].Folio;
                        NombreCliente = trabajos[i].NombreCliente;
                        FechaVenta = trabajos[i].FechaVenta;
                        FechaPromesa = trabajos[i].FechaPromesa;

                        vdata = JSON.stringify(trabajos);

                        trs = trs + "<tr onclick='fngetDetaleTrabajo("+idVenta+","+vdata+")' style='cursor: pointer'><td class='text-center'>"+FolioVenta+"</td><td>"+NombreCliente+"</td><td>"+FechaVenta+"</td><td>"+FechaPromesa+"</td></tr>";


                    }
                }

                $("#tblListaTrabajos").html(trs);
                $("#tblListaTrabajos tr ").click(function () {
                    $("#tblListaTrabajos tr ").removeClass('bg-light-blue-gradient text-white');
                    $(this).addClass('bg-light-blue-gradient text-white');
                });

            }else{
                getMessage("No se encontraron trabajos","Trabajos","success",850);
            }

        }else{
            MyAlert(response.message);
        }

    }).fail(function (jqhr,textStatus,errno) {
       if(console && console.log){
           if(textStatus == "timeout"){
               MyAlert("Tiempo de espera agotado");
           }else{
               MyAlert("Error al cargar la visra");
           }
       }
    });
}
function fnTrabajosPendientes(opc){

    switch (opc){
        case 1:
            SenderAjax(
                "modules/ventas/views/trabajos/",
                "FrmTrabajos.php",
                null,
                "form_caja",
                "post",
                {
                   opc:opc
                }
            )
            break;
    }
}

function fnVentaCierreCaja(opcion){

    switch (opcion){
        //Validar cierre del dia anterior
        case 1:

            $.ajax({
                url:"modules/ventas/src/cierre/fn_cierre_caja.php",
                type:"POST",
                data:{opc:opcion},
                dataType:"JSON"
            }).done(function(response){

                if(response.result == "success"){


                    switch (response.mensaje){
                        case 'cierre_anterior':
                            //No se realizo cierre del dia enterior
                            $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-danger"><h4>No se ha realizado el cierre del dia anterior !</h4><p>Realize primeramente el cierre del día anterior, para hacer la apertura de la caja.</p></div></div></div>');
                            break;
                        case 'dia_cerrado':
                            //Ciere del dia realizado
                            $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-success"><h4>Cierre Realizado !</h4><p>Movimientos no proceden El día ya fue cerrado.</p></div></div></div>');
                            break;
                        case 'sistema_nuevo':
                            //Realizar Apertura
                            $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-warning"><h4>No se ha realizado la apertura !</h4><p>Realize primeramente la apertura del dia para poder realizar las ventas diarias.</p></div></div></div>');

                            break;
                        case 'Apertura_mayor':
                            //
                            break;
                        default:
                            $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-danger"><h4>Error 404</h4><p>Intentelo nuevamente o Contacte al administrador.</p></div></div></div>');
                            break;
                    }
                }
            }).fail(function(jqh,textStatus){
                MyAlert("error al realizar la solicitud" + textStatus,"alert");
            });

            break;
        case 2:

            var saldo_final_caja = $("#saldo_final_caja").val(),
                saldo_final_caja = setFormatoMoneda(1,saldo_final_caja),
                fecha_cierre = $("#fecha_cierre").val();

            $.ajax({
                url:"modules/ventas/src/cierre/fn_cierre_caja.php",
                type:"POST",
                data:{opc:opcion,saldo_final_caja:saldo_final_caja,fecha_cierre:fecha_cierre},
                dataType:"JSON"
            }).done(function(data){

                if(data.result == "error"){
                    MyAlert(data.mensaje,"error");
                }

                if(data.result == "ok"){
                    MyAlert("Cierre finalizado correctamente","alert");
                }

            }).fail(function(jqh,textStatus){
                MyAlert("error al realizar la solicitud" + textStatus,"alert");
            });

            break;
        default:
            break;
    }

}

function setApertura(opc,FechaApertura,SaldoApertura) {
    switch (opc){
        case 1:
            //Mostrar Formulario de Apertura
            SenderAjax(
              "modules/ventas/views/apertura/",
                "frm_apertura.php",
                null,
                "idgeneral",
                "post",
                {opc:opc,FechaApertura:FechaApertura}
            );
            break;
        case 2:

            var FechaApertura = $("#FechaApertura").val(),
                SaldoApertura = $("#SaldoApertura").val();

            SaldoApertura = setFormatoMoneda(1,SaldoApertura);

            $.ajax({
                url:"modules/ventas/src/apertura/fn_apertura_caja.php",
                type:"post",
                dataType:"json",
                data:{
                    FechaApertura:FechaApertura,
                    SaldoApertura:SaldoApertura,
                    opc:opc
                }
            }).done(function (response) {

                MyAlert(response.message);

            }).fail(function (jqhr,textStatus,errno) {

                if(console && console.log){

                    if(textStatus == "timeout"){
                        MyAlert("Tiempo de espera agotado");
                    }else{
                        MyAlert("Error al cargar la vista");
                    }

                }

            });



            break;
    }
}

function getValidarApertura(FechaActual) {
    $.ajax({
        url:"modules/ventas/src/apertura/getValidaApertura.php",
        type:"get",
        dataType:"json",
        data:{FechaActual:FechaActual}
    }).done(function (response) {

        //console.log(response);
        return response;


    }).fail(function (jqhr,textStatus,errno) {
        if(console && console.log){

            if(textStatus == "timeout"){
                return "error";
            }else{
                return "error";
            }
        }
    });
}

function getValidarCierre(FechaActual) {

    $.ajax({
        url:"modules/ventas/src/cierre/getValidaCierre.php",
        type:"get",
        dataType:"json",
        data:{FechaActual:FechaActual}
    }).done(function (response) {

        if(response.result){

            switch (response.data.opc){
                case 1:

                   var res = getValidarApertura(FechaActual);

                    if(res.result){

                        setApertura(1,FechaActual,1);
                        $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-info"><h4>Realizar Apertura!</h4><p>Realize la apertura del dia.</p></div></div></div>');

                    }else {
                        setApertura(1,FechaActual,1);

                    }


                    break;
                case 2:
                    $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-success"><h4>Cierre Realizado !</h4><p>Movimientos no proceden El día ya fue cerrado.</p></div></div></div>');
                    break;
                case 3:
                    $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-danger"><h4>No se ha realizado el cierre del dia anterior !</h4><p>Realize primeramente el cierre del día anterior, para hacer la apertura de la caja.</p></div></div></div>');

                    break;
            }

        }else{
            MyAlert(response.message);
        }


    }).fail(function (jqhr,textStatus,errno) {

        if(console && console.log){

            if(textStatus == "timeout"){
                MyAlert("Tiempo de espera agotado");
            }else{
                MyAlert("Vista no encontrada");
            }
        }

    })
}

//Apertura Caja
function fnVentaAperturaCaja(opcion) {

    switch (opcion){
        case 1:

            //Validar si existe el cierre del dia anterior
            $.ajax({
                url:"modules/ventas/src/apertura/fn_apertura_caja.php",
                type:"POST",
                data:{opc:opcion},
                dataType:"JSON"
            }).done(function(data){

                if(data.result == "error"){
                    MyAlert(data.mensaje,"alert");
                    $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-warning"><h4>No se ha realizado la apertura !</h4><p>Realize primeramente la apertura del dia para poder realizar las ventas diarias.</p></div></div></div>');
                }

            }).fail(function(jqh,textStatus){
                MyAlert("error al realizar la solicitud" + textStatus,"alert");
            });

            break;
        case 2:
            //realizar apertura de caja
            var SaldoInicial = $("#saldo_inicial").val();
            SaldoInicial = setFormatoMoneda(1,SaldoInicial);

            $.ajax({
                url:"modules/ventas/src/apertura/fn_apertura_caja.php",
                type:"POST",
                data:{opc:opcion,SaldoInicial:SaldoInicial},
                dataType:"JSON"
            }).done(function(data){

                if(data.result == "ok"){
                    gn_menu_principal(9,9);
                }

                if(data.result == "error"){
                    MyAlert(data.mensaje,"alert");
                    $("#form_caja").html('<div class="row"><div class="col-md-12"><div class="callout callout-warning"><h4>No se ha realizado la apertura !</h4><p>Realize primeramente la apertura del dia para poder realizar las ventas diarias.</p></div></div></div>');
                    viewContentVenta = false;
                }

            }).fail(function(jqh,textStatus){
                MyAlert("error al realizar la solicitud" + textStatus,"alert");
            });

            break;
        case 3:
            break;
        default:
            break;

    }

}



function fnVentaTrabajosPendientes(opc) {

    SenderAjax(
      "modules/ventas/views/ventas/",
        "frm_trabajos_pendientes.php",
        null,
        "cashOpen",
        "post",
        {opc:opc}
    );

}

function fnVentaServicios() {


    $.ajax({
        url:"modules/servicios/src/servicios/fn_registrar_recarga.php",
        type:"post",
        dataType:"json",
        beforeSend:function () {
          fnloadSpinner(1);
        },
        data:{
            SysKey:"12345",
            id:"19201012021",
            referencia:"5555555525",
            producto:"TEL200",
            bolsaID:"1"
        },
    }).done(function (response) {

        fnloadSpinner(2);

        if(response.error == 0){
            MyAlert("<span class='text-bold' ><i class='fa fa-check-circle-o text-success'></i> "+response.message+"<br><span class='text-center'>Fecha: "+response.data.Fecha+"<br>Transaccion: "+response.data.TransID+"<br>Folio: "+response.data.Folio+"</span></span>","alert");
        }else{

            MyAlert("<span class='text-bold' ><i class='fa fa-exclamation-triangle'></i>"+response.message+"</span>","alert");

        }

    }).fail(function (jqh,textStatus) {

        fnloadSpinner(2);
        console.log("Error: "+textStatus);
    })

}

function fnVentaAceptarPago(){

    var folio_venta = $("#folio_venta").val(),
        importe_pendiente = setFormatoMoneda(1,$("#textSaldoPendiente").val()),
        importe_pago = setFormatoMoneda(1,$("#textPago").val()),
        importe_total = setFormatoMoneda(1,$("#textImporteTotal").val()),
        tipo_venta = $("#textTipoVenta").val();

    $.ajax(
        {
            url:"modules/ventas/src/ventas/fn_realizar_pago.php",
            type:"post",
            dataType:"json",
            data:{
             folio_venta:folio_venta,
                importe_pendiente:importe_pendiente,
                importe_pago:importe_pago,
                importe_total:importe_total,
                tipo_venta:tipo_venta
            },
            beforeSend:function(){
                $("#btnAceptarPago").attr("disabled",true);
            }
        }
    ).done(function(response){

            //$("#modal_result").text(response.toString());

            switch (response.result){
                case "ok":

                    $("#folio_venta").attr("disabled",true);
                    $("#result_pago").toggleClass("hidden");
                    $("#detalle_pago").toggleClass("hidden");

                    if(response.data.tipo_ticket == 'abono'){
                        $("#row_cambio").toggleClass("hidden");
                    }

                    $("#folio_pago").val(response.data.folio_pago);
                    $("#total_cambio").val(response.data.cambio);
                    $("#total_cambio").addClass("currency");
                    $(".currency").numeric({prefix:'$ ', cents: true});

                    break;
                case "error":
                    MyAlert(response.mensaje,"error");
                    break;
            }


        }).fail(function(jxqh,textStatus){
            MyAlert("Error al llamar el metodo fn_realiza_pago: "+textStatus,"error");
        })

}

function fnVentaCorteDiario(opc){

    switch (opc){
        case 1:
            //Corte Diario - Solo Del dia acutal
            bootbox.confirm({
                title: "Corte diario",
                message: "Se borrara la información si no ha guardado, esta seguro de continuar ?",
                size:"small",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancelar'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Aceptar'
                    }
                },
                callback: function (result) {

                    if(result){

                        SenderAjax(
                            "modules/ventas/src/ventas/",
                            "fn_movimientos_diario.php",
                            null,
                            "form_caja",
                            "POST",
                            "{opc:0}"
                        );

                    }
                }
            });
            break;
        case 2:
            //Corto Diario - por rango de fechas
            bootbox.confirm({
                title: "Corte diario",
                message: "Se borrara la información si no ha guardado, esta seguro de continuar ?",
                size:"small",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancelar'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Aceptar'
                    }
                },
                callback: function (result) {

                    if(result){

                        SenderAjax(
                            "modules/ventas/src/ventas/",
                            "fn_corte_diario.php",
                            null,
                            "form_caja",
                            "POST",
                            "{opc:0}"
                        );

                    }
                }
            });
            break;
    }

}

/**
 * Historial Cliente
 * @param {data}
 */

function fnVentaHistorialCliente(data){

    var opcion = data.opc;

    switch (opcion){

        case 1: //Mostrar formulario para buscar cliente

            SenderAjax(
                "modules/ventas/views/clientes/",
                "frm_cliente.php",
                null,
                "form_caja",
                "post",
                {opc:opcion}
            );

            break;
        case 2: // Realizar la busqueda del cliente

            SenderAjax(
                "modules/ventas/views/clientes/",
                "frm_folios_clientes.php",
                null,
                "lista_folios_cte",
                "post",
                {
                    idcliente:data.idcliente
                }

            );

            break;
        case 3: //Mostrar detalles y pagos del folio de venta seleccionado
            SenderAjax(
                "modules/ventas/views/clientes/",
                "frm_folios_clientes.php",
                null,
                "lista_folios_cte",
                "post",
                {
                    idcliente:data.idcliente
                }

            );
            break;
        case 4: //Mostrar detalles y pagos del folio de venta seleccionado
            SenderAjax(
                "modules/ventas/views/clientes/",
                "frm_detalle_venta_cliente.php",
                null,
                "detalle_venta",
                "post",
                {
                    idcliente:data.idcliente,
                    folio_venta:data.folio_venta
                }

            );
            break;

    }






}

function fnVentaEliminarProducto(idproducto){

    $.ajax({
        url:"modules/ventas/src/ventas/fn_eliminar_producto.php",
        data:{idproducto:idproducto},
        type:"post",
        dataType:"JSON"
    }).done(function(data){

        if(data.result == "ok"){

            fnVentaShowCartProducto(2);

        }else{
            MyAlert(data.mensaje,"alert");
        }

    }).fail(function(jqh,textStatus){
       MyAlert("Error al llamar el metodo delete","alert");
    });

}

function fnVentaTipoPago(valor) {
    switch (valor){
        case '1':
            // Pago en Efectivo
            gPagoEfectivo = true;
            gPagoCombinado = false;
            gPagoTerjeta = false;
            $("#gpoPagoEfectivo").removeClass("hidden");
            $("#gpoPagoTarjeta").addClass("hidden");
            break;
        case '2':
            // Pago en Tarjeta
            gPagoEfectivo = false;
            gPagoCombinado = false;
            gPagoTerjeta = true;
            $("#gpoPagoEfectivo").addClass("hidden");
            $("#gpoPagoTarjeta").removeClass("hidden");
            break;
        case '3':
            // Pago en Combinado
            gPagoEfectivo = false;
            gPagoCombinado = true;
            gPagoTerjeta = false;
            $("#gpoPagoEfectivo").removeClass("hidden");
            $("#gpoPagoTarjeta").removeClass("hidden");
            break;
    }
}

function fnVentaTipoVenta(valor) {


   if(valor == 2){
        $("#txtPagoMinimo").toggleClass("hidden");
       gPagoInicial = true;
   }else{
       $("#txtPagoMinimo").toggleClass("hidden");
       gPagoInicial = false;
   }


}

function fnAgregarTipoDiseno(idDiseno){

    if(idDiseno > 0){
        $.ajax({
            url:"modules/ventas/src/ventas/fnAgregarTipoDiseno.php",
            type:"post",
            dataType:"json",
            data:{idDiseno:idDiseno}
        }).done(function(response){

            console.log(response);

            if(response.result){

                fnVentaShowCartProducto(1);

            }else{
                MyAlert(response.message);
            }

        }).fail(function(jqhR,textStatus,errno){

            if(console && console.log){

                if(textStatus == "timeout"){
                    MyAlert("Tiempo de espera agotado");
                }else{
                    MyAlert("Error al cargar la vista");
                }

            }

        })
    }

}

function fnVentaCobrarVenta(opc) {

    switch (opc){
        //Abrir modal para cobrar
        case 1:

            var idcliente = $('#idcliente').val();
            var tipodiseno = $("#costotrabajo").val();

            if(idcliente == 0){
                MyAlert("Seleccione un cliente, antes de realizar el cobro","alert");
            }else if(tipodiseno == 0) {
                MyAlert("Seleccione el tipo de diseño","alert");
            }else{

                SenderAjax(
                    "modules/ventas/views/ventas/",
                    "frm_caja_ventas.php",
                    null,
                    "show_modal",
                    "post",
                    {opc: opc}
                );
            }
            break;
        case 2:
            //Realizar Cobro de la venta
            var idcliente = $("#idcliente").val(),
                TipoVenta = $("#opcTipoVenta").val(),
                pago_efectivo = $("#idpago_efectivo").val(),
                pago_tarjeta = $("#idpago_tarjeta").val(),
                total_venta = $("#idtotal_venta").val();

            var pago_inicial = $("#idtotal_pago_inicial").text(),
                descripcion_general = $("#descripcion_general").val();

            var FechaEntrega = $("#FechaEntrega").val(),
                HoraEntrega = $("#HoraEntrega").val(),
                MinutoEntrega = $("#MinutoEntrega").val(),
                FormatoHora = $("#FormatoHora").val();


            pago_efectivo = parseFloat(setFormatoMoneda(1,pago_efectivo));
            pago_tarjeta = parseFloat(setFormatoMoneda(1,pago_tarjeta));
            total_venta = parseFloat(setFormatoMoneda(1,total_venta));
            pago_inicial = parseFloat(setFormatoMoneda(1,pago_inicial));

            if(FechaEntrega == ""){
                MyAlert("Seleccione la fecha de entrega");
            }else{

                if(TipoVenta == 2){

                    //Venta a credito

                    if(pago_efectivo < pago_inicial){
                        MyAlert("El pago minimo debe ser de : " + pago_inicial ,"alert");
                    }else{

                        $("#btnRealizarCobro").attr('disabled',true);

                        SenderAjax(
                            "modules/ventas/src/ventas/",
                            "fn_realizar_cobro.php",
                            null,
                            "modal_result",
                            "post",
                            {
                                tipo_venta:2,
                                tipo_pago:1,
                                total_venta:total_venta,
                                pago_inicial:pago_inicial,
                                pago_efectivo:pago_efectivo,
                                pago_tarjeta:0,
                                descripcion_general:descripcion_general,
                                idcliente:idcliente,
                                FechaEntrega:FechaEntrega,
                                HoraEntrega:HoraEntrega,
                                MinutoEntrega:MinutoEntrega,
                                FormatoHora:FormatoHora
                            }
                        )
                    }
                }else{
                    //Venta de contado

                    if(pago_efectivo < total_venta){
                        MyAlert("El pago es inferior al total de la venta","alert");
                    }else{

                        SenderAjax(
                            "modules/ventas/src/ventas/",
                            "fn_realizar_cobro.php",
                            null,
                            "modal_result",
                            "post",
                            {
                                tipo_venta:1,
                                tipo_pago:1,
                                total_venta:total_venta,
                                pago_inicial:0,
                                pago_efectivo:pago_efectivo,
                                pago_tarjeta:0,
                                descripcion_general:descripcion_general,
                                idcliente:idcliente,
                                FechaEntrega:FechaEntrega,
                                HoraEntrega:HoraEntrega,
                                MinutoEntrega:MinutoEntrega,
                                FormatoHora:FormatoHora
                            }
                        )
                    }
                }

            }


            break;
        default:
            MyAlert("Processnado : 31212012","alert");
            break;
    }


}

function fnVentaAgregarCombo(opc){

    var descripcion = $("#descripcion_combo").val(),
        idcombo = setFormatoColor(1,$("#idcombo").val()),
        send = false;

    if($.trim(descripcion) == ""){
        bootbox.confirm({
            title: "Agregar Combo",
            message: "No agrego ninguna descripción, está seguro de continuar ?",
            size:"small",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancelar'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Aceptar'
                }
            },
            callback: function (result) {

                if(result){

                    //SI Continuar si descripcion
                    send = true;

                }
            }
        });
    }else{

        send = true;

    }

    if(send){

        $.ajax({
            url:"modules/ventas/src/combos/fn_agregar_combo.php",
            type:"post",
            dataType:"json",
            data:{idcombo:idcombo,descripcion:descripcion}
        }).done(function (response) {

            for (l in response.data.ART) {

                $.ajax({
                    url:"modules/ventas/src/ventas/fn_agregar_producto.php",
                    type:"post",
                    data:{
                        producto:response.data.ART[l].idproducto    ,
                        tipo_producto:response.data.ART[l].tipo_producto,
                        descripcion:response.data.ART[l].descripcion,
                        nombre_producto:response.data.ART[l].nombre_producto,
                        idproducto:response.data.ART[l].idproducto,
                        idcantidad:1
                    },
                    dataType:"JSON"
                }).done(function(response2){

                    if(response2.result == "ok"){

                        fnVentaShowCartProducto(1);

                    }else if(response2.result == "error"){
                        MyAlert(response2.mensaje,"alert");
                    }

                }).fail(function(jqXHR,textStatus,errorThrown){

                    console.log(textStatus + errorThrown);

                });

                //data.libros.lib[l].autores[0]
            }

            for (l in response.data.MAT) {

                $.ajax({
                    url:"modules/ventas/src/ventas/fn_agregar_producto.php",
                    type:"post",
                    data:{
                        producto:response.data.MAT[l].idproducto    ,
                        tipo_producto:response.data.MAT[l].tipo_producto,
                        descripcion:response.data.MAT[l].descripcion,
                        nombre_producto:response.data.MAT[l].nombre_producto,
                        idproducto:response.data.MAT[l].idproducto,
                        idcantidad:1
                    },
                    dataType:"JSON"
                }).done(function(response2){

                    if(response2.result == "ok"){

                        fnVentaShowCartProducto(1);

                    }else if(response2.result == "error"){
                        MyAlert(response2.mensaje,"alert");
                    }

                }).fail(function(jqXHR,textStatus,errorThrown){

                    console.log(textStatus + errorThrown);

                });

                //data.libros.lib[l].autores[0]
            }


        }).fail(function (jqh,textStatus) {
            MyAlert("error al registrar el combo"+textStatus,"alert");
        })
    }
}

function fnVentaShowCartProducto(opc) {

    switch (opc){

        case 1:
            SenderAjax(
              "modules/ventas/src/ventas/",
               "fn_mostrar_productos.php",
                null,
                "list_cart_ventas",
                "post",
                {opc:opc}
            );
            getMessage("Producto agregado correctamente",'Venta de producto','success',3000);
            break;
        case 2:
            SenderAjax(
                "modules/ventas/src/ventas/",
                "fn_mostrar_productos.php",
                null,
                "list_cart_ventas",
                "post",
                {opc:opc}
            );
            getMessage("Producto eliminado correctamente",'Venta de producto','error',3000);
            break;
    }

}

function fnVentaAddCartMateriales(){

    var producto = $("#material").val(),
        idproducto = setFormatoColor(1,producto),
        nombre_producto = setFormatoColor(2,producto),
        idcantidad = $("#txtCantidad").val(),
        tipo_producto = "MAT";

    if(idcantidad == 100){idcantidad = '1';}else{idcantidad = '0.'+idcantidad;}

    if($.trim(producto) == ""){
        MyAlert("No se encontro ningun producto seleccionado","alert");
    }else if(isNaN(idcantidad)){
        MyAlert("La cantidad seleccionada es incorrecta","alert");
    }else if(idcantidad <= 0.0){
        MyAlert("La cantidad seleccionada es incorrecta","alert");
    }else{

        $.ajax({
            url:"modules/ventas/src/ventas/fn_agregar_producto.php",
            type:"post",
            data:{
                producto:producto,
                nombre_producto:nombre_producto,idproducto:idproducto,idcantidad:idcantidad,tipo_producto:tipo_producto},
            dataType:"JSON"
        }).done(function(data){

            if(data.result == "ok"){

                fnVentaShowCartProducto(1);

            }else if(data.result == "error"){
                MyAlert(data.mensaje,"alert");
            }

        }).fail(function(jqXHR,textStatus,errorThrown){

            console.log(textStatus + errorThrown);

        });


    }

}

function fnVentaAddCartProducto(opc,param) {

    var producto = $("#producto").val(),
        idproducto = setFormatoColor(1,producto),
        nombre_producto = setFormatoColor(2,producto),
        idcantidad = $("#txtCantidad").val(),
        descripcion_por_producto = $("#descripcion_por_producto").val(),
        tipo_producto = "ART";

    if($.trim(producto) == ""){
        MyAlert("No se encontro ningun producto seleccionado","alert");
    }else if(isNaN(idcantidad)){
        MyAlert("La cantidad seleccionada es incorrecta","alert");
    }else if(idcantidad <= 0){
        MyAlert("La cantidad seleccionada es incorrecta","alert");
    }else{

        $.ajax({
            url:"modules/ventas/src/ventas/fn_agregar_producto.php",
            type:"post",
            data:{
                opc:opc,
                producto:producto,
                tipo_producto:tipo_producto,
                descripcion:descripcion_por_producto,
                nombre_producto:nombre_producto,
                idproducto:idproducto,
                idcantidad:idcantidad
            },
            dataType:"JSON"
        }).done(function(response){

            console.log(response);


            if(response.result == "ok"){

                fnVentaShowCartProducto(1);
                $("#producto").val("");
                $("#txtCantidad").val(1);

            }else if(response.result == "error"){

                if(response.result.data.opc == 2 ){
                    MyAlert("Prueba.... "+response.mensaje,"alert");
                }else{
                    MyAlert(response.mensaje,"alert");
                }

            }

        }).fail(function(jqXHR,textStatus,errorThrown){

            console.log(textStatus + errorThrown);

        });
    }
}

function fnVentaComboSeleccionado(idcombo,nombrecombo) {




}

function fnVentaOpenModal(data) {

    var opcion = data.opc;


    switch (opcion){
        case 1:
                SenderAjax(
                    "modules/ventas/views/ventas/",
                    "frm_modal_productos.php",
                    null,"show_modal","post",{

                    }
                );
            break;
        case 2:

            var textSearch = $("#textSearch").val();

            if(textSearch.length >= 3){
                $.ajax({
                    url:"modules/ventas/src/ventas/fn_buscar_producto.php",
                    type:"post",
                    data:{opc:opcion,textSearch:textSearch},
                    beforeSend:function(){
                        $("#lista_busqueda_producto").html("Espere un momento.....");
                    }
                }).done(function(data){

                    $("#lista_busqueda_producto").html(data);

                }).fail(function(jqr,textstatus){

                });
            }

            break;
        case 3:
            //Abrir modal buscar material
            SenderAjax(
                "modules/ventas/views/ventas/",
                "frm_modal_materiales.php",
                null,"show_modal","post",{

                }
            );
            break;
        case 4:
            //Buscar MAterial
            var textSearch = $("#txtNombreMaterial").val();

            if(textSearch.length >= 3){
                $.ajax({
                    url:"modules/ventas/views/ventas/frm_modal_colores_materiales.php",
                    type:"post",
                    data:{opc:opcion,textSearch:textSearch},
                    beforeSend:function(){
                        $("#lista_materiales").html("<div class='col-md-12'>Espere un momento.....</div>");
                    }
                }).done(function(data){

                    $("#lista_materiales").html(data);

                }).fail(function(jqr,textstatus){

                });
            }
            break;
        //Modal para apertura de caja
        case 5:
            SenderAjax(
                "modules/ventas/views/apertura/",
                "frm_apertura.php",
                null,"show_modal","post",{

                }
            );
            break;
        //Modal para cierre de caja
        case 6:
            SenderAjax(
                "modules/ventas/views/cierre/",
                "frm_cierre.php",
                null,"show_modal","post",{

                }
            );
            break;
        //Modal para arqueo de caja
        case 7:
            SenderAjax(
                "modules/ventas/views/arqueo/",
                "frm_arqueo.php",
                null,"show_modal","post",{

                }
            );
            break;
        case 8:
            SenderAjax(
                "modules/ventas/views/ventas/",
                "frm_productos_virtuales.php",
                null,"show_modal","post",{

                }
            );
            break;
        case 9:

            var textSearch = $("#textSearchCombo").val();

            $.ajax({
                url: "modules/ventas/src/ventas/fn_buscar_producto_virtual.php",
                type:"post",
                data:{opc:opcion,textSearch:textSearch},
                beforeSend:function(){
                    $("#lista_combos").html("<div class='col-md-12'>Espere un momento.....</div>");
                }
            }).done(function(data){

                $("#lista_combos").html(data);

            }).fail(function(jqr,textstatus){

            });

            break;
    }
}