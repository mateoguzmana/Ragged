/*
 * Created By Activity Technology S.A.S.
 */

$('.selectMarkCompanies').on('change', function () {
    var Companias = "";
    if ($(this).val() != null) {
        $.each($(this).val(), function (index, value) {
            Companias += value + "|";
        });
        Companias = Companias.slice(0, -1);
    }
    var tableMark = $('#tblDinamicMark').dataTable();
    if ($(this).val() == null) {
        tableMark.fnFilter('', 1);
        return false;
    } else {
        tableMark.fnFilter(Companias, 1, true);
        return false;
    }
});

$(document).ready(function () {
    var option_default = $('.selectMarkCompanies').val();
    var tableMark = $('#tblDinamicMark').dataTable();
    if (option_default == null) {
        tableMark.fnFilter('', 1);
        return false;
    } else {
        tableMark.fnFilter(option_default, 1);
        return false;
    }
});