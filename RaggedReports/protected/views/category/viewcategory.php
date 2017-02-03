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
                                    <p class="h2 text-center">Categorías</p>
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
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                        <div id="CategoryContainer" class="table-responsive table-full-width">

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

<script>
    $(document).ready(function () {
        var Categories = JSON.parse('<?= $Categories; ?>');
        if (JSON.stringify(Categories) !== '[]') {
            var columnas = "";
            var contador = 0;
            var flag = false;
            var optionCompanies = "<div class='col-md-3'><div class='form-group'><select class='selectpicker selectCategoryCompanies' title='Seleccione una compañía' multiple data-live-search='true' data-actions-box='true' data-selected-text-format='count'>";
            var count = 0;
            var defaultcompany = 0;
            $.each(Categories.companies, function (key, val) {
                optionCompanies += "<option value=" + val.idcompany + ">" + val.reasonsocial + "</option>";
                count++;
                if (count == 1)
                    defaultcompany = val.idcompany;
            });
            optionCompanies += '</select></div></div>';
            var tablaHTML = "<table id='tblDinamicCategory' class='display dataTable bordered centered' width='100%'>";
            tablaHTML = tablaHTML + "<thead><tr>"; // class='filter'>";
            $.each(Categories.datas, function (key, val) {
                $.each(val, function (ky, vl) {
                    tablaHTML = tablaHTML + "<th>" + ky + "</th>";
                });
                return false;
            });
            tablaHTML = tablaHTML + "</tr></thead>";
            tablaHTML = tablaHTML + "<tbody>";
            $.each(Categories.datas, function (key, val) {

                tablaHTML = tablaHTML + '<tr data-id="' + val.Id + '">';
                $.each(val, function (ky, vl) {
                    tablaHTML = tablaHTML + '<td>' + vl + '</td>';
                });
                tablaHTML = tablaHTML + "</tr>";
            });
            tablaHTML = tablaHTML + "</tbody>";
            tablaHTML = tablaHTML + "</table>";
            $('#CompanyContainer').html(optionCompanies);
            $('.selectpicker').selectpicker('refresh');
            $('#CategoryContainer').html(tablaHTML);            
            //Ocultar Combo de filtro y datos de la grid según la cantidad de compañías que puede ver el usuario
            if (count == 1) {
                $(".selectCategoryCompanies").val(defaultcompany);
                $('.selectpicker').selectpicker('refresh');
            } /*else {
             $(".selectCategoryCompanies").val(0);
             $('.selectpicker').selectpicker('refresh');
             }*/

            //Aplicar privilegios a los botones
            for (var b = 0; b < Categories.privileges.length; b++)
            {
                if (Categories.privileges[b].active == 0) {
                    $(".content").find('.' + Categories.privileges[b].idsourcecode).addClass('disabled');
                }
            }

            var table = $('#tblDinamicCategory').DataTable({
                aLengthMenu: [
                    [10, 50, 100, 200, -1],
                    [10, 50, 100, 200, "All"]
                ],
                iDisplayLength: 10,
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
            var i = 0;
            $.each(Categories.datas, function (key, val) {
                $.each(val, function (ky, vl) {
                    for (var b = 0; b < Categories.config.length; b++)
                    {
                        if (Categories.config[b].columndescription == ky && Categories.config[b].hide == "1")
                            table.column(i).visible(false);
                    }
                    i++;
                });
                return false;
            });
        } else {
            $('#CategoryContainer').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
        }
    });</script>
<script type="text/javascript" src="js/entities/categories/categories.js"></script>