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
                                        <label for="profile">PERFIL</label>
                                        <select class="selectpicker" id="profile" title="Seleccione un perfil..">
                                            <?php
                                            $allprofiles = json_decode($allprofiles);
                                            foreach ($allprofiles as $itemprofile) {
                                                ?>
                                                <option value="<?php echo $itemprofile->id; ?>"><?php echo $itemprofile->description; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="5">
                                </div>
                                <div class="6">
                                </div>
                                <div class="4">
                                </div>
                                <div class="col-md-4 hide">
                                    <div class="form-group">
                                        <label for="profile">TIPO PRIVILEGIO</label>
                                        <select class="selectpicker" id="privilege" title="Seleccione un tipo..">
                                            <?php
                                            $allprivilege = json_decode($allprivileges);
                                            foreach ($allprivilege as $itemprivilege) {
                                                ?>
                                                <option value="<?php echo $itemprivilege->idprivilegetype; ?>"><?php echo $itemprivilege->descriptionprivilegetype; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <p id="lblview" class="h3"></p>
                            <div class="renderprofiles">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {                        
        
        $('.selectpicker').selectpicker('refresh');
        
    });
</script>
