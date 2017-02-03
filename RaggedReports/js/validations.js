//$(document).on('ready', function () {
//
    //solo numeros
    $('body').on('keydown', '.nums', function () {
            $(this).val($(this).val().replace(/[^0-9]+/g, ''));
    });
    $('body').on('keyup', '.nums', function () {
            $(this).val($(this).val().replace(/[^0-9]+/g, ''));
    });
    $('body').on('blur', '.nums', function () {
            $(this).val($(this).val().replace(/[^0-9]+/g, ''));
    });
    
    //solo letras y numeros
    $('body').on('keydown', '.letrasnums', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9αινσϊρΡ\s]+/g, ''));
    });
    $('body').on('keyup', '.letrasnums', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9αινσϊρΡ\s]+/g, ''));
    });
    $('body').on('blur', '.letrasnums', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9αινσϊρΡ\s]+/g, ''));
    });
    
    //solo letras
    $('body').on('keydown', '.char', function () {
            $(this).val($(this).val().replace(/[^a-zA-ZαινσϊρΡ\s]+/g, ''));
    });
    $('body').on('keyup', '.char', function () {
            $(this).val($(this).val().replace(/[^a-zA-ZαινσϊρΡ\s]+/g, ''));
    });
    $('body').on('blur', '.char', function () {
            $(this).val($(this).val().replace(/[^a-zA-ZαινσϊρΡ\s]+/g, ''));
    });
    
    //solo letras, numeros (). rec
    $('body').on('keydown', '.letrasnumsrec', function () {
            $(this).val($(this).val().replace(/[^Ρραινσϊa-zA-Z0-9().\s]+/g, ''));
    });
    $('body').on('keyup', '.letrasnumsrec', function () {
            $(this).val($(this).val().replace(/[^Ρραινσϊa-zA-Z0-9().\s]+/g, ''));
    });
    $('body').on('blur', '.letrasnumsrec', function () {
            $(this).val($(this).val().replace(/[^Ρραινσϊa-zA-Z0-9().\s]+/g, ''));
    });
    
    //Correo
    $('body').on('keydown', '.mail', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9.@_-]+/g, ''));
    });
    $('body').on('keyup', '.mail', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9.@_-]+/g, ''));
    });
    $('body').on('blur', '.mail', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9.@_-]+/g, ''));
    });
    
    //Direccion
    $('body').on('keydown', '.dir', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9.-\s#]+/g, ''));            
            $(this).val($(this).val().replace(/\s\s+/g, ' '));         
    });
    $('body').on('keyup', '.dir', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9.-\s#]+/g, ''));            
            $(this).val($(this).val().replace(/\s\s+/g, ' '));         
    });
    $('body').on('blur', '.dir', function () {
            $(this).val($(this).val().replace(/[^a-zA-Z0-9.-\s#]+/g, ''));            
            $(this).val($(this).val().replace(/\s\s+/g, ' '));         
    });
