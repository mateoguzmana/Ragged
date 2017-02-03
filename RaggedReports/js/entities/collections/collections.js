/*
 * Created By Activity Technology S.A.S.
 */

$('.selectCollectionCompanies').on('change', function () {    
    var tableCollection = $('#tblDinamicCollection').dataTable();
    if ($(this).val() == 0) {
        tableCollection.fnFilter('', 3);
        return false;
    } else {
        tableCollection.fnFilter($(this).val(), 3);
        return false;
    }
});

$( document ).ready(function() {
   var option_default = $('.selectCollectionCompanies').val();
   var tableCollection = $('#tblDinamicCollection').dataTable();
   if (option_default == 0) {
        tableCollection.fnFilter('', 3);
        return false;
    } else {        
        tableCollection.fnFilter(option_default, 3);
        return false;
    }
   
});

$('#tblDinamicCollection').on("click", ".btnchangestate", function () {    
    var data = {};
    data.id = $(this).parent().parent().attr('data-id');
    data.status = $(this).attr('data-status');
    data.user = localStorage.getItem('nickname');

    if (data.id == "" || data.status == "") {
        swal("Error", "No se han encontrado los datos necesarios para modificar el estado de esta colección", "Error");
        return false;
    } else
    {
        $.ajax({
            data: {
                'data': data
            },
            url: 'index.php?r=Collection/AjaxChangeStatusCollection',
            type: 'post',
            beforeSend: function () {

            },
            success: function (response) {
                $('#itemlstCollections').trigger('click');                
            },
            error: function () {
                swal("Error", "Error al enviar datos, por favor comuniquese con Activity S.A.S.", "Error");
            }
        });

    }
});

$('body').on("click", "#selectAll", function () {   
    
    var company = $('.selectCollectionCompanies option:selected').val();        
    var status = 1;        
    $.ajax({
            data: {
                'company': company,
                'status': status
            },
            url: 'index.php?r=Collection/AjaxChangeAllStatusCollection',
            type: 'post',
            beforeSend: function () {

            },
            success: function (response) {                
                $('#itemlstCollections').trigger('click');
            },
            error: function () {
                swal("Error", "Error al enviar datos, por favor comuniquese con Activity S.A.S.", "Error");
            }
        }); 
        return false;
});

$('body').on("click", "#unselectAll", function () {   
    
    var company = $('.selectCollectionCompanies option:selected').val();        
    var status = 0;        
    $.ajax({
            data: {
                'company': company,
                'status': status
            },
            url: 'index.php?r=Collection/AjaxChangeAllStatusCollection',
            type: 'post',
            beforeSend: function () {

            },
            success: function (response) {                
                $('#itemlstCollections').trigger('click');
            },
            error: function () {
                swal("Error", "Error al enviar datos, por favor comuniquese con Activity S.A.S.", "Error");
            }
        }); 
        return false;
});
    
    


