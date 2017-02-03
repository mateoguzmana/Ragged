<!-- 
Create by Activity Technology S.A.S.
-->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="content">
                        <form>
                            <div class="row">
                                <div class="row">
                                    <p class="h2 text-center">Pedido Web</p>
                                </div>
                            </div>
                            <div class="row">

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                        <div id="RouterContainer" class="table-responsive table-full-width">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-backdrop {
        display:none;
    }
</style>
<div class="modal fade" tabindex="-1" role="dialog" id="customersModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title"> </h4>
            </div>
            <div class="modal-body table-responsive table-full-width">
                <div style="overflow-x: scroll" class="customerModalbody">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <style>
        .width100{ width: 100%;}
    </style>

    <!-- END PAGE -->

    <script>
        //var tableToStorage;
        var RoutersStorage;
        var defautlCompanyStorage;
        var defaultForPaymentArrayStorage;
        $(document).ready(function () {
            var routersFlag = localStorage.getItem('Routers');
            if (routersFlag == null) {
                var Routers = JSON.parse('<?= $Routers; ?>');
                RoutersStorage = '<?= $Routers; ?>';
                localStorage.setItem('Routers', RoutersStorage);
                displayTable(Routers);
            }
            else
            {
                $('#RouterContainer').html("");
                RoutersJson = localStorage.getItem('Routers');
                Routers = JSON.parse(RoutersJson);
                var Check = JSON.parse(localStorage.getItem('customers'));
                var priceLists = JSON.parse(localStorage.getItem('priceLists'));
                var formPays = JSON.parse(localStorage.getItem('formPays'));
                displayTable(Routers);
                var tableRouters = $("#tblDinamicRouter").DataTable();
                var countDefault = 0;
                var checkCounter = 0;
                for (checkCounter = 0; checkCounter < Check.length; checkCounter++) {
                    tableRouters.$('[data-idselectpricelist="' + Check[checkCounter] + '"]').val(priceLists[checkCounter]);
                    tableRouters.$('[data-idselectformpay="' + Check[checkCounter] + '"]').val(formPays[checkCounter]);
                    tableRouters.$('[data-idCheck="' + Check[checkCounter] + '"]').attr('checked', 'checked');
                }
                tableRouters.$('.selectpicker').selectpicker('refresh');
            }
        });

        function displayTable(Routers) {
            if (JSON.stringify(Routers) !== '[]') {       
                var flag = false;
                var count = 0;
                var defaultcompany = 0;
                var defaultPriceList;                
                var defaultForPaymentArray = {};
                var countDefault = 0;
                // Valores por defecto para asignar a los selectores
                $.each(Routers.companies, function (key, val) {
                    count++;
                    if (count == 1)
                    {
                        defaultcompany = val.Company;
                        defaultPriceList = val.IdPriceList;
                    }
                });
                defautlCompanyStorage = defaultcompany;

                var tablaHTML = '<div id="RouterStorageDiv">';
                tablaHTML = tablaHTML + '<table id="tblDinamicRouter" class="display dataTable bordered centered" width="100%">';
                tablaHTML = tablaHTML + '<thead><tr>';

                $.each(Routers.datas, function (key, val) {
                    tablaHTML = tablaHTML + "<th>" + '' + "</th>";
                    $.each(val, function (ky, vl) {
                        tablaHTML = tablaHTML + "<th>" + ky + "</th>";
                    });
                    return false;
                });       

                tablaHTML = tablaHTML + "</tr></thead>";
                tablaHTML = tablaHTML + "<tbody>";
                $.each(Routers.datas, function (key, val) {

                    tablaHTML = tablaHTML + '<tr data-id="' + val.Id + '">';
                    tablaHTML = tablaHTML + '<td>' + '<input type="checkbox" class="customer-checkbox" data-idCheck="' + val.Id + '" id="answer" name=""/>' + '</td>';                    
                    $.each(val, function (ky, vl) {

                        $.each(Routers.config, function (data, row) {
                            if (row.columndescription == ky) {

                                if ("1" == row.value) {
                                    tablaHTML = tablaHTML + '<td>';
                                    tablaHTML = tablaHTML + '<select data-idSelectPriceList="' + val.Id + '" class="selectpicker ' + row.class + '" title="Seleccione lista de precio">';
                                    $.each(Routers.priceList, function (dataPriceList, rowPriceList) {
                                        if (defaultcompany == rowPriceList.Company)
                                        {
                                            tablaHTML = tablaHTML + '<option value="' + rowPriceList.Id + '">' + rowPriceList["Lista de Precio"] + '</option>';
                                        }
                                    });
                                    tablaHTML = tablaHTML + '</td>';
                                    flag = true;
                                    return false;
                                }
                                else if ("2" == row.value) {
                                    tablaHTML = tablaHTML + '<td>';
                                    tablaHTML = tablaHTML + '<select id="pay' + val.Id + '" data-idSelectFormPay="' + val.Id + '" class="selectpicker ' + row.class + '" title="Seleccione lista de precio">';
                                    $.each(Routers.formPayments, function (dataformPayment, rowformPayment) {
                                        //Identifica si es de contado
                                        if (val["Id Forma de pago"] == "1")
                                        {
                                            tablaHTML = tablaHTML + '<option value="' + rowformPayment.Id + '">' + rowformPayment["Forma de Pago"] + '</option>';
                                            return false;
                                        }

                                        else
                                        {
                                            tablaHTML = tablaHTML + '<option value="' + rowformPayment.Id + '">' + rowformPayment["Forma de Pago"] + '</option>';
                                            defaultForPaymentArray[val.Id] = val["Id Forma de pago"];
                                        }
                                    });
                                    tablaHTML = tablaHTML + '</td>';
                                    flag = true;
                                    return false;
                                }

                            } else {
                                flag = false;
                            }
                        });
                        if (!flag) {
                            tablaHTML = tablaHTML + '<td>' + vl + '</td>';
                        }
                    });
                    tablaHTML = tablaHTML + "</tr>";
                });
                tablaHTML = tablaHTML + "</tbody>";
                tablaHTML = tablaHTML + "</table>";
                tablaHTML = tablaHTML + "</div>";

                tablaHTML = tablaHTML + "<br>";
                tablaHTML = tablaHTML + '<div class="col-md-3" >';
                tablaHTML = tablaHTML + '</div>';
                tablaHTML = tablaHTML + '<div class="col-md-2 col-xs-4"><button style="width:100%" id="btngetwallet" type="button" class="btn btn-default">Consultar Cartera</button></div>';
                tablaHTML = tablaHTML + '<div class="col-md-2 col-xs-4"><button style="width:100%" id="btnnext" type="button" class="btn btn-default">Siguiente</button></div>';
                tablaHTML = tablaHTML + '<div class="col-md-2 col-xs-4"><button style="width:100%" id="btnexit" type="button" class="btn btn-default ">Salir</button></div>';

                $('#RouterContainer').html(tablaHTML);
                $('.selectpicker').selectpicker('refresh');
                $(".customer-checkbox").each(function () {
                    $(this).prettyCheckable();
                });
                $("label[for='answer']").hide();
                //Aplicar privilegios a los botones
                for (var b = 0; b < Routers.privileges.length; b++)
                {
                    if (Routers.privileges[b].active == 0) {
                        $(".content").find('.' + Routers.privileges[b].idsourcecode).addClass('disabled');
                    }

                }

                var table = $('#tblDinamicRouter').DataTable({
                    aLengthMenu: [
                        [10, 50, 100, 200, -1],
                        [10, 50, 100, 200, "All"]
                    ],
                    iDisplayLength: 10,
                    select: true,
                    "bFilter": true,
                    "aoColumnDefs": [
                        {"bSearchable": false, "aTargets": [0]},                        
                        {"bSearchable": false, "aTargets": [2]},
                    ],
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ning n dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "lengthMenu": "_MENU_",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });

                //Ocultar columnas
                var i = 1;
                $.each(Routers.datas, function (key, val) {
                    $.each(val, function (ky, vl) {
                        for (var b = 0; b < Routers.config.length; b++)
                        {
                            if (Routers.config[b].columndescription == ky && Routers.config[b].hide == "1")
                                table.column(i).visible(false);
                        }
                        i++;
                    });
                    return false;
                });
                table.$(".selectOrderPriceList").val(defaultcompany);
                table.$(".selectFormPayOrder").val(1);
                table.$(".selectOrderPriceList ").val(defaultPriceList);
                table.$('.selectFormPayOrder').attr('disabled', true);

                var creditFormPay = '2';
                var pendingFormPay = '3';
                
                
                 if ($(window).width() <= 680) 
                {                               
                    $("#btngetwallet").html("");            
                    $("#btnnext").html("");
                    $("#btnexit").html("");                    
                    $("#btngetwallet").addClass("glyphicon glyphicon-credit-card");            
                    $("#btnnext").addClass("glyphicon glyphicon-ok");                    
                    $("#btnexit").addClass("glyphicon glyphicon-home");                    
                
                }  
                

                $.each(defaultForPaymentArray, function (key, val) {
                    table.$("#pay" + key).val(val);
                    // si la forma de pago es crédito
                    if (val == creditFormPay)
                    {
                        table.$("#pay" + key).attr('disabled', false);
                        // Elimino la opción pendiente del select
                        table.$("#pay" + key + " option[value='" + pendingFormPay + "']").remove();

                    }
                });

                /*for(countDefault=0; countDefault<defaultForPaymentArray.length;countDefault++)
                 {
                 table.$("#pay"+defaultForPaymentArray[countDefault]).val(2);
                 table.$("#pay"+defaultForPaymentArray[countDefault]).attr('disabled',false);
                 }*/

                table.$('.selectpicker').selectpicker('refresh');
            } else {
                $('#RouterContainer').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
                return false;
            }
        }
    </script>
    <script type="text/javascript" src="js/entities/wallet/wallet.js"></script>
    <script type="text/javascript" src="js/entities/orders/orders.js"></script>