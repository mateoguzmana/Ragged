<?php 

$data1 = json_encode($DataDashboardReport);
$data1 = json_decode($data1);

?>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);

@keyframes bake-pie {
  from {
    transform: rotate(0deg) translate3d(0,0,0);
  }
}

body {
  font-family: "Open Sans", Arial;
  background: #EEE;
}
main {
  width: 600px;
  margin: 30px auto;
}
section {
  margin-top: 30px;
}
.pieID {
  display: inline-block;
  vertical-align: right;
}
.pie {
  height: 200px;
  width: 200px;
  position: relative;
  margin: 0 30px 30px 0;
}
.pie::before {
  content: "";
  display: block;
  position: absolute;
  z-index: 1;
  width: 100px;
  height: 100px;
  background: #EEE;
  border-radius: 50%;
  top: 50px;
  left: 50px;
}
.pie::after {
  content: "";
  display: block;
  width: 120px;
  height: 2px;
  background: rgba(0,0,0,0.1);
  border-radius: 50%;
  box-shadow: 0 0 3px 4px rgba(0,0,0,0.1);
  margin: 220px auto;
  
}
.slice {
  position: absolute;
  width: 200px;
  height: 200px;
  clip: rect(0px, 200px, 200px, 100px);
  animation: bake-pie 1s;
}
.slice span {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  background-color: black;
  width: 200px;
  height: 200px;
  border-radius: 50%;
  clip: rect(0px, 200px, 200px, 100px);
}
.legend {
  list-style-type: none;
  padding: 0;
  margin: 0;
  background: #FFF;
  padding: 15px;
  font-size: 13px;
  box-shadow: 1px 1px 0 #DDD,
              2px 2px 0 #BBB;
}
.legend li {
  width: 300px;
  height: 1.25em;
  margin-bottom: 0.7em;
  padding-left: 0.5em;
  border-left: 1.25em solid black;
}
.legend em {
  font-style: normal;
}
.legend span {
  float: right;
}
footer {
  position: fixed;
  bottom: 0;
  right: 0;
  font-size: 13px;
  background: #DDD;
  padding: 5px 10px;
  margin: 5px;
}

</style>
<!--<h5><?=$data1?></h5>-->
<div class="col-sm-6">
<div class="panel panel-primary">
	<div class="panel-footer primary">
            <span class="pull-left">Usuarios</span>
            <span class="pull-right"></span>
            <div class="clearfix"></div>
        </div>
   	<div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
                <i class="fa fa-user fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
                <div class="huge"><h3 id="totalsellers"></h3></div>
                <div>Usuarios transmitiendo</div>
            </div>
        </div>
    </div>
        <div class="panel-footer">
            <span class="pull-left">Usuarios activos</span>
            <span class="pull-right" id="activesellers"></span>
            <div class="clearfix"></div>
        </div>
        <div class="panel-footer">
            <span class="pull-left">Clientes activos</span>
            <span class="pull-right" id="activecustomers"></span>
            <div class="clearfix"></div>
        </div>
        <div class="panel-footer">
            <span class="pull-left">Clientes visitados</span>
            <span class="pull-right" id="customers"></span>
            <div class="clearfix"></div>
        </div>
</div>
</div>
<div class="col-sm-6">
<div class="panel panel-success">
	<div class="panel-footer success">
            <span class="pull-left">Pedidos</span>
            <span class="pull-right"></span>
            <div class="clearfix"></div>
        </div>
   	<div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
                <i class="fa fa-cart-plus fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
                <div class="huge"><h3 id="totalorders"></h3></div>
                <div class="huge" id="nettotalvalue"></div>
                <div>Pedidos d&iacute;a</div>
            </div>
        </div>
    </div>
        <div class="panel-footer">
            <span class="pull-left">N&uacute;mero pedidos mes</span>
            <span class="pull-right" id="totalordersmonth"></span>
            <div class="clearfix"></div>
        </div>
        <div class="panel-footer">
            <span class="pull-left">Valor neto mes</span>
            <span class="pull-right" id="nettotalvaluemonth"></span>
            <div class="clearfix"></div>
        </div>
        <div class="panel-footer">
            <span class="pull-left">Valor Iva mes</span>
            <span class="pull-right" id="ivatotalvaluemonth"></span>
            <div class="clearfix"></div>
        </div>
</div>
</div>
<main>
  <h4>Participaci&oacute;n productos mes</h4>
  <section>
    <div class="pieID pie">
      
    </div>
    <ul class="pieID legend" id="Piex">
    </ul>
  </section>
</main>
<script type="text/javascript">
var data1 = <?=$data1?>;
var totalsellers      = data1["sellers"][0]["totalsellers"];
var activesellers     = data1["activesellers"][0]["totalactivesellers"];
var activecustomers   = data1["activecustomers"][0]["totalcustomers"];
var customers         = data1["customers"][0]["totalcustomers"];
var totalorders       = data1["orders"][0]["totalorders"];
var nettotalvalue     = data1["orders"][0]["nettotalvalue"];
var ivatotalvalue     = data1["orders"][0]["ivatotalvalue"];
var totalordersmonth  = data1["ordersmonth"][0]["totalorders"];
var nettotalvaluemonth= data1["ordersmonth"][0]["nettotalvalue"];
var ivatotalvaluemonth= data1["ordersmonth"][0]["ivatotalvalue"];
var toporders         = data1["toporders"];

var HTMLPie = "";

for (var i = 0; i < toporders.length; i++) {
  HTMLPie+="<li><em>"+toporders[i]["referencecode"]+" - "+toporders[i]["size"]+" - "+toporders[i]["descriptioncolor"]+"</em><span>"+toporders[i]["total"]+"</span></li>";
};

$("#totalsellers").html(totalsellers); 
$("#activesellers").html(activesellers);
$("#activecustomers").html(activecustomers);
$("#customers").html(customers);
$("#totalorders").html(totalorders);
$("#nettotalvalue").html(nettotalvalue);
$("#ivatotalvalue").html(ivatotalvalue);
$("#totalordersmonth").html(totalordersmonth);
$("#nettotalvaluemonth").html(nettotalvaluemonth);
$("#ivatotalvaluemonth").html(ivatotalvaluemonth);
$("#Piex").html(HTMLPie);



console.log(toporders);
function sliceSize(dataNum, dataTotal) {
  return (dataNum / dataTotal) * 360;
}
function addSlice(sliceSize, pieElement, offset, sliceID, color) {
  $(pieElement).append("<div class='slice "+sliceID+"'><span></span></div>");
  var offset = offset - 1;
  var sizeRotation = -179 + sliceSize;
  $("."+sliceID).css({
    "transform": "rotate("+offset+"deg) translate3d(0,0,0)"
  });
  $("."+sliceID+" span").css({
    "transform"       : "rotate("+sizeRotation+"deg) translate3d(0,0,0)",
    "background-color": color
  });
}
function iterateSlices(sliceSize, pieElement, offset, dataCount, sliceCount, color) {
  var sliceID = "s"+dataCount+"-"+sliceCount;
  var maxSize = 179;
  if(sliceSize<=maxSize) {
    addSlice(sliceSize, pieElement, offset, sliceID, color);
  } else {
    addSlice(maxSize, pieElement, offset, sliceID, color);
    iterateSlices(sliceSize-maxSize, pieElement, offset+maxSize, dataCount, sliceCount+1, color);
  }
}
function createPie(dataElement, pieElement) {
  var listData = [];
  $(dataElement+" span").each(function() {
    listData.push(Number($(this).html()));
  });
  var listTotal = 0;
  for(var i=0; i<listData.length; i++) {
    listTotal += listData[i];
  }
  var offset = 0;
  var color = [
    "cornflowerblue", 
    "olivedrab", 
    "orange", 
    "tomato", 
    "crimson", 
    "purple", 
    "turquoise", 
    "forestgreen", 
    "navy", 
    "gray"
  ];
  for(var i=0; i<listData.length; i++) {
    var size = sliceSize(listData[i], listTotal);
    iterateSlices(size, pieElement, offset, i, 0, color[i]);
    $(dataElement+" li:nth-child("+(i+1)+")").css("border-color", color[i]);
    offset += size;
  }
}
createPie(".pieID.legend", ".pieID.pie");


</script>