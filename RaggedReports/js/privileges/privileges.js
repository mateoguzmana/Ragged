var profileid = "";
var profilename = "";
var typeprofileid = "";

$('body').on('click', '#btneditprofile', function () {
    var profile = $('#profile').val();
    if (profile == "") {
        swal("Debe seleccionar un perfil para poder editarlo");
    } else {
        profileid = profile;
        //localStorage.setItem("profileid", profile);
        profilename = $("#profile option:selected").text();
        //localStorage.setItem("profilename", $("#profile option:selected").text());
        $.ajax({
            data: {
                'profile': profile
            },
            type: 'post',
            url: 'index.php?r=Privileges/EditProfile',
            success: function (response) {
                $('.renderprofiles').html(response);
            }
        });
    }
});

$('body').on('click', '#btncreateprofile', function () {
    $.ajax({
        url: 'index.php?r=Privileges/LoadCreateProfileView',
        success: function (response) {
            $('.renderprofiles').html(response);
        }
    });
});

$('body').on('click', '#btndeleteprofile', function () {
    var profile = $('#profile').val();
    if (profile == "") {
        swal("Debe seleccionar un perfil para poder eliminarlo!");
    } else {
        swal({
            title: "¿Está seguro que desea borrar este perfil?",
            //text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Confirmar",
            closeOnConfirm: false
        },
        function () {
            var profilejson = {};
            profilejson.profileid = profile;
            $.ajax({
                data: {
                    'profile': profilejson
                },
                type: 'post',
                url: 'index.php?r=Privileges/DeleteProfile',
                success: function (resp) {
                    var response = JSON.parse(resp);
                    if (response == "OK") {
                        swal("Borrado!", "El perfil ha sido borrado del sistema!", "success");
                        LoadProfileView();
                    } else {
                        swal("Alerta!", "Ocurrio un error eliminando el perfil", "warning");
                    }
                }
            });
        });
    }
});

$('body').on('change', '.checkall', function () {
    var id = $(this).attr('data-chkall');
    if ($(this).is(':checked')) {
        $('.' + id).each(function () {
            $(this).prettyCheckable('check');
        });
    } else {
        $('.' + id).each(function () {
            $(this).prettyCheckable('uncheck');
        });
    }
});

$('body').on('click', '#btnsaveprofile', function () {
    var nameprofile = $('#txtnameprofile').val();
    if ($.trim(nameprofile) == "") {
        swal("No ha ingresado ningún nombre para el perfil");
    } else {
        var profiletype = $('#profiletype').val();
        if (profiletype == "") {
            swal("No ha seleccionado ningún tipo de perfil");
        } else {
            var ban = true;
            $("#profile option").each(function () {
                if ($.trim($(this).text().toLowerCase()) == $.trim(nameprofile.toLowerCase())) {
                    ban = false;
                }
            });
            if (!ban) {
                swal("el perfil que esta intentando crear ya existe!");
            } else {
                var general = {};
                general.name = nameprofile;
                general.profiletype = profiletype;
                general.data = [];
                $('.chk').each(function () {
                    var data = $(this).attr('data-chk');
                    var info = data.split('-');
                    if ($(this).is(':checked')) {
                        general.data.push({module: info[0], typeprivilege: info[1], submodule: info[2], option: info[3], active: 1});
                    } else {
                        general.data.push({module: info[0], typeprivilege: info[1], submodule: info[2], option: info[3], active: 0});
                    }
                });
                if (general.data.length > 0) {
                    $.ajax({
                        data: {
                            'profile': general
                        },
                        dataType: "json",
                        type: 'post',
                        url: 'index.php?r=Privileges/SaveProfile',
                        success: function (response) {
                            if (response == "OK") {
                                swal("Guardado!", "El perfil ha sido guardado exitosamente!", "success");
                                LoadProfileView();
                            } else
                                swal(response);
                        }
                    });
                } else {
                    swal("No ha seleccionado ningun elemento");
                }
            }
        }
    }
});

$('body').on('click', '#btnsaveprofileedit', function () {
    var nameprofile = $('#txtnameprofile').val();
    if ($.trim(nameprofile) == "") {
        swal("El nombre del perfil no puede estar vacio");
    } else {
        var ban = true;
        var ban2 = true;
        $("#profile option").each(function () {
            if ($.trim($(this).text().toLowerCase()) == $.trim(nameprofile.toLowerCase())) {
                ban2 = false;
                if ($(this).val() != profileid) {
                    ban = false;
                }
            }
        });
        if (!ban) {
            swal("el perfil que esta intentando cambiar ya existe!");
        } else {
            var general = {};
            general.profileid = profileid;
            if (ban2) {
                general.name = nameprofile;
            } else {
                general.name = "";
            }
            var profiletype = $('#profiletype').val();
            if (typeprofileid == profiletype) {
                general.profiletype = "";
            } else {
                general.profiletype = profiletype;
            }
            general.data = [];
            general.datae = [];
            $('.chk').each(function () {
                if ($(this).is(':checked')) {
                    var data = $(this).attr('data-chk');
                    var info = data.split('-');
                    if (info[4] != 1) {
                        general.data.push({Module: info[0], SubModule: info[1], Option: info[2], privilegeid: info[3]});
                    }
                } else {
                    var data = $(this).attr('data-chk');
                    var info = data.split('-');
                    if (info[4] == 1) {
                        general.data.push({Module: info[0], SubModule: info[1], Option: info[2], privilegeid: info[3]});
                    }
                }
            });
            if ((general.data.length > 0) || (general.datae.length > 0) || (general.name != "") || (general.profiletype != "")) {
                $.ajax({
                    data: {
                        'profile': general
                    },
                    dataType: "json",
                    type: 'post',
                    url: 'index.php?r=Privileges/SaveProfileEdit',
                    success: function (response) {
                        if (response = "OK") {
                            swal("Correcto!", "El perfil ha sido actualizado correctamente!", "success");
                            LoadProfileView();
                        } else {
                            swal("Error!", "Ocurrio un error guardando la información", "error");
                        }
                    }
                });
            } else {
                swal({
                    title: "No ha realizado ninguna modificación!",                    
                    type: "warning",                    
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Cerrar",
                    closeOnConfirm: false
                });
            }
        }
    }
});
function getPrivileges() {
    var cont = 1;
    var jsonarr = {};
    jsonarr.privilege = [];
    $("#privilege option").each(function () {
        if ($(this).val() != "") {
            jsonarr.privilege.push({"privilegeid": $(this).val(), "description": $(this).text()});
        }
    });
    $('#lblview').html('Crear Perfil');
    $('.privilegesrender').empty();
    $.ajax({
        url: 'index.php?r=Privileges/allModSubOpt',
        success: function (response) {
            var modules = JSON.parse(response);
            for (var m = 0; m < jsonarr.privilege.length; m++) {
                var modulesappend = "";
                modulesappend += '<p class="h2">' + jsonarr.privilege[m].description + '</p>';
                for (var i = 0; i < modules.length; i++) {
                    if (jsonarr.privilege[m].privilegeid == modules[i].privilegetypeid) {
                        modulesappend += '<div class="col-md-12"><div class="card"><div class="header"><h4 class="title">' + modules[i].moduledescription + '</h4></div>';
                        for (var j = 0; j < modules[i].submodules.length; j++) {
                            modulesappend += '<div class="header"><p class="category"><input type="checkbox" class="checkall" data-chkall="' + cont + '" id="answer" name="" />' + modules[i].submodules[j].submodule.submoduledescription + '</p></div><div class="content table-responsive table-full-width"><table class="table table-hover table-striped"><thead><th>Opciones</th><th></th></thead><tbody>';
                            for (var k = 0; k < modules[i].submodules[j].submodule.options.length; k++) {
                                modulesappend += '<tr>' +
                                        '<td>' + modules[i].submodules[j].submodule.options[k].option.optiondescription + '</td>' +
                                        '<td><input type="checkbox" class="chk ' + cont + '" id="answer" name="" data-chk="' + modules[i].idmodule + '-' + modules[i].privilegetypeid + '-' + modules[i].submodules[j].submodule.idsubmodule + '-' + modules[i].submodules[j].submodule.options[k].option.idoption + '" /></td></tr>';
                            }
                            modulesappend += '</tbody></table></div>';
                            cont++;
                        }
                        modulesappend += '</div></div>';
                    }
                }
                $('.privilegesrender').append(modulesappend);
            }
            $(".chk").each(function () {
                $(this).prettyCheckable();
            });
            $(".checkall").each(function () {
                $(this).prettyCheckable();
            });
            $("label[for='answer']").hide();
        }
    });
}

function getPrivilegesedit() {
    var profile = $('#profile').val();
    var cont = 1;
    var jsonarr = {};
    $('#txtnameprofile').val(profilename);
    jsonarr.privilege = [];
    $("#privilege option").each(function () {
        if ($(this).val() != "") {
            jsonarr.privilege.push({"privilegeid": $(this).val(), "description": $(this).text()});
        }
    });
    $('#lblview').html('Editar Perfil');
    $('.privilegesrender').empty();
    var selectval = "";
    $.ajax({
        data: {
            'profile': profile
        },
        type: 'post',
        url: 'index.php?r=Privileges/QueryProfileEdit',
        success: function (response) {
            var modules = JSON.parse(response);
            for (var m = 0; m < jsonarr.privilege.length; m++) {
                var modulesappend = "";
                modulesappend += '<p class="h2">' + jsonarr.privilege[m].description + '</p>';
                for (var i = 0; i < modules.AllMSO.length; i++) {
                    if (jsonarr.privilege[m].privilegeid == modules.AllMSO[i].privilegetypeid) {
                        modulesappend += '<div class="col-md-12"><div class="card"><div class="header"><h4 class="title">' + modules.AllMSO[i].moduledescription + '</h4></div>';
                        for (var j = 0; j < modules.AllMSO[i].submodules.length; j++) {
                            modulesappend += '<div class="header"><p class="category"><label><input type="checkbox" class="checkall" data-chkall="' + cont + '" id="answer" name=""/>' + modules.AllMSO[i].submodules[j].submodule.submoduledescription + '</label></p></div><div class="content table-responsive table-full-width"><table class="table table-hover table-striped"><thead><th>Opciones</th><th></th></thead><tbody>';
                            for (var k = 0; k < modules.AllMSO[i].submodules[j].submodule.options.length; k++) {
                                //if (modules[i].submodules[j].submodule.options[k].option.privilegeid == "") {
                                modulesappend += '<tr><td>' + modules.AllMSO[i].submodules[j].submodule.options[k].option.optiondescription + '</td>' +
                                        '<td><input type="checkbox" class="chk ' + cont + '" id="answer" name="" data-chk="' + modules.AllMSO[i].idmodule + '-' + modules.AllMSO[i].submodules[j].submodule.idsubmodule + '-' + modules.AllMSO[i].submodules[j].submodule.options[k].option.idoption + '-' + modules.AllMSO[i].submodules[j].submodule.options[k].option.privilegeid + '-' + modules.AllMSO[i].submodules[j].submodule.options[k].option.active + '"'
                                //'<td><input type="checkbox" class="chk ' + cont + '" data-chk="' + modules[i].submodules[j].submodule.options[k].option.privilegeid + '-' + modules[i].submodules[j].submodule.options[k].option.active + '"';
                                if (modules.AllMSO[i].submodules[j].submodule.options[k].option.active == 1) {
                                    modulesappend += 'checked /></td></tr>';
                                } else {
                                    modulesappend += ' /></td></tr>';
                                }
                                /*if (modules[i].submodules[j].submodule.options[k].option.active == 1) {
                                 modulesappend += 'checked></td></tr>';
                                 } else {
                                 modulesappend += '></td></tr>';
                                 }*/
                                /*} else {
                                 modulesappend += '<tr><td>' + modules[i].submodules[j].submodule.options[k].option.optiondescription + '</td>' +
                                 '<td><input type="checkbox" class="chk ' + cont + '" id="" data-chk="' + modules[i].idmodule + '-' + modules[i].submodules[j].submodule.idsubmodule + '-' + modules[i].submodules[j].submodule.options[k].option.idoption + '-' + modules[i].privilegetypeid + '-' + modules[i].submodules[j].submodule.options[k].option.privilegeid + '" checked></td></tr>';
                                 }*/
                            }
                            modulesappend += '</tbody></table></div>';
                            cont++;
                        }
                        modulesappend += '</div></div>';
                    }
                }
                $('.privilegesrender').append(modulesappend);
            }
            var selectprofiletype = "";
            selectprofiletype += '<div class="col-md-4"><div class="form-group"><label for="profiletype">TIPO PERFIL</label><select class="selectpicker" id="profiletype" title="Seleccione un tipo..">';
            //alert(JSON.stringify(modules.userprofiletypeid));
            //alert(JSON.stringify(modules.profilestypes));
            for (var n = 0; n < modules.profilestypes.length; n++) {
                //alert(modules.profilestypes[n].idprofiletype +'=='+ modules[3].userprofiletypeid);
                selectprofiletype += '<option value="' + modules.profilestypes[n].idprofiletype + '">' + modules.profilestypes[n].profiletypedescription + '</option>';
                if (modules.profilestypes[n].idprofiletype == modules.userprofiletypeid) {
                    //selectprofiletype += '<option value="' + modules.profilestypes[n].idprofiletype + '">' + modules.profilestypes[n].profiletypedescription + '</option>';
                    selectval = modules.profilestypes[n].idprofiletype;
                } /*else {
                 selectprofiletype += '<option value="' + modules.profilestypes[n].idprofiletype + '">' + modules.profilestypes[n].profiletypedescription + '</option>';
                 }*/
            }
            selectprofiletype += '</select></div></div>';
            $(".rendertypeprofile").html(selectprofiletype);
        }
    }).done(function () {
        $('.selectpicker').selectpicker('refresh');
        typeprofileid = selectval;
        //localStorage.setItem("typeprofileid", selectval);
        $('#profiletype').val(selectval);
        $('.selectpicker').selectpicker('refresh');
        $(".chk").each(function () {
            $(this).prettyCheckable();
        });
        $(".checkall").each(function () {
            $(this).prettyCheckable();
        });
        $("label[for='answer']").hide();
    });
}

function LoadProfileView() {
    $('#itemlstPrivileges').trigger('click');
}