//$(document).ready(function () {
var createuserviewjs = "";
var usereditviewjs = "";
var usereditprofile = "";
var usereditiduser = "";
var birthdayidname = "";

function CreateUser() {
    $.ajax({
        url: 'index.php?r=AdminUsers/CreateUser',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).complete(function () {
        $.ajax({
            url: 'index.php?r=AdminUsers/CreateUserdata',
            success: function (response) {
                var userviewrender = '';
                var sumrows = 0;
                var createuserview = JSON.parse(response);
                createuserviewjs = createuserview;
                for (var i = 0; i < createuserview.data.length; i++) {
                    if (createuserview.data[i].idtable == 1) {
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
                                            '<select class="selectpicker" id="' + createuserview.data[i].columnname + '" data-id="1" title="Seleccione un tipo..">';
                                    for (var j = 0; j < createuserview.storagemethod[createuserview.data[i].columnname].length; j++) {
                                        userviewrender += '<option value="' + createuserview.storagemethod[createuserview.data[i].columnname][j].id + '">' + createuserview.storagemethod[createuserview.data[i].columnname][j].description + '</option>';
                                    }
                                    userviewrender += '</select></div></div>';
                                }
                                break;
                            case "date":
                                {
                                    userviewrender += '<div class="col-md-' + createuserview.data[i].size + '"><div class="form-group"><label>' + createuserview.data[i].columndescription.toUpperCase() + '</label>' +
                                            '<div class="input-group date" class="datetimepicker" id="datetimepicker1"><input type="text" id="' + createuserview.data[i].columnname + '" class="form-control" style="cursor:pointer" placeholder="' + createuserview.data[i].columndescription + '" readonly />' +
                                            '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div>';
                                    birthdayidname = createuserview.data[i].columnname;
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
                }
                if (sumrows != 12) {
                    userviewrender += '</div>';
                }
                $('.createuserdata').html(userviewrender);
                $('#iduserstate').val(1);
            }
        }).complete(function () {
            var i18n = {
                previousMonth: 'Mes anterior',
                nextMonth: 'Próximo mes',
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', "Octubre", "Noviembre", "Diciembre"],
                weekdays: ["Domingo", " Lunes ", " Martes ", " Miércoles ", " Jueves ", " Viernes ", " Sabado "],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
            };
            var picker1 = new Pikaday({
                numberOfMonths: 1,
                field: document.getElementById(birthdayidname),
                firstDay: 1,
                format: "YYYY/MM/DD",
                maxDate: moment().toDate(),
                yearRange: [1950, 2030],
                i18n: i18n,
            });
            $('.selectpicker').selectpicker('refresh');
        });
    });
}

$('body').on('click', '#btnsaveuser', function () {
    var userdata = {};
    userdata.user = $('#username').val();
    userdata.idencard = $('#identitycard').val();
    if ((userdata.user == "") || (userdata.idencard == "")) {
        swal("Alerta", "No has ingresado aun un usuario y un número de identificación", "error");
    } else {
        $.ajax({
            data: {
                'newuser': userdata
            },
            type: 'post',
            url: 'index.php?r=AdminUsers/QueryUserExistence',
            success: function (response) {
                if (response == "NO") {
                    swal("Alerta", "El usuario que esta intentando crear ya existe!", "error");
                } else if (response == "OK") {
                    var validatefields = true;
                    var userinfo = createuserviewjs;
                    userdata.tables = [];
                    var userarray = [];
                    var contpos = 0;
                    for (var i = 0; i < userinfo.tables.length; i++) {
                        userarray = [];
                        for (var j = 0; j < userinfo.data.length; j++) {
                            if (($('#' + userinfo.data[j].columnname).val() != undefined) && (userinfo.tables[i].idtable == userinfo.data[j].idtable))
                            {
                                userarray[contpos] = $('#' + userinfo.data[j].columnname).val();
                                if (userinfo.data[j].columnname == "email") {
                                    if (!isValidEmailAddress($('#' + userinfo.data[j].columnname).val())) {
                                        swal("Alerta", "Por favor ingrese una direccion de correo valida!", "error");
                                        return;
                                    }
                                }
                                if ($('#' + userinfo.data[j].columnname).val() == "") {
                                    validatefields = false;
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
                            userdata.tables.push({'tableid': userinfo.tables[i].idtable, 'data': stringdata});
                        }
                    }
                    if (validatefields) {
                        /*alert(JSON.stringify(userdata));
                         return;*/
                        $.ajax({
                            data: {
                                'newuser': userdata
                            },
                            type: 'post',
                            url: 'index.php?r=AdminUsers/SaveUser',
                            success: function (response) {
                                if (response == "OK")
                                {
                                    swal("Guardado!", "El usuario se ha guardado con éxito!", "success");
                                    LoadUserAdmin();
                                } else {
                                    swal("Alerta", "Hubo un error guardando el usuario!", "error");
                                }
                            }
                        });
                    } else {
                        swal("Alerta!", "Debe llenar todos los campos para crear el usuario!", "error");
                    }

                } else {
                    swal("Alerta", "Hubo un error consultando si el usuario existe!", "error");
                }
            }
        });
    }
});

$('body').on('click', '#btnsaveuseredit', function () {
    var userdata = {};
    userdata.user = $('#username').val();
    userdata.idencard = $('#identitycard').val();
    userdata.userid = usereditiduser;
    if ((userdata.user == "") || (userdata.idencard == "")) {
        swal("Alerta", "El campo usuario o número de identificación no pueden estar vacios", "error");
    } else {
        $.ajax({
            data: {
                'useredit': userdata
            },
            type: 'post',
            url: 'index.php?r=AdminUsers/QueryUserExistenceEdit',
            success: function (response) {
                var validatefields = true;
                if (response == "NO")
                {
                    swal("Alerta", "El usuario que esta intentando editar ya existe!", "error");
                } else if (response == "OK") {
                    userdata.usertype = usereditprofile;
                    var userinfo = usereditviewjs;
                    userdata.tables = [];
                    var userarray = [];
                    var contpos = 0;
                    for (var i = 0; i < userinfo.data.length; i++) {
                        userarray = [];
                        for (var j = 0; j < userinfo.data[i].length; j++) {
                            if ($('#' + userinfo.data[i][j].columnname).val() != undefined)
                            {
                                userarray[contpos] = $('#' + userinfo.data[i][j].columnname).val();
                                if ($('#' + userinfo.data[i][j].columnname).val() == "") {
                                    validatefields = false;
                                }
                                if (userinfo.data[i][j].columnname == "email") {
                                    if (!isValidEmailAddress($('#' + userinfo.data[i][j].columnname).val())) {
                                        swal("Alerta", "Por favor ingrese una direccion de correo valida!", "error");
                                        return;
                                    }
                                }
                                contpos++;
                            }
                            if (j == 0) {
                                var tableid = userinfo.data[i][j].idtable;
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
                            userdata.tables.push({'tableid': tableid, 'data': stringdata});
                        }
                        if (usereditprofile == 1) {
                            i = userinfo.data.length;
                        }
                    }
                    if (validatefields) {
                        $.ajax({
                            data: {
                                'user': userdata
                            },
                            type: 'post',
                            url: 'index.php?r=AdminUsers/SaveUserEdit',
                            success: function (response) {
                                if (response == "OK")
                                {
                                    swal("Guardado!", "El usuario se ha guardado con éxito!", "success");
                                    LoadUserAdmin();
                                } else {
                                    swal("Alerta", "Hubo un error guardando el usuario!", "error");
                                }
                            }
                        });
                    }
                    else {
                        swal("Alerta!", "Debe llenar todos los campos para editar el usuario!", "error");
                    }
                } else {
                    swal("Alerta!", "Hubo un error consultando si el usuario existe!", "error");
                }
            }
        });
    }
});

$('body').on('change', '#idprofile', function () {
    var id = $(this).attr('data-id');
    var profile = {'profiletype': $(this).val()};
    $.ajax({
        data: {
            'id': profile
        },
        type: 'post',
        url: 'index.php?r=AdminUsers/SellerFields',
        success: function (response) {
            if (id != 1) {
                usereditprofile = response;
            }
            if (response != 1) {
                if (id == 1) {
                    FillCreateUserSeller(createuserviewjs);
                } else {
                    FillEditUserSeller(usereditviewjs);
                }
            }
            else {
                $('.otherdata').empty();
            }
        }
    });
});

function EditUser(id) {
    //var id = $(this).attr('id');
    usereditiduser = id;
    var Dptoid;
    $.ajax({
        url: 'index.php?r=AdminUsers/EditUser',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).complete(function () {
        var fillselect = {};
        fillselect.data = [];
        fillselect.user = id;
        $.ajax({
            data: {
                id: fillselect
            },
            type: 'post',
            url: 'index.php?r=AdminUsers/EditUserdata',
            success: function (response) {
                var userviewrender = '';
                var sumrows = 0;
                var edituserview = JSON.parse(response);
                usereditprofile = edituserview.typeuser;
                usereditviewjs = edituserview;
                var ban = true;
                for (var r = 0; r < edituserview.data.length; r++) {
                    for (var i = 0; i < edituserview.data[r].length; i++) {
                        if (sumrows == 0) {
                            if ((r > 0) && (ban)) {
                                userviewrender += '<div class="row otherdata"><div class="row">';
                                ban = false;
                            } else
                                userviewrender += '<div class="row">';
                        }
                        switch (edituserview.data[r][i].inputtype) {
                            case "text":
                                {
                                    userviewrender += '<div class="col-md-' + edituserview.data[r][i].size + '"><div class="form-group"><label>' + edituserview.data[r][i].columndescription.toUpperCase() + '</label><input type="text" id="' + edituserview.data[r][i].columnname + '" class="form-control ' + edituserview.data[r][i].classvalidation + '" placeholder="' + edituserview.data[r][i].columndescription + '" value="' + edituserview.data[r][i].value + '" maxlength="' + edituserview.data[r][i].length + '"></div></div>';
                                }
                                break;
                            case "select":
                                {
                                    if (edituserview.data[r][i].columnname == 'idcity') {
                                        var Department = $.grep(edituserview.storagemethod[edituserview.data[r][i].columnname], function (element, index) {
                                            return element.id == edituserview.data[r][i].value;
                                        });
                                        if (typeof Department[0] === "undefined") {

                                        } else {
                                            Dptoid = Department[0].iddepartment;
                                        }
                                        userviewrender += '<div class="col-md-4"><div class="form-group"><label>DEPARTAMENTO</label><select class="selectpicker" id="departmente" title="Seleccione un departamento..">';
                                        for (var k = 0; k < edituserview.departments.length; k++) {
                                            userviewrender += '<option value="' + edituserview.departments[k].iddepartment + '">' + edituserview.departments[k].departmentdescription + '</option>';
                                        }
                                        userviewrender += '</select></div></div>';
                                    }
                                    userviewrender += '<div class="col-md-' + edituserview.data[r][i].size + '"><div class="form-group"><label>' + edituserview.data[r][i].columndescription.toUpperCase() + '</label>' +
                                            '<select class="selectpicker" id="' + edituserview.data[r][i].columnname + '" title="Seleccione un tipo..">';
                                    for (var j = 0; j < edituserview.storagemethod[edituserview.data[r][i].columnname].length; j++) {
                                        if (edituserview.storagemethod[edituserview.data[r][i].columnname][j].iddepartment == Dptoid) {
                                            userviewrender += '<option value="' + edituserview.storagemethod[edituserview.data[r][i].columnname][j].id + '">' + edituserview.storagemethod[edituserview.data[r][i].columnname][j].description + '</option>';
                                        }
                                    }
                                    userviewrender += '</select></div></div>';
                                    fillselect.data.push({id: edituserview.data[r][i].columnname, value: edituserview.data[r][i].value});
                                }
                                break;
                            case "date":
                                {
                                    userviewrender += '<div class="col-md-' + edituserview.data[r][i].size + '"><div class="form-group"><label>' + edituserview.data[r][i].columndescription.toUpperCase() + '</label>' +
                                            '<div class="input-group date" class="datetimepicker" id="datetimepicker1"><input type="text" id="' + edituserview.data[r][i].columnname + '" class="form-control" style="cursor:pointer" placeholder="' + edituserview.data[r][i].columndescription + '" value="' + edituserview.data[r][i].value + '" readonly />' +
                                            '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div>';
                                    birthdayidname = edituserview.data[r][i].columnname;
                                }
                                break;
                            default:
                        }
                        sumrows += parseInt(edituserview.data[r][i].size);
                        if (sumrows == 12) {
                            userviewrender += '</div>';
                            sumrows = 0;
                        }
                    }
                    if ((sumrows != 12) && (sumrows != 0)) {
                        userviewrender += '</div>';
                        sumrows = 0;
                    }
                    if (edituserview.typeuser == 1) {
                        r = edituserview.data.length;
                        userviewrender += '<div class="row otherdata"></div>';
                    }
                }
                $('.edituserdata').html(userviewrender);
            }
        }).complete(function () {
            $('.selectpicker').selectpicker('refresh');
            for (var i = 0; i < fillselect.data.length; i++) {
                $('#' + fillselect.data[i].id).val(fillselect.data[i].value);
            }
            $('#departmente').val(Dptoid);
            $('.selectpicker').selectpicker('refresh');
            var i18n = {
                previousMonth: 'Mes anterior',
                nextMonth: 'Próximo mes',
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', "Octubre", "Noviembre", "Diciembre"],
                weekdays: ["Domingo", " Lunes ", " Martes ", " Miércoles ", " Jueves ", " Viernes ", " Sabado "],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
            };
            var picker1 = new Pikaday({
                numberOfMonths: 1,
                field: document.getElementById(birthdayidname),
                firstDay: 1,
                format: 'YYYY/MM/DD',
                maxDate: moment().toDate(),
                yearRange: [1950, 2030],
                i18n: i18n,
            });
        });
    });
}

$('body').on('change', '#department', function () {
    var id = $(this).val();
    $('#idcity').empty();
    for (var j = 0; j < createuserviewjs.storagemethod['idcity'].length; j++) {
        if (createuserviewjs.storagemethod['idcity'][j].iddepartment == id) {
            $('#idcity').append('<option value="' + createuserviewjs.storagemethod['idcity'][j].id + '">' + createuserviewjs.storagemethod['idcity'][j].description + '</option>');
        }
    }
    $('.selectpicker').selectpicker('refresh');
});

$('body').on('change', '#departmente', function () {
    var id = $(this).val();
    $('#idcity').empty();
    var userinfo = usereditviewjs;
    for (var j = 0; j < userinfo.storagemethod['idcity'].length; j++) {
        if (userinfo.storagemethod['idcity'][j].iddepartment == id) {
            $('#idcity').append('<option value="' + userinfo.storagemethod['idcity'][j].id + '">' + userinfo.storagemethod['idcity'][j].description + '</option>');
        }
    }
    $('.selectpicker').selectpicker('refresh');
});
//});

function DeleteUser(id) {
    //var id = $(this).attr('id');
    swal({
        title: "¿Está seguro que desea borrar este usuario?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Confirmar",
        closeOnConfirm: false
    },
    function () {
        var userjson = {};
        userjson.userid = id;
        $.ajax({
            data: {
                'user': userjson
            },
            type: 'post',
            url: 'index.php?r=AdminUsers/DeleteUser',
            success: function (response) {
                if (response == "OK") {
                    swal("Borrado!", "El usuario ha sido borrado del sistema!", "success");
                    LoadUserAdmin();
                } else {
                    swal("Alerta", "Ocurrio un error eliminando el usuario", "error");
                }
            }
        });
    });
}

function LoadUserAdmin() {
    $('#itemlstUsersAdministration').trigger('click');
}

function FillCreateUserSeller(createuserview) {
    var userviewrender = '';
    var sumrows = 0;
    for (var i = 0; i < createuserview.data.length; i++) {
        if (createuserview.data[i].idtable == 2) {
            if (sumrows == 0) {
                userviewrender += '<div class="row">';
            }
            switch (createuserview.data[i].inputtype) {
                case "text":
                    {
                        userviewrender += '<div class="col-md-' + createuserview.data[i].size + '"><div class="form-group"><label>' + createuserview.data[i].columndescription.toUpperCase() + '</label><input type="text" id="' + createuserview.data[i].columnname + '" class="form-control" placeholder="' + createuserview.data[i].columndescription + '"></div></div>';
                    }
                    break;
                case "select":
                    {
                        if (createuserview.data[i].columnname == 'idcity') {
                            userviewrender += '<div class="col-md-4"><div class="form-group"><label>DEPARTAMENTO</label><select class="selectpicker" id="department" title="Seleccione un departamento..">';
                            for (var k = 0; k < createuserview.departments.length; k++) {
                                userviewrender += '<option value="' + createuserview.departments[k].iddepartment + '">' + createuserview.departments[k].departmentdescription + '</option>';
                            }
                            userviewrender += '</select></div></div>';
                        }
                        userviewrender += '<div class="col-md-' + createuserview.data[i].size + '"><div class="form-group"><label>' + createuserview.data[i].columndescription.toUpperCase() + '</label>' +
                                '<select class="selectpicker" id="' + createuserview.data[i].columnname + '" title="Seleccione un tipo..">';
                        if (createuserview.data[i].columnname != 'idcity') {
                            for (var j = 0; j < createuserview.storagemethod[createuserview.data[i].columnname].length; j++) {
                                userviewrender += '<option value="' + createuserview.storagemethod[createuserview.data[i].columnname][j].id + '">' + createuserview.storagemethod[createuserview.data[i].columnname][j].description + '</option>';
                            }
                        }
                        userviewrender += '</select></div></div>';
                    }
                    break;
                case "date":
                    {
                        userviewrender += '<div class="col-md-' + createuserview.data[i].size + '"><div class="form-group"><label>' + createuserview.data[i].columndescription.toUpperCase() + '</label>' +
                                '<div class="input-group date" class="datetimepicker" id="datetimepicker1"><input type="text" id="' + createuserview.data[i].columnname + '" class="form-control" />' +
                                '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div>';
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
    }
    $('.otherdata').html(userviewrender);
    $('.selectpicker').selectpicker('refresh');
}

function FillEditUserSeller(createuserview) {
    var userviewrender = '';
    var sumrows = 0;
    for (var i = 0; i < createuserview.data[1].length; i++) {
        if (sumrows == 0) {
            userviewrender += '<div class="row">';
        }
        switch (createuserview.data[1][i].inputtype) {
            case "text":
                {
                    userviewrender += '<div class="col-md-' + createuserview.data[1][i].size + '"><div class="form-group"><label>' + createuserview.data[1][i].columndescription.toUpperCase() + '</label><input type="text" id="' + createuserview.data[1][i].columnname + '" class="form-control" placeholder="' + createuserview.data[1][i].columndescription + '"></div></div>';
                }
                break;
            case "select":
                {
                    if (createuserview.data[1][i].columnname == 'idcity') {
                        userviewrender += '<div class="col-md-4"><div class="form-group"><label>DEPARTAMENTO</label><select class="selectpicker" id="departmente" title="Seleccione un departamento..">';
                        for (var k = 0; k < createuserview.departments.length; k++) {
                            userviewrender += '<option value="' + createuserview.departments[k].iddepartment + '">' + createuserview.departments[k].departmentdescription + '</option>';
                        }
                        userviewrender += '</select></div></div>';
                    }
                    userviewrender += '<div class="col-md-' + createuserview.data[1][i].size + '"><div class="form-group"><label>' + createuserview.data[1][i].columndescription.toUpperCase() + '</label>' +
                            '<select class="selectpicker" id="' + createuserview.data[1][i].columnname + '" title="Seleccione un tipo..">';
                    if (createuserview.data[1][i].columnname != 'idcity') {
                        for (var j = 0; j < createuserview.storagemethod[createuserview.data[1][i].columnname].length; j++) {
                            userviewrender += '<option value="' + createuserview.storagemethod[createuserview.data[1][i].columnname][j].id + '">' + createuserview.storagemethod[createuserview.data[1][i].columnname][j].description + '</option>';
                        }
                    }
                    userviewrender += '</select></div></div>';
                }
                break;
            case "date":
                {
                    userviewrender += '<div class="col-md-' + createuserview.data[1][i].size + '"><div class="form-group"><label>' + createuserview.data[1][i].columndescription.toUpperCase() + '</label>' +
                            '<div class="input-group date" class="datetimepicker" id="datetimepicker1"><input type="text" id="' + createuserview.data[1][i].columnname + '" class="form-control" />' +
                            '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div>';
                }
                break;
            default:
        }
        sumrows += parseInt(createuserview.data[1][i].size);
        if (sumrows == 12) {
            userviewrender += '</div>';
            sumrows = 0;
        }
    }
    $('.otherdata').html(userviewrender);
    $('.selectpicker').selectpicker('refresh');
}

$('body').on('click', '.btnsellerdetails', function () {
    var id = $(this).attr('id');
    $.ajax({
        url: 'index.php?r=AdminUsers/getAllSellers',
        success: function (response) {
            var Users = JSON.parse(response)
            var Seller = $.grep(Users.sellers.seller, function (element, index) {
                return element.sellerid == id;
            });
            if (Seller[0] == undefined) {
                swal("Este usuario no registra mas información!");
            } else {
                $('.modal-body').html('<div class="content"><div class="container-fluid"><div class="row"><div class="col-md-12"><div class="card"><div class="content table-responsive table-full-width"><table class="table table-hover table-striped">' +
                        '<thead><tr>' + Users.sellers.columns + '</tr></thead><tbody><tr>' + Seller[0].info + '</tr></tbody></table></div></div></div></div></div>');
                $("#myModal").modal('show');
            }
        }
    });
});

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}
;