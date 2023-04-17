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
    $municipio = new Municipio( ' municipio.id ' , $llave_Primaria_Contructor);

if ($id == 1 && $permisos)
{
?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/gestionar.png"/><label class="where">Modulo Indicativa – Dirección de Formación Profesional</label></div>
            <br><br>
            <label style="font-size: 1em; " >Tabla Municipio</label>  
            <label style="font-size: 1em; " id="aviso" class="aviso" ></label> 
            <label style="font-size: 1em; " id="aviso2" class="aviso" ><?= $municipio->getId() ?></label> 
        </div> 
        <div>
            <fieldset>
                <legend title='NOMBRE DE MUNICIPIO'>NOMBRE DE MUNICIPIO</legend>
                <input type="text" value='<?= $municipio->getMunicipio() ?>' required name='municipios' id="municipios">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='REGIONAL'>REGIONAL</legend>
                <select  required name='id_departamento' id="id_departamento">
                    <?= Select::listaopciones( 2 , $municipio->getId_departamento() , "select id , nom_departamento from departamento ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='CODIGO MUNICIPIO'>CODIGO MUNICIPIO</legend>
                <input type="number" value='<?= $municipio->getCodigo_municipio() ?>' required name='codigo_municipio' id="codigo_municipio">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='DANE'>DANE</legend>
                <input type="number" value='<?= $municipio->getDane() ?>' required name='dane' id="dane">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='CODIGO REGIONAL DEPARTAMENTO'>CODIGO REGIONAL MUNICIPIO</legend>
                <input type="number" value='<?= $municipio->getCod_dpto_mpio() ?>' required name='cod_dpto_mpio' id="cod_dpto_mpio">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='ACTIVO'>ACTIVO</legend>
                <select required name='activo' id='activo'>
                    <?= Select::listaopciones( 11 , $municipio->getEstado()  )?>
                </select>
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $municipio->getId() ?>" name="id" id="id">
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
                    Se realizara la accion "<?= $accion ?>" a Indicativa <?=$llave_Primaria?> cargado en el modulo de la Dirección de Formación Profesional.
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>  
        <div>        
            <input type="hidden" value="<?= $municipio->getId() ?>" name="id" id="id">
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
                <img src="img/icon/excel.png"/><label class="where">Módulo Indicativa Centros de Formación –  Dirección de Formación Profesional</div>
            </label><br><br>
            <label style="font-size: 1em; " >Archivos Planos para carga de municipios</label>   
            <label style="font-size: 1em; " id="aviso"></label>   
        </div>
        <div>
            <fieldset>
                <legend title='ARCHIVO EXCEL CSV '>ARCHIVO EXCEL CSV</legend>
                <input type='file' required value=''  name='archivo' id='archivo' ">
            </fieldset>
        </div>  
        <div>        
            <input type="hidden" value="<?= $municipio->getId() ?>" name="id" id="id">
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
                    Indicativa Municipio – Dirección de Formación Profesional
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>
        <div class="nuevaseccion" >
            <fieldset>
                <section>
                    <h3>ID DEL NUNICIPIO: </h3> 
                    <p> <?= $municipio->getId() ?></p>
                </section>
                <section>
                    <h3>NOMBRE DEL MUNICIPIO: </h3> 
                    <p> <?= $municipio->getMunicipio() ?></p>
                </section>
                <section>
                    <h3>NOMBRE REGIONAL: </h3> 
                    <p> <?= $municipio->getId_departamento() ?></p>
                </section>                
                <section>
                    <h3>CODIGO DEL MUNICIPIO: </h3> 
                    <p> <?= $municipio->getCodigo_municipio() ?></p>
                </section>                
                <section>
                    <h3>DANE: </h3> 
                    <p> <?= $municipio->getDane() ?></p>
                </section>                
                <section>
                    <h3>ACTIVO: </h3> 
                    <p> <?= ( $municipio->getEstado() == 'A' ) ? 'ACTIVO' : 'INACTIVO' ; ?></p>
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
                <img src="img/icon/excel.png"/><label class="where">Módulo Indicativa Centros de Formación –  Dirección de Formación Profesional</div>
            </label><br><br>
            <label style="font-size: 1em; " >Bloqueo de municipio</label>   
            <label style="font-size: 1em; " id="aviso"></label>   
        </div>         
        <div>        
            <input type="hidden" value="<?= $municipio->getId() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?=$_SESSION['user']?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar()'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
        </div>
</div>   
<?PHP 
}
