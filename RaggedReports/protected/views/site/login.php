<!DOCTYPE HTML>
<html lang="en">
    <?php
    /* @var $this SiteController */
    /* @var $model LoginForm */
    /* @var $form CActiveForm  */

    /*$this->pageTitle = Yii::app()->name . ' - Login';
    $this->breadcrumbs = array(
        'Login',
    );*/
    ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="en">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        

        <!-- Website CSS style -->
        <link rel="stylesheet" type="text/css" href="assets/css/main.css">

        <!-- Website Font style -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

        <!-- core CSS Files    -->
        <!-- Bootstrap css    -->
        <link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="assets/bootstrap/css/bootstrap-theme.css" rel="stylesheet" />
        <link href="assets/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" />        
        
        <script src="assets/jquery.min.js" type="text/javascript"></script>
        
        

        <style>
            body, html{
                height: 100%;
                background-repeat: no-repeat;
                background-color: #d3d3d3;
                font-family: 'Oxygen', sans-serif;
            }

            .main{
                margin-top: 70px;
            }

            h1.title { 
                font-size: 50px;
                font-family: 'Passion One', cursive; 
                font-weight: 400; 
            }

            hr{
                width: 10%;
                color: #fff;
            }

            .form-group{
                margin-bottom: 15px;
            }

            label{
                margin-bottom: 15px;
            }

            input,
            input::-webkit-input-placeholder {
                font-size: 11px;
                padding-top: 3px;
            }

            .main-login{
                background-color: #fff;
                /* shadows and rounded borders */
                -moz-border-radius: 2px;
                -webkit-border-radius: 2px;
                border-radius: 2px;
                -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
                -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
                box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);

            }

            .main-center{
                margin-top: 30px;
                margin: 0 auto;
                max-width: 330px;
                padding: 40px 40px;

            }

            .login-button{
                margin-top: 5px;
            }

            .login-register{
                font-size: 11px;
                text-align: center;
            }

        </style>
        
    </head>
    <body>
        <div class="container">
            <div class="row main">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <div class="header" align="center">
                            <img src="images/ragged-logo.png" alt="" class="responsive-img valign center-block">                                    
                        </div>
                        <hr />
                    </div>
                </div> 
                <div class="main-login main-center">
                    <form class="form-horizontal" method="post" action="#">
                        <div class="form-group">
                            <label for="username" class="cols-sm-2 control-label">Username</label>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="username" id="username"  placeholder="Enter your Username"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="cols-sm-2 control-label">Password</label>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control" name="password" id="password"  placeholder="Enter your Password"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <button type="button" id="authenticate" class="btn btn-primary btn-lg btn-block login-button login">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--   Core JS Files   -->
        <script src="js/site/login.js" type="text/javascript"></script>
        <!--   jquery js  -->
        
        <!--   Bootstrap js  -->
        <script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/bootstrap/js/bootstrap.js" type="text/javascript"></script>
        <script src="assets/bootstrap/js/npm.js" type="text/javascript"></script>
        
        
        <link href="assets/sweet-alert/sweetalert.css" rel="stylesheet" />
        <script type="text/javascript" src="assets/sweet-alert/sweetalert.min.js"></script>
    </body>
</html>