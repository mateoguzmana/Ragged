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
                                    <p class="h2 text-center">Colecciones</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profile">Compañías:</label>
                                        <div id="CompanyContainer">

                                        </div>                                       
                                        
                                    </div>                                    
                                </div>                                                                                                   
                                <div class="68 pull-right inner" style="padding-top:30px"></div>
                                <div class="67 pull-right" style="padding-top:30px"></div>                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                        <div class="content table-responsive table-full-width">
                                            <div id="CollectionContainer">

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
    
    .width100
    {
        width: 100%;        
    }
    
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
            $.each(Collections.companies, function (key, val) {
                optionCompanies = optionCompanies + "<option value=" + val.idcompany + ">" + val.reasonsocial + "</option>";
                count++;
                if (count == 1)
                    defaultcompany = val.idcompany;
            });


            var tablaHTML = "<table id='tblDinamicCollection' class='table table-hover table-striped' width='100%'>";
            tablaHTML = tablaHTML + "<thead><tr>"; // class='filter'>";

            $.each(Collections.datas, function (key, val) {
                $.each(val, function (ky, vl) {
                    tablaHTML = tablaHTML + "<th>" + ky + "</th>";
                });
                return false;
            });

            tablaHTML = tablaHTML + "</tr></thead>";
            tablaHTML = tablaHTML + "<tbody>";
            $.each(Collections.datas, function (key, val) {

                tablaHTML = tablaHTML + '<tr data-id="' + val.Id + '">';
                $.each(val, function (ky, vl) {
                    $.each(Collections.config, function (data, row) {
                        if (row.columndescription == ky) {

                            if (vl == row.value) {
                                tablaHTML = tablaHTML + '<td><a data-status="' + row.value + '" class="btn btn-default ' + row.class + '" href="#"><span style="display:none">"' + row.value + '"</span><span class="' + row.showvalue + '"></span></a></td>';
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

            $('#CompanyContainer').html(optionCompanies);
            $('.selectpicker').selectpicker('refresh');
            $('#CollectionContainer').html(tablaHTML);

            if (count == 1) {
                $(".selectCollectionCompanies").val(defaultcompany); 
                $('.selectpicker').selectpicker('refresh');
            } else {
                $(".selectCollectionCompanies").val(0);
                $('.selectpicker').selectpicker('refresh');
            }

            for (var b = 0; b < Collections.privileges.length; b++)
            {
                if (Collections.privileges[b].active == 0)
                {
                    $(".content").find('.' + Collections.privileges[b].idsourcecode).addClass('disabled');
                }
            }

            var table = $('#tblDinamicCollection').DataTable({
                aLengthMenu: [
                    [10, 50, 100, 200, -1],
                    [10, 50, 100, 200, "All"]
                ],
                iDisplayLength: 10,
                "bFilter": true,
                "aoColumnDefs": [
                    { "bSearchable": false, "aTargets": [ 0 ]},
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
            var i = 0;
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

            /*if (count == 1) {
                var table = $('#tblDinamicCustomer').dataTable();
                table.fnFilter('1', 4);
            }*/

        } else {
            $('#CollectionContainer').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
        }
    });
</script>
<script type="text/javascript" src="js/entities/collections/collections.js"></script>