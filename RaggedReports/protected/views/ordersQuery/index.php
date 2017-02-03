<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-plain">
                                    <div class="header">
                                        <h3 class="title">Consulta de Pedidos</h3>
                                    </div>
                                    <br>
                                    <br>
                                    <div>
                                        <?php
                                        $cont = 0;
                                        foreach ($AllOrders->storagemethod as $key => $value) {
                                            if ($key != "Customers") {
                                                if ($cont % 4 == 0) {
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                            if ($cont % 4 == 0 || $cont == 0) {
                                                ?>
                                                <div class="row">
                                                    <?php
                                                }
                                                if ($key == 'Compania') {
                                                    $onchangename = str_replace(' ', '', $key) . "slc" . "()";
                                                } else {
                                                    $onchangename = "";
                                                }

                                                $labelname = $key == "Numero Pedido" ? "Número Pedido" : ($key == "Compania" ? "Compañía" : $key);
                                                ?>
                                                <div class="col-md-3"><div class="form-group"><label><?php echo $labelname ?></label>
                                                        <select onchange="<?php echo $onchangename ?>" class="selectpicker" id="<?php echo str_replace(' ', '', $key) ?>" title="Seleccione un tipo.." multiple data-live-search="true" data-actions-box="true" data-selected-text-format="count">';
                                                            <?php
                                                            for ($i = 0; $i < count($value); $i++) {
                                                                ?>
                                                                <option value="<?php echo $value[$i]->id ?>"><?php echo $value[$i]->description ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                                $cont++;
                                            }
                                        }
                                        ?>
                                        <div class="col-md-3"><div class="form-group"><label>Fecha Inicial</label>
                                                <div class="input-group date" class="datetimepicker" id="datetimepicker1"><input type="text" id="fechaini" class="form-control" style="cursor:pointer" value="<?php echo date('Y/m/d') ?>" readonly/>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Fecha Final</label>
                                                <div class="input-group date" class="datetimepicker" id="datetimepicker2"><input type="text" id="fechafin" class="form-control" style="cursor:pointer" value="<?php echo date('Y/m/d') ?>" readonly/>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div>
                                    </div>
                                </div>
                                <button style="margin-bottom:5px" type="button" onclick="SearchOrder()" class="btn btn-info btn-fill pull-left">
                                    <span class="glyphicon glyphicon-search"></span>
                                    Consultar
                                </button>
                            </div>
                            <div class="clearfix"></div>
                            <div id="rendertable">
                                <div class="content table-responsive">
                                    <table class="table table-hover table-striped" id="tblOrders" width="100%">
                                        <thead>
                                            <tr>
                                                <?php
                                                for ($j = 0; $j < count($AllOrders->permissions); $j++) {
                                                    if ($AllOrders->permissions[$j]->idoption == 61) {
                                                        ?>
                                                        <th>EDITAR</th>
                                                        <?php
                                                    }
                                                }
                                                echo $AllOrders->Orders->columns;
                                                for ($j = 0; $j < count($AllOrders->permissions); $j++) {
                                                    if ($AllOrders->permissions[$j]->idoption == 62) {
                                                        ?>
                                                        <th>DETALLES</th>
                                                        <?php
                                                    } else if ($AllOrders->permissions[$j]->idoption == 63) {
                                                        ?>
                                                        <th>VALIDACION REGLAS DE NEGOCIO</th>
                                                        <?php
                                                    } else if ($AllOrders->permissions[$j]->idoption == 64) {
                                                        ?>
                                                        <th>ENVIAR SIN REGLAS DE NEGOCIO</th>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for ($k = 0; $k < count($AllOrders->Orders->Order); $k++) {
                                                ?> 
                                                <tr>
                                                    <?php
                                                    for ($l = 0; $l < count($AllOrders->permissions); $l++) {
                                                        if ($AllOrders->permissions[$l]->idoption == 61) {
                                                            for ($p = 0; $p < count($AllOrders->storagemethod->{'Numero Pedido'}); $p++) {
                                                                if (($AllOrders->storagemethod->{'Numero Pedido'}[$p]->stateid == 1) && ($AllOrders->storagemethod->{'Numero Pedido'}[$p]->typecustomerid == 2) && ($AllOrders->storagemethod->{'Numero Pedido'}[$p]->id == $AllOrders->Orders->Order[$k]->orderid)) {
                                                                    $ban = true;
                                                                    return;
                                                                }
                                                            }
                                                            $ban = false;
                                                            $disable = $ban ? '' : 'disabled';
                                                            ?>
                                                            <td><a onclick="EditOrder(<?php echo $AllOrders->Orders->Order[$k]->orderid; ?>)" class="<?php echo $AllOrders->permissions[$l]->class; ?>" <?php echo $disable; ?>><span class="<?php echo $AllOrders->permissions[$l]->icon; ?>"></span></a></td>
                                                            <?php
                                                        }
                                                    }
                                                    echo $AllOrders->Orders->Order[$k]->info;
                                                    for ($j = 0; $j < count($AllOrders->permissions); $j++) {
                                                        if ($AllOrders->permissions[$j]->idoption == 62) {
                                                            ?>
                                                            <td><button onclick="ViewDetails(<?php echo $AllOrders->Orders->Order[$k]->orderid; ?>)" type="button" class="btn btn-primary btn-xs">Ver detalle</button></td>
                                                            <?php
                                                        } else if ($AllOrders->permissions[$j]->idoption == 63) {
                                                            ?>
                                                            <td><a onclick="BusinessRulesValidation(<?php echo $AllOrders->Orders->Order[$k]->orderid; ?>)" class="<?php echo $AllOrders->permissions[$j]->class; ?>"><span class="<?php echo $AllOrders->permissions[$j]->icon; ?>"></span></a></td>
                                                            <?php
                                                        } else if ($AllOrders->permissions[$j]->idoption == 64) {
                                                            ?>
                                                            <td><button onclick="SendOrder(<?php echo $AllOrders->Orders->Order[$k]->orderid; ?>)" type="button" class="btn btn-primary btn-xs">Enviar</button></td>
                                                            <?php
                                                        }
                                                    }
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
<div class="modal fade" tabindex="-1" role="dialog" id="myModalDetail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detalles del pedido</h4>
            </div>
            <div class="modal-body-detail">
                <div class="modal-footer-detail">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="myModal2">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reglas de Negocio</h4>
            </div>
            <div class="modal-body2">
                <div class="modal-footer2">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="myModal3">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar Pedido</h4>
            </div>
            <div class="modal-body3">
                <div class="modal-footer3">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style>
    .prettycheckbox > a, .prettyradio > a {
        height: 30px;
        width: 30px;
        display: block;
        float: left;
        cursor: pointer;
        margin: -4px !important;
    }
    
    .bootstrap-select 
    {
        width:100% !important;
    }
    
</style>
<script>
    $(document).ready(function () {
        var Orders = ('<?= json_encode($AllOrders->storagemethod); ?>');
        localStorage.setItem('jsonfilter', JSON.parse(JSON.stringify(Orders)));
        $('.selectpicker').selectpicker('refresh');
        $('#tblOrders').DataTable({
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
        var i18n = {
            previousMonth: 'Mes anterior',
            nextMonth: 'Próximo mes',
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', "Octubre", "Noviembre", "Diciembre"],
            weekdays: ["Domingo", " Lunes ", " Martes ", " Miércoles ", " Jueves ", " Viernes ", " Sabado "],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
        };
        var picker1 = new Pikaday({
            field: document.getElementById('fechaini'),
            numberOfMonths: 1,
            firstDay: 1,
            format: "YYYY/MM/DD",
            maxDate: moment().toDate(),
            yearRange: [1950, 2030],
            defaultDate: moment().toDate(),
            i18n: i18n,
        });
        var picker2 = new Pikaday({
            field: document.getElementById('fechafin'),
            numberOfMonths: 1,
            firstDay: 1,
            format: "YYYY/MM/DD",
            maxDate: moment().toDate(),
            yearRange: [1950, 2030],
            defaultDate: moment().toDate(),
            i18n: i18n,
        });
    });
</script>
<script type="text/javascript" src="js/ordersquery/OrdersQuery.js"></script>
<link href="css/order/orderdetail.css" rel="stylesheet" />