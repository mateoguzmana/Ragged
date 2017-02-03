<!-- 
Create by Activity Technology S.A.S.
-->

<div id="accoridionContentDiv">     

</div>    

<!-- END PAGE -->


<script>
    $(document).ready(function () {
        var Plus = JSON.parse('<?= $Plus; ?>');
        if (JSON.stringify(Plus.plus.datas) !== '[]') {
            var referenceCodeTemp = 0;
            var referenceIdTemp = 0;
            var noColorPrice = 0;
            var colorTemp = 0;
            var colorIdTemp = 0;
            var priceListIdTemp = 0;
            var createAccordion = true;
            var tableInsideAccordion;
            var courtQuantity = 0;
            var physicalQuantity = 0;
            var totalQuantity = 0;
            var contIndex = 0;
            var exists;
            $.each(Plus.plus.datas, function (key, val) {
                var priceListHeader = [];
                var sizeHeader = [];
                var indexCount = 0;
                if (referenceCodeTemp == val['C&#243;digo Referencia'])
                    createAccordion = false;
                else
                {
                    createAccordion = true;
                    referenceCodeTemp = val['C&#243;digo Referencia'];
                    referenceIdTemp = val['IdReferencia'];
                }
                if (createAccordion)
                {
                    var referenceAccordion = "";
                    referenceAccordion = '<div class="panel-group" id="accordion-' + val.Id + '">';
                    referenceAccordion = referenceAccordion + '<div class="panel panel-default"><div class="panel-heading">';
                    referenceAccordion = referenceAccordion + '<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-' + val.Id + '" href="#collapse-' + val.Id + '">';
                    referenceAccordion = referenceAccordion + val['C&#243;digo Referencia'] + " - " + val['Descripci&#243;n Referencia'];
                    referenceAccordion = referenceAccordion + '</a></h4></div>';
                    referenceAccordion = referenceAccordion + '<div id="collapse-' + val.Id + '" class="panel-collapse collapse">';
                    referenceAccordion = referenceAccordion + ' <div style="overflow-x: scroll" class="panel-body">';
                    tableInsideAccordion = "<table id='tblDinamicPlus' class='display dataTable bordered centered' width='100%'>";
                    tableInsideAccordion = tableInsideAccordion + "<thead><tr>";
                    tableInsideAccordion = tableInsideAccordion + "<th>" + 'Color' + "</th>";
                    $.each(Plus.plus.datas, function (keyTableHead, valTableHead) {
                        $.each(val, function (kyTableHead, vlTableHead) {
                            if (referenceCodeTemp == valTableHead['C&#243;digo Referencia'] && kyTableHead == 'Tallas') {
                                exists = ($.inArray(valTableHead['Tallas'], sizeHeader));
                                if (exists == -1)
                                {
                                    sizeHeader.push(valTableHead['Tallas']);
                                    tableInsideAccordion = tableInsideAccordion + "<th>" + valTableHead['Tallas'] + "</th>";
                                }

                            }
                        });
                    });
                    $.each(Plus.priceList.datas, function (keyTableHead, valTableHead) {
                        $.each(valTableHead, function (kyTableHead, vlTableHead) {
                            if (referenceIdTemp == valTableHead['IdReferencia'] && kyTableHead == 'Lista de precio') {
                                exists = ($.inArray(valTableHead['Lista de precio'], priceListHeader));
                                if (exists == -1) {
                                    priceListHeader.push(valTableHead['Lista de precio']);
                                    tableInsideAccordion = tableInsideAccordion + "<th>" + valTableHead['Lista de precio'] + "</th>";
                                }
                            }
                        });
                    });
                    tableInsideAccordion = tableInsideAccordion + "</tr></thead>";
                    tableInsideAccordion = tableInsideAccordion + "<tbody>";
                    tableInsideAccordion = tableInsideAccordion + '<tr data-id="' + val.Id + '">';
                    $.each(Plus.plus.datas, function (keyTable, valTable) {
                        $.each(valTable, function (kyTable, vlTable) {
                            if (valTable['Colores'] != colorTemp) {
                                if (referenceCodeTemp == valTable['C&#243;digo Referencia'] && kyTable == 'Colores') {
                                    tableInsideAccordion = tableInsideAccordion + "<td style='cursor:pointer' class='tdColor' data-idColor=" + valTable.Id + " >" + valTable['C&#243;digo Color'] + " - " + valTable['Colores'] + "</td>";
                                    colorTemp = valTable['Colores'];
                                    colorIdTemp = valTable['IdColor'];
                                    var sizeFound = false;
                                    contIndex = 0;
                                    for (contIndex = 0; contIndex < sizeHeader.length; contIndex++) {
                                        sizeFound = false;
                                        $.each(Plus.plus.datas, function (keyTableQuant, valTableQuant) {
                                            if (referenceCodeTemp == valTableQuant['C&#243;digo Referencia']) {
                                                if (valTableQuant['Colores'] == colorTemp) {
                                                    if (sizeHeader[contIndex] == valTableQuant['Tallas'])
                                                    {
                                                        tableInsideAccordion = tableInsideAccordion + '<td>' + valTableQuant['Cantidad'] + '</td>';
                                                        sizeFound = true;
                                                    }
                                                }

                                            }
                                        });
                                        if (sizeFound == false) {
                                            tableInsideAccordion = tableInsideAccordion + "<td>" + "0" + "</td>";
                                        }
                                    }
                                    var priceListFound = false;
                                    contIndex = 0;
                                    for (contIndex = 0; contIndex < priceListHeader.length; contIndex++) {
                                        priceListFound = false;
                                        $.each(Plus.priceList.datas, function (keyTableHead, valTableHead) {
                                            var i;
                                            if (referenceIdTemp == valTableHead['IdReferencia']) {
                                                if (valTableHead['IdColor'] == 0 && priceListHeader[contIndex] == valTableHead['Lista de precio'])
                                                {
                                                    noColorPrice = valTableHead['Precio'];
                                                }
                                                if (colorIdTemp == valTableHead['IdColor'])
                                                {
                                                    if (priceListHeader[contIndex] == valTableHead['Lista de precio']) {
                                                        valTableHead['Precio'] = valTableHead['Precio'].toString().replace(".", ",");
                                                        valTableHead['Precio'] = valTableHead['Precio'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                                        tableInsideAccordion = tableInsideAccordion + "<td>" + valTableHead['Precio'] + "</td>";
                                                        priceListFound = true;
                                                    }
                                                }
                                            }
                                        });
                                        if (priceListFound == false) {
                                            noColorPrice = noColorPrice.toString().replace(".", ",");
                                            noColorPrice = noColorPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                            tableInsideAccordion = tableInsideAccordion + "<td>" + noColorPrice + "</td>";
                                        }
                                        noColorPrice = 0;
                                    }
                                    tableInsideAccordion = tableInsideAccordion + "</tr>";
                                }
                            }
                        });
                    });
                    tableInsideAccordion = tableInsideAccordion + "</tbody>";
                    tableInsideAccordion = tableInsideAccordion + "</table>";
                    referenceAccordion = referenceAccordion + tableInsideAccordion;
                    tableInsideAccordion = "";
                    referenceAccordion = referenceAccordion + '</div></div></div></div>';
                    $('#accoridionContentDiv').append(referenceAccordion);
                }
            });
        } else {
            $('#accoridionContentDiv').html('<div style="font-family: Segoe; font-size: 20px;">No se encontraron resultados.</div>');
        }
    });
</script>
<script type="text/javascript" src="js/entities/references/references.js"></script>