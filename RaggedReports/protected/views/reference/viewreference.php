<!-- 
Create by Activity Technology S.A.S.
-->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 panelcontent">
                <div class="card">
                    <div class="content panelhead">
                        <form>
                            <div class="row">
                                <div class="row">
                                    <p style="cursor:pointer;" id="title" class="h2 text-center">Referencias <span style="cursor:pointer;" class="clickable"><i class="glyphicon glyphicon-chevron-up"></i></span></p>                                    
                                </div>
                            </div>
                            <div class="row panelbody">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label for="profile">Compañías:</label>
                                        <div id="CompanyContainer">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="profile">Colecciones:</label>
                                        <div id="OptionCollectionsContainer">
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <label for="profile">Referencias:</label>
                                        <div id="OptionReferencesContainer">
                                        </div>
                                    </div>
                                    <div id="finder" class="col-md-3">
                                        <label class="filter-hide" for="profile">Lista de Precios:</label>
                                        <div class="filter-hide" id="OptionPriceListContainer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row panelbody">
                                <div class="col-md-12" style="padding-top: 0px">
                                    <div class="card card-plain">
                                        <div id="ReferenceContainer">
                                            <br>
                                            <div class="col-md-2"></div>
                                            <div class="col-md-2 col-xs-6"><button style="width:100%" id="btnback" type="button" class="btn btn-default">Atr&#225;s</button></div>
                                            <div class="col-md-2 col-xs-6"><button style="width:100%" id="btnconsultreferencesorder" type="button" class="btn btn-default">Filtrar</button></div>
                                            <div class="col-md-2 col-xs-6"><button style="width:100%" id="btnsaveorder" type="button" class="btn btn-default">Guardar Pedido</button></div>
                                            <div class="col-md-2 col-xs-6"><button style="width:100%" id="btnexitorderdetail" type="button" class="btn btn-default">Salir</button></div>
                                            <div class="col-md-2"></div>
                                            <br>
                                            <div style="text-align: center;" class="44">
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div id="DetailContainer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div> <div style="text-align: center;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE -->
<style>
    
    @media (max-width: 710px) {
       
        #btnback, #btnconsultreferencesorder {
            margin-bottom: 5px;
        }
        
        
        
    }
    
    .width100{ width: 100%;}

    .glyphicon.glyphicon-chevron-up {
        font-size: 20px;
    }

    .glyphicon.glyphicon-chevron-down {
        font-size: 20px;
    }

    .h2{
        margin-top : 5px;
    }

    .card .content {
        padding: 0px 15px 0px 15px;
    }

    .card-plain {
        margin-bottom: 0px;
    }

    .card {
        margin-bottom: 5px;
    }
    
    .bootstrap-select {
        width:100% !important;
    }

</style>

<script>
    $(document).ready(function () {
        var priceListStorage = localStorage.getItem('priceLists');
        if (priceListStorage == null)
        {
            displayReferenceFilters();
        }
        else
        {
            displayReferenceFilters();
            $("#title").html('Pedido Web <span style="cursor:pointer" class="clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>');
            var priceLists = JSON.parse(localStorage.getItem('priceLists'));
            var idCompany = localStorage.getItem('Company');
            disableSelects(priceLists, idCompany);
            filterOptionsCollections(idCompany, null);
            filterOptionsReferences(idCompany, null);
            $(".filter-hide").hide();
        }
    });
    function displayReferenceFilters()
    {
        var Collections = JSON.parse('<?= $Collections; ?>');
        var References = JSON.parse('<?= $References; ?>');
        var PriceList = JSON.parse('<?= $PriceList; ?>');
        if (JSON.stringify(References) !== '[]' && JSON.stringify(Collections) !== '[]' && JSON.stringify(PriceList) !== '[]') {
            var columnas = "";
            var contador = 0;
            var flag = false;
            var optionCompanies = "<select id='selectCompany' class='selectpicker selectReferenceCompanies' title='Seleccione la compañía'> <option value='0'>TODAS</option>";
            var optionCollections = "<select id='selectCollection' multiple='multiple' data-live-search='true' data-actions-box='true' class='selectpicker selectCollectionOptions' title='Seleccione las colecciones'>";
            var optionReferences = "<select id='selectReferenceOptions' multiple='multiple' data-live-search='true' data-actions-box='true' class='selectpicker ' title='Seleccione las referencias'> ";
            var optionPriceList = "<select id='selectPriceListOptions' multiple='multiple' data-live-search='true' data-actions-box='true' class='selectpicker ' title='Seleccione las listas de precios'>";
            var count = 0;
            var defaultcompany = 0;

            $.each(References.companies, function (key, val) {
                optionCompanies = optionCompanies + "<option value=" + val.idcompany + ">" + val.reasonsocial + "</option>";
                count++;
                if (count == 1)
                    defaultcompany = val.idcompany;
            });

            $.each(Collections.datas, function (key, val) {
                optionCollections = optionCollections + " <option value=" + val.Id + ">" + val["Descripci&#243;n"] + " </option> ";
            });

            $.each(References.datas, function (key, val) {
                optionReferences = optionReferences + "<option value=" + val.Id + ">" + val["C&#243;digo Referencia"] + " - " + val["Descripci&#243;n Referencia"] + "</option>";
            });

            $.each(PriceList.datas, function (key, val) {
                optionPriceList = optionPriceList + "<option value=" + val.Id + ">" + val["Descripci&#243;n"] + "</option>";
            });

            $('#CompanyContainer').html(optionCompanies);
            $('#OptionCollectionsContainer').html(optionCollections);
            $('#OptionReferencesContainer').html(optionReferences);
            $('#OptionPriceListContainer').html(optionPriceList);
            $('.selectpicker').selectpicker('refresh');

            //Ocultar Combo de filtro y datos de la grid según la cantidad de compañías que puede ver el usuario
            if (count == 1) {
                $(".selectReferenceCompanies").val(defaultcompany);
                $('.selectpicker').selectpicker('refresh');
            }
            else {
                $(".selectReferenceCompanies").val(0);
                $('.selectpicker').selectpicker('refresh');
            }
            //Aplicar privilegios a los botones
            for (var b = 0; b < References.privileges.length; b++)
            {
                if (References.privileges[b].active == 0) {
                    $(".content").find('.' + References.privileges[b].idsourcecode).addClass('disabled');
                }
            }
        } else {
            $('#ReferenceContainer').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
        }
    }

    function disableSelects(priceList, idCompany)
    {
        $("#selectPriceListOptions").val(priceList);
        $("#selectPriceListOptions").attr('disabled', true);
        $("#selectCompany").val(idCompany);
        $("#selectCompany").attr('disabled', true);
        $('.selectpicker').selectpicker('refresh');
    }

    function filterOptionsReferences(idCompany, idCollection)
    {
        var References = JSON.parse('<?= $References; ?>');
        var optionReferences = "<select id='selectReferenceOptions' multiple='multiple' data-live-search='true' data-actions-box='true' class='selectpicker' title='Seleccione las referencias'> ";
        $.each(References.datas, function (key, val) {

            if (idCollection == null)
            {
                if (idCompany == val["Compa&#241;&#237;a"]) {
                    optionReferences = optionReferences + "<option value=" + val.Id + ">" + val["C&#243;digo Referencia"] + " - " + val["Descripci&#243;n Referencia"] + "</option>";
                }
                else if (idCompany == 0)
                {
                    optionReferences = optionReferences + "<option value=" + val.Id + ">" + val["C&#243;digo Referencia"] + " - " + val["Descripci&#243;n Referencia"] + "</option>";
                }
            }

            else {
                var i = 0;
                for (i = 0; i < idCollection.length; i++)
                {
                    if (idCollection[i] == val["Colecci&#243;n"]) {
                        optionReferences = optionReferences + "<option value=" + val.Id + ">" + val["C&#243;digo Referencia"] + " - " + val["Descripci&#243;n Referencia"] + "</option>";
                    }
                }
            }
        });
        $('#OptionReferencesContainer').html(optionReferences);
        $('.selectpicker').selectpicker('refresh');
    }

    function filterOptionsCollections(idCompany, idCollection)
    {
        $('#selectCollection').find('option').remove();
        var Collections = JSON.parse('<?= $Collections; ?>');
        $.each(Collections.datas, function (key, val) {
            if (idCompany == val["Compa&#241;&#237;a"])
            {
                $('#selectCollection').append("<option class='collectionItem' value=" + val.Id + ">" + val["Descripci&#243;n"] + "</option>");
            }
            else if (idCompany == 0)
            {
                $('#selectCollection').append("<option class='collectionItem' value=" + val.Id + ">" + val["Descripci&#243;n"] + "</option>");
            }
        });
    }

    function filterOptionsPriceList(idCompany, idCollection)
    {
        var PriceList = JSON.parse('<?= $PriceList; ?>');
        var optionPriceList = "<select id='selectPriceListOptions' multiple='multiple' data-live-search='true' data-actions-box='true' class='selectpicker ' title='Seleccione las listas de precios'>";
        $.each(PriceList.datas, function (key, val) {
            if (idCompany == val["Compa&#241;&#237;a"])
                if (idCompany == val["Compa&#241;&#237;a"])
                {
                    optionPriceList = optionPriceList + "<option value=" + val.Id + ">" + val["Descripci&#243;n"] + "</option>";
                }
                else if (idCompany == 0)
                {
                    optionPriceList = optionPriceList + "<option value=" + val.Id + ">" + val["Descripci&#243;n"] + "</option>";
                }
        });
        $('#OptionPriceListContainer').html(optionPriceList);
        $('.selectpicker').selectpicker('refresh');
    }
</script>
<script type="text/javascript" src="js/entities/references/references.js"></script>
<script src="assets/bootstrap-select/js/bootstrap-select.js" type="text/javascript"></script>
<script src="assets/find_custom/find_custom2.js" type="text/javascript"></script>