/*
 * Created By Activity Technology S.A.S.
 */

$('.selectSizeCompanies').on('change', function () {
    var Companias = "";
    if ($(this).val() != null) {
        $.each($(this).val(), function (index, value) {
            Companias += value + "|";
        });
        Companias = Companias.slice(0, -1);
    }
    var tableSize = $('#tblDinamicSize').dataTable();
    if ($(this).val() == null) {
        tableSize.fnFilter('', 1);
        return false;
    } else {
        tableSize.fnFilter(Companias, 1, true);
        return false;
    }
});

$(document).ready(function () {
    var option_default = $('.selectSizeCompanies').val();
    var tableSize = $('#tblDinamicSize').dataTable();
    if (option_default == null) {
        tableSize.fnFilter('', 1);
        return false;
    } else {
        tableSize.fnFilter(option_default, 1);
        return false;
    }

});