<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
session_unset();
session_destroy();

require_once dirname(__FILE__)  "/autoload.php";

$avisoSesion = "";
$aviso = "";
$empresa = ConectorBD::ejecutarQuery( " select nombre , icono from empresa" , null ) ;

date_default_timezone_set("America/Bogota");

foreach ($_GET as $key => $value)
    ${$key} = $value;

if ($aviso == '1') {
    $avisoSesion = '<br> USUARIO O CONTRASEÑA INCORRECTA';
} elseif ($aviso == '2') {
    $avisoSesion = '<br> TIEMPO CUMPLIDO DE INACTIVIDAD ';
} elseif ($aviso == '3') {
    $avisoSesion = '<br> USUARIO BLOQUEADO O INACTIVO ';
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="theme-color" content="#5e8210" />
        <link rel="icon" type="image/png" href="<?=$empresa[0][1]?>" />  
        <title><?=$empresa[0][0]?></title>

        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />

        <!--     Fonts and icons     -->
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">

        <!-- CSS Files -->
        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/gsdk-bootstrap-wizard.css" rel="stylesheet" />

        <!-- CSS Just for demo purpose, don't include it in your project -->
        <link href="css/demo.css" rel="stylesheet" />
    </head>

    <body>
        <div class="cargar_load" id="cargar_load" style="margin-top: 150px; margin-left: 45%;background: transparent; position: fixed; width: auto; height: auto;"></div>
        <div class="image-container set-full-height" >

            <!--   Big container   -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">

                        <!--      Wizard container        -->
                        <div class="wizard-container">

                            <div class="card wizard-card" data-color="orange" id="wizardProfile">
                                <form class="contenedorForm" id="modalesV" method="post" >   
                                    <!--        You can switch ' data-color="orange" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->

                                    <div class="wizard-header">
                                        <h3>
                                            <b><?=$empresa[0][0]?></b><br>
                                            <small>Sistema de Gestión de Información </small>
                                        </h3>
                                    </div>

                                    <div class="wizard-navigation">
                                        <ul>
                                            <li><a href="#about" data-toggle="tab"></a></li>
                                        </ul>

                                    </div>

                                    <div class="tab-content">
                                        <div class="tab-pane" id="about">
                                            <div class="row">
                                                <h4 class="info-text"> Inicio de sesión SIS-<?=$empresa[0][0]?> <small id="fecha"></small></h4>
                                                <div class="col-sm-4 col-sm-offset-1">
                                                    <div class="picture-container">
                                                        <div class="picture">
                                                            <img src="<?=$empresa[0][1]?>" class="picture-src" id="wizardPicturePreview" title=""/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Usuario <small>(required)</small></label>
                                                        <input name="usuario" required id="usuario" type="text" class="form-control" placeholder="afmorenor@sena.edu.co o 1085264553...">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contraseña <small>(required)</small></label>
                                                        <input name="pass" required id="pass" type="password" class="form-control" placeholder="***********************">
                                                    </div>
                                                    <div class="form-group">
                                                        <label> <small><a class="form_add" onclick="recuperar_clave()">¿Has olvidado tu contraseña?</a></small></label>                                        
                                                    </div>
                                                </div>                                  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wizard-footer height-wizard">
                                        <div class="pull-right">
                                            <input type="hidden" name="MiEmpresa" id="MiEmpresa" value="<?=$empresa[0][0]?>" />
                                            <input type="hidden" name="accion" id="accion" value="INICIAR" />
                                            <input type="submit" class='btn btn-finish btn-fill btn-warning btn-wd btn-sm' name="accionU" id="accionU" value="INICIAR" onclick="cargar( 'aviso' , 'validar' )"/>
                                        </div>
                                        <div class="pull-left">
                                            <input type='button' class='btn btn-previous btn-fill btn-default btn-wd btn-sm' name='previous' value='Previous' />
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                        </div> <!-- wizard container -->
                    </div>
                </div><!-- end row -->
            </div> <!--  big container -->
            <div class="wizard-header" style="margin-top: 100px ; margin-left: 100px;">
                <h4 id="aviso">  
                </h4>
                <h3>
                    <b>Ingenieria y Seguridad en las Alturas</b>
                </h3>
            </div>
        </div>
    </body>

    <!--   Core JS Files   -->
    <script src="js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

    <!--  Plugin for the Wizard -->
    <script src="js/gsdk-bootstrap-wizard.js"></script>

    <!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
    <script src="js/jquery.validate.min.js"></script>

</html>
<script src="js/Ajax.js"></script>   
<script src="js/Cargar.js"></script>   
<script src="js/recuperar.js"></script>   
<script src="js/Validar.js"></script>   
<script src="js/Menu.js"></script>   