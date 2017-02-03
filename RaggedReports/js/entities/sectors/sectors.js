/*
 * Created By Activity Technology S.A.S.
 */

$('.selectSectorCompanies').on('change', function () {
    var Companias = "";
    if ($(this).val() != null) {
        $.each($(this).val(), function (index, value) {
            Companias += value + "|";
        });
        Companias = Companias.slice(0, -1);
    }
    var tableSector = $('#tblDinamicSector').dataTable();
    if ($(this).val() == null) {
        tableSector.fnFilter('', 1);
        return false;
    } else {
        tableSector.fnFilter(Companias, 1, true);
        return false;
    }
});

$(document).ready(function () {
    var option_default = $('.selectSectorCompanies').val();
    var tableSector = $('#tblDinamicSector').dataTable();
    if (option_default == null) {
        tableSector.fnFilter('', 1);
        return false;
    } else {
        tableSector.fnFilter(option_default, 1);
        return false;
    }

});