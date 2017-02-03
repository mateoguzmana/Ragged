<!-- 
Create by Activity Technology S.A.S.
-->
<div class="container-fluid">
    <div id="accoridionContentDiv" style="overflow-y:scroll; height:35vh"></div>
</div>

<!-- END PAGE -->

<div class="modal fade" tabindex="-1" role="dialog" id="orderDetailModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header orderDetail-header">
                <h4 class="modal-title" id="modal-title"> </h4>
            </div>
            <div class="modal-body orderDetail-body table-responsive table-full-width">
                <div style="overflow-x: scroll" class="orderDetailModalModalbody">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="orderObservationModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header orderObservation-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title"> </h4>
            </div>
            <div class="modal-body orderObservation-body table-responsive table-full-width">
                <div style="overflow-x: scroll" class="orderObservationModalbody">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<script>
    $(document).ready(function () {
        var Plus = JSON.parse('<?= $Plus; ?>');
        var singleCustomer = ('<?= $SingleCustomer; ?>');
        
        if (JSON.stringify(Plus.plus.datas) !== '[]') {   
            
            $('#accoridionContentDiv').append('<?= $Table; ?>');            
            
            $('[data-toggle="tooltip"]').tooltip();
            $(".customer-checkbox").each(function () {
                $(this).prettyCheckable();
            });
            $("label[for='answer']").hide();
            var table = $('.table-plus').DataTable({
                "bPaginate": false,
                "bInfo": false,
                "bFilter": false,
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
            // En caso de que se esté retomando el pedido.
            // Asigno los valores ingresados previamente por el usuario a los inputs y checkbox.
            var Quantities = ('<?= $Quantities; ?>');
            var Checks = ('<?= $Checks; ?>');
            if (Quantities != "")
            {
                Quantities = (JSON.parse(Quantities));
                $.each(Quantities, function (key, val) {
                    $.each(val, function (ky, vl) {
                        $("[data-quantity='" + ky + "']").val(vl);
                    });
                });
            }
            if (Checks != "")
            {
                Checks = (JSON.parse(Checks));
                $.each(Checks, function (key, val) {
                    $("[data-idcheck='" + val + "']").attr('checked', true);
                });
            }
        } else {
            $('#accoridionContentDiv').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
        }
    });
</script>
<link href="css/order/orderdetail.css" rel="stylesheet" />
