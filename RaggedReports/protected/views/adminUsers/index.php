<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="content">
                        <form>
                            <div class="row">
                                <div class="1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                        <div class="header">
                                            <h4 class="title">Usuarios del Sistema</h4>
                                        </div>
                                        <br>
                                        <div class="content table-responsive table-full-width">
                                            <table class="table table-hover table-striped" id="tblusers">
                                                <thead>
                                                    <tr>
                                                        <?php
                                                        for ($j = 0; $j < count($tabla_usuarios->permissions); $j++) {
                                                            if ($tabla_usuarios->permissions[$j]->idoption == 2) {
                                                                ?>
                                                                <th>EDITAR</th>
                                                                <?php
                                                            } else if ($tabla_usuarios->permissions[$j]->idoption == 3) {
                                                                ?>
                                                                <th>BORRAR</th>
                                                                <?php
                                                            }
                                                        }
                                                        echo $tabla_usuarios->users->columns;
                                                        ?>
                                                        <th>VER DETALLES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($k = 0; $k < count($tabla_usuarios->users->user); $k++) {
                                                        ?>
                                                        <tr>
                                                            <?php
                                                            for ($l = 0; $l < count($tabla_usuarios->permissions); $l++) {
                                                                if ($tabla_usuarios->permissions[$l]->idoption == 2) {
                                                                    ?>
                                                                    <td><a onclick="EditUser(<?php echo $tabla_usuarios->users->user[$k]->userid; ?>)" class="<?php echo $tabla_usuarios->permissions[$l]->class; ?>"><span class="<?php echo $tabla_usuarios->permissions[$l]->icon; ?>"></span></a></td>
                                                                    <?php
                                                                } else if ($tabla_usuarios->permissions[$l]->idoption == 3) {
                                                                    ?>
                                                                    <td><a onclick="DeleteUser(<?php echo $tabla_usuarios->users->user[$k]->userid; ?>)" class="<?php echo $tabla_usuarios->permissions[$l]->class; ?>"><span class="<?php echo $tabla_usuarios->permissions[$l]->icon; ?>"></span></a></td>
                                                                    <?php
                                                                }
                                                            }
                                                            echo $tabla_usuarios->users->user[$k]->info;
                                                            ?>
                                                            <td><button id="<?php echo $tabla_usuarios->users->user[$k]->userid; ?>" type="button" class="btn btn-primary btn-xs btnsellerdetails">Ver detalle</button></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .modal-backdrop {
        display:none;
    }
</style>
<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Información del vendedor</h4>
            </div>
            <div class="modal-body">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $(document).ready(function () {
        $('#tblusers').DataTable({
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"]
            ],
            iDisplayLength: 10,
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ning?n dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "?ltimo",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    });
</script>
<script type="text/javascript" src="js/adminusers/usersadmin.js"></script>