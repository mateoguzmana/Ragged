/*
 * Created By Activity Technology S.A.S.
 */

$('body').on('click', '#btngetwallet', function () {

    var table = $("#tblDinamicRouter").DataTable();
    var showModal = true;
    var routers = [];
    table.$('input:checkbox.customer-checkbox').each(function () {
        var isChecked = (this.checked ? $(this).val() : "");
        if (isChecked == 'on') {
            var idcustomer = $(this).attr('data-idCheck');
            routers.push(idcustomer);
        }
    });

    if (routers == "") {
        swal("Alerta", "Debe de seleccionar el cliente que desea consultar", "error");
        showModal = false;
    }
    if (showModal)
    {
        $.ajax({
            data: {
                idRouters: routers
            },
            type: 'post',
            url: 'index.php?r=Order/ShowWallet',
            success: function (response) {

                var customersData = JSON.parse(response);
                var modalTitle = "Transacciones de clientes";
                displayTable(customersData, modalTitle);
            }
        });
    }
    return false;
});

$('body').on('click', '#btnnext', function () {

    var table = $("#tblDinamicRouter").DataTable();
    var customers = [];
    var priceLists = [];
    var formPays = [];
    table.$('input:checkbox.customer-checkbox').each(function () {
        var isChecked = (this.checked ? $(this).val() : "");
        if (isChecked == 'on') {
            var idcustomer = $(this).attr('data-idCheck');
            customers.push(idcustomer);
        }
    });

    if (customers != "")
    {
        var i = 0;
        for (i = 0; i < customers.length; i++)
        {
            priceLists.push(table.$('[data-idselectpricelist="' + customers[i] + '"]').val());
            formPays.push(table.$('[data-idselectformpay="' + customers[i] + '"]').val());
        }

        var user = {};

        user.user = localStorage.getItem('nickname');
        localStorage.setItem('customers', JSON.stringify(customers));
        localStorage.setItem('priceLists', JSON.stringify(priceLists));
        localStorage.setItem('formPays', JSON.stringify(formPays));
        localStorage.setItem('Company', defautlCompanyStorage);
        $.ajax({
            data: {
                'user': user,
                'item': 'itemlstReferences',
                'customers': customers,
                'priceList': priceLists,
                'company': defautlCompanyStorage
            },
            type: 'post',
            url: 'index.php?r=Order/CreateOrderDetail',
            beforeSend: function () {
                $('#processing-modal').modal('show');
            },
            success: function (response) {
                $('.bodyrender').html(response);
                $('#processing-modal').modal('hide');
            }
        }).done(function () {
            searchprivileges('itemlstOrder');
        });
    }
    else
        swal("Se debe de seleccionar el cliente para continuar con el pedido", "", "error");
});


$('body').on('click', '#btnexit', function () {
    var message = "";
    exit(message);
});


function exit(message)
{
    swal({
        title: "\u00BFEst\u00e1  seguro de salir?",
        text: message,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Salir",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false
    },
    function () {
        swal("Has Salido!", "", "success");
        localStorage.removeItem('Routers');
        localStorage.removeItem('customers');
        localStorage.removeItem('priceLists');
        localStorage.removeItem('formPays');
        localStorage.removeItem('Company');
        $('.bodyrender').html("");
    });
}

$(window).resize(function(){    
    
       if ($(window).width() <= 680) {         
            $("#btngetwallet").html("");            
            $("#btnnext").html("");
            $("#btnexit").html("");                    
            $("#btngetwallet").addClass("glyphicon glyphicon-credit-card");            
            $("#btnnext").addClass("glyphicon glyphicon-ok");                    
            $("#btnexit").addClass("glyphicon glyphicon-home");                    
                
       } 
       
        if ($(window).width() > 680)                                  
        {            
            $("#btngetwallet").removeClass("glyphicon glyphicon-credit-card");            
            $("#btnnext").removeClass("glyphicon glyphicon-ok");                    
            $("#btnexit").removeClass("glyphicon glyphicon-home"); 
            $("#btngetwallet").html("Consultar Cartera");            
            $("#btnnext").html("Siguiente");
            $("#btnexit").html("Salir"); 
        }
        
        else
        {
            $(".responsive-helper").remove();
            $('.MenuRagged').html("");            
            $( ".nav" ).prepend(menu);
        }
   });