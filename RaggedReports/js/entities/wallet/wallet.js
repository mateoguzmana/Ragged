/*
 * Created By Activity Technology S.A.S.
 */

function displayTable(customerData, modalTitle) {

    var flag = false;

    if (JSON.stringify(customerData.datas) !== '[]') {
        var tablaHTML = "<table id='tblcustomerwallet' class='display dataTable bordered centered' width='100%'>";
        tablaHTML = tablaHTML + "<thead><tr>";
        $.each(customerData.datas, function (key, val) {
            $.each(val, function (ky, vl) {
                tablaHTML = tablaHTML + "<th>" + ky + "</th>";
            });
            return false;
        });

        tablaHTML = tablaHTML + "</tr></thead>";
        tablaHTML = tablaHTML + "<tbody>";
        $.each(customerData.datas, function (key, val) {
            tablaHTML = tablaHTML + '<tr data-id="' + val.Id + '">';
            $.each(val, function (ky, vl) {

                $.each(customerData.config, function (data, row) {
                    if (row.columndescription == ky) {

                        if (row.class == "changeNumFormat") {

                            vl = vl.toString().replace(".", ",");
                            vl = vl.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }

                        if (vl == row.value) {
                            tablaHTML = tablaHTML + '<td><a data-idAddress="' + val.Id + '" class="btn btn-default ' + row.class + '" href="#"><span style="display:none">"' + row.value + '"</span><span class="' + row.showvalue + '"></span></a></td>';
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
        $("#customersModal").modal('show');
        $("#modal-title").text(modalTitle);
        $('.customerModalbody').html(tablaHTML);

        //Ocultar columnas               

        var tableCustomerModal = $('#tblcustomerwallet').DataTable({
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

        var i = 0;
        $.each(customerData.datas, function (key, val) {
            $.each(val, function (ky, vl) {
                for (var b = 0; b < customerData.config.length; b++)
                {
                    if (customerData.config[b].columndescription == ky && customerData.config[b].hide == "1")
                        tableCustomerModal.column(i).visible(false);
                }
                i++;
            });
            return false;
        });
    }
    else {
        $("#customersModal").modal('show');
        $("#modal-title").text(modalTitle);
        $('.customerModalbody').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
    }
}