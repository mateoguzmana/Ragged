//$(document).on('ready', function () {

$("#password, #username").keyup(function(event){
    if(event.keyCode == 13){        
        $("#authenticate").click();
    }
});

$('body').on('click', '#authenticate', function () {
    var user = $('#username').val();    
    localStorage.setItem('nickname', user);    
    var pass = $('#password').val();    
    var login = {'user': user, 'pass': pass};
    $.ajax({
        data: {
            'client': login,
        },        
        type: 'post',
        url: 'index.php?r=Login/Login',        
        success: function (response) {
            var obj = jQuery.parseJSON(response);
            
            if (obj.status == "DENIED") {                                
                //swal({   title: "Usuario o contraseña inválida!",   showConfirmButton: false, allowOutsideClick: true, type: "error" });                
                swal({   title: "Usuario o contraseña inválida!", type: "error",   showCancelButton: true,   showConfirmButton: false,   closeOnConfirm: false, cancelButtonText: "Aceptar" });
            } else {                                
                Menulayout(obj.values);
            }
        }
    });
});

function Menulayout(values) {
    $.ajax({
        data: {
            'data':values
        },
        //dataType: "json",
        type: 'post',
        url: 'index.php?r=Login/Load',
        success: function (res) {  
            console.log(res);
            window.open('index.php?r=Login/Index', '_self');
        }
    });

}
//});            