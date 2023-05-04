<?php

//error_reporting(0);
session_start();
date_default_timezone_set('America/Bogota');

foreach ($_POST as $key => $value) ${$key}=  $value;

require_once dirname(__FILE__).'/autoload.php';
 
$permisos = new Persona(' identificacion ', "'".$_SESSION['user']."'");
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "MI USUATIO");
 
?>
 <head>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
        <link rel="stylesheet" href="css/formulario.css?1">
        <link rel="stylesheet" href="css/tablas.css">
        <link rel="stylesheet" href="css/buscar.css">
        <link rel="stylesheet" href="css/modal.css?1">
        <link rel="stylesheet" href="css/body.css">
        <link rel="stylesheet" href="css/menu.css?1">
        <link rel="stylesheet" href="css/tabs.css">
        <link rel="stylesheet" href="css/seccion.css?1">
        <link rel="stylesheet" href="css/submenu.css">
        <link rel="stylesheet" href="css/titulo.css">
        <link rel="stylesheet" href="css/tituloPag.css">
        <link rel="stylesheet" href="css/tablaCompras.css">
        <link rel="stylesheet" href="css/estado.css">
        <link rel="stylesheet" href="css/contenido.css">
        <link rel="stylesheet" href="css/user.css">
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="<?= $_SESSION['Icon']?>" /> 
        <script src="https://kit.fontawesome.com/4fd211b547.js" crossorigin="anonymous"></script>
        <title><?= $_SESSION['MiEmpresa']?> </title>
        <meta charset="UTF-8">
</head>
<body onload="">
    <div class="modales" id="modales">
        <button class="fas fa-times-circle salir" onclick="cerrarventana()"></button>
        <div class="formularioDiv" id="formularioDiv">              
            <form class="modalesV" id="modalesV" action="" method="POST" enctype='multipart/form-data'>                 
                <p id="modalVentana"></p>                           
            </form>
        </div>
    </div>
    
    <div class="cargar_load" id="cargar_load" style="margin-top: 150px; margin-left: 45%;background: transparent; position: fixed; width: auto; height: auto;"></div>
    <!-- menu de inicio --> 
    
    <?php include_once './View/Menu/MenuNormal.php'; ?>
    <?php ''//include_once './Controller/Menu/MenuTop.php'; ?>
    <?php ''//include_once './Controller/Menu/MenuLeft.php'; ?>
    
    <div style=" width : 100% ; height : 250px ; overflow: hidden ">
        <div id="buscar" class="buscar"><br>
            <div id="fecha" class="fecha"><?= $_SESSION['ultima_sesion']?></div><br>
            <form method="post" id="formBuscar">
                <input type="serch" name="bucarPalabraClave" onkeyup="BuscarElementos()" id="bucarPalabraClave" class="bucarPalabraClave" placeholder=" BUSCADOR" />
            </form>     
        </div>
        <img src="<?=$_SESSION['banner']?>" style=" width : 100% ; height : 800px ; margin-top: -400px ; "/>
    </div>
    
    <div class="contenido">            
        <div class="tituloDonde">
<<<<<<<<< Temporary merge branch 1
            <div>SIS-<?= $_SESSION['MiEmpresa']?></div><br> 
            <label>MODULO :: MODULO ADMINISTRATIVO </label>  
=========
            <div>ROL :: <?= !empty( ( $_rol = ConectorBD::ejecutarQuery( " select nombrecargo from cargo where nombrecargo <> '' and codigocargo = '{$permisos->getIdTipo()}' " , 'eagle_admin' ) ) ) ? $_rol[0][0] : 'SUPER ADMIN' ?></div><br> 
            <label style="color: white"><b>MODULO > <?= $_SESSION['MiEmpresa']?> > <span id="sections" ></span> </b></label>  
>>>>>>>>> Temporary merge branch 2
        </div>
        <table class="tableIntT c">   
            <tr>
                <td  colspan="3" class="noHover">
                    <button class="fas fa-angle-double-left" name="Atras" id="Atras" title="Pag Atras" onclick="anterior()"></button>
                    <label class="pag" name="pag" id="pag">1</label>
                    <button class="fas fa-angle-double-right" name="Adelante" id="Adelante" title="Pag Adelante" onclick="siguiente()"></button>
                </td>  
            </tr>       
        </table>
        <div id="tableIntT" style="margin-bottom : 50px ; width: 100%; margin-bottom: 50px ; padding: 50px 0;"></div>  
    </div>
    <a id='botonE'  title='Descargar Excel' style="display: none"></a>    
    <div id='tablareporte' class="tableIntT tableIntTa" style="display: none;  border: 1px solid black;"></div>
</body>

<script>
    json = eval(<?php print_r( Http::url() ) ?>);
</script>

<script src="js/Http.js"> </script>
<script src="js/menu.js"> </script>
<script src="js/Ajax.js"> </script>
<script src="js/Paginas.js"> </script>
<script src="js/Buscar.js"> </script>
<script src="js/Ventana.js"> </script>
<script src="js/Cargar.js"> </script>
<script src="js/Eliminar.js"> </script>
<script src="js/Boton.js"> </script>
<script src="js/Validar.js"> </script>
