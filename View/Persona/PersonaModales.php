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
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "eagle_admin");

if ($ingreso === false && $permisos->getIdTipo() !== "SA" && $_SESSION["rol"] !== "SA") {
    $permisos = false;
}

$llave_Primaria_Contructor = ( $llave_Primaria == "" ) ? "null" : "'$llave_Primaria'";

// llamamos la clase y verificamos si ya existe info de este dato que llega
$persona = new Persona( ' identificacion ' , $llave_Primaria_Contructor);
if ($id == 1 && $permisos)
{
?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/gestionar.png"/><label class="where">Administrador DFP – Dirección de Formación Profesional</label></div>
            <br><br>
            <label style="font-size: 1em; " >Tabla persona</label>  
            <label style="font-size: 1em; " id="aviso" class="aviso" ></label> 
            <label style="font-size: 1em; " id="aviso2" class="aviso" ><?= $persona->getId() ?></label> 
        </div> 
        <div>
            <fieldset>
                <legend title='IDENTIFICACION'>IDENTIFICACION</legend>
                <input type="number" value='<?= $persona->getId() ?>' required name='identificacion' id="identificacion">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='NOMBRES'>NOMBRES</legend>
                <input type="text" value='<?= $persona->getNombre() ?>' required name='nombres' id="nombres">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='APELLIDOS'>APELLIDOS</legend>
                <input type="text" value='<?= $persona->getApellido() ?>' required name='apellidos' id="apellidos">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='TELEFONO'>TELEFONO</legend>
                <input type="number" value='<?= $persona->getTel() ?>' required name='telefono' id="telefono">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='CELULAR'>CELULAR</legend>
                <input type="number" value='<?= $persona->getCelular() ?>' required name='celular' id="celular">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='CORREO SENA'>CORREO SENA</legend>
                <input type="email" value='<?= $persona->getCorreo() ?>' required name='correoinstitucional' id="correoinstitucional">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='ROL EN SISTEMA'>ROL EN SISTEMA</legend>
                <input list="rolUser" required name='idtipo' id="idtipo" value='<?= $persona->getIdTipo() ?>'>
                <datalist id="rolUser" >
                    <?= Select::listaopciones( 2 , $persona->getIdTipo() , "select id , nombrecargo from cargo WHERE  codigocargo <> 'SA' ;" )?>
                </datalist>
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $persona->getId() ?>" name="id" id="id">
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
                <img src="img/icon/borrar.png"/>
                <lablel>
                    Se realizara la accion "<?= $accion ?>" al Usuario <?=$llave_Primaria?> cargado en el modulo de la Dirección de Formación Profesional.
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>  
        <div>        
            <input type="hidden" value="<?= $persona->getId() ?>" name="id" id="id">
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
                <section>
                    <h3>IDENTIFICACION: </h3> 
                    <p> <?= $persona->getId() ?></p>
                </section>
                <section>
                    <h3>NOMBRES: </h3> 
                    <p> <?= $persona->getNombre() ?></p>
                </section>
                <section>
                    <h3>APELLIDOS: </h3> 
                    <p> <?= $persona->getApellido() ?></p>
                </section>
                <section>
                    <h3>CORREO SENA: </h3> 
                    <p> <?= $persona->getCorreo() ?></p>
                </section>
                <section>
                    <h3>TELEFONO: </h3> 
                    <p> <?= $persona->getTel() ?></p>
                </section>
                <section>
                    <h3>CELULAR: </h3> 
                    <p> <?= $persona->getCelular() ?></p>
                </section>
                <section>
                    <h3>CENTRO AL QUE PERTENECE: </h3> 
                    <p> <?= $persona->getidsede() ?></p>
                </section>
                <section>
                    <h3>NOMBRE DEL CENTRO : </h3> 
                    <p> <?= ConectorBD::ejecutarQuery( " select nombresede from sede where codigosede = '{$persona->getidsede()}' " , 'eagle_admin' )[0][0]  ?></p>
                </section>
                <section>
                    <h3>ROL EN SISTEMA DE INFORMACION: </h3> 
                    <p> <?= $persona->getIdTipo() ?></p>
                </section>
                <section>
                    <h3>NOMBRE DEL ROL : </h3> 
                    <p> <?= ConectorBD::ejecutarQuery( " select nombrecargo from cargo where codigocargo = '{$persona->getIdTipo()}' " , 'eagle_admin' )[0][0]   ?></p>
                </section>
            </fieldset>
        </div>
    </div>
<?PHP
}
elseif ($id == 4 && $permisos ) {
    ?>
    <div class="carga_Documento">
         <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <label style="font-size: 1em; " >Manuales y documentos <br> Administrador DFP – Dirección de Formación Profesional<br><br></label> 
            </div>
        </div>
    </div>
    <div id="conte_seccion" class="conte_seccion_icon tableIntT">
        <section>
            <div>
                <p>MANUAL PASO A PASO INDICATIVA VIRTUAL</p><a href="Archivos/Ejemplos/MANUAL_VIRTUAL.pdf" target="_blank"><img src="img/icon/pdf.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>MANUAL PASO A PASO INDICATIVA PRESENCIAL</p><a href="Archivos/Ejemplos/MANUAL_PRESENCIAL.pdf" target="_blank"><img src="img/icon/pdf.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>MANUAL PASO A PASO INDICATIVA REGIONAL</p><a href="Archivos/Ejemplos/MANUAL_REGIONAL.pdf" target="_blank"><img src="img/icon/pdf.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>MANUAL PASO A PASO INDICATIVA ADMINISTRADOR</p><a href="Archivos/Ejemplos/MANUAL_ADMIN.pdf" target="_blank"><img src="img/icon/pdf.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>ARCHIVO CARGA PLANO CSV PRESENCIAL</p><a href="Archivos/Ejemplos/CATALOGO_FORMATO_PRESENCIAL.csv"><img src="img/icon/excel.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>ARCHIVO CARGA PLANO CSV VIRTUAL</p><a href="Archivos/Ejemplos/CATALOGO_FORMATO_VIRTUAL.csv"><img src="img/icon/excel.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>ARCHIVO CARGA PLANO CSV PE04</p><a href="Archivos/Ejemplos/PE04.csv"><img src="img/icon/excel.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>ARCHIVO CARGA PLANO CSV METAS</p><a href="Archivos/Ejemplos/METAS.csv"><img src="img/icon/excel.png" class="zoom" width=70" height=70"/></a>
            </div>
        </section>
    </div>
    <div class="carga_Documento">
         <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <label style="font-size: 1em; " >Videos de ayuda dministrador DFP – Dirección de Formación Profesional<br><br></label> 
            </div>
        </div>
        <div style="width: auto">
            <fieldset>
                <legend title='PASO A PASO GENERAL '>PASO A PASO GENERAL CENTROS PRESENCIAL</legend>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/5y9Sg7okmjE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>            
            </fieldset>
         </div>
         <div style="width: auto">
            <fieldset>
                <legend title='PASO A PASO GENERAL '>PASO A PASO GENERAL CENTROS VIRTUAL</legend>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/VCtFmKXgWks" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>            </fieldset>
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
                <img src="img/icon/excel.png"/><label class="where">Administrador DFP – Dirección de Formación Profesional</div>
            </label><br><br>
            <label style="font-size: 1em; " >Bloqueo de usuario</label>   
            <label style="font-size: 1em; " id="aviso"></label>   
        </div>         
        <div>        
            <input type="hidden" value="<?= $persona->getId() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?=$_SESSION['user']?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar()'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
        </div>
</div>   
<?PHP 
}
?>
