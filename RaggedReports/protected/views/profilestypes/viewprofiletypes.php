<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="content">
                        <form>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">                                        
                                        <label for="profiletype">TIPO DE PERFIL</label>
                                        <select class="selectpicker" id="profiletype" title="Seleccione un Tipo de Perfil..">
                                            <?php
                                            $allprofilestypes = json_decode($allprofilestypes);
                                            foreach ($allprofilestypes as $item) {
                                                ?>
                                                <option value="<?php echo $item->idprofiletype; ?>"><?php echo $item->profiletypedescription; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="33">
                                </div>
                                <div class="34">
                                </div>
                                <div class="35">
                                </div>
                            </div>
                            <p id="lblview" class="h3"></p>
                            <div class="renderprofilestypes">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script type="text/javascript">
    $(document).ready(function () {
        $('.selectpicker').selectpicker('refresh');
        /*$('.selectpicker').each(function () {
            //$('.selectpicker').selectpicker('refresh');
            $(this).selectpicker('refresh');
        });*/
        /*$('.selectpicker').selectpicker();*/
    });
</script>-->