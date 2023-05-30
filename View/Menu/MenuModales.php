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
$fecha_vigencia = date("Y");

// variable variable trae las variables que trae POST
foreach ($_POST as $key => $value)
    ${$key} = $value;

// desencripta las variables
$nuevo_POST = Http::decryptIt($I);
// evalua las nuevas variables que vienen ya desencriptadas
foreach ($nuevo_POST as $key => $value)
    ${$key} = $value;

// verificamos permisos
$permisos = new Persona(" identificacion ", "'" . $_SESSION['user'] . "'");

// permisos desde Http validando los permisos de un usuario segun la tabla personamenu
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "eagle");

if ($ingreso === false && $permisos->getIdTipo() !== "SA" && $_SESSION["rol"] !== "SA") {
    $permisos = false;
}

$llave_Primaria_Contructor = ( $llave_Primaria == "" ) ? "null" : "'$llave_Primaria'";

// llamamos la clase y verificamos si ya existe info de este dato que llega
$menu = new Menu( ' id ' , $llave_Primaria_Contructor);
if ($id == 1 && $permisos)
{
?>
<!--h1>1</h1-->
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/gestionar.png"/><label class="where">Administrador DFP – Dirección de Formación Profesional</label></div>
            <br><br>
            <label style="font-size: 1em; " >Tabla Menú</label>  
            <label style="font-size: 1em; " id="aviso" class="aviso" ></label> 
            <label style="font-size: 1em; " id="aviso2" class="aviso" ><?= $menu->getId() ?></label> 
        </div> 
        <div>
            <fieldset>
                <legend title='NOMBRE DEL MENÚ'>NOMBRE DEL MENÚ</legend>
                <input type="text" value='<?= $menu->getNombre() ?>' required name='nombre' id="nombre">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='REDIRECCIÓN DEL MENÚ'>REDIRECCIÓN DEL MENÚ</legend>
                <input type="text" value='<?= $menu->getPnombre() ?>' required name='pnombre' id="pnombre">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='NOMBRE ÍCONO'>NOMBRE ÍCONO</legend>
                <input type="text" value='<?= $menu->getIcono() ?>' required name='icono' id="icono">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='IMAGEN ÍCONO PNG'>IMAGEN ÍCONO PNG</legend>
                <input type='file'  value=''  name='imagen' id='imagen' >
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $menu->getId() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?=$_SESSION['user']?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar( "aviso" )'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
        </div>
    </div>
<?PHP 
}
if ($id == 2 && $permisos)
{
?>
<!--h1>2</h1-->
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/borrar.png"/>
                <lablel>
                    Se realizara la acción "<?= $accion ?>" al menú <?=$llave_Primaria?> cargado en el modulo de la Dirección de Formación Profesional.
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>  
        <div>        
            <input type="hidden" value="<?= $menu->getId() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type="submit" title="ACEPTA <?= $accion ?> EL ITEM ELEGIDO"  value="<?= $accion ?>" name="accionU" id="accionU" onclick="eliminar('aviso')">
        </div>
    </div>    
<?PHP
}
elseif ($id == 3 && $permisos)
{
?>
<!--h1>3</h1-->
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/estado.png"/>
                <lablel>
                    Administrador DFP – Dirección de Formación Profesional
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>
        <div class="nuevaseccion" >
            <fieldset>
                <section tyle="padding: 10px">
                    <h3>ID DE MENÚ: </h3> 
                    <p> <?= $menu->getId() ?></p>
                </section>
                <section tyle="padding: 10px">
                    <h3>NOMBRE DE MENÚ: </h3> 
                    <p> <?= $menu->getNombre() ?></p>
                </section>
                <section tyle="padding: 10px">
                    <h3>PNOMBRE DE MENÚ: </h3> 
                    <p style="word-wrap: break-word; margin: 10px"> <?= $menu->getPnombre() ?></p>
                </section>
                <section style="padding: 10px">
                    <h3>ÍCONO DE MENÚ: </h3>
                    <p><?= $menu->getIcono() ?></p>
                    <img width="50" src="img/icon/<?= $menu->getIcono() ?>.png" alt="<?= $menu->getIcono() ?>" />
                </section>
            </fieldset>
        </div>
    </div>
<?PHP
}
elseif ($id == 4 && $permisos ) {
    ?>
    <p>(Ayuda aquí)</p>
    <!--h1>4</h1-->
    
<?PHP
}
elseif ($id == 5 && $permisos ) {
    ?>
    <p>(Manuales aquí)</p>
    <!--h1>5</h1-->
<?PHP
}
?>