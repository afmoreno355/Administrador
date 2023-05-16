<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// require auntomatico encuentra todas las clases/Model qeu se solicitan en el Controlador
require_once dirname(__FILE__) . "/../../autoload.php";

// Iniciamos sesion para tener las variables
session_start();

date_default_timezone_set("America/Bogota");
$fecha = date("Y-m-d");
$fecha_regional = date("Y");

// variable variable trae las variables que trae POST
foreach ($_POST as $key => $value)
    ${$key} = $value;

// desencripta las variables
$nuevo_POST = Http::decryptIt($I);
// evalua las nuevas variables que vienen ya desencriptadas
// array['numero', 1]
foreach ($nuevo_POST as $key => $value)
    ${$key} = $value;
     // ${numero} $numero = 1
// verificamos permisos
$permisos = new Persona(" identificacion ", "'" . $_SESSION['user'] . "'");

// permisos desde Http validando los permisos de un usuario segun la tabla personamenu
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "Indicativa");

if ($ingreso === false && $permisos->getIdTipo() !== "SA" && $_SESSION["rol"] !== "SA") {
    $permisos = false;
}

$llave_Primaria_Contructor = ( $llave_Primaria == "" ) ? "null" : "'$llave_Primaria'";

// llamamos la clase y verificamos si ya existe info de este dato que llega
$regional = new Regional( ' id  ' , $llave_Primaria_Contructor);

if ($id == 1 && $permisos)
{
    $anio_actas = ConectorBD::ejecutarQuery( " select anio from evidencia group by anio ; " , null ) ;
    $nom_dep = ConectorBD::ejecutarQuery( " select nom_departamento from departamento where id = {$regional->getCod()} " , null )[0][0] ;
    for ( $i = 0; $i < count( $anio_actas ); $i++ ) {
?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <lablel>
                    Actas cargadas regional <?= strtolower( $nom_dep ) ?>  año <?= $anio_actas[$i][0]  ?>   cargado en el modulo de la Dirección de Formación Profesional.
                </label>            </div><br><br>

            <label style="font-size: 1em; " id="aviso"></label>  
        </div> 
<?PHP
        $_actas = ConectorBD::ejecutarQuery( " select evidencia from evidencia where anio = '{$anio_actas[$i][0]}' and regional = {$regional->getCod()} order by evidencia desc; " , null ) ;
       // print_r(" select evidencia from evidencia where anio = '{$anio_actas[$i][0]}' and regional = {$regional->getCod()} ; ");
        for ($j = 0;  $j < count($_actas); $j++) 
        {
?>        
        <div>
            <span>
                <a title="Eliminar documento del proceso" ><i onclick="eliminarPro()" style="color: black;font-size: 1.2rem;cursor: pointer"  class="fas fa-times-circle salir"></i></a>
            </span>
            <p style="margin-top: -10px">
                ARCHIVO CARGADO : <?= str_replace( [ 'Archivos/pdf/' , '.pdf' ], ['',''] ,  $_actas[$j][0] ) ?>
            </p>
            <a target="_blank" href="<?= $_actas[$j][0] ?>"><img src="img/icon/pdf.png" class="zoom" width=70" height=70"/></a>
        </div>
<?PHP
    }
?>         
     </div> 
<?PHP 
    }
}
elseif ($id == 2 && $permisos)
{
?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/gestionar.png"/><label class="where">Modulo Autoevaluaciones – Dirección de Formación Profesional</label></div>
            <br><br>
            <label style="font-size: 1em; " >Tabla Documentos</label>  
            <label style="font-size: 1em; " id="aviso" class="aviso" ></label> 
        </div> 
        <div>
            <fieldset>
                <legend title='ARCHIVO ACTA REGIONAL'>ARCHIVO ACTA REGIONAL</legend>
                <input type='file' value='' required name='documento' id="documento">
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $regional->getCod() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?=$_SESSION['user']?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar( "aviso" )'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
        </div>
        <br>         
     </div>
     <div class="contenedor_barra_estado">
        <div class="barra_estado" id="barra_estado">
            <span id="spam">
            </span>
        </div>  
     </div>  
<?PHP 
}
if ($id == 3 && $permisos)
{
    ?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
            <img src="<?PHP if($accion == 'BORRAR TODO'){  print_r('img/icon/borrar.png'); } else { print_r('img/icon/send.png'); }?>"/>
                <lablel>
                    Se realizara la accion "<?= $accion ?>" a Indicativa Virtual o Presencial cargado en el modulo de la Dirección de Formación Profesional.
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div> 
        <div>
            <fieldset>
                <legend title='ACCION'>ACCION</legend>
                 Presencial <br> <input type='radio' required checked value='presencial'  name='modalidad' id='modalidad' "><br>
                 Virtual <br> <input type='radio' required value='virtual'  name='modalidad' id='modalidad' ><br>
            </fieldset>
        </div> 
        <div>        
            <input type="hidden" value="<?= $regional->getCod() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type="submit" title="ACEPTA <?= $accion ?> EL ITEM ELEGIDO"  value="<?= $accion ?>" name="accionU" id="accionU" onclick="cargar()">
        </div>
    </div>    
<?PHP
}
?>