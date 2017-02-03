<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nombre del Perfil</label>
                    <input type="text" id="txtnameprofile" class="form-control char" placeholder="Nombre del perfil" value="">
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row rendertypeprofile"></div>
        </div>        
        <div class="row privilegesrender">
        </div>
        <div class="row">
            <button type="button" id="btnsaveprofileedit" class="btn btn-info btn-fill pull-right">
                <span class="glyphicon glyphicon-saved"></span>
                Guardar perfil</button>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        getPrivilegesedit();
    });
</script>