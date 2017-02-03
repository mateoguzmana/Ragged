
function searchprivileges(submodule) {
    var userdata = {};
    userdata.user = localStorage.getItem('nickname');
    userdata.submodule = submodule;
    $.ajax({
        data: {
            user: userdata
        },
        type: 'post',
        url: 'index.php?r=Privileges/LoadActionsSubmodule',
        success: function (response) {
            var submodulearr = JSON.parse(response);
            for (var i = 0; i < submodulearr.length; i++) {
                $('.' + submodulearr[i].idoption).html('<div class="' + submodulearr[i].Btrtpsizeclass + '"><button id="' + submodulearr[i].idsourcecode + '" type="button" class="' + submodulearr[i].class + '" onclick="' + submodulearr[i].onclick + '" >' +
                        '<span class="' + submodulearr[i].icon + '"></span>' + submodulearr[i].optiondescription + '</button></div>');
            }            
            if ($(window).width() <= 590) 
            {                               
                $("#btneditprofile").html($(".glyphicon-edit"));            
                $("#btncreateprofile").html($(".glyphicon-plus"));
                $("#btndeleteprofile").html($(".glyphicon-trash"));
                $("#btncreateprofiletype").html($(".glyphicon-plus"));
                $("#btndeleteprofiletype").html($(".glyphicon-trash"));
                $("#btneditprofiletype").html($(".glyphicon-edit"));                
            }    
        }
    });
}

function loadRouters()
{
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstOrder'
        },
        type: 'post',
        url: 'index.php?r=Order/GetRouters',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstOrder');
    });
}

function resumeOrder(response) {
    var user = {};
    user.user = localStorage.getItem('nickname');
    var orderData = JSON.parse(response);
    var customers = orderData.customers;
    var priceList = orderData.priceLists;
    var company = orderData.company;
    var formPays = orderData.formPays;
    localStorage.setItem('customers', JSON.stringify(customers));
    localStorage.setItem('priceLists', JSON.stringify(priceList));
    localStorage.setItem('formPays', JSON.stringify(formPays));
    localStorage.setItem('Company', company);
    console.log(orderData);
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstReferences',
            'customers': customers,
            'priceList': priceList,
            'quantities': orderData.quantities,
            'checks': orderData.checks,
            'company': company

        },
        type: 'post',
        url: 'index.php?r=Order/CreateOrderDetail',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstOrder');
    });

}

$('body').on('click', '#itemlstPrivileges', function () {
    $.ajax({
        url: 'index.php?r=Privileges/Index',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        $('.selectpicker').selectpicker('refresh');
        searchprivileges('itemlstPrivileges');
    });
});

$('body').on('click', '#itemlstUsersAdministration', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user
        },
        type: 'post',
        url: 'index.php?r=AdminUsers/Index',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstUsersAdministration');
    });
});

$('body').on('click', '#itemlstAdministrationModule', function () {
    $('.bodyrender').empty(); 
});

$('body').on('click', '#itemlstCollections', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstCollections'
        },
        type: 'post',
        url: 'index.php?r=Collection/AjaxLoad',
        beforeSend: function () {
            $('#processing-modal').modal('show');
        },        
        success: function (response) {
            $('.bodyrender').html(response);
            $('#processing-modal').modal('hide');
        }
    }).done(function () {
        searchprivileges('itemlstCollections');
    });;
});

$('body').on('click', '#itemlstCategories', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstCategories'
        },
        type: 'post',
        url: 'index.php?r=Category/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    });
});

$('body').on('click', '#itemlstColors', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstColors'
        },
        type: 'post',
        url: 'index.php?r=Color/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    });
});

$('body').on('click', '#itemlstLines', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstLines'
        },
        type: 'post',
        url: 'index.php?r=Line/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    });
});

$('body').on('click', '#itemlstMarks', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstMarks'
        },
        type: 'post',
        url: 'index.php?r=Mark/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    });
});

$('body').on('click', '#itemlstSizes', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstSizes'
        },
        type: 'post',
        url: 'index.php?r=Size/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    });
});

$('body').on('click', '#itemlstSectors', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstSectors'
        },
        type: 'post',
        url: 'index.php?r=Sector/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    });
});

$('body').on('click', '#itemlstReferences', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    localStorage.removeItem('customers');
    localStorage.removeItem('priceLists');
    localStorage.removeItem('collections');
    localStorage.removeItem('formPays');
    localStorage.removeItem('Company');
    localStorage.removeItem('column');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstReferences'
        },
        type: 'post',
        url: 'index.php?r=Reference/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstReferences');
    });
});

$('body').on('click', '#itemlstCustomers', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstCustomers'
        },
        type: 'post',
        url: 'index.php?r=Customer/AjaxLoad',
        beforeSend: function () {
            $('#processing-modal').modal('show');
        },
        success: function (response) {
            $('.bodyrender').html(response);
            $('#processing-modal').modal('hide');
        }
    }).done(function () {
        searchprivileges('itemlstCustomers');
    });
});

$('body').on('click', '#itemlstOrder', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    localStorage.removeItem('Routers');
    localStorage.removeItem('customers');
    localStorage.removeItem('priceLists');
    localStorage.removeItem('formPays');
    localStorage.removeItem('collections');
    localStorage.removeItem('Company');
    localStorage.removeItem('column');
    $.ajax({
        data: {
            'user': user,
            'item': 'itemlstOrder'
        },
        type: 'post',
        url: 'index.php?r=Order/AjaxLoad',
        beforeSend: function () {
            $('#processing-modal').modal('show');
        },
        success: function (response) {
            if (response != "[]")
            {
                $('#processing-modal').modal('hide');
                swal({
                    title: "Tiene un pedido sin finalizar",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "blue",
                    confirmButtonText: "Retomar Pedido",
                    cancelButtonText: "Nuevo Pedido",
                    closeOnConfirm: false
                },
                function () {
                    swal.close();
                    resumeOrder(response);
                });
                $(".cancel").addClass("newOrder");
                $(".newOrder").css("background-color", "green");
            }
            else
            {
                loadRouters();
            }
        }
    }).done(function () {
        $('#processing-modal').modal('hide');
        searchprivileges('itemlstOrder');
    });
});

$('body').on('click', '#itemlstProfileTypes', function () {
    $.ajax({
        url: 'index.php?r=ProfileTypes/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        $('.selectpicker').selectpicker('refresh');
        searchprivileges('itemlstProfileTypes');
    });
});

$('body').on('click', '.mod', function () {
    var data_id = $(this).attr("data-mod");
    $("ul").find("[data-id='" + data_id + "']").toggle();
});


$('body').on('click', '.newOrder', function () {
    loadRouters();
});

$('body').on('click', '#itemlstCompaniesConfiguration', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user
        },
        type: 'post',
        url: 'index.php?r=CompaniesConfiguration/Index',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstcompaniesConfiguration');
    });
});

$('body').on('click', '#itemlstEmailsConfiguration', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user
        },
        type: 'post',
        url: 'index.php?r=EmailsConfiguration/Index',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstEmailsConfiguration');
    });
});

$('body').on('click', '#itemlstOrdersQuery', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user
        },
        type: 'post',
        url: 'index.php?r=OrdersQuery/Index',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstOrdersQuery');
    });
});

$('body').on('click', '#itemlstExcecuteProcess', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user
        },
        type: 'post',
        url: 'index.php?r=RaggedProcess/Index',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstExcecuteProcess');
    });
});

$('body').on('click', '#itemlstDashboard', function () {
    var user = {};
    user.user = localStorage.getItem('nickname');
    $.ajax({
        data: {
            'user': user
        },
        type: 'post',
        url: 'index.php?r=Dashboard/AjaxLoad',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    }).done(function () {
        $('.selectpicker').selectpicker('refresh');
        searchprivileges('itemlstDashboard');
    });
});