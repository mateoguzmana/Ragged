<?php 

$data = json_encode($alldashboard); 
$data = json_decode($data); 

?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="content">
                        <form>
                            <div class="row">
                                <div class="row">
                                    <p class="h2 text-center">Dashboard</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <input id="date" class="form-control" type="date" data-date-format="YYYY-MM-DD" value="<?=date('Y-m-d');?>">
                                </div>
                                <div class="col-sm-5">
                                    <select id="company" class='form-control selectCustomerCompanies' title='Seleccione una compañía'>
                                    
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button id="btnQueryDashboard" type="button" class="form-control btn btn-primary">Consultar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label id="companiesLabel" for="profile"></label>
                                        <div id="CompanyContainer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                        <div id="CustomerContainer" class="table-responsive table-full-width">
                                        </div>
                                        <div style="text-align: center;">
                                            <br>
                                            <div class="36"></div>
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
<!-- END PAGE -->
<script type="text/javascript" src="js/entities/wallet/wallet.js"></script>
<script type="text/javascript" src="js/entities/customers/customer.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    var data = <?=$data?>;

    var options = "";

    for (var i = 0; i < data["companies"].length; i++) {
        options+="<option value="+data["companies"][i]["id"]+">"+data["companies"][i]["description"]+"</option>";
    }

    $(".selectCustomerCompanies").html(options);

    console.log("hola");

    $("#btnQueryDashboard").click(function(){

        var date    = $("#date").val();
        var company = $("#company").val();

        $.ajax({
            data: {
                'date'   : date,
                'company': company 
            },
            type: 'post',
            url: 'index.php?r=Dashboard/AjaxLoadReport',
            success: function (response) {
                $('#CompanyContainer').html(response);
            }
            }).done(function () {
                console.log("melo");
        });
    });

});

</script>