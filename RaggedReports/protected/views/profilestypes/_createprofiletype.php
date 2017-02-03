<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tipo Perfil</label>
                    <input type="text" id="txtnameprofiletype" class="form-control char" placeholder="Nombre del Tipo de Perfil" value="">
                    <div class="clearfix"></div>
                </div>
            </div>
            <!--<div class="col-md-4 col-md-offset-1">
                <div class="form-group">
                    <label for="privilegetype">TIPO PERFIL</label>
                    <select class="selectpicker" id="profiletype" title="Seleccione un tipo..">
                        <?php
                        $allprofilestype = json_decode($allprofilestypes);
                        foreach ($allprofilestype as $itemprofiletype) {
                            ?>
                            <option value="<?php echo $itemprofiletype->idprofiletype; ?>"><?php echo $itemprofiletype->profiletypedescription; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>   -->     
        <!--<div class="row privilegesrender">
        </div>-->
        <div class="row">
            <button type="button" id="btnsaveprofiletype" class="btn btn-info btn-fill pull-right">
                <span class="glyphicon glyphicon-save"></span>
                Guardar tipo perfil</button>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        //getPrivileges();
        $('#lblview').html('Crear Tipo de Perfil');
        $('.selectpicker').selectpicker('refresh');
        /*$('.selectpicker').each(function () {
            //$('.selectpicker').selectpicker('refresh');
            $(this).selectpicker('refresh');
        });*/
    });
</script>