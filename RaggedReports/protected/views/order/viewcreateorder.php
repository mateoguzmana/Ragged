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
                                        <div id="CreateOrderContainer" class="table-responsive table-full-width">
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
<!-- END PAGE -->
<style>
    .width100{ width: 100%;}
</style>


<script>
    $(document).ready(function () {
        var OrderOptions = JSON.parse('<?= $OrderOptions; ?>');
        console.log(JSON.stringify(OrderOptions));
        if (JSON.stringify(OrderOptions) !== '[]') {
            var columnas = "";
            var contador = 0;
            var flag = false;
            var count = 0;
            var defaultcompany = 0;
            var customersArray = [];
            var idCounter = 1;
            var optionSalePoints = "<select id='selectSalePoints' data-live-search='true' data-actions-box='true' class='selectpicker selectCollectionOptions' title='Seleccione el punto de venta'>";

            var tablaHTML = "<table id='tblDinamicOrderOptions' class='display dataTable bordered centered' width='100%'>";
            tablaHTML = tablaHTML + "<thead><tr>";

            tablaHTML = tablaHTML + "<th>" + "Cuenta Cliente" + "</th>";
            tablaHTML = tablaHTML + "<th>" + "Razón Social" + "</th>";
            tablaHTML = tablaHTML + "<th>" + "Dirección" + "</th>";

            $.each(OrderOptions.plus, function (key, val) {

                tablaHTML = tablaHTML + "<th class='teache' >" + val["Tallas"] + "</th>";
            });

            tablaHTML = tablaHTML + "<th>" + "Encargo" + "</th>";
            tablaHTML = tablaHTML + "<th>" + "Observaciones" + "</th>";
            tablaHTML = tablaHTML + "<th>" + "Adicionar/Elminar" + "</th>";

            tablaHTML = tablaHTML + "</tr></thead>";
            tablaHTML = tablaHTML + "<tbody>";
            $.each(OrderOptions.address, function (key, val) {

                var index = $.inArray(val["Id Cliente"], customersArray);

                if (index < 0)
                {
                    tablaHTML = tablaHTML + '<tr id="row-' + idCounter + '" data-id="' + val["Id Cliente"] + '">';
                    customersArray.push(val["Id Cliente"]);
                }

                else
                {
                    tablaHTML = tablaHTML + '<tr id="row-' + idCounter + '" class="hideRow" data-id="' + val["Id Cliente"] + '">';

                }

                tablaHTML = tablaHTML + '<td>' + val["Cuenta Cliente"] + '</td>';
                tablaHTML = tablaHTML + '<td>' + val["Raz&#243;n Social"] + '</td>';

                tablaHTML = tablaHTML + '<td>';
                tablaHTML = tablaHTML + '<select id="address' + idCounter + '" data-idSelectAddress="' + val["Direcci&#243;n"] + '" class="selectpicker SelectAddress" title="Seleccione Dirección">';

                $.each(OrderOptions.address, function (keyAddress, valAddress) {

                    if (val["Id Cliente"] == valAddress["Id Cliente"])
                        tablaHTML = tablaHTML + '<option value="' + valAddress["Id Punto de Venta"] + '">' + valAddress["Direcci&#243;n"] + '</option>';
                });

                tablaHTML = tablaHTML + '</td>';

                var i = 0;
                $.each(OrderOptions.plus, function (keySize, valSize) {

                    tablaHTML = tablaHTML + '<td><input data-size="' + valSize["Tallas"] + '" type="text" data-sizeRow="' + idCounter + '" data-idCustomer="' + val["Id Cliente"] + '" class="form-control nums" maxlength="10"> </td>';
                });

                //tablaHTML = tablaHTML + '<td style="text-align: center">' + '<input type="checkbox" data-checkRow="' + idCounter + '" data-idCheck="' + val["Id Cliente"] + '" class="customer-checkbox">' + '</td>';
                tablaHTML = tablaHTML + '<td style="text-align: center">' + '<input type="checkbox" data-checkRow="' + idCounter + '" data-idCheck="' + val["Id Cliente"] + '" class="customer-checkbox id="answer" name=""/>' + '</td>';
                tablaHTML = tablaHTML + '<td><input type="text" data-textRow="' + idCounter + '" data-idText="' + val["Id Cliente"] + '" class="form-control" maxlength="100"> </td>';
                tablaHTML = tablaHTML + '<td style="text-align: center"><a data-idAdd="' + val["Id Cliente"] + '" class="btn btn-default btnAdd " href="#"><span style="display:none">"' + val["Id Cliente"] + '"</span><span class="glyphicon glyphicon-plus"></span></a> <a data-removeRow="' + idCounter + '" data-idAdd="' + val["Id Cliente"] + '" class="btn btn-default btnRemove " href="#"><span style="display:none">"' + val["Id Cliente"] + '"</span><span class="glyphicon glyphicon-remove"></span></a></td>';
                tablaHTML = tablaHTML + "</tr>";
                idCounter++;
            });
            tablaHTML = tablaHTML + "</tbody>";
            tablaHTML = tablaHTML + "</table>";
            tablaHTML = tablaHTML + "<br>";
            tablaHTML = tablaHTML + '<div class="col-md-2" >';
            tablaHTML = tablaHTML + '</div>';
            tablaHTML = tablaHTML + '<div class="col-md-2"><button id="btnbackorder" type="button" class="btn btn-default width100">Atr&#225;s</button></div>';
            tablaHTML = tablaHTML + '<div class="col-md-2"><button id="btnsaveandcontinue" type="button" class="btn btn-default width100">Guardar y seguir</button></div>';
            tablaHTML = tablaHTML + '<div class="col-md-2"><button id="btnsave" type="button" class="btn btn-default width100">Finalizar</button></div>';
            tablaHTML = tablaHTML + '<div class="col-md-2"><button id="btnexit" type="button" class="btn btn-default width100">Salir</button></div>';
            $('.selectpicker').selectpicker('refresh');
            $('#CreateOrderContainer').html(tablaHTML);
            //Ocultar Combo de filtro y datos de la grid según la cantidad de compañías que puede ver el usuario
            if (count == 1) {
                $(".selectOrderOptionsCompanies").val(defaultcompany);
                $('.selectpicker').selectpicker('refresh');
            }
            else {
                $(".selectOrderOptionsCompanies").val(0);
                $('.selectpicker').selectpicker('refresh');
            }
            $(".customer-checkbox").each(function () {
                $(this).prettyCheckable();
            });
            $("label[for='answer']").hide();
            $(".hideRow").hide();
            createDataTable();
            //Aplicar privilegios a los botones
            /*for (var b = 0; b < OrderOptions.privileges.length; b++)
             {
             if (OrderOptionss.privileges[b].active == 0) {
             $(".content").find('.' + OrderOptionss.privileges[b].idsourcecode).addClass('disabled');
             }
             }*/

        } else {
            $('#CreateOrderContainer').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
        }
    });

    function createDataTable()
    {
        var table = $('#tblDinamicOrderOptions').DataTable({
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"]
            ],
            iDisplayLength: 10,
            autoWidth: false,
            "bFilter": true,
            "bInfo": false,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]},
                //{ "bSearchable": false, "aTargets": [ 1 ]},
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
    }
</script>
<script type="text/javascript" src="js/entities/orders/createorder.js"></script>