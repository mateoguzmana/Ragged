/*
 * Created By Activity Technology S.A.S.
 */


$('#tblDinamicOrderOptions tbody tr').on('click', '.btnAdd', function () {

    var index = $(this).attr('data-idadd');
    var elements = $('[data-id="' + index + '"]');
    $('[data-id="' + index + '"]').each(function () {
        if ($(this).hasClass("hideRow"))
        {
            $(this).show();
            $(this).removeClass("hideRow");
            return false;
        }
    });
});

$('#tblDinamicOrderOptions tbody tr').on('click', '.btnRemove', function () {

    var index = $(this).attr('data-removeRow');
    var element = $("#row-" + index);
    element.hide();
    element.addClass("hideRow");
});

$('body').on('click', '#btnsave', function () {

    var rowCount = $('#tblDinamicOrderOptions tr').length;
    var order = {};
    var orderArray = [];
    var j = 0;



    var priceList = JSON.parse(localStorage.getItem('priceLists'));
    var customers = JSON.parse(localStorage.getItem('customers'));
    var formPays = JSON.parse(localStorage.getItem('formPays'));


    var customerPayInfoArray = [];

    for (counter = 0; counter < customers.length; counter++)
    {
        var customerPayInfo = {};
        customerPayInfo["idrouter"] = customers[counter];
        customerPayInfo["idPriceList"] = priceList[counter];
        customerPayInfo["idFormPays"] = formPays[counter];
        customerPayInfoArray.push(customerPayInfo);
    }

    alert(JSON.stringify(customerPayInfoArray));

    for (i = 1; i < rowCount; i++)
    {
        var order = {};
        var quantity = false;
        var address = true;
        var shown = true;
        var obj = $("#row-" + i);

        if (obj.hasClass("hideRow"))
            shown = false;

        order["idCustomer"] = obj.attr('data-id');
        order["idAddress"] = $("#address" + i).val();
        if ($("#address" + i).val() == "")
            address = false;

        $('[data-sizeRow="' + i + '"]').each(function () {
            var key = $(this).attr('data-size');
            var value = $(this).val();
            if (value != "")
                quantity = true;
            order[key] = value;
            j++;
        });
        j = 0;
        if ($('[data-checkRow="' + i + '"]').is(":checked"))
            order["custom"] = "1";
        else
            order["custom"] = "0";
        order["observation"] = $('[data-textRow="' + i + '"]').val();
        if (quantity && address && shown)
            orderArray.push(order);
    }

    $.ajax({
        data: {
            'orderArray': orderArray,
            'customerPayInfoArray': customerPayInfoArray
        },
        type: 'post',
        url: 'index.php?r=Order/CreateTempOrder',
        success: function (response) {
            $('.bodyrender').html(response);
        }
    });


});
