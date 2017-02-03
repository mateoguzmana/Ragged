<?php
/* @var $this Controller */
$session = new CHttpSession;
$session->open();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="assets/images/favicon.ico">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />
        
        <script src="//librariessd2.amovil.net/ES/jquery/jquery.min.js"></script>
        <script src="//librariessd2.amovil.net/ES/jqueryt/jquery.min.js"></script>
        <link href="//librariessd2.amovil.net/ES/bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link href="//librariessd2.amovil.net/ES/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
        <link href="assets/DataTables/media/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
        <link href="assets/bootstrap/css/bootstrap-theme.css" rel="stylesheet" />
        <link href="assets/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" />
        <link href="//librariessd2.amovil.net/ES/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
        <link href="//librariessd2.amovil.net/ES/DataTables/media/css/jquery.dataTables.min.css" rel="stylesheet" />
        <link href="assets/light-bootstrap/css/light-bootstrap-dashboard.css" rel="stylesheet" />
        <link href="assets/light-bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="assets/light-bootstrap/css/animate.min.css" rel="stylesheet" />
        <link href="assets/light-bootstrap/css/demo.css" rel="stylesheet" />
        <link href="assets/sweet-alert/sweetalert.css" rel="stylesheet" />
        <link rel="stylesheet" href="assets/pikaday/css/pikaday.css">
        <link rel="stylesheet" src="assets/bootstrap-datepicker/css/bootstrap-datepicker.css">
        <!--<link href="assets/switchable-master/switchable.css" rel="stylesheet">-->
        <link href="assets/prettyCheckable/dist/prettyCheckable.css" rel="stylesheet">
        <title>Ragged</title>
    </head>
    <body>
        <div class="wrapper">
            <div class="sidebar" data-color="black" data-image="assets/img/sidebar-4.jpg">
                <div class="sidebar-wrapper">
                    <div class="logo">
                        <a href="#" class="simple-text">
                            RAGGED
                        </a>
                    </div>
                    <div class="MenuRagged">
                    </div>
                </div>
            </div>            
            <div class="main-panel">
                <nav class="navbar navbar-default navbar-fixed">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>-->
                            <a class="navbar-brand" href="#">Dashboard</a>
                        </div>
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-left">
                                <!--<li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-dashboard"></i>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-globe"></i>
                                        <b class="caret"></b>
                                        <span class="notification">5</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Notification 1</a></li>
                                        <li><a href="#">Notification 2</a></li>
                                        <li><a href="#">Notification 3</a></li>
                                        <li><a href="#">Notification 4</a></li>
                                        <li><a href="#">Another notification</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </li>-->
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a href="">
                                        Account
                                    </a>
                                </li>
                                <!--<li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        Dropdown
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </li>-->
                                <li>
                                    <a href="index.php?r=Site/Logout">
                                        Log out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="content">
                    <!-- Static Modal processing-->
                    <div class="modal modal-static fade" id="processing-modal" role="dialog" style="display: none" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="text-center">
                                        <img src="images/loading.gif" class="icon" />
                                        <div id="processing_message">CARGANDO...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid bodyrender">
                        <?php echo $content; ?>
                    </div>
                </div>
                <footer class="footer">
                    <div class="container-fluid">
                        <nav class="pull-left">
                            <ul>
                                <li>
                                    <a href="#">
                                        Soporte
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Ayuda
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <p class="copyright pull-right">
                            &copy; <?php echo date("Y"); ?> <a href="#">Creado por Activity Tecnology.</a>
                        </p>
                    </div>
                </footer>
            </div>
        </div>
        <script type="text/javascript" src="https://librariessd2.amovil.net/ES/bootstrap/js/bootstrap.min.js "></script>
        <!-- <script src="https://librariessd2.amovil.net/ES/bootstrap-select/dist/js/bootstrap-select.js"></script> -->
        <script src="assets/pikaday/moment.min.js"></script>
        <script src="assets/pikaday/pikaday.js"></script>
        <script type="text/javascript" src="assets/DataTables/media/js/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/DataTables/media/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="assets/light-bootstrap/js/light-bootstrap-dashboard.js"></script>
        <script type="text/javascript" src="assets/light-bootstrap/js/bootstrap-notify.js"></script>
        <script type="text/javascript" src="assets/light-bootstrap/js/demo.js"></script>
        <script type="text/javascript" src="assets/light-bootstrap/js/chartist.min.js"></script>
        <!--<script type="text/javascript" src="assets/light-bootstrap/js/bootstrap-checkbox-radio-switch.js"></script>-->
        <script type="text/javascript" src="assets/sweet-alert/sweetalert.min.js"></script>
        <script type="text/javascript" src="js/privileges/privileges.js"></script>
        <script type="text/javascript" src="js/profiletypes/profiletypes.js"></script>
        <script type="text/javascript" src="js/validations.js"></script>
        <script type="text/javascript" src="js/entities/orders/ordersummary.js"></script>
        <script type="text/javascript" src="js/entities/orders/orderdetail.js"></script>
        <!--<script src="assets/switchable-master/switchable.min.js"></script>-->
        <script src="assets/prettyCheckable/dist/prettyCheckable.min.js"></script>
    </body>
</html>

<style>     

.nav:nth-of-type(1) {
 color: transparent;   
}
    
</style>

<script type="text/javascript">
   var menu;
    $(window).resize(function(){    
    
       if ($(window).width() <= 680) {         
            $("#btneditprofile").html($(".glyphicon-edit"));            
            $("#btncreateprofile").html($(".glyphicon-plus"));
            $("#btndeleteprofile").html($(".glyphicon-trash"));
            $("#btncreateprofiletype").html($(".glyphicon-plus"));
            $("#btndeleteprofiletype").html($(".glyphicon-trash"));
            $("#btneditprofiletype").html($(".glyphicon-edit"));
       } 
       
        if ($(window).width() > 680)                                  
        {            
            $(".responsive-helper").remove();
            $('.MenuRagged').html("");            
            $('.MenuRagged').append(menu); 
        }
        
        else
        {
            $(".responsive-helper").remove();
            $('.MenuRagged').html("");            
            $( ".nav" ).prepend(menu);
        }
   });
   
    $(document).ready(function () {     
        
        function getPrivileges() {
            $.ajax({
                url: "index.php?r=Privileges/getModSubOptPri",
                success: function (data) {       
                    menu = "";
                    var obj = jQuery.parseJSON(data);
                    menu += '<div class="responsive-helper">';
                    menu += '<ul class="nav">';
                    for (var i = 0; i < obj.length; i++) {
                        menu += '<li id="' + obj[i].Modules.sourcecode + '">' +
                                '<a style="cursor:pointer" class="mod" data-mod="' + i + '">' +
                                '<i class="icon-book"></i>' +
                                '<span style="color:white !important" class="title dropup">' + obj[i].Modules.description + ' </span>' +
                                '<span class="arrow caret"></span>' +
                                '</a>';
                        for (var j = 0; j < obj[i].Submodules.length; j++) {
                            menu += '<ul style="display:none" class="sub-menu" data-id="' + i + '" id="' + obj[i].Submodules[j].idsourcecode + '">' +
                                    '<li>' +
                                    '<a style="color:white !important" href="#">' + obj[i].Submodules[j].submoduledescription + '</a>' +
                                    '</li>' +
                                    '</ul>';
                        }
                        menu += '</li>';
                    }             
                    menu += '</ul>';   
                    menu += '</div>';   
                    
                    isMobile(menu);
                }
            });
        }
        getPrivileges();              
        
        function isMobile(menu)
        {            
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
            {                
                $( ".navbar-nav" ).prepend(menu);                                                        
            }
            else
            {
                if ($(window).width() <= 901) 
                {                     
                    $( ".nav" ).prepend(menu);
                } 
                else
                {                  
                    $('.MenuRagged').append(menu); 
                }
            }
        }   
    });
</script>
<style>

    .modal-static {
        position: fixed;
        top: 50% !important;
        left: 50% !important;
        margin-top: -100px;
        margin-left: -100px;
        overflow: visible !important;
    }
    .modal-static,
    .modal-static .modal-dialog,
    .modal-static .modal-content {
        width: 200px;
        height: 200px;
    }
    .modal-static .modal-dialog,
    .modal-static .modal-content {
        padding: 0 !important;
        margin: 0 !important;
    }
    .modal-static .modal-content .icon {
    }
    body.modal-open #wrap{
        -webkit-filter: blur(17px);
        -moz-filter: blur(25px);
        -o-filter: blur(25px);
        -ms-filter: blur(25px);
        filter: blur(25px);
    }
    .modal-backdrop {background: #ffffff;}
    .close {
        font-size: 50px;
        display:block;
    }

</style>