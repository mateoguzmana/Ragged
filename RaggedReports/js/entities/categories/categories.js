/*
 * Created By Activity Technology S.A.S.
 */

$('.selectCategoryCompanies').on('change', function () {
    var tableCategory = $('#tblDinamicCategory').dataTable();
    var Companias = "";
    if ($(this).val() != null) {
        $.each($(this).val(), function (index, value) {
            Companias += value + "|";
        });
        Companias = Companias.slice(0, -1);
    }
    if ($(this).val() == null) {
        tableCategory.fnFilter('', 1);
        return false;
    } else {
        tableCategory.fnFilter(Companias, 1, true);
        return false;
    }
});

$(document).ready(function () {
    var option_default = $('.selectCategoryCompanies').val();
    var tableCategory = $('#tblDinamicCategory').dataTable();
    if (option_default == null) {
        tableCategory.fnFilter('', 1);
        return false;
    } else {
        tableCategory.fnFilter(option_default, 1);
        return false;
    }
});