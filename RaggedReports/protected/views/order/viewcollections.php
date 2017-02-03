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
                                        <div class="content table-responsive table-full-width">
                                            <div id="CollectionContainer">
                                            </div>
                                            <br>
                                            <div class="col-md-3" >
                                            </div>
                                            <div class="col-md-2"><button id="btnback" type="button" class="btn btn-default width100">Atr&#225;s</button></div>
                                            <div class="col-md-2"><button id="btnnextcollection" type="button" class="btn btn-default width100">Siguiente</button></div>
                                            <div class="col-md-2"><button id="btnexit" type="button" class="btn btn-default width100">Salir</button></div>
                                            <div class="col-md-3" >
                                            </div>
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
        var Collections = JSON.parse('<?= $Collections ?>');
        if (JSON.stringify(Collections) !== '[]') {
            var columnas = "";
            var contador = 0;
            var flag = false;
            var optionCompanies = "<select class='selectpicker selectCollectionCompanies' title='Seleccione una compañía'><option value='0'>TODAS</option>";
            var count = 0;
            var defaultcompany = 0;

            var tablaHTML = "<table id='tblDinamicCollection' class='table table-hover table-striped' width='100%'>";
            tablaHTML = tablaHTML + "<thead><tr>"; // class='filter'>";

            $.each(Collections.datas, function (key, val) {
                tablaHTML = tablaHTML + "<th>" + '' + "</th>";
                $.each(val, function (ky, vl) {
                    $.each(Collections.config, function (data, row) {
                        if (row.columndescription == ky) {

                            if (vl == row.value) {
                                flag = true;
                                return false;
                            }
                        } else {
                            flag = false;
                        }
                    });
                    if (!flag) {
                        tablaHTML = tablaHTML + "<th>" + ky + "</th>";
                    }
                });
                return false;
            });

            tablaHTML = tablaHTML + "</tr></thead>";
            tablaHTML = tablaHTML + "<tbody>";
            $.each(Collections.datas, function (key, val) {

                tablaHTML = tablaHTML + '<tr data-id="' + val.Id + '">';
                //tablaHTML = tablaHTML + '<td>' + '<input type="checkbox" data-idCheckCollection="' + val.Id + '" class="collection-checkbox">' + '</td>';
                tablaHTML = tablaHTML + '<td>' + '<input type="checkbox" data-idCheckCollection="' + val.Id + '" class="collection-checkbox" id="answer" name=""/>' + '</td>';
                $.each(val, function (ky, vl) {
                    $.each(Collections.config, function (data, row) {
                        if (row.columndescription == ky) {

                            if (vl == row.value) {

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

            $('#CollectionContainer').html(tablaHTML);

            for (var b = 0; b < Collections.privileges.length; b++)
            {
                if (Collections.privileges[b].active == 0)
                {
                    $(".content").find('.' + Collections.privileges[b].idsourcecode).addClass('disabled');
                }
            }

            $(".collection-checkbox").each(function () {
                $(this).prettyCheckable();
            });
            $("label[for='answer']").hide();

            var table = $('#tblDinamicCollection').DataTable({
                aLengthMenu: [
                    [10, 50, 100, 200, -1],
                    [10, 50, 100, 200, "All"]
                ],
                iDisplayLength: 10,
                "bFilter": true,
                "aoColumnDefs": [
                    {"bSearchable": false, "aTargets": [0]},
                    //{ "bSearchable": false, "aTargets": [ 3 ]},
                    //{ "bSearchable": false, "aTargets": [ 4 ]},

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
            $.each(Collections.datas, function (key, val) {
                $.each(val, function (ky, vl) {
                    for (var b = 0; b < Collections.config.length; b++)
                    {
                        if (Collections.config[b].columndescription == ky && Collections.config[b].hide == "1")
                            table.column(i).visible(false);
                    }
                    i++;
                });
                return false;
            });


        } else {
            $('#CollectionContainer').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
        }
    });
</script>
<script type="text/javascript" src="js/entities/orders/ordercollections.js"></script>