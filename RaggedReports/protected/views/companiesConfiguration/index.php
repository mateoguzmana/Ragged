<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="content">
                        <form>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                        <div class="header">
                                            <h3 class="title text-center">Configuración de Compañias
                                            </h3>
                                        </div>
                                        <br>
                                        <div class="content table-responsive table-full-width">
                                            <table class="table table-hover table-striped" id="tblcompanies">
                                                <thead>
                                                    <tr>
                                                        <?php
                                                        for ($j = 0; $j < count($AllCompanies->permissions); $j++) {
                                                            if ($AllCompanies->permissions[$j]->idoption == 55) {
                                                                ?>
                                                                <th>Editar</th>
                                                                <?php
                                                            }
                                                        }
                                                        echo $AllCompanies->companies->columns;
                                                        /*for ($m = 0; $m < count($AllCompanies->permissions); $m++) {
                                                            if ($AllCompanies->permissions[$m]->idoption == 56) {
                                                                ?>
                                                                <th>Estado</th>
                                                                <?php
                                                            }
                                                        }*/
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($k = 0; $k < count($AllCompanies->companies->company); $k++) {
                                                        ?>
                                                        <tr>
                                                            <?php
                                                            for ($l = 0; $l < count($AllCompanies->permissions); $l++) {
                                                                if ($AllCompanies->permissions[$l]->idoption == 55) {
                                                                ?>
                                                                <td><a onclick="EditCompany('<?php echo $AllCompanies->companies->company[$k]->idcompany; ?>')" class="<?php echo $AllCompanies->permissions[$l]->class; ?>"><span class="<?php echo $AllCompanies->permissions[$l]->icon; ?>"></span></a></td>
                                                                <?php
                                                                }
                                                            }
                                                            echo $AllCompanies->companies->company[$k]->info;
                                                            /*for ($n = 0; $n < count($AllCompanies->permissions); $n++) {
                                                                if ($AllCompanies->permissions[$n]->idoption == 56) {
                                                                ?>
                                                                <td><a href="#" id="<?php echo $AllCompanies->companies->company[$k]->idcompany; ?>" class="<?php echo $AllCompanies->permissions[$n]->class; ?>"><span class="<?php if($AllCompanies->companies->company[$k]->stateid=="ACTIVO") echo 'glyphicon glyphicon-ok'; else echo 'glyphicon glyphicon-remove'; ?>"></span></a></td>
                                                                <?php
                                                                }
                                                            }*/
                                                            ?>
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
<script>
    $(document).ready(function () {
        $('#tblcompanies').DataTable({
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
<script type="text/javascript" src="js/companiesconfiguration/CompaniesConfiguration.js"></script>