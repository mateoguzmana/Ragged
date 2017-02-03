var jsonfilternew = "";

function Companiaslc() {
    $('#processing-modal').modal('show');
    var Companiaselect = $('#Compania').val();
    if (jsonfilternew == "") {
        jsonfilternew = JSON.parse(localStorage.getItem('jsonfilter'));
    }
    $.each(jsonfilternew, function (key, storagemethod) {
        if ((key != 'Compania') && (key != 'Estado') && (key != 'Customers')) {
            $('#' + key.replace(" ", "")).empty();
            $('.selectpicker').selectpicker('refresh');
            $.each(storagemethod, function (k, data) {
                if (Companiaselect != null) {
                    if (key != 'CuentaCliente') {
                        $.each(Companiaselect, function (index, element) {
                            if (element == data.companyid) {
                                $('#' + key.replace(" ", "")).append('<option value="' + data.id + '">' + data.description + '</option>');
                                $('.selectpicker').selectpicker('refresh');
                            }
                        });
                    }
                } else {
                    $('#' + key.replace(" ", "")).append('<option value="' + data.id + '">' + data.description + '</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            });
            if (Companiaselect != null) {
                if (key.replace(" ", "") == 'CuentaCliente') {
                    $('#CuentaCliente').empty();
                    $('.selectpicker').selectpicker('refresh');
                    $.each(Companiaselect, function (index, element) {
                        $.each(jsonfilternew.Customers, function (ind, data) {
                            if (element == data.companyid) {
                                $('#CuentaCliente').append('<option value="' + data.id + '">' + data.description + '</option>');
                                $('.selectpicker').selectpicker('refresh');
                            }
                        });
                    });
                }
            }
        }
    });
    $('#processing-modal').modal('hide');
}

function SearchOrder() {
    if ($('#fechaini').val() > $('#fechafin').val()) {
        sweetAlert("", "La fecha inicial no puede ser mayor a la fecha final", "error");
        return;
    }
    var userquery = {};
    var Companias = "";
    var Companiaselect = $('#Compania').val();
    if (Companiaselect != null) {
        $.each(Companiaselect, function (index, value) {
            Companias += value + ",";
        });
        Companias = Companias.slice(0, -1);
        userquery.Companias = Companias;
    }
    var NumeroOrdenes = "";
    var NumeroOrdenesselect = $('#NumeroPedido').val();
    if (NumeroOrdenesselect != null) {
        $.each(NumeroOrdenesselect, function (index, value) {
            NumeroOrdenes += value + ",";
        });
        NumeroOrdenes = NumeroOrdenes.slice(0, -1);
        userquery.NumeroOrdenes = NumeroOrdenes;
    }
    var CuentaClientes = "";
    var CuentaClientesselect = $('#CuentaCliente').val();
    if (CuentaClientesselect != null) {
        $.each(CuentaClientesselect, function (index, value) {
            CuentaClientes += value + ",";
        });
        CuentaClientes = CuentaClientes.slice(0, -1);
        userquery.CuentaClientes = CuentaClientes;
    }
    var Vendedores = "";
    var Vendedorselect = $('#Vendedor').val();
    if (Vendedorselect != null) {
        $.each(Vendedorselect, function (index, value) {
            Vendedores += value + ",";
        });
        Vendedores = Vendedores.slice(0, -1);
        userquery.Vendedores = Vendedores;
    }
    var Estados = "";
    var Estadoselect = $('#Estado').val();
    if (Estadoselect != null) {
        $.each(Estadoselect, function (index, value) {
            Estados += value + ",";
        });
        Estados = Estados.slice(0, -1);
        userquery.Estados = Estados;
    }
    var oqrender = "";
    userquery.Fechaini = $('#fechaini').val();
    userquery.Fechafin = $('#fechafin').val();
    userquery.user = localStorage.getItem('nickname');
    $('#processing-modal').modal('show');
    $.ajax({
        data: {
            'userquery': userquery
        },
        type: 'post',
        url: 'index.php?r=OrdersQuery/QueryOrders',
        success: function (response) {
            $('#processing-modal').modal('hide');
            var OrdersQuery = JSON.parse(response);
            jsonfilternew = OrdersQuery.storagemethod;
            if (OrdersQuery.length == 0) {
                return;
            }
            oqrender = '<div class="content table-responsive"><table class="table table-hover table-striped" id="tblOrders"><thead><tr>';
            $.each(OrdersQuery.permissions, function (key, data) {
                if (data.idoption == 61) {
                    oqrender += "<th>EDITAR</th>";
                }
            });
            oqrender += OrdersQuery.Orders.columns;
            $.each(OrdersQuery.permissions, function (key, data) {
                if (data.idoption == 62) {
                    oqrender += "<th>DETALLES</th>";
                } else if (data.idoption == 63) {
                    oqrender += "<th>VALIDACION REGLAS DE NEGOCIO</th>";
                } else if (data.idoption == 64) {
                    oqrender += "<th>ENVIAR SIN REGLAS DE NEGOCIO</th>";
                }
            });
            oqrender += '</tr></thead><tbody>';
            $.each(OrdersQuery.Orders.Order, function (key, data) {
                oqrender += '<tr>';
                var disable = "";
                $.each(OrdersQuery.permissions, function (ke, dataperm) {
                    if (dataperm.idoption == 61) {
                        var ban = false;
                        $.each(jsonfilternew['Numero Pedido'], function (key2, datafilter) {
                            //alert("(" + datafilter.stateid + "==" + 1 + ") && (" + datafilter.typecustomerid + "==" + 1 + ") && (" + datafilter.id + "==" +data.orderid+ ")");
                            if ((datafilter.stateid == 1) && (datafilter.typecustomerid == 2) && (datafilter.id == data.orderid)) {
                                ban = true;
                                return;
                            }
                        });
                        disable = ban ? '' : 'disabled';
                        oqrender += "<td><a onclick='EditOrder(" + data.orderid + ")' class='" + dataperm.class + "'" + disable + "><span class='" + dataperm.icon + "'></span></a></td>";
                    }
                });
                oqrender += data.info;
                $.each(OrdersQuery.permissions, function (k, dataperm2) {
                    if (dataperm2.idoption == 62) {
                        oqrender += "<td><button onclick='ViewDetails(" + data.orderid + ")' type='button' class='btn btn-primary btn-xs'>Ver detalle</button></td>";
                    } else if (dataperm2.idoption == 63) {
                        oqrender += "<td><a onclick='BusinessRulesValidation(" + data.orderid + ")' class='" + dataperm2.class + "'><span class='" + dataperm2.icon + "'></span></a></td>";
                    } else if (dataperm2.idoption == 64) {
                        oqrender += "<td><button onclick='SendOrder(" + data.orderid + ")' type='button' class='btn btn-primary btn-xs'>Enviar</button></td>";
                    }
                });
                oqrender += '</tr>';
            });
            $('#rendertable').html(oqrender);
        }
    }).complete(function () {
        $('#tblOrders').DataTable({
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"]
            ],
            iDisplayLength: 10,
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ning?n dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "?ltimo",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    });
}

var customerid;
var addressid;
var jsonaddress = "";
var companyeditid = "";

function EditOrder(id) {
    if (jsonfilternew == "") {
        jsonfilternew = JSON.parse(localStorage.getItem('jsonfilter'));
    }
    var ban = false;
    $.each(jsonfilternew['Numero Pedido'], function (key, data) {
        if ((data.stateid == 1) && (data.typecustomerid == 2) && (id == data.id)) {
            ban = true;
        }
    });
    if (!ban) {
        return false;
    }
    companyeditid = id;
    customerid = 0;
    addressid = 0;
    $('#processing-modal').modal('show');
    /*$.ajax({
     url: 'index.php?r=OrdersQuery/EditOrdersQuery',
     success: function (response) {
     $('.bodyrender').html(response);
     }
     }).complete(function () {*/
    var fillselect = {};
    fillselect.order = id;
    //fillselect.user = localStorage.getItem('nickname');
    fillselect.data = [];
    
    $.ajax({
        data: {
            id: fillselect
        },
        type: 'post',
        url: 'index.php?r=OrdersQuery/DataforEditOrders',
        success: function (response) {
            //alert(JSON.stringify(JSON.parse(response)));
            var orderviewrender = '';
            var headerth = "";
            var onchangeslc = "";
            var userviewrendercomplete = '<div class="content table-responsive table-full-width"><table class="table table-hover table-striped" width="100%" id="tblOrdersEdit"><thead><tr>';
            var editcompanyview = JSON.parse(response);
            usereditviewjs = editcompanyview;
            jsonaddress = editcompanyview.storagemethod.address;
            var observations = "";
            for (var r = 0; r < editcompanyview.data.length; r++) {
                orderviewrender += '<tr>';
                for (var i = 0; i < editcompanyview.data[r].length; i++) {
                    if (editcompanyview.data[r][i].columnname != 'observations') {
                        headerth += '<th>' + editcompanyview.data[r][i].columndescription + '</th>';
                    }
                    switch (editcompanyview.data[r][i].inputtype) {
                        case "text":
                            {
                                if (editcompanyview.data[r][i].columnname != 'observations') {
                                    orderviewrender += '<td>' + editcompanyview.data[r][i].value + '</td>';
                                } else {
                                    if (editcompanyview.data[r][i].value != undefined) {
                                        observations = editcompanyview.data[r][i].value;
                                    }
                                }
                            }
                            break;
                        case "select":
                            {
                                if (editcompanyview.data[r][i].columnname == 'idcustomer') {
                                    var customer = $.grep(editcompanyview.storagemethod[editcompanyview.data[r][i].columnname], function (element, index) {
                                        return element.id == editcompanyview.data[r][i].value;
                                    });
                                    if (typeof customer[0] === "undefined") {

                                    } else {
                                        customerid = customer[0].id;
                                    }
                                }
                                if (editcompanyview.data[r][i].columnname == 'address') {
                                    var address = $.grep(editcompanyview.storagemethod[editcompanyview.data[r][i].columnname], function (element, index) {
                                        if (element.description == editcompanyview.data[r][i].value) {
                                            return element.id;
                                        }
                                    });
                                    if (typeof address[0] === "undefined") {

                                    } else {
                                        addressid = address[0].id;
                                    }
                                }
                                if (editcompanyview.data[r][i].columnname == 'idcustomer') {
                                    onchangeslc = "ChangeCustomer()";
                                } else {
                                    onchangeslc = "";
                                }
                                orderviewrender += '<td><select class="selectpicker" id="' + editcompanyview.data[r][i].columnname + '" title="Seleccione un tipo.." onchange="' + onchangeslc + '" data-live-search="true" data-actions-box="true">';
                                for (var j = 0; j < editcompanyview.storagemethod[editcompanyview.data[r][i].columnname].length; j++) {
                                    if (editcompanyview.data[r][i].columnname == 'address') {
                                        if (customerid == editcompanyview.storagemethod[editcompanyview.data[r][i].columnname][j].customerid) {
                                            orderviewrender += '<option value="' + editcompanyview.storagemethod[editcompanyview.data[r][i].columnname][j].id + '">' + editcompanyview.storagemethod[editcompanyview.data[r][i].columnname][j].description + '</option>';
                                        }
                                    } else {
                                        orderviewrender += '<option value="' + editcompanyview.storagemethod[editcompanyview.data[r][i].columnname][j].id + '">' + editcompanyview.storagemethod[editcompanyview.data[r][i].columnname][j].description + '</option>';
                                    }
                                }
                                orderviewrender += '</select></td>';
                                fillselect.data.push({id: editcompanyview.data[r][i].columnname, value: editcompanyview.data[r][i].value});
                            }
                            break;
                        default:
                            break;
                    }
                }
                orderviewrender += '</tr>';
            }
            
            userviewrendercomplete = userviewrendercomplete + headerth + '</tr></thead><tbody>' + orderviewrender + '</tbody></table>';
            var half='<div class="content"><div class="container-fluid"><div class="row"><div class="col-md-12"><div class="card"><div class="content">';
            half+=userviewrendercomplete+'<p>Observaciones: ' + observations + '</p>';
            /*$('.editorderdata').html(userviewrendercomplete);
            $('.editorderdata').append('<p>Observaciones: ' + observations + '</p>');*/
            //$('.observation').html('Observaciones: ' + observations);
            //ordersdetails
            var newTable = "";
            var headerthod = "";
            var orderviewrenderdetails = "";
            newTable += "<table class='display dataTable bordered centered tblOrder'>";
            newTable += "<thead style='background-color:#EFEFEF'><tr>";
            var totales = '<tr class="total"><td>Totales</td>';
            for (var r = 0; r < editcompanyview.dataod.length; r++) {
                orderviewrenderdetails += '<tr>';
                for (var i = 0; i < editcompanyview.dataodcolumns.length; i++) {
                    if (r == 0) {
                        headerthod += '<th>' + editcompanyview.dataodcolumns[i].columndescription + '</th>';
                        if (i > 0) {
                            if ((Object.keys(editcompanyview.totals[0]).length) < (editcompanyview.dataodcolumns.length - i)) {
                                totales += '<td></td>'
                            } else {
                                totales += '<td>' + editcompanyview.totals[0][editcompanyview.dataodcolumns[i].columnname] + '</td>'
                            }
                        }
                    }
                    orderviewrenderdetails += '<td>' + editcompanyview.dataod[r][editcompanyview.dataodcolumns[i].columnname] + '</td>';
                }
                orderviewrenderdetails += '</tr>';
            }
            totales += '</tr>';
            var endhalf='<button type="button" onclick="SaveOrderEdit()" class="btn btn-info btn-fill pull-right"><span class="glyphicon glyphicon-saved"></span>Guardar Pedido</button><div class="clearfix"></div></div></form></div></div></div></div></div>'
            endhalf += newTable + headerthod + '</tr></thead><tbody>' + orderviewrenderdetails + totales + '</tbody></table>';
            $('.modal-body3').html('');            
            $('.modal-body3').html(half+endhalf);
            
            //$('#processing-modal').modal('hide');
            $("#myModal3").modal('show');
            $('#myModal3').css('overflow', 'auto');
            //$('.newTable').html(userviewrendercomplete);
        }
    }).complete(function () {
        $('.selectpicker').selectpicker('refresh');
        for (var i = 0; i < fillselect.data.length; i++) {
            $('#' + fillselect.data[i].id).val(fillselect.data[i].value);
        }
        $('#idcustomer').val(customerid);
        $('#address').val(addressid);
        $('.selectpicker').selectpicker('refresh');
        $('#processing-modal').modal('hide');
    });
    //});
}

function SaveOrderEdit() {
    if (($('#idcustomer').val() == customerid) && ($('#address').val() == addressid)) {
        sweetAlert("", "No ha realizado ningun cambio", "error");
        return;
    }
    if ($('#idcustomer').val() == "") {
        sweetAlert("", "No ha seleccionado ningun cliente", "error");
        return;
    }
    if ($('#address').val() == "") {
        sweetAlert("", "No ha seleccionado ninguna direccion", "error");
        return;
    }
    var fillselect = {};
    fillselect.order = companyeditid;
    fillselect.cust = $('#idcustomer').val();
    fillselect.addr = $('#address option:selected').text();
    $.ajax({
        data: {
            'orderdetail': fillselect
        },
        type: 'post',
        url: 'index.php?r=OrdersQuery/ChangeOrderDetails',
        success: function (response) {
            if (response == "OK") {
                sweetAlert("", "El cambio se ha realizado correctamente", "success");
                $('#itemlstOrdersQuery').trigger('click');
            } else {
                sweetAlert("", "Hubo un error guardando la informacion", "error");
            }
        }
    });
}

function ChangeCustomer() {
    var customeridslc = $('#idcustomer').val();
    $('#processing-modal').modal('show');
    //alert(JSON.stringify(response));
    $('#address').empty();
    $('.selectpicker').selectpicker('refresh');
    $.each(jsonaddress, function (key, data) {
        if (customeridslc == data.customerid) {
            $('#address').append('<option value="' + data.id + '">' + data.description + '</option>');
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $('#processing-modal').modal('hide');
}

function ViewDetails(id) {
    companyeditid = id;
    customerid = 0;
    addressid = 0;
    //$('#processing-modal').modal('show');
    $('.modal-body-detail').empty();
    var fillselect = {};
    fillselect.order = id;
    $.ajax({
        data: {
            id: fillselect
        },
        type: 'post',
        url: 'index.php?r=OrdersQuery/DataforEditOrders',
        success: function (response) {
            //alert(JSON.stringify(JSON.parse(response)));
            var orderviewrender = '';
            var headerth = "";
            var userviewrendercomplete = '<div class="content table-responsive"><table class="table table-hover table-striped" width="100%"><thead><tr>';
            var editcompanyview = JSON.parse(response);
            usereditviewjs = editcompanyview;
            jsonaddress = editcompanyview.storagemethod.address;
            var observations = "";
            for (var r = 0; r < editcompanyview.data.length; r++) {
                orderviewrender += '<tr>';
                for (var i = 0; i < editcompanyview.data[r].length; i++) {
                    if (editcompanyview.data[r][i].columnname != 'observations') {
                        headerth += '<th>' + editcompanyview.data[r][i].columndescription + '</th>';
                    }
                    switch (editcompanyview.data[r][i].inputtype) {
                        case "text":
                            {
                                if (editcompanyview.data[r][i].columnname != 'observations') {
                                    orderviewrender += '<td>' + editcompanyview.data[r][i].value + '</td>';
                                } else {
                                    if (editcompanyview.data[r][i].value != undefined) {
                                        observations = editcompanyview.data[r][i].value;
                                    }
                                }
                            }
                            break;
                        case "select":
                            {
                                if (editcompanyview.data[r][i].columnname == 'idcustomer') {
                                    var customer = $.grep(editcompanyview.storagemethod[editcompanyview.data[r][i].columnname], function (element, index) {
                                        if (element.id == editcompanyview.data[r][i].value) {
                                            orderviewrender += '<td>' + element.description + '</td>';
                                        }
                                    });
                                } else if (editcompanyview.data[r][i].columnname == 'address') {
                                    orderviewrender += '<td>' + editcompanyview.data[r][i].value + '</td>';
                                }
                            }
                            break;
                        default:
                            break;
                    }
                }
                orderviewrender += '</tr>';
            }
            userviewrendercomplete = userviewrendercomplete + headerth + '</tr></thead><tbody>' + orderviewrender + '</tbody></table>';
            $('.modal-body-detail').html(userviewrendercomplete);
            $('.modal-body-detail').append('<p>Observaciones: ' + observations + '</p>')
            //$('.observation').html('Observaciones: ' + observations);
            //ordersdetails
            var newTable = "";
            var headerthod = "";
            var orderviewrenderdetails = "";
            newTable += "<table class='display dataTable bordered centered'>";
            newTable += "<thead style='background-color:#EFEFEF'><tr>";
            var totales = '<tr class="total"><td>Totales</td>';
            for (var r = 0; r < editcompanyview.dataod.length; r++) {
                orderviewrenderdetails += '<tr>';
                for (var i = 0; i < editcompanyview.dataodcolumns.length; i++) {
                    if (r == 0) {
                        headerthod += '<th>' + editcompanyview.dataodcolumns[i].columndescription + '</th>';
                        if (i > 0) {
                            if ((Object.keys(editcompanyview.totals[0]).length) < (editcompanyview.dataodcolumns.length - i)) {
                                totales += '<td></td>'
                            } else {
                                totales += '<td>' + editcompanyview.totals[0][editcompanyview.dataodcolumns[i].columnname] + '</td>'
                            }
                        }
                    }
                    orderviewrenderdetails += '<td>' + editcompanyview.dataod[r][editcompanyview.dataodcolumns[i].columnname] + '</td>';
                }
                orderviewrenderdetails += '</tr>';
            }
            totales += '</tr>';
            userviewrendercomplete = newTable + headerthod + '</tr></thead><tbody>' + orderviewrenderdetails + totales + '</tbody></table>';
            $('.modal-body-detail').append(userviewrendercomplete);
        }
    });
    //$('#processing-modal').modal('hide');
    $("#myModalDetail").modal('show');
    $('#myModalDetail').css('overflow', 'auto');
}

function BusinessRulesValidation(id) {
    $('.modal-body2').html('');
    var fillselect = {};
    fillselect.order = id;
    $.ajax({
        data: {
            order: fillselect
        },
        type: 'post',
        url: 'index.php?r=OrdersQuery/QueryBusinessRulesValidation',
        success: function (response) {
            //alert(JSON.stringify(JSON.parse(response)));
            var BusinessRulesVal = JSON.parse(response);
            var userviewrendercomplete = '<div class="content table-responsive"><table class="table table-hover table-striped"><thead><tr>';
            userviewrendercomplete += '<th class="text-center">Numero</th>' + BusinessRulesVal.BusRulVals.columns + '</tr></thead><tbody>' + BusinessRulesVal.BusRulVals.BusRulVal + '</tbody></table></div>';
            //alert(userviewrendercomplete);
            $('.modal-body2').html(userviewrendercomplete);
        }
    });
    //$('#processing-modal').modal('hide');
    $("#myModal2").modal('show');
    $('#myModal2').css('overflow', 'auto');
}

function ChangeValidationBusinessRule(id) {
    /*var fillselect = {};
     fillselect.VBRid = id;
     $.ajax({
     data: {
     VBR: fillselect
     },
     type: 'post',
     url: 'index.php?r=OrdersQuery/ChangeValidationBusinessRule',
     success: function (response) {
     if (response == "OK") {
     $("#myModal2").modal('hide');
     //setTimeout(function(){ BusinessRulesValidation(id); }, 3000);
     //BusinessRulesValidation(id);
     } else{
     sweetAlert("", "Hubo un error guardando la informacion", "error");
     }
     }
     });*/
    //$('#processing-modal').modal('hide');
    /*$("#myModal2").modal('show');
     $('#myModal2').css('overflow', 'auto');
     $("#myModal2").modal('hide');
     
     BusinessRulesValidation(id);*/
}