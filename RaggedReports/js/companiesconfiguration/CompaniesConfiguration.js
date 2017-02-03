//$(document).ready(function () {
var companyeditid = "";
var usereditprofile = "";
var usereditviewjs = "";
var pricelistid;
var stateid;
var dayscancellationlimit;

function EditCompany(id) {
    companyeditid = id;
    $('#processing-modal').modal('show');
    $.ajax({
        url: 'index.php?r=CompaniesConfiguration/EditCompany',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).complete(function () {
        var fillselect = {};        
        fillselect.company = id;
        fillselect.data = [];
        $.ajax({
            data: {
                id: fillselect
            },
            type: 'post',
            url: 'index.php?r=CompaniesConfiguration/DataforEditCompany',
            success: function (response) {
                var companyviewrender = '';
                var sumrows = 0;
                var ban = true;
                var editcompanyview = JSON.parse(response);
                usereditviewjs = editcompanyview;                
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
                                    companyviewrender += '<div class="col-md-' + editcompanyview.data[r][i].size + '"><div class="form-group"><label>' + editcompanyview.data[r][i].columndescription.toUpperCase() + '</label><input type="text" id="' + editcompanyview.data[r][i].columnname + '" class="form-control ' + editcompanyview.data[r][i].classvalidation + '" placeholder="' + editcompanyview.data[r][i].columndescription + '" value="' + editcompanyview.data[r][i].value + '" maxlength="' + editcompanyview.data[r][i].length + '" readonly></div></div>';
                                }
                                break;
                            case "select":
                                {
                                    if (editcompanyview.data[r][i].columnname == 'idpricelist') {
                                        var pricelist = $.grep(editcompanyview.storagemethod[editcompanyview.data[r][i].columnname], function (element, index) {
                                            return element.id == editcompanyview.data[r][i].value;
                                        });
                                        if (typeof pricelist[0] === "undefined") {
                                            
                                        } else {
                                            pricelistid = pricelist[0].id;
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
                $('.editcompanydata').html(companyviewrender);
            }
        }).complete(function () {
            $('.selectpicker').selectpicker('refresh');
            for (var i = 0; i < fillselect.data.length; i++) {
                $('#' + fillselect.data[i].id).val(fillselect.data[i].value);
            }
            $('#idpricelist').val(pricelistid);
            $('#idstate').val(stateid);
            $('.selectpicker').selectpicker('refresh');
            $('#processing-modal').modal('hide');
        });
    });
}
//});

function ChangeCompanyStatus(id) {
    var Company = {};
    Company.CompanyId = id;
    $('#processing-modal').modal('show');
    $.ajax({
        data: {
            'Company': Company
        },
        type: 'post',
        url: 'index.php?r=CompaniesConfiguration/ChangeCompanyStatus',
        success: function (response) {
            $('#processing-modal').modal('hide');
            if (response != "OK") {
                swal("Alerta", "Ocurrio un error cambiando el estado de la compañia", "error");
            } else {
                LoadCompanyConfiguration();
                /*var span = $(this).children('span').attr('class');
                 if (span=="glyphicon glyphicon-ok"){
                 $(this).children('span').removeclass('glyphicon glyphicon-ok').addclass('glyphicon glyphicon-remove');
                 } else {
                 $(this).children('span').removeclass('glyphicon glyphicon-remove').addclass('glyphicon glyphicon-ok');
                 }*/
            }
        }
    });
}

function ChangeBusinessStatus(busrulid, idstat) {
    if (idstat == 1) {
        swal("Alerta", "Esta regla de negocio no permite modificarse!", "error");
    } else {
        $('#processing-modal').modal('show');
        var BusinessRule = {};
        BusinessRule.busrulid = busrulid;
        $.ajax({
            data: {
                'BusinessRule': BusinessRule
            },
            type: 'post',
            url: 'index.php?r=CompaniesConfiguration/ChangeBusinessStatus',
            success: function (response) {
                $('#processing-modal').modal('hide');
                if (response != "OK") {
                    swal("Alerta", "Ocurrio un error cambiando el estado de la regla de negocio", "error");
                } else {
                    getBusinessRules();
                    /*var span = $(this).children('span').attr('class');
                     if (span=="glyphicon glyphicon-ok"){
                     $(this).children('span').removeclass('glyphicon glyphicon-ok').addclass('glyphicon glyphicon-remove');
                     } else {
                     $(this).children('span').removeclass('glyphicon glyphicon-remove').addclass('glyphicon glyphicon-ok');
                     }*/
                }
            }
        });
    }
}

$('body').on('click', '#btnsavecompanyedit', function () {
    var data = {};
    data.pricelist = $('#idpricelist').val();
    data.state = $('#idstate').val();
    data.dayscancellation = $('#dayscancellationlimit').val();
    data.company = companyeditid;
    if (data.dayscancellation == "") {
        swal("Alerta", "El campo Limite dias de cancelacion no puede estar vacio", "error");
    } else if (pricelistid == data.pricelist && stateid == data.state && dayscancellationlimit == data.dayscancellation) {
        swal("Alerta", "No ha realizado ningun cambio!", "error");
    } else {
        $('#processing-modal').modal('show');
        $.ajax({
            data: {
                'data': data
            },
            type: 'post',
            url: 'index.php?r=CompaniesConfiguration/SaveCompanyEdit',
            success: function (response) {
                $('#processing-modal').modal('hide');
                if (response == "OK")
                {
                    swal("Guardado!", "La compañia se ha guardado con exito!", "success");
                    LoadCompanyConfiguration();
                } else {
                    swal("Alerta", "Hubo un error guardando la compañia!", "error");
                }
            }
        });
    }
});

function getBusinessRules() {
    var Company = {};
    var brrender = "";
    Company.company = companyeditid;
    $.ajax({
        data: {
            'Company': Company
        },
        type: 'post',
        url: 'index.php?r=CompaniesConfiguration/LoadBusinessRules',
        success: function (response) {
            var businessrules = JSON.parse(response);
            brrender = '<div class="content"><div class="container-fluid"><div class="row"><div class="col-md-10 col-md-offset-1"><div class="card"><div class="content"><form><div class="row"><div class="1"></div></div><div class="row"><div class="col-md-12"><div class="card card-plain"><div class="header"><h3 class="title text-center">Reglas de negocio</h3></div><div class="content table-responsive table-full-width"><table class="table table-hover table-striped" id="tblbusinessrules"><thead><tr>';
            brrender += '<th>Numero</th><th>Descripcion</th><th>Estado</th></tr></thead><tbody>';
            $.each(businessrules, function (key, data) {
                if (data.idstateprocess == 0) {
                    brrender += "<tr><td>" + (key + 1) + "</td><td>" + data.descriptionbusinessrule + "</td><td>" + "<a onclick='ChangeBusinessStatus(" + data.idbusinessrulecompany + "," + data.idstatestatic + ")' class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a></td></tr>";
                } else {
                    brrender += "<tr><td>" + (key + 1) + "</td><td>" + data.descriptionbusinessrule + "</td><td>" + "<a onclick='ChangeBusinessStatus(" + data.idbusinessrulecompany + "," + data.idstatestatic + ")' class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a></td></tr>";
                }
            });
            brrender += '</tbody></table></div></div></div></div></form></div></div></div></div></div></div>';
        }
    }).complete(function () {
        $('#BusinessRules').html(brrender);
    }).complete(function () {
        $('#tblbusinessrules').DataTable({
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

function LoadCompanyConfiguration() {
    $('#itemlstCompaniesConfiguration').trigger('click');
}
