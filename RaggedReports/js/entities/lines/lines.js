/*
 * Created By Activity Technology S.A.S.
 */

$('.selectLineCompanies').on('change', function () {
    var tableLine = $('#tblDinamicLine').dataTable();
    var Companias = "";
    if ($(this).val() != null) {
        $.each($(this).val(), function (index, value) {
            Companias += value + "|";
        });
        Companias = Companias.slice(0, -1);
    }
    if ($(this).val() == null) {
        tableLine.fnFilter('', 1);
        return false;
    } else {
        tableLine.fnFilter(Companias, 1, true);
        return false;
    }
});

$(document).ready(function () {
    var option_default = $('.selectLineCompanies').val();
    var tableLine = $('#tblDinamicLine').dataTable();
    if (option_default == null) {
        tableLine.fnFilter('', 1);
        return false;
    } else {
        tableLine.fnFilter(option_default, 1);
        return false;
    }

});
