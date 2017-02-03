/*
 * Created By Activity Technology S.A.S.
 */

function displayOrder(Order)
{
    var Order = JSON.parse(Order);
    if (JSON.stringify(Order) !== '[]')
    {
        var Id = 0;
        var tablaHTML = "";
        var newTable = "";
        var summary = "";
        var observationButton = '<div class=""><button id="btnobservation" type="button" class="btn btn-warning width100" >Observaci&#243;n</button></div>';
        var finishOrderButton = '<div class="col-md-12"><button id="btnfinishorder" type="button" class="btn btn-info btn-fill pull-right"><span class="glyphicon glyphicon-save"></span>Finalizar pedido</button></div>';
        $.each(Order.orderTempSummary, function (key, val) {
            summary = summary + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            $.each(val, function (ky, vl) {
                summary = summary + "<div style='font-weight: bold;' class='col-md-3'>" + ky + "</div>";
                summary = summary + "<div class='col-md-8'>" + vl + "</div>";
            });
            return false;
        });
        $('.orderDetail-header').html(summary);
        newTable = "<div class='newTable'>";
        newTable = newTable + "<table class='display dataTable bordered centered tblOrder' width='100%'>";
        newTable = newTable + "<thead style='background-color:#EFEFEF'><tr>";
        var header = "";
        $.each(Order.orderTemp, function (key, val) {
            $.each(val, function (ky, vl) {
                header = header + "<th>" + ky + "</th>";
            });
            return false;
        });
        header = header + "<th></th>";
        var body = "";
        body = body + "</tr></thead>";
        body = body + "<tbody>";
        $.each(Order.orderTemp, function (key, val) {
            tablaHTML = tablaHTML + newTable;
            tablaHTML = tablaHTML + header;
            tablaHTML = tablaHTML + body;
            tablaHTML = tablaHTML + '<tr>';
            $.each(val, function (ky, vl) {
                if (ky == "Id")
                    Id = vl;
                tablaHTML = tablaHTML + '<td>' + vl + '</td>';
            });
            tablaHTML = tablaHTML + '<td data-observationbutton="' + Id + '">' + observationButton + '</td>';
            tablaHTML = tablaHTML + "</tr>";
            tablaHTML = tablaHTML + "<table  class='display dataTable bordered centered tblOrderDetail' width='100%'>";
            tablaHTML = tablaHTML + "<thead><tr>";
            $.each(Order.orderDetailTemp, function (keyDetail, valDetail) {
                $.each(valDetail, function (kyDetail, vlDetail) {
                    tablaHTML = tablaHTML + "<th>" + kyDetail + "</th>";
                });
                return false;
            });
            tablaHTML = tablaHTML + "</tr></thead>";
            tablaHTML = tablaHTML + "<tbody>";
            $.each(Order.orderDetailTemp, function (keyDetail, valDetail) {
                if (valDetail["Id Pedido"] == Id)
                {
                    tablaHTML = tablaHTML + '<tr>';
                    $.each(valDetail, function (kyDetail, vlDetail) {
                        tablaHTML = tablaHTML + '<td>' + vlDetail + '</td>';
                    });
                    tablaHTML = tablaHTML + "</tr>";
                }
            });
            $.each(Order.orderTempTotals, function (keyTotal, valTotal) {
                if (valTotal.Id == Id)
                {
                    tablaHTML = tablaHTML + '<tr class="total">';
                    var hideId = true;
                    $.each(valTotal, function (kyTotal, vlTotal) {
                        if (!hideId)
                            tablaHTML = tablaHTML + '<th>' + vlTotal + '</th>';
                        else
                        {
                            tablaHTML = tablaHTML + '<th></th>'; //Columna Vacía
                            tablaHTML = tablaHTML + '<th></th>';
                            tablaHTML = tablaHTML + '<th>Totales</th>';
                            tablaHTML = tablaHTML + '<th></th>';
                            tablaHTML = tablaHTML + '<th></th>';
                            hideId = false;
                        }
                    });
                    tablaHTML = tablaHTML + "</tr>";
                }
            });
            tablaHTML = tablaHTML + "</tbody>";
            tablaHTML = tablaHTML + "</table>";
            tablaHTML = tablaHTML + "</tbody>";
            tablaHTML = tablaHTML + "</table>";
            tablaHTML = tablaHTML + "</div>";
            tablaHTML = tablaHTML + "<br>";

        });
        tablaHTML = tablaHTML + finishOrderButton;
        $('.orderDetail-body').html(tablaHTML);
        $("#orderDetailModal").modal('show');
        //Ocultar columnas
        var table = ".tblOrder";
        var i = 1;
        $.each(Order.orderTemp, function (key, val) {
            $.each(val, function (ky, vl) {
                for (var b = 0; b < Order.orderTempConfig.length; b++)
                {
                    if (Order.orderTempConfig[b].columndescription == ky && Order.orderTempConfig[b].hide == "1")
                    {
                        $(table + ' td:nth-child(' + i + '), ' + table + ' th:nth-child(' + i + ')').hide();
                    }
                }
                i++;
            });
            return false;
        });
        table = ".tblOrderDetail";
        var i = 1;
        $.each(Order.orderDetailTemp, function (key, val) {
            $.each(val, function (ky, vl) {
                for (var b = 0; b < Order.orderDetailTempConfig.length; b++)
                {
                    if (Order.orderDetailTempConfig[b].columndescription == ky && Order.orderDetailTempConfig[b].hide == "1")
                    {
                        $(table + ' td:nth-child(' + i + '), ' + table + ' th:nth-child(' + i + ')').hide();
                    }
                }
                i++;
            });
            return false;
        });
    }
}

$('body').on('click', '[data-observationbutton]', function () {
    var idTempOrder = $(this).attr('data-observationbutton');
    $.ajax({
        data: {
            'idTempOrder': idTempOrder
        },
        type: 'post',
        url: 'index.php?r=Order/GetOrderObservations',
        success: function (response) {
            showObservationsDialog(response);
        }
    }).done(function () {
        searchprivileges('itemlstOrder');
    });
});

function showObservationsDialog(data)
{
    var Data = JSON.parse(data);
    var viewrender = "";
    var header = "";
    var observation = "";
    var Id;
    header = header + '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
    header = header + '<span aria-hidden="true">&times;</span></button>';
    $.each(Data.observations, function (key, val) {
        observation = val.Observaciones;
        Id = val.Id;
    });
    $.each(Data.orderTempConfigForm, function (key, val) {
        if (val.iscreable == '1')
        {
            viewrender = viewrender + '<div class="col-md-' + val.size + '">';
            viewrender = viewrender + '<div class="form-group">';
            viewrender = viewrender + '<textarea data-observationtext="' + Id + '" class="form-control ' + val.classvalidation + '" maxlength="' + val.length + '" rows="10" >';
            viewrender = viewrender + observation;
            viewrender = viewrender + '</textarea></div>';
            header = header + '<h4>' + val.columndescription.toUpperCase() + '</h4>';
            localStorage.setItem('column', val.columnname);
        }
    });
    viewrender = viewrender + '<div data-saveobservation=' + Id + ' class=""><button id="btnsaveorderobservation" type="button" class="btn btn-info btn-fill pull-right" ><span class="glyphicon glyphicon-save"></span>Guardar observaci&#243;n</button></div></div></div>';
    $('.orderObservation-header').html(header);
    $('.orderObservation-body').html(viewrender);
    $("#orderObservationModal").modal('show');
}

$('body').on('click', '[data-saveobservation]', function () {
    var idTempOrder = $(this).attr('data-saveobservation');
    var observation = $('[data-observationtext=' + idTempOrder + ']').val();
    if (observation != '')
    {
        $.ajax({
            data: {
                'idTempOrder': idTempOrder,
                'column': localStorage.getItem('column'),
                'observation': observation
            },
            type: 'post',
            url: 'index.php?r=Order/SaveOrderObservations',
            success: function (response) {
                if (response != "")
                {
                    $("#orderObservationModal").modal('hide');
                    swal("", "Comentario guardado satisfactoriamente", "success");
                }
                else
                    sweetAlert("", "No se ha podido guardar el comentario", "error");
            }
        });
    }
    else
        swal("No ha ingresado ning\u00FAn dato", "", "error");
});

$('body').on('click', '#btnfinishorder', function () {

    var user = {};
    user.user = localStorage.getItem('nickname');
    var company = localStorage.getItem('Company');
    $.ajax({
        data: {
            'user': user,
            'company': company
        },
        type: 'post',
        url: 'index.php?r=Order/SaveOrder',
        success: function (response) {
            swal({title: "",
                text: "El pedido se ha guardado satisfactoriamente",
                type: "success",
                showConfirmButton: false});
            $("#orderObservationModal").modal('hide');
            $("#orderDetailModal").modal('hide');
            localStorage.removeItem('Routers');
            localStorage.removeItem('customers');
            localStorage.removeItem('priceLists');
            localStorage.removeItem('formPays');
            localStorage.removeItem('Company');
            localStorage.removeItem('collections');
            localStorage.removeItem('column');
            setTimeout(function () {
                location.reload();
            }, 1500);
        }
    });
    return false;
});