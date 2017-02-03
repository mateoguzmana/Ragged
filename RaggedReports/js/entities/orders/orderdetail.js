/*
 * Created By Activity Technology S.A.S.
 */

$('body').on('click', '.expandFind', function () {

    var isExpanded = $(this).attr("aria-expanded");

    var id = $(this).attr("href");

    if (isExpanded == "true")
    {
        $(id).removeClass("isClicked");
    }
    else
    {
        $(id).addClass("isClicked");
    }
});

var previousInputFocus = "";
var firstFocus = true;

$('body').on('change', '.input-quantity', function () {
    var availableQuantity = $(this).attr('placeholder');
    var requiredQuantity = $(this).val();
    if (requiredQuantity != "")
    {
        if (Number(requiredQuantity) > Number(availableQuantity))
            swal("Alerta", "La cantidad ingresada supera la cantidad disponible", "error");
    }
});

$('body').on('focus', '.input-quantity', function () {

    var currentFocus = $(this).attr('data-quantity');
    var td = $(this).parent();
    currentFocus = td.parent().attr('data-id');

    if (previousInputFocus != currentFocus && firstFocus == false)
    {
        var showAlert = false;
        SaveOrderDetail(showAlert);
    }

    previousInputFocus = currentFocus;
    firstFocus = false;


    return false;
});

$('body').on('click', '#btnexitorderdetail', function () {
    var message = "Recuerde que no se almacenar\u00E1n datos del pedido que est\u00E1 digitando";
    exit(message);
});

$('body').on('click', '#btnsaveorder', function () {
    var showAlert = true;
    SaveOrderDetail(showAlert);
});

function SaveOrderDetail(showAlert) {

    var dataQuantity;
    var IdArray;
    var quantity;
    var checked;
    var dataIdCheck;
    var RowArray = {};
    var OrderDetailArray = [];
    var checkArray = [];
    var quantityArray = {};
    var allQuantitiesArray = [];
    var checkIndex;

    $.each($('[data-quantity]'), function (key, value) {

        quantity = $(this).val();
        if (quantity != "" && quantity != "0")
        {
            dataQuantity = $(this).attr('data-quantity');
            quantityArray[dataQuantity] = quantity;
            allQuantitiesArray.push(quantityArray);
            quantityArray = {};
            // Identifico el data-id del checkbox correspondiente al input al que se le ingresó un valor.
            IdArray = dataQuantity.split("*");
            dataIdCheck = IdArray[0] + "*" + IdArray[4] + "*" + IdArray[2];
            if ($('[data-idcheck="' + dataIdCheck + '"]').is(':checked'))
            {
                checked = 1;
                checkIndex = $.inArray(dataIdCheck, checkArray);
                if (checkIndex < 0)
                    checkArray.push(dataIdCheck);

            }
            else
                checked = 0;

            RowArray['idAddress'] = IdArray[0];
            RowArray['referenceCode'] = IdArray[1];
            RowArray['idReference'] = IdArray[2];
            RowArray['colorCode'] = IdArray[3];
            RowArray['idColor'] = IdArray[4];
            RowArray['Size'] = IdArray[5];
            RowArray['idSize'] = IdArray[6];
            RowArray['quantity'] = quantity;
            RowArray['custom'] = checked;
            OrderDetailArray.push(RowArray);
            RowArray = {};
        }

    });

    var orderDetailJson;
    orderDetailJson = JSON.stringify(OrderDetailArray);
    var viewDataJson = {};
    viewDataJson['quantities'] = allQuantitiesArray;
    viewDataJson['checks'] = checkArray;
    viewDataJson['priceLists'] = JSON.parse(localStorage.getItem('priceLists'));
    viewDataJson['customers'] = JSON.parse(localStorage.getItem('customers'));
    viewDataJson['company'] = localStorage.getItem('Company');
    viewDataJson['formPays'] = JSON.parse(localStorage.getItem('formPays'));
    viewDataJson = JSON.stringify(viewDataJson);

    if (OrderDetailArray.length > 0)
    {

        var user = {};
        user.user = localStorage.getItem('nickname');

        var routers = localStorage.getItem('customers');
        var priceLists = localStorage.getItem('priceLists');
        var formPays = localStorage.getItem('formPays');
        var company = localStorage.getItem('Company');

        $.ajax({
            data: {
                'OrderDetailJson': orderDetailJson,
                'user': user,
                'routers': routers,
                'priceLists': priceLists,
                'formPays': formPays,
                'viewDataJson': viewDataJson,
                'company': company

            },
            type: 'post',
            url: 'index.php?r=Order/SaveOrderTemp',
            beforeSend: function () {
                $('#processing-modal').modal('show');
            },
            success: function (response) {
                if (showAlert)                    
                    displayOrder(response);
                $('#processing-modal').modal('hide');
            }
        }).done(function () {
            searchprivileges('itemlstOrder');
        });

    }
    else
    if (showAlert)
        swal("No ha ingresado ning\u00FAn dato", "", "error");
}

function changeHeight(isOpen)
{
    if (isOpen == true)
    {
        document.getElementById("accoridionContentDiv").style.height = "35vh";
    }
    else
        document.getElementById("accoridionContentDiv").style.height = "71vh";

    return false;
}