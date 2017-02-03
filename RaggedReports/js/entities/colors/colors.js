/*
 * Created By Activity Technology S.A.S.
 */

$('.selectColorCompanies').on('change', function () {    
    var tableColor = $('#tblDinamicColor').dataTable();
    var Companias = "";
    if ($(this).val() != null) {
        $.each($(this).val(), function (index, value) {
            Companias += value + "|";
        });
        Companias = Companias.slice(0, -1);
    }
    if ($(this).val() == null) {
        tableColor.fnFilter('', 1);
        return false;
    } else {
        tableColor.fnFilter(Companias, 1, true);
        return false;
    }
});

$( document ).ready(function() {
   var option_default = $('.selectColorCompanies').val();
   var tableColor = $('#tblDinamicColor').dataTable();
   if (option_default == null) {
        tableColor.fnFilter('', 1);
        return false;
    } else {        
        tableColor.fnFilter(option_default, 1);
        return false;
    }   
});