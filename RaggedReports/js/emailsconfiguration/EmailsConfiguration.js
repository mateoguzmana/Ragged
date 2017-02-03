$(document).ready(function () {
    $('.selectpicker').selectpicker('refresh');
});

var companyeditid = "";
var usereditviewjs = "";
var pricelistid;
var stateid;
var dayscancellationlimit;

function ChangeSendEmailStatus(id) {
    var SendEmail = {};
    SendEmail.SendEmailId = id;
    $('#processing-modal').modal('show');
    $.ajax({
        data: {
            'SendEmail': SendEmail
        },
        type: 'post',
        url: 'index.php?r=EmailsConfiguration/ChangeSendEmailStatus',
        success: function (response) {
            $('#processing-modal').modal('hide');
            if (response != "OK") {
                swal("Alerta", "Ocurrio un error cambiando el estado de la compañia", "error");
            } else {
                LoadEmailConfiguration();
            }
        }
    });
}

var jsonfilternew = "";

function slctcompany() {
    $('#processing-modal').modal('show');
    var cerender = "";
    if (jsonfilternew == "") {
        jsonfilternew = JSON.parse(localStorage.getItem('jsonemails'));
    }
    //alert(JSON.stringify(jsonfilternew));    
    var Companiaselect = $('#company').val();
    cerender = '<div class="content table-responsive table-full-width"><table class="table table-hover table-striped" id="tblconfigurationemails"><thead><tr>';
    cerender += '<th>Editar</th><th>Correo</th><th>Documento</th><th>Estado</th></tr></thead><tbody>';
    $.each(jsonfilternew, function (k, data) {
        if (Companiaselect != null) {
            $.each(Companiaselect, function (index, element) {
                if (element == data.idcompany) {
                    if (data.state == 1) {
                        cerender += "<tr><td><a onclick='EditEmailsConfiguration(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-edit'></span></a></td><td>" + data.email + "</td><td>" + data.description + "</td><td><a onclick='ChangeSendEmailStatus(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a></td></tr>";
                    } else {
                        cerender += "<tr><td><a onclick='EditEmailsConfiguration(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-edit'></span></a></td><td>" + data.email + "</td><td>" + data.description + "</td><td><a onclick='ChangeSendEmailStatus(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a></td></tr>";
                    }
                }
            });
        } else {
            if (data.state == 1) {
                cerender += "<tr><td><a onclick='EditEmailsConfiguration(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-edit'></span></a></td><td>" + data.email + "</td><td>" + data.description + "</td><td><a onclick='ChangeSendEmailStatus(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a></td></tr>";
            } else {
                cerender += "<tr><td><a onclick='EditEmailsConfiguration(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-edit'></span></a></td><td>" + data.email + "</td><td>" + data.description + "</td><td><a onclick='ChangeSendEmailStatus(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a></td></tr>";
            }
        }
    });
    cerender += '</tbody></table></div>';
    $('#rendertable').html(cerender);
    $('#tblconfigurationemails').DataTable({
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
    $('#processing-modal').modal('hide');
    /*data.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'data': data
        },
        type: 'post',
        url: 'index.php?r=EmailsConfiguration/QueryAllEmailsforCompany',
        success: function (response) {
            var CompanysEmail = JSON.parse(response);
            cerender = '<div class="content table-responsive table-full-width"><table class="table table-hover table-striped" id="tblconfigurationemails"><thead><tr>';
            cerender += '<th>Editar</th><th>Correo</th><th>Documento</th><th>Estado</th></tr></thead><tbody>';
            $.each(CompanysEmail, function (key, data) {
                if (data.state == 1) {
                    cerender += "<tr><td><a onclick='EditEmailsConfiguration(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-edit'></span></a></td><td>" + data.email + "</td><td>" + data.description + "</td><td><a onclick='ChangeSendEmailStatus(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a></td></tr>";
                } else {
                    cerender += "<tr><td><a onclick='EditEmailsConfiguration(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-edit'></span></a></td><td>" + data.email + "</td><td>" + data.description + "</td><td><a onclick='ChangeSendEmailStatus(" + data.id + ")' class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a></td></tr>";
                }
            });
            cerender += '</tbody></table></div>';
        }
    }).complete(function () {
        $('#processing-modal').modal('hide');
        $('#rendertable').html(cerender);
    }).complete(function () {
        $('#tblconfigurationemails').DataTable({
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
    });*/
}

function CreateEmailAccount() {
    $.ajax({
        url: 'index.php?r=EmailsConfiguration/CreateEmail',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).complete(function () {
        $.ajax({
            url: 'index.php?r=EmailsConfiguration/CreateEmailData',
            success: function (response) {
                var userviewrender = '';
                var sumrows = 0;
                var createuserview = JSON.parse(response);
                createuserviewjs = createuserview;
                for (var i = 0; i < createuserview.data.length; i++) {
                    if (sumrows == 0) {
                        userviewrender += '<div class="row">';
                    }
                    switch (createuserview.data[i].inputtype) {
                        case "text":
                            {
                                userviewrender += '<div class="col-md-' + createuserview.data[i].size + '"><div class="form-group"><label>' + createuserview.data[i].columndescription.toUpperCase() + '</label><input type="text" id="' + createuserview.data[i].columnname + '" class="form-control ' + createuserview.data[i].classvalidation + '" placeholder="' + createuserview.data[i].columndescription + '" maxlength="' + createuserview.data[i].length + '" ></div></div>';
                            }
                            break;
                        case "select":
                            {
                                userviewrender += '<div class="col-md-' + createuserview.data[i].size + '"><div class="form-group"><label>' + createuserview.data[i].columndescription.toUpperCase() + '</label>' +
                                        '<select class="selectpicker" id="' + createuserview.data[i].columnname + '" title="Seleccione un tipo..">';
                                for (var j = 0; j < createuserview.storagemethod[createuserview.data[i].columnname].length; j++) {
                                    userviewrender += '<option value="' + createuserview.storagemethod[createuserview.data[i].columnname][j].id + '">' + createuserview.storagemethod[createuserview.data[i].columnname][j].description + '</option>';
                                }
                                userviewrender += '</select></div></div>';
                            }
                            break;
                        default:
                    }
                    sumrows += parseInt(createuserview.data[i].size);
                    if (sumrows == 12) {
                        userviewrender += '</div>';
                        sumrows = 0;
                    }
                }
                if (sumrows != 12) {
                    userviewrender += '</div>';
                }
                $('.createemaildata').html(userviewrender);
                $('#iduserstate').val(1);
            }
        }).complete(function () {
            $('.selectpicker').selectpicker('refresh');
        });
    });
}

function SaveEmailConfiguration() {
    var Emaildata = {};
    Emaildata.Company = $('#idcompany').val();
    Emaildata.Email = $('#email').val();
    Emaildata.Document = $('#idmaildocument').val();
    if (!isValidEmailAddress(Emaildata.Email)) {
        swal("Alerta", "Por favor ingrese una direccion de correo valida!", "error");
    } else if ((Emaildata.Company == "") || (Emaildata.Email == "") || (Emaildata.Document == "")) {
        swal("Alerta", "Ningun campo puede quedar vacio", "error");
    } else {
        $.ajax({
            data: {
                'Email': Emaildata
            },
            type: 'post',
            url: 'index.php?r=EmailsConfiguration/QueryEmailConfigurationExistence',
            success: function (response) {
                if (response == "NO") {
                    swal("Alerta", "La configuracion de correo que esta intentando crear ya existe!", "error");
                } else if (response == "OK") {
                    var userinfo = createuserviewjs;
                    var userarray = [];
                    userarray = [];
                    for (var j = 0; j < userinfo.data.length; j++) {
                        if ($('#' + userinfo.data[j].columnname).val() != undefined)
                        {
                            userarray[userinfo.data[j].idcolumn] = $('#' + userinfo.data[j].columnname).val();
                        }
                    }
                    var stringdata = "";
                    $.each(userarray, function (key, data) {
                        if (data != undefined) {
                            stringdata += "'" + data + "'" + '|~|';
                        }
                    });
                    Emaildata = {};
                    if (stringdata.length > 0) {
                        stringdata = stringdata.slice(0, -3);
                        Emaildata.data = stringdata;
                    }
                    $.ajax({
                        data: {
                            'Email': Emaildata
                        },
                        type: 'post',
                        url: 'index.php?r=EmailsConfiguration/SaveNewEmailConfiguration',
                        success: function (response) {
                            if (response == "OK")
                            {
                                swal("Guardado!", "La configuracion de correo se ha guardado con éxito!", "success");
                                LoadEmailConfiguration();
                            } else {
                                swal("Alerta", "Hubo un error guardando la configuracion de correo!", "error");
                            }
                        }
                    });
                } else {
                    swal("Alerta", "Hubo un error consultando si el usuario existe!", "error");
                }
            }
        });
    }
}

var companyid;
var maildocumentid;
var email = "";

function EditEmailsConfiguration(id) {
    companyeditid = id;
    companyid = 0;
    stateid = 0;
    maildocumentid = 0;
    $('#processing-modal').modal('show');
    $.ajax({
        url: 'index.php?r=EmailsConfiguration/EditEmailsConfiguration',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).complete(function () {
        var fillselect = {};
        fillselect.email = id;
        fillselect.data = [];
        $.ajax({
            data: {
                id: fillselect
            },
            type: 'post',
            url: 'index.php?r=EmailsConfiguration/DataforEditEmail',
            success: function (response) {
                var companyviewrender = '';
                var sumrows = 0;
                var editcompanyview = JSON.parse(response);
                usereditviewjs = editcompanyview;
                var ban = true;
                for (var r = 0; r < editcompanyview.data.length; r++) {
                    for (var i = 0; i < editcompanyview.data[r].length; i++) {
                        if (sumrows == 0) {
                            if ((r > 0) && (ban)) {
                                companyviewrender += '<div class="row otherdata"><div class="row">';
                                ban = false;
                            } else
                                companyviewrender += '<div class="row">';
                        }
                        switch (editcompanyview.data[r][i].inputtype) {
                            case "text":
                                {
                                    companyviewrender += '<div class="col-md-' + editcompanyview.data[r][i].size + '"><div class="form-group"><label>' + editcompanyview.data[r][i].columndescription.toUpperCase() + '</label><input type="text" id="' + editcompanyview.data[r][i].columnname + '" class="form-control ' + editcompanyview.data[r][i].classvalidation + '" placeholder="' + editcompanyview.data[r][i].columndescription + '" value="' + editcompanyview.data[r][i].value + '" maxlength="' + editcompanyview.data[r][i].length + '"></div></div>';
                                    email = editcompanyview.data[r][i].value;
                                }
                                break;
                            case "select":
                                {
                                    if (editcompanyview.data[r][i].columnname == 'idcompany') {
                                        var company = $.grep(editcompanyview.storagemethod[editcompanyview.data[r][i].columnname], function (element, index) {
                                            return element.id == editcompanyview.data[r][i].value;
                                        });
                                        if (typeof company[0] === "undefined") {

                                        } else {
                                            companyid = company[0].id;
                                        }
                                    }
                                    if (editcompanyview.data[r][i].columnname == 'idstate') {
                                        var state = $.grep(editcompanyview.storagemethod[editcompanyview.data[r][i].columnname], function (element, index) {
                                            return element.id == editcompanyview.data[r][i].value;
                                        });
                                        if (typeof state[0] === "undefined") {

                                        } else {
                                            stateid = state[0].id;
                                        }
                                    }
                                    if (editcompanyview.data[r][i].columnname == 'idmaildocument') {
                                        var maildocument = $.grep(editcompanyview.storagemethod[editcompanyview.data[r][i].columnname], function (element, index) {
                                            return element.id == editcompanyview.data[r][i].value;
                                        });
                                        if (typeof maildocument[0] === "undefined") {

                                        } else {
                                            maildocumentid = maildocument[0].id;
                                        }
                                    }
                                    companyviewrender += '<div class="col-md-' + editcompanyview.data[r][i].size + '"><div class="form-group"><label>' + editcompanyview.data[r][i].columndescription.toUpperCase() + '</label>' +
                                            '<select class="selectpicker" id="' + editcompanyview.data[r][i].columnname + '" title="Seleccione un tipo..">';
                                    for (var j = 0; j < editcompanyview.storagemethod[editcompanyview.data[r][i].columnname].length; j++) {
                                        companyviewrender += '<option value="' + editcompanyview.storagemethod[editcompanyview.data[r][i].columnname][j].id + '">' + editcompanyview.storagemethod[editcompanyview.data[r][i].columnname][j].description + '</option>';
                                    }
                                    companyviewrender += '</select></div></div>';
                                    fillselect.data.push({id: editcompanyview.data[r][i].columnname, value: editcompanyview.data[r][i].value});
                                }
                                break;
                            case "number":
                                {
                                    companyviewrender += '<div class="col-md-' + editcompanyview.data[r][i].size + '"><div class="form-group"><label>' + editcompanyview.data[r][i].columndescription.toUpperCase() + '</label><input type="number" min="0" id="' + editcompanyview.data[r][i].columnname + '" class="form-control ' + editcompanyview.data[r][i].classvalidation + '" placeholder="' + editcompanyview.data[r][i].columndescription + '" value="' + editcompanyview.data[r][i].value + '" maxlength="' + editcompanyview.data[r][i].length + '"></div></div>';
                                    dayscancellationlimit = editcompanyview.data[r][i].value;
                                }
                                break;
                            default:
                        }
                        sumrows += parseInt(editcompanyview.data[r][i].size);
                        if (sumrows == 12) {
                            companyviewrender += '</div>';
                            sumrows = 0;
                        }
                    }
                    if ((sumrows != 12) && (sumrows != 0)) {
                        companyviewrender += '</div>';
                        sumrows = 0;
                    }
                }
                $('.editemaildata').html(companyviewrender);
            }
        }).complete(function () {
            $('.selectpicker').selectpicker('refresh');
            for (var i = 0; i < fillselect.data.length; i++) {
                $('#' + fillselect.data[i].id).val(fillselect.data[i].value);
            }
            $('#idcompany').val(companyid);
            $('#idstate').val(stateid);
            $('#idmaildocument').val(maildocumentid);
            $('.selectpicker').selectpicker('refresh');
            $('#processing-modal').modal('hide');
        });
    });
}

function SaveEmailEdit() {
    var data = {};
    data.Company = $('#idcompany').val();
    data.Email = $('#email').val();
    data.Document = $('#idmaildocument').val();
    data.State = $('#idstate').val();
    data.EmailId = companyeditid;
    if (!isValidEmailAddress(data.Email)) {
        swal("Alerta", "Por favor ingrese una direccion de correo valida!", "error");
    } else if (data.Email == "") {
        swal("Alerta", "El campo de correo no puede estar vacio", "error");
    } else if (companyid == data.Company && email == data.Email && maildocumentid == data.Document && stateid == data.State) {
        swal("Alerta", "No ha realizado ningun cambio!", "error");
    } else {
        $('#processing-modal').modal('show');
        $.ajax({
            data: {
                'Email': data
            },
            type: 'post',
            url: 'index.php?r=EmailsConfiguration/QueryEmailConfigurationExistenceEdit',
            success: function (response) {
                if (response == "NO")
                {
                    $('#processing-modal').modal('hide');
                    swal("Alerta", "La configuracion de correo que esta intentando editar ya existe!", "error");
                } else if (response == "OK") {
                    data = {};
                    data.EmailId = companyeditid;
                    var userinfo = usereditviewjs;
                    var userarray = [];
                    var contpos = 0;
                    for (var i = 0; i < userinfo.data.length; i++) {
                        userarray = [];
                        for (var j = 0; j < userinfo.data[i].length; j++) {
                            if (($('#' + userinfo.data[i][j].columnname).val() != undefined) && ($('#' + userinfo.data[i][j].columnname).val() != "")) {
                                userarray[contpos] = $('#' + userinfo.data[i][j].columnname).val();
                                if (userinfo.data[i][j].columnname == "email") {
                                    if (!isValidEmailAddress(userarray[contpos])) {

                                        return;
                                    }
                                }
                                contpos++;
                            }
                        }
                        var stringdata = "";
                        $.each(userarray, function (key, data) {
                            if (data != undefined) {
                                stringdata += "'" + data + "'" + '|~|';
                            }
                        });
                        if (stringdata.length > 0) {
                            stringdata = stringdata.slice(0, -3);
                            data.data = stringdata;
                        }
                    }
                    $.ajax({
                        data: {
                            'data': data
                        },
                        type: 'post',
                        url: 'index.php?r=EmailsConfiguration/SaveEmailConfigurationEdit',
                        success: function (response) {
                            $('#processing-modal').modal('hide');
                            if (response == "OK")
                            {
                                swal("Guardado!", "La configuracion de correo se ha guardado con exito!", "success");
                                LoadEmailConfiguration();
                            } else {
                                swal("Alerta", "Hubo un error guardando la configuracion de correo!", "error");
                            }
                        }
                    });
                }
            }
        });
    }
}

function LoadEmailConfiguration() {
    $('#itemlstEmailsConfiguration').trigger('click');
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}
;