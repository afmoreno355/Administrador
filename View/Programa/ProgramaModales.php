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
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "Indicativa");

if ($ingreso === false && $permisos->getIdTipo() !== "SA" && $_SESSION["rol"] !== "SA") {
    $permisos = false;
}

$llave_Primaria_Contructor = ( $llave_Primaria == "" ) ? "null" : "'$llave_Primaria'";

// llamamos la clase y verificamos si ya existe info de este dato que llega
    $programa = new Programa( ' id_programa ' , $llave_Primaria_Contructor);

if ($id == 1 && $permisos)
{
?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/gestionar.png"/><label class="where">Modulo Indicativa – Dirección de Formación Profesional</label></div>
            <br><br>
            <label style="font-size: 1em; " >Tabla Programas Virtual</label>  
            <label style="font-size: 1em; " id="aviso" class="aviso" ></label> 
            <label style="font-size: 1em; " id="aviso2" class="aviso" ><?= $programa->getId_programa() ?></label> 
        </div> 
        <div>
            <fieldset>
                <legend title='CODIGO DE PROGRAMA'>CODIGO DE PROGRAMA</legend>
                <input type="number" value='<?= $programa->getId_programa() ?>' required name='id_programa' id="id_programa">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='NOMBRE DEL PROGRAMA'>NOMBRE DEL PROGRAMA</legend>
                <input type="text" value='<?= $programa->getNombre_programa() ?>' required name='nombre_programa' id="nombre_programa">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='NIVEL DE FORMACION'>NIVEL DE FORMACION</legend>
                <select  required name='nivel_formacion' id="nivel_formacion">
                    <?= Select::listaopciones( 1 , $programa->getNivel_formacion() , "select nivel_formacion , nivel_formacion from programas group by nivel_formacion ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='DURACION DEL PROGRAMA'>DURACION DEL PROGRAMA</legend>
                <input type="number" value='<?= $programa->getDuracion() ?>' required name='duracion' id="duracion">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='RED DE CONOCIMIENTO'>RED DE CONOCIMIENTO</legend>
                <input list="red_conocimientos" value='<?= $programa->getRed_conocimiento() ?>' required name='red_conocimiento' id="red_conocimiento">
                <datalist id="red_conocimientos">
                    <?= Select::listaopciones( 2 , $programa->getRed_conocimiento() , "select id_red , concat( id_red , ' ' , red) from red_conocimiento ;" )?>
                </datalist>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='LINEA TECNOLOGICA'>LINEA TECNOLOGICA</legend>
                <select required name='linea_tecnologica' id="linea_tecnologica">
                    <?= Select::listaopciones( 2 , $programa->getLinea_tecnologica() , "select id , concat( id , ' ' , nombre )  from  linea_tecnologica ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='SEGMENTO'>SEGMENTO</legend>
                <select required name='segmento' id="segmento">
                    <?= Select::listaopciones( 1 , $programa->getSegmento() , "select segmento , segmento from programas where segmento <> '' group by segmento ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='MODALIDAD'>MODALIDAD</legend>
                <select required name='modalidad' id='modalidad'>
                    <?= Select::listaopciones( 1 , $programa->getModalidad() , "select modalidad , modalidad from programas where modalidad is not null and modalidad <> '' group by modalidad ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='FIC'>FIC</legend>
                <select required name='fic' id='fic'>
                    <?= Select::listaopciones( 4 , $programa->getFic()  )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='ACTIVO'>ACTIVO</legend>
                <select required name='activo' id='activo'>
                    <?= Select::listaopciones( 4 , $programa->getActivo()  )?>
                </select>
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $programa->getId_programa() ?>" name="id" id="id">
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
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="<?PHP if($accion == 'ELIMINAR'){  print_r('img/icon/borrar.png'); } else { print_r('img/icon/estado.png'); }?>"/>
                <lablel>
                    Se realizara la accion "<?= $accion ?>" a Indicativa Virtual <?=$llave_Primaria?> cargado en el modulo de la Dirección de Formación Profesional.
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>  
        <div>        
            <input type="hidden" value="<?= $programa->getId_programa() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type="submit" title="ACEPTA <?= $accion ?> EL ITEM ELEGIDO"  value="<?= $accion ?>" name="accionU" id="accionU" onclick="eliminar('aviso')">
        </div>
    </div>    
<?PHP
}
elseif ($id == 3 && $permisos)
{
?>
<div class="carga_Documento">
         <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/excel.png"/><label class="where">Módulo Indicativa Virtual Centros de Formación –  Dirección de Formación Profesional</div>
            </label><br><br>
            <label style="font-size: 1em; " >Archivos Planos para carga de programas virtual</label>   
            <label style="font-size: 1em; " id="aviso"></label>   
        </div>
        <div>
            <fieldset>
                <legend title='ARCHIVO EXCEL CSV '>ARCHIVO EXCEL CSV</legend>
                <input type='file' required value=''  name='archivo' id='archivo' ">
            </fieldset>
        </div>  
        <div>        
            <input type="hidden" value="<?= $programa->getId_programa() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?=$_SESSION['user']?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar()'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
        </div>
</div>   
<?PHP 
}
elseif ($id == 4 && $permisos)
{
?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/estado.png"/>
                <lablel>
                    Indicativa Programas Virtual – Dirección de Formación Profesional
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>
        <div class="nuevaseccion" >
            <fieldset>
                <section>
                    <h3>CODIGO DEL PROGRAMA: </h3> 
                    <p> <?= $programa->getId_programa() ?></p>
                </section>
                <section>
                    <h3>NOMBRE DEL PROGRAMA: </h3> 
                    <p> <?= $programa->getNombre_programa() ?></p>
                </section>
                <section>
                    <h3>DURACION PROGRAMA: </h3> 
                    <p> <?= $programa->getDuracion() ?></p>
                </section>                
                <section>
                    <h3>FIC: </h3> 
                    <p> <?= strtoupper( $programa->getFic() ) ?></p>
                </section>                
                <section>
                    <h3>ACTIVO: </h3> 
                    <p> <?= strtoupper( $programa->getActivo() ) ?></p>
                </section>                
                <section>
                    <h3>MODALIDAD PROGRAMA: </h3> 
                    <p><?= strtoupper( $programa->getModalidad() ) ?></p>
                </section>                
            </fieldset>
        </div>
    </div>
<?PHP
}
elseif ($id == 5 && $permisos)
{
?>
<div class="carga_Documento">
         <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/excel.png"/><label class="where">Módulo Indicativa Virtual Centros de Formación –  Dirección de Formación Profesional</div>
            </label><br><br>
            <label style="font-size: 1em; " >Bloqueo de programa virtual</label>   
            <label style="font-size: 1em; " id="aviso"></label>   
        </div>         
        <div>        
            <input type="hidden" value="<?= $programa->getId_programa() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?=$_SESSION['user']?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar()'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
        </div>
</div>   
<?PHP 
}
