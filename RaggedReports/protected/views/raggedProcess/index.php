<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="active"><a href="#ExcecuteProcess" aria-controls="home" role="tab" data-toggle="tab">Ejecutar Proceso Actualización</a></li>
                    <li role="presentation"><a href="#ListProcessExcecution" onclick="getListProcessExcecution()" aria-controls="profile" role="tab" data-toggle="tab">Listado Ejecución de Procesos</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="ExcecuteProcess">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="content">
                                <form>
                                    <div class="header">
                                        <h3 class="title text-center">Ejecución de Procesos</h3>
                                    </div>
                                    <br>
                                    <div class="content">
                                        <div>
                                            <div class="65"></div>
                                            <label for="company">Compañias: <br></label><br>
                                            <select class='selectpicker' id="company" title='Seleccione una compañía' multiple data-live-search="true" data-actions-box="true" data-selected-text-format="count">
                                                <?php
                                                for ($i = 0; $i < count($AllProcess->companies); $i++) {
                                                    ?>
                                                    <option value='<?php echo $AllProcess->companies[$i]->id; ?>'><?php echo $AllProcess->companies[$i]->description; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="processdata">
                                            <div class="content table-responsive">
                                                <table class="table table-hover table-striped" id="tblProcesses" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <?php
                                                            echo $AllProcess->Processes->columns;
                                                            ?>
                                                            <th class="text-center">Estado</th>
                                                            <th class="text-center">Dependencias</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        for ($k = 0; $k < count($AllProcess->Processes->Process); $k++) {
                                                            ?> 
                                                            <tr class="text-center">
                                                                <td><?php echo ($k + 1); ?></td>
                                                                <td><?php echo $AllProcess->Processes->Process[$k]->description; ?></td>
                                                                <td>
                                                                    <input type="checkbox" class="mycheck" id="answer" data-idproc="<?php echo $AllProcess->Processes->Process[$k]->idmethod; ?>" name=""/>
                                                                </td>
                                                                <td><a onclick="SeeDependencies(<?php echo $AllProcess->Processes->Process[$k]->idmethod; ?>)" class="btn btn-default"><span class="glyphicon glyphicon-list"></span></a></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="66">
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="ListProcessExcecution">        
    </div>
</div>
<style>
    .modal-backdrop {
        display:none;
    }

    @media (max-width: 991px) {
    
        .bootstrap-select {
            width:100% !important;
            margin-bottom: 5px;
        }
    }
    
    

</style>
<div class="modal fade" tabindex="-1" role="dialog" id="myModalDependencies">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title">
            </div>
            <div class="modal-body-dependencies">                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $(document).ready(function () {
        var dependencies = ('<?= json_encode($AllProcess->dependencies); ?>');
        localStorage.setItem('jsondependencies', JSON.parse(JSON.stringify(dependencies)));
        $('.selectpicker').selectpicker('refresh');
        $(".mycheck").each(function () {
            $(this).prettyCheckable();
        });
        $("label[for='answer']").hide();
        $('#tblProcesses').DataTable({
            aLengthMenu: [
                [20, 50, 100, -1],
                [20, 50, 100, "All"]
            ],
            iDisplayLength: 20,
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
<script type="text/javascript" src="js/raggedprocess/ProcesoRagged.js"></script>
