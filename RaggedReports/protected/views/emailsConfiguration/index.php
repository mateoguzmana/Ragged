<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="content">
                        <form>
                            <div class="row">
                                <div class="57">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                        <div class="header">
                                            <h3 class="title text-center">Configuración de Correos
                                            </h3>
                                        </div>
                                        <br>
                                        <label for="company">Compañias: <br></label><br>
                                        <select class='selectpicker' id="company" onchange="slctcompany()" title='Seleccione una compañía' multiple data-live-search="true" data-actions-box="true" data-selected-text-format="count">
                                            <?php
                                            for ($i = 0; $i < count($data->Companies); $i++) {
                                                ?>
                                                <option value='<?php echo $data->Companies[$i]->id; ?>'><?php echo $data->Companies[$i]->description; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <div id="rendertable">
                                            <div class="content table-responsive table-full-width">
                                                <table class="table table-hover table-striped" id="tblconfigurationemails">
                                                    <thead>
                                                        <tr>
                                                            <?php
                                                            for ($j = 0; $j < count($data->permissions); $j++) {
                                                                if ($data->permissions[$j]->idoption == 59) {
                                                                    ?>
                                                                    <th>EDITAR</th>
                                                                    <?php
                                                                }
                                                            }
                                                            echo $tabla_usuarios->users->columns;
                                                            ?>
                                                            <th>Correo</th>
                                                            <th>Documento</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        for ($i = 0; $i < count($data->SendEmails); $i++) {
                                                            ?>
                                                        <tr>
                                                            <?php
                                                            for ($l = 0; $l < count($data->permissions); $l++) {
                                                                if ($data->permissions[$l]->idoption == 59) {
                                                                    ?>
                                                                    <td><a onclick='EditEmailsConfiguration(<?php echo $data->SendEmails[$i]->id; ?>)' class='btn btn-default'><span class='glyphicon glyphicon-edit'></span></a></td>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <td><?php echo $data->SendEmails[$i]->email; ?></td>
                                                            <td><?php echo utf8_decode($data->SendEmails[$i]->description); ?></td>
                                                            <?php
                                                            if ($data->SendEmails[$i]->state == 1) {
                                                                ?>
                                                                <td><a onclick='ChangeSendEmailStatus(<?php echo $data->SendEmails[$i]->id; ?>)' class='btn btn-default'><span class='glyphicon glyphicon-ok'></span></a></td>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <td><a onclick='ChangeSendEmailStatus(<?php echo $data->SendEmails[$i]->id; ?>)' class='btn btn-default'><span class='glyphicon glyphicon-remove'></span></a></td>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
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

    .dropdown-toggle
    {
        margin-bottom: 5px !important;
    }

</style>
<script>
    $(document).ready(function () {
        var Emails = ('<?= json_encode($data->SendEmails); ?>');
        localStorage.setItem('jsonemails', JSON.parse(JSON.stringify(Emails)));
        $('#tblconfigurationemails').DataTable({
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
<script type="text/javascript" src="js/emailsconfiguration/EmailsConfiguration.js"></script>