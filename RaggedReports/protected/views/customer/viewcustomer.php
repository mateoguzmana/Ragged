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
                                <div class="38">
                                </div>
                            </div>
                            <div class="row">
                                <div class="row">
                                    <p class="h2 text-center">Clientes</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label id="companiesLabel" for="profile">Compañías:</label>
                                        <div id="CompanyContainer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                        <div id="CustomerContainer" class="table-responsive table-full-width">
                                        </div>
                                        <div style="text-align: center;">
                                            <br>
                                            <div class="36"></div>
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
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- END PAGE -->
<script>
    $(document).ready(function () {

        var Customers = JSON.parse('<?= $Customers; ?>');
        if ((JSON.stringify(Customers.config) !== '[]') && (Object.keys(Customers.datas).length > 0)) {

            var optionCompanies = "<select class='selectpicker selectCustomerCompanies' title='Seleccione una compañía' multiple data-live-search='true' data-actions-box='true' data-selected-text-format='count'>";
            var count = 0;
            var defaultcompany = 0;
            $.each(Customers.companies, function (key, val) {
                optionCompanies = optionCompanies + "<option value=" + val.idcompany + ">" + val.reasonsocial + "</option>";
                count++;
                if (count == 1)
                    defaultcompany = val.idcompany;
            });
            optionCompanies += '</select></div></div>';

            $('#CompanyContainer').html(optionCompanies);
            $('.selectpicker').selectpicker('refresh');
            debugger;
            $('#CustomerContainer').html('<?= $Table; ?>');

            //Ocultar Combo de filtro y datos de la grid según la cantidad de compañías que puede ver el usuario
            if (count == 1) {
                $(".selectCustomerCompanies").val(defaultcompany);
                $('.selectpicker').selectpicker('refresh');
            }

            $(".customercheckbox").each(function () {
                $(this).prettyCheckable();
            });
            $("label[for='answer']").hide();
            //Aplicar privilegios a los botones
            for (var b = 0; b < Customers.privileges.length; b++)
            {
                if (Customers.privileges[b].active == 0) {
                    $(".content").find('.' + Customers.privileges[b].idsourcecode).addClass('disabled');
                }
            }

            var table = $('#tblDinamicCustomer').DataTable({
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

            //Ocultar columnas
            for (var i = 0; i < Customers.columns_hide.length; i++)
            {
                table.column(Customers.columns_hide[i]).visible(false);
            }

        } else {
            //$('#CustomerContainer').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron clientes.</div>');
            $('.36').hide();
            $('#companiesLabel').hide();
            var tdheader = "";
            var tddetails = "";
            if (Object.keys(Customers.config).length > 0) {
                $.each(Customers.config, function (key, val) {
                    if (val.hide == 0) {
                        tdheader += '<td>' + val.columndescription + '</td>';
                        tddetails += '<td></td>';
                    }
                });
            }
            var tablavacia = '<table id="tblDinamicCustomer" class="display dataTable bordered centered" width="100%"><thead><tr>' + tdheader + '</tr></thead><tbody><tr>' + tddetails + '</tr></tbody></table>';
            $('#CustomerContainer').html(tablavacia);
        }
    });
</script>
<script type="text/javascript" src="js/entities/wallet/wallet.js"></script>
<script type="text/javascript" src="js/entities/customers/customer.js"></script>