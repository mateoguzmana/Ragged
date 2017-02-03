$(document).ready(function () {
    $("#btnGuardarPerfil").click(function () {
        var Perfil = $("#txtPerfilNuevo").val();
        if(Perfil==""){
            alert("Debe ingresar un perfil");
            return;
        }
        $.ajax({
            data: {'Perfil': Perfil},
            url: "index.php?r=administrarPrivilegios/GuardarPerfil",
            type: 'POST',
            success: function (data) {
                if (data == "OK") {
                    alert("Perfil almacenado correctamente.");
                    location.reload();
                }
            }
        });
    });

    $("#btnCancelar").click(function () {
        $("#modalConfirmacion").hide();
    });

    $("#btnGuardarPrivilegios").click(function () {
        $.ajax({
            data: {},
            url: "index.php?r=administrarPrivilegios/GuardarPrivilegios",
            type: 'POST',
            success: function (data) {
                alert(data);
            }

        });
    });



});

function seleccionarHijos(idmodule) {

    if ($("input:checkbox[name='" + idmodule + "']:checked").is(':checked')) {

        $(".bloque-" + idmodule + "").each(function () {
            $(this).prop('checked', true);
        });
    } else {
        $(".bloque-" + idmodule + "").each(function () {
            $(this).prop('checked', false);
        });
    }
}

function EliminarPerfil(IdPerfil) {
    if (confirm("Esta seguro de eliminar el perfil?") == true) {
        $.ajax({
            data: {'IdPerfil': IdPerfil},
            url: "index.php?r=administrarPrivilegios/EliminarPerfil",
            type: 'POST',
            success: function (data) {
                if (data == "OK") {
                    alert("Perfil eliminado correctamente");
                    location.reload();
                }
            }
        })
    }
}

