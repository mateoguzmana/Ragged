/*
 * Created By Activity Technology S.A.S.
 */

$('.selectReferenceCompanies').on('change', function () {
    filterOptionsReferences($(this).val(), null);
    filterOptionsCollections($(this).val(), null);
    filterOptionsPriceList($(this).val(), null);
    return false;
});

$('.selectCollectionOptions').on('change', function () {
    filterOptionsReferences(null, $(this).val());
    return false;
});

$('body').on('click', '#btnconsultreferencesorder', function () {
    var referencesId = $('#selectReferenceOptions').val();
    $(".accordion-reference").hide();
    for (i = 0; i < referencesId.length; i++)
    {
        $("#reference-" + referencesId[i]).show();
    }
    return false;
});

$('body').on('click', '#btnconsultreferences', function () {
    var collectionsId = $('.selectCollectionOptions').val();
    var referencesId = $('#selectReferenceOptions').val();
    var priceListId = $('#selectPriceListOptions').val();
    if (collectionsId != null && referencesId != null && priceListId != null) {
        $.ajax({
            data: {
                'collections': collectionsId,
                'references': referencesId,
                'priceList': priceListId
            },
            type: 'post',
            url: 'index.php?r=Reference/GetReferenceDetail',
            success: function (response) {
                $('#DetailContainer').html(response);
            }
        });
    }
    else {
        swal("Alerta", "Debe seleccionar todas las opciones", "error");
    }
});

$('body').on('click', '#btnback', function () {
    $.ajax({
        data: {
        },
        type: 'post',
        url: 'index.php?r=Order/BackToRouters',
        success: function (response) {
            $('.bodyrender').html("");
            $('.bodyrender').html(response);
        }
    }).done(function () {
        searchprivileges('itemlstOrder');
    });
});

$(".panelcontent").on('click', '#title', function (e) {
    var $this = $(this);
    if (!$this.hasClass('panel-collapsed')) {
        $this.parents('.panelcontent').find('.panelbody').slideUp();
        $this.addClass('panel-collapsed');
        $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        var isOpen = false;
    } else {
        $this.parents('.panelcontent').find('.panelbody').slideDown();
        $this.removeClass('panel-collapsed');
        $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        var isOpen = true;
    }
    try {
        changeHeight(isOpen);
    }
    catch (err) {
        //Do nothing
    }
    return false;
});