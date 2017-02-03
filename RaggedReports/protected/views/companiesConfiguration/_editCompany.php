<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="active"><a href="#Companies" aria-controls="home" role="tab" data-toggle="tab">Compañias</a></li>
                    <li role="presentation"><a href="#BusinessRules" onclick="getBusinessRules()" aria-controls="profile" role="tab" data-toggle="tab">Reglas de Negocio</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="Companies">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="card">
                            <div class="content">
                                <form>
                                    <div class="header">
                                        <h3 class="title text-center">Editar Compañia</h3>
                                    </div>
                                    <br>
                                    <div class="content">
                                        <div class="editcompanydata"></div>
                                        <div class="">
                                            <button type="button" id="btnsavecompanyedit" class="btn btn-info btn-fill pull-right">
                                                <span class="glyphicon glyphicon-saved"></span>
                                                Guardar Compañia</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="BusinessRules">        
    </div>
</div>
<style>

    .bootstrap-select {
        width:100% !important;
    }
    
</style>