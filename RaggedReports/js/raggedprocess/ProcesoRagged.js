function SeeDependencies(id) {
    var dependencies = JSON.parse(localStorage.getItem('jsondependencies'));
    var tablerender = '<div class="content table-responsive"><table class="table table-hover table-striped" width="100%"><thead><tr><th class="text-center">#</th><th class="text-center">Servicio</th></tr></thead><tbody>';
    var cont = 1;
    $('.modal-body-dependencies').html('');
    $('.modal-title').html('');
    $.each(dependencies, function (index, value) {
        if (id == value['iddependentmethod']) {
            tablerender += '<tr class="text-center"><td>' + cont + '</td><td>' + value['description'] + '</td></tr>';
            cont++;
        }
    });
    tablerender += '</tbody></table></div>';
    if (cont==1){
        $('.modal-title').html('<h3 align="center">Este servicio no tiene dependencias!!</h3>');
    } else{
        $('.modal-title').html('<h3>Dependencias</h3>');
        $('.modal-body-dependencies').html(tablerender);
    }    
    $("#myModalDependencies").modal('show');
    $('#myModalDependencies').css('overflow', 'auto');
}

function getListProcessExcecution() {
    $.ajax({
        url: 'index.php?r=RaggedProcess/QueryListProcessExcecution',
        success: function (response) {
            var Process = JSON.parse(response);
            var selectcompanies = '<div class="col-md-3"><div class="form-group"><label>COMPA&#209;&#205;AS</label><select class="selectpicker" id="Company" title="Seleccione una compa&#241;&#237;a" multiple data-live-search="true" data-actions-box="true" data-selected-text-format="count">';
            $.each(Process.companies, function (index, value) {
                selectcompanies += '<option value=' + value['id'] + '>' + value['description'] + '</option>';
            });
            selectcompanies += '</select></div></div>';
            var selectusers = '<div class="col-md-3"><div class="form-group"><label>USUARIOS</label><select class="selectpicker" id="users" title="Seleccione un usuario" multiple data-live-search="true" data-actions-box="true" data-selected-text-format="count">';
            $.each(Process.users, function (index, value) {
                selectusers += '<option value=' + value['id'] + '>' + value['description'] + '</option>';
            });
            selectusers += '</select></div></div>';
            var cd = new Date();
            var dt = cd.getFullYear() + "/"
                    + (cd.getMonth() + 1 > 9 ? cd.getMonth() + 1 : '0' + (cd.getMonth() + 1)) + "/"
                    + (cd.getDate() > 9 ? cd.getDate() : '0' + cd.getDate());
            var fechas = '<div class="col-md-3"><div class="form-group"><label>Fecha Inicial</label><div class="input-group date" class="datetimepicker" id="datetimepicker1"><input type="text" id="fechaini" class="form-control" value="' + dt + '" readonly />';
            fechas += '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="col-md-3"><div class="form-group"><label>Fecha Final</label><div class="input-group date" class="datetimepicker" id="datetimepicker2">';
            fechas += '<input type="text" id="fechafin" class="form-control" value="' + dt + '" readonly /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div>';
            var button = '<button style="margin-bottom:5px" type="button" onclick="SearchProcess()" class="btn btn-info btn-fill pull-left"><span class="glyphicon glyphicon-search"></span> Consultar</button>';
            var table = '<div class="tablerender"><div class="content table-responsive"><table class="table table-hover table-striped" id="tblProcess" width="100%"><thead><tr>';
            table += '<th>#</th>' + Process.Processes.columns + '<th>Detalle</th></tr></thead>';
            var cont = 1;
            table += '<tbody>';
            $.each(Process.Processes.Process, function (index, value) {
                table += '<tr><td>' + cont + '</td><td>' + value['usernm'] + '</td><td>' + value['startdate'] + '</td><td>' + value['enddate'] + '</td><td>' + value['starttime'] + '</td><td>' + value['endtime'] + '</td><td><a onclick="ProcessDetails(' + value['id'] + ')" class="btn btn-default"><span class="glyphicon glyphicon-list"></span></a></td></tr>';
                cont++
            });
            table += '</tbody></table></div></div>';
            var page = '<div class="content"><div class="container-fluid"><div class="row"><div class="col-md-12"><div class="card"><div class="content"><div class="row"><div class="col-md-12"><div class="card card-plain"><div class="header"><h3 class="title">Listado de Procesos</h3></div><br><br><div>';
            page += selectcompanies + selectusers + fechas + button + '</div>' + '<div class="clearfix"></div>' + table;
            page += '</div></div></div></div></div></div></div>';
            page += '<div class="modal fade" tabindex="-1" role="dialog" id="myModalProcess"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Detalle del Proceso</h4></div><div class="modal-body-Process"><div class="modal-footer"></div></div></div></div></div>';
            $('#ListProcessExcecution').html(page);
        }
    }).complete(function () {
        $(".selectpicker").each(function () {
            $(this).selectpicker('refresh');
        });
        //$('.selectpicker').selectpicker('refresh');
        $('#tblProcess').DataTable({
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
        var i18n = {
            previousMonth: 'Mes anterior',
            nextMonth: 'Próximo mes',
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', "Octubre", "Noviembre", "Diciembre"],
            weekdays: ["Domingo", " Lunes ", " Martes ", " Miércoles ", " Jueves ", " Viernes ", " Sabado "],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
        };
        var picker1 = new Pikaday({
            field: document.getElementById('fechaini'),
            numberOfMonths: 1,
            firstDay: 1,
            format: "YYYY/MM/DD",
            maxDate: moment().toDate(),
            yearRange: [1950, 2030],
            defaultDate: moment().toDate(),
            i18n: i18n,
        });
        var picker2 = new Pikaday({
            field: document.getElementById('fechafin'),
            numberOfMonths: 1,
            firstDay: 1,
            format: "YYYY/MM/DD",
            maxDate: moment().toDate(),
            yearRange: [1950, 2030],
            defaultDate: moment().toDate(),
            i18n: i18n,
        });
    });
}

function RunProcess() {
    var Companiesselect = $('#company').val();
    if (Companiesselect == null) {
        sweetAlert("", "Debe seleccionar al menos una compania!", "error");
        return;
    }
    var Process = "";
    $(".mycheck").each(function () {
        if ($(this).is(':checked')) {
            Process += $(this).attr('data-idproc') + ",";
        }
    });
    if (Process == "") {
        sweetAlert("", "No has seleccionado ningun servicio a ejecutar!", "error");
        return;
    }
    swal({
        title: "Esta seguro que desea ejecutar el proceso?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Confirmar",
        closeOnConfirm: true
    },
    function () {
        $("#btnRun").prop("disabled", true);
        $("#btnExecCompProc").prop("disabled", true);
        $('#processing-modal').modal('show');
        var Companies = "";
        $.each(Companiesselect, function (index, value) {
            Companies += value + ",";
        });
        var query = {};
        Companies = Companies.slice(0, -1);
        query.Companies = Companies;
        Process = Process.slice(0, -1);
        query.Processes = Process;
        query.user = localStorage.getItem('nickname');
        $.ajax({
            data: {
                'query': query
            },
            type: 'post',
            url: 'index.php?r=RaggedProcess/RunProcess',
            success: function (response) {
                $("#btnRun").prop("disabled", false);
                $("#btnExecCompProc").prop("disabled", false);
                $('#processing-modal').modal('hide');
                if (response == "OK") {
                    sweetAlert("", "El proceso se esta ejecutando con exito", "success");
                    $('#itemlstExcecuteProcess').trigger('click');
                } else if (response == "") {
                    sweetAlert("", "Ocurrio un error ejecutando el servicio", "error");
                } else {
                    sweetAlert("", "Respuesta del servicio: " + response, "error");
                }
            }
        });
    });
}

function ExcecuteCompleteProcess() {
    var Companiesselect = $('#company').val();
    if (Companiesselect == null) {
        sweetAlert("", "Debe seleccionar al menos una compania!", "error");
        return;
    }
    swal({
        title: "Esta seguro que desea correr el proceso completo?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Confirmar",
        closeOnConfirm: true
    },
    function () {
        $("#btnExecCompProc").prop("disabled", true);
        $("#btnRun").prop("disabled", true);
        $('#processing-modal').modal('show');
        var Companies = "";
        $.each(Companiesselect, function (index, value) {
            Companies += value + ",";
        });
        var query = {};
        Companies = Companies.slice(0, -1);
        query.Companies = Companies;
        query.user = localStorage.getItem('nickname');
        $.ajax({
            data: {
                'Companies': query
            },
            type: 'post',
            url: 'index.php?r=RaggedProcess/ExcecuteCompleteProcess',
            success: function (response) {
                $("#btnRun").prop("disabled", false);
                $("#btnExecCompProc").prop("disabled", false);
                $('#processing-modal').modal('hide');
                if (response == "OK") {
                    sweetAlert("", "El proceso se esta ejecutando con exito", "success");
                    $('#itemlstExcecuteProcess').trigger('click');
                } else if (response == "") {
                    sweetAlert("", "Ocurrio un error ejecutando el servicio", "error");
                } else {
                    sweetAlert("", "respuesta del servicio: " + response, "error");
                }
            }
        });
    });
}

function ProcessDetails(id) {
    var query = {};
    query.processid = id;
    //var Companiesselect = $('#Company').val();
    var Companies = "";
    if ((Companiesselect != "") && (Companiesselect != null)) {
        $.each(Companiesselect, function (index, value) {
            Companies += value + ",";
        });
        Companies = Companies.slice(0, -1);
    }
    query.Companies = Companies;
    $.ajax({
        data: {
            'process': query
        },
        type: 'post',
        url: 'index.php?r=RaggedProcess/QueryListProcessExcecutionDetail',
        success: function (response) {
            var ProcessDet = JSON.parse(response);
            var table = '<div class="tablerender"><div class="content table-responsive"><table class="table table-hover table-striped" id="tblProcess" width="100%"><thead><tr>';
            table += '<th>#</th>' + ProcessDet.Processes.columns + '</tr></thead>';
            var cont = 1;
            table += '<tbody>';
            $.each(ProcessDet.Processes.Process, function (index, value) {
                table += '<tr><td>' + cont + '</td>' + value['info'] + '</tr>';
                cont++
            });
            table += '</tbody></table></div></div>';
            $('.modal-body-Process').html(table);
            $('#myModalProcess').modal('show');
            $('#myModalProcess').css('overflow', 'auto');
        }
    });
}

var Companiesselect = "";
function SearchProcess() {
    if ($('#fechaini').val() > $('#fechafin').val()) {
        sweetAlert("", "La fecha inicial no puede ser mayor a la fecha final", "error");
        return;
    }
    var query = {};
    Companiesselect = $('#Company').val();
    if (Companiesselect != null) {
        var Companies = "";
        $.each(Companiesselect, function (index, value) {
            Companies += value + ",";
        });
        Companies = Companies.slice(0, -1);
        query.Companies = Companies;
    }
    var Userssselect = $('#users').val();
    if (Userssselect != null) {
        var Users = "";
        $.each(Userssselect, function (index, value) {
            Users += value + ",";
        });
        Users = Users.slice(0, -1);
        query.Users = Users;
    }
    query.Fechaini = $('#fechaini').val();
    query.Fechafin = $('#fechafin').val();
    $.ajax({
        data: {
            'userquery': query
        },
        type: 'post',
        url: 'index.php?r=RaggedProcess/QueryListProcessExcecutionUser',
        success: function (response) {
            $('.tablerender').html();
            var Process = JSON.parse(response);
            var table = '<div class="content table-responsive"><table class="table table-hover table-striped" id="tblProcess" width="100%"><thead><tr>';
            table += '<th>#</th>' + Process.Processes.columns + '<th>Detalle</th></tr></thead>';
            var cont = 1;
            table += '<tbody>';
            $.each(Process.Processes.Process, function (index, value) {
                table += '<tr><td>' + cont + '</td><td>' + value['usernm'] + '</td><td>' + value['startdate'] + '</td><td>' + value['enddate'] + '</td><td>' + value['starttime'] + '</td><td>' + value['endtime'] + '</td><td><a onclick="ProcessDetails(' + value['id'] + ')" class="btn btn-default"><span class="glyphicon glyphicon-list"></span></a></td></tr>';
                cont++
            });
            table += '</tbody></table></div>';
            $('.tablerender').html(table);
        }
    }).complete(function () {
        $('#tblProcess').DataTable({
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

