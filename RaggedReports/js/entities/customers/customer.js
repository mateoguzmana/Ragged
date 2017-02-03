/*
 * Created By Activity Technology S.A.S.
 */

var formIds = [];

$('.selectCustomerCompanies').on('change', function () {
    var tableCustomer = $('#tblDinamicCustomer').dataTable();
    var Companias = "";
    if ($(this).val() != null) {
        $.each($(this).val(), function (index, value) {
            Companias += value + "|";
        });
        Companias = Companias.slice(0, -1);
    }
    if ($(this).val() == null) {
        tableCustomer.fnFilter('', 2);
        return false;
    } else {
        tableCustomer.fnFilter(Companias, 2, true);
        return false;
    }
});

$(document).ready(function () {
    var option_default = $('.selectCustomerCompanies').val();
    var tableCustomer = $('#tblDinamicCustomer').dataTable();
    if (option_default == null) {
        tableCustomer.fnFilter('', 2);
        return false;
    } else {
        tableCustomer.fnFilter(option_default, 2);
        return false;
    }
});

$('body').on('click', '#btnconsultwallet', function () {
    var customers = [];
    var showModal = true;
    var table = $("#tblDinamicCustomer").DataTable();

    table.$('input:checkbox.customercheckbox').each(function () {
        var isChecked = (this.checked ? $(this).val() : "");
        if (isChecked == 'on') {
            var idcustomer = $(this).attr('data-idCheck');
            customers.push(idcustomer);
        }

    });

    if (customers == "") {
        swal("Alerta", "Debe de seleccionar el cliente que desea consultar", "error");
        showModal = false;
    }

    if (showModal)
    {
        $.ajax({
            data: {
                idCustomers: customers
            },
            type: 'post',
            url: 'index.php?r=Customer/ShowWallet',
            success: function (response) {

                var customersData = JSON.parse(response);
                var modalTitle = "Transacciones de clientes";
                displayTable(customersData, modalTitle);

            }
        });
    }
});

$('#tblDinamicCustomer').on("click", ".btnshowaddress", function () {
    var idAdress = $(this).attr('data-idAddress');
    $.ajax({
        data: {
            idCustomers: idAdress
        },
        type: 'post',
        url: 'index.php?r=Customer/ShowSalePoints',
        success: function (response) {

            var customersData = JSON.parse(response);
            var modalTitle = "Direcciones";
            displayTable(customersData, modalTitle);

        }

    });
});

$('body').on('click', '#btnCreateCustomer', function () {

    //Renderizamos
    $.ajax({
        url: 'index.php?r=Customer/CreateCustomer',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).complete(function () {

        var userName = localStorage.getItem('nickname');

        $.ajax({
            url: 'index.php?r=Customer/CreateCustomerData',
            type: 'post',
            data: {
                'userName': userName
            },
            success: function (response) {
                formIds = [];
                var viewrender = '';
                var sumrows = 0;
                var result = JSON.parse(response);
                $.each(result.data, function (key, val) {

                    if (val['iscreable'] == '1') {
                        if (sumrows == 0) {
                            viewrender += '<div class="row">';
                        }


                        //Agregamos el id de las columnas en el array para su posterior uso

                        formIds.push(val.columnname);


                        switch (val.idcolumntype) {

                            case "1":
                                {
                                    viewrender += '<div class="col-md-' + val.size + '"><div class="form-group"><label>' + val.columndescription.toUpperCase() + '</label><input type="text" id="' + val.columnname + '" class="form-control ' + val.classvalidation + '" placeholder="' + val.columndescription + '" maxlength="' + val.length + '" ></div></div>';
                                }
                                break;
                            case "2":
                                {
                                    //console.log(JSON.stringify(result.storagemethod));
                                    viewrender += '<div class="col-md-' + val.size + '"><div class="form-group"><label>' + val.columndescription.toUpperCase() + '</label>&#32;&#32;' +
                                            '<select class="selectpicker ' + val.classvalidation + '" id="' + val.columnname + '" data-id-column="' + val.columnname + '" data-id="1" data-live-search="true" title="Seleccione un tipo..">';

                                    $.each(result.storagemethod[val.columnname], function (k1, v1) {
                                        viewrender += '<option value="' + v1.id + '">' + v1.description + '</option>';
                                    });


                                    viewrender += '</select></div></div>';
                                }
                                break;
                            case "4":
                                {
                                    viewrender += '<div class="col-md-' + val.size + '"><div class="form-group"><label>' + val.columndescription.toUpperCase() + '</label>' +
                                            '<div class="input-group date" class="datetimepicker" id="datetimepicker1"><input type="text" id="' + val.columnname + '" class="form-control" placeholder="' + val.columndescription + '"  readonly />' +
                                            '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div>';
                                    viewrender = val.columnname;
                                }
                                break;
                            default:
                        }
                        sumrows += parseInt(val.size);


                        if (sumrows == 12) {
                            viewrender += '</div>';
                            sumrows = 0;
                        }

                    }
                });

                if (sumrows != 12) {
                    viewrender += '</div>';
                }

                $('.createcustomerdata').html(viewrender);
                $('.selectpicker').selectpicker('refresh');

                $.each(result.privileges, function (k, v) {
                    if (v.active == '0') {
                        $('#' + v.idsourcecode).prop('disabled', true);
                        $('.selectpicker').selectpicker('refresh');

                        if (v.idsourcecode == 'idcompany') {
                            getCompanyUser(userName);
                        }
                    }
                });

            }
        });

    });
});


function getCompanyUser(userName) {

    $.ajax({
        url: 'index.php?r=Customer/GetCompanyCustomerByUser',
        type: 'post',
        data: {
            'userName': userName
        },
        success: function (response) {

            var data = JSON.parse(response);

            $.each(data, function (k, v) {
                $('#idcompany').val(v.idcompany);
                $('#idcompany').selectpicker('refresh');
            });
        }
    });

}


$('body').on('click', '#btnsavecustomer', function () {

    var flagDataEmpty = true;


    var jsonData = {};

    for (var i = 0; i < formIds.length; i++) {
        jsonData[formIds[i]] = $('#' + formIds[i]).val();
    }

    //Validamos datos vacios



    $.each(jsonData, function (k, v) {


        if ($('#' + k).hasClass('required')) {
            if ((v == '') || (v == undefined)) {

                flagDataEmpty = false;
                return false;
            }
        }

    });


    if (flagDataEmpty == false) {
        swal("Alerta", "Complete el formulario por favor!", "error");
    }
    else {

        //Validamos correo electronico
        if (validateEmail($('#email').val()) == 'error') {
            swal("Alerta", "Ingrese un correo valido por favor!", "error");
        }
        else {


            //Validamos que el numero de cuenta no exista

            $.ajax({
                url: 'index.php?r=Customer/CustomerExists',
                type: 'post',
                data: {
                    'jsonData': jsonData
                },
                beforeSend: function () {
                    $('#processing-modal').modal('show');
                },
                success: function (response) {
                    if (response == 'error') {
                        $('#processing-modal').modal('hide');
                        swal("Alerta", "El usuario que desea ingresar ya existe", "error");
                    }
                    else {

                        var userName = localStorage.getItem('nickname');

                        $.ajax({
                            url: 'index.php?r=Customer/SaveCustomer',
                            type: 'post',
                            data: {
                                'jsonData': JSON.stringify(jsonData),
                                'userName': userName
                            },
                            success: function (response) {
                                $('#processing-modal').modal('hide');
                                swal({
                                    title: "Correcto!",
                                    text: "Cliente registrado satisfactoriamente!",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#8CD4F5",
                                    confirmButtonText: "Correcto!",
                                    closeOnConfirm: true
                                },
                                function () {
                                    $('#itemlstCustomers').trigger('click');
                                });

                            }
                        });
                    }
                }
            });
        }
    }

});

function validateEmail(email) {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!expr.test(email))
        return 'error';
    else
        return 'success';
}

function buildSelect(data, select) {
    var optionsHTML = '';
    $.each(data, function (key, val) {
        optionsHTML = optionsHTML + '<option value="' + val['id'] + '">' + val['description'] + '</option>';
    });


    $('#' + select).html(optionsHTML);
    $('#' + select).selectpicker('refresh');

}

