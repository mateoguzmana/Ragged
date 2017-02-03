$('body').on('click', '#btneditprofiletype', function () {
    var profiletype = $('#profiletype').val();
    if (profiletype === '') {
        swal('Por favor seleccione un tipo de perfil para poder editarlo');
    } else {
        localStorage.setItem("profiletypeid", profiletype);
        localStorage.setItem("profiletypename", $("#profiletype option:selected").text());
        $.ajax({
            data: {
                'profiletype': profiletype
            },
            type: 'post',
            url: 'index.php?r=ProfileTypes/EditProfileType',
            success: function (response) {
                $('.renderprofilestypes').html(response);
            }
        });
    }
});

$('body').on('click', '#btncreateprofiletype', function () {

    $.ajax({
        url: 'index.php?r=ProfileTypes/LoadCreateProfileTypeView',
        success: function (response) {
            $('.renderprofilestypes').html(response);
        }
    });
});


$('body').on('click', '#btndeleteprofiletype', function () {
    var profiletype = $('#profiletype').val();
    if (profiletype === '') {
        swal('Por favor seleccione un tipo de perfil para poder eliminarlo');
    } else {
        swal({
            title: "�Est� seguro que desea eliminar el tipo de perfil?", //+ $('#profiletype option:selected').text(),
            type: 'warning',
            shwoCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Confirmar',
            closeOnConfirm: false
        },
                function () {
                    var profiletypejson = {};
                    profiletypejson.profiletypeid = profiletype;
                    $.ajax({
                        data: {
                            'profiletype': profiletype
                        },
                        type: 'post',
                        url: 'index.php?r=ProfileTypes/DeleteProfileType',
                        success: function (response) {
                            switch (response) {
                                case '1':
                                    swal("�Borrado!", "El tipo de perfil ha sido borrado correctamente", "success");
                                    LoadProfileTypeView();
                                    break;
                                case '2':
                                    swal("�Atenci�n!", "No se puede borrar este tipo de perfil porque ya est� asociado a un perfil", "success");
                                    break;
                                case '3':
                                    swal("�Alert!", "Ocurri� un error eliminando del tipo de perfil", "success");
                                    break;
                                default:
                                    swal("�Alert!", "Ocurri� un error eliminando del tipo de perfil", "success");
                            }
                            //var response = JSON.parse(result);
/*                            if (response === 'true') {
                                swal("�Borrado!", "El tipo de perfil ha sido borrado correctamente", "success");
                                LoadProfileTypeView();

                            } else {
                                swal("�Alert!", "Ocurri� un error eliminando del tipo de perfil", "success");
                            }*/
                        }
                    });
                });
    }
});

$('body').on('click', '#btnsaveprofiletype', function () {
    var nameprofiletype = $('#txtnameprofiletype').val();
    if ($.trim(nameprofiletype) === "") {
        swal("�No ha ingresado ning�n nombre para el tipo de perfil!");
    } else {
        var ban = true;
        $("#profiletype option").each(function () {
            if ($.trim($(this).text().toLowerCase()) === $.trim(nameprofiletype.toLowerCase())) {
                ban = false;
            }
        });
        if (!ban) {
            swal("�El tipo de perfil que est� intentando crear ya existe!");
        } else {
            $.ajax({
                data: {
                    'profiletype': nameprofiletype
                },
                type: 'post',
                url: 'index.php?r=ProfileTypes/SaveProfileType',
                success: function (response) {
                    if (response === 'true') {
                        swal("�Guardado!", "�El tipo de perfil ha sido guardado exitosamente!", "success");
                        LoadProfileTypeView();
                    } else
                        swal(response);
                }
            });
        }
    }
});



$('body').on('click', '#btnsaveprofiletypeedit', function () {
    var nameprofiletype = $('#txtnameprofiletype').val();

    if ($.trim(nameprofiletype) === "") {
        swal("El nombre del tipo de perfil no puede estar vac�o");
    } else {
        var ban = true;
        var ban2 = true;
        $("#profiletype option").each(function () {
            if ($.trim($(this).text().toLowerCase()) === $.trim(nameprofiletype.toLowerCase())) {
                ban2 = false;
                if ($(this).val() !== localStorage.getItem("profiletypeid")) {
                    ban = false;
                }
            }
        });
        if (!ban) {
            swal("�El tipo de perfil que est� intentando cambiar ya existe!");
        } else {
            var profiletypejson = {};
            profiletypejson.profiletypeid = localStorage.getItem("profiletypeid");
            profiletypejson.profiletypename = nameprofiletype;
            $.ajax({
                data: {
                    'profiletype': profiletypejson
                },
                type: 'post',
                url: 'index.php?r=ProfileTypes/SaveProfileTypeEdit',
                success: function (response) {
                    if (response === 'true') {
                        swal("�Correcto!", "�El perfil ha sido actualizado correctamente!", "success");
                        LoadProfileTypeView();
                    } else {
                        swal("Error!", "Ocurri� un error guardando la informaci�n", "error");
                    }
                }
            });
        }
    }
});


function LoadProfileTypeView() {
    $('#itemlstProfileTypes').trigger('click');
}


