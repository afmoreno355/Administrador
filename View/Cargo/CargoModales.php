<?php
require_once dirname(__FILE__) . "/../../autoload.php";
session_start();
date_default_timezone_set("America/Bogota");
$fecha = date("Y-m-d");
$fecha_vigencia = date("Y");
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
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), null);
if ($ingreso === false && $permisos->getIdTipo() !== "SA" && $_SESSION["rol"] !== "SA") {
    $permisos = false;
}
$llave_Primaria_Contructor = ( $llave_Primaria == "" ) ? "null" : "'$llave_Primaria'";
// llamamos la clase y verificamos si ya existe info de este dato que llega
$cargo = new Cargo(' id ', $llave_Primaria_Contructor);
if ($id == 1 && $permisos) {
    ?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/gestionar.png"/><label class="where">Cargo DFP – Dirección de Formación Profesional</label></div>
            <br><br>
            <label style="font-size: 1em; " >Cargo a modificar o agregar: </label>
            <label style="font-size: 1em; " id="aviso2" class="aviso" ><?= $cargo->getNombrecargo() ?></label> 
        </div> 
        <div>
            <fieldset>
                <legend title='ID DEL CARGO'>ID DEL CARGO</legend>
                <input type="text" value='<?= $cargo->getId() ?>' required name='id_cargo' id="id_cargo">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='CÓDIGO DEL CARGO'>CÓDIGO DEL CARGO</legend>
                <input type="text" value='<?= $cargo->getCodigocargo() ?>' required name='cod_cargo' id="cod_cargo">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='NOMBRE DEL CARGO'>NOMBRE DEL CARGO</legend>
                <input type="text" value='<?= $cargo->getNombrecargo() ?>' required name='nom_cargo' id="nom_cargo">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='DETALLE DEL CARGO'>DETALLE DEL CARGO</legend>
                <input type="text" value='<?= $cargo->getDetalle() ?>' required name='det_cargo' id="detalle_cargo">
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $cargo->getCodigocargo() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?= $_SESSION['user'] ?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar("aviso")'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
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
                                    Se realizara la accion de "<?= $accion ?>" al cargo <?= $cargo->getNombrecargo() ?> de los módulos de adminitración de la Dirección de Formación Profesional.
                    </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>  
        <div>        
            <input type="hidden" value="<?= $cargo->getId() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type="submit" title="ACEPTA <?= $accion ?> EL ITEM ELEGIDO"  value="<?= $accion ?>" name="accionU" id="accionU" onclick="eliminar('aviso')">
        </div>
    </div>    
    <?PHP
} elseif ($id == 3 && $permisos) {
    ?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/analisis.png"/>
                <lablel>
                             Información – Cargo
                    </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>
        <div class="nuevaseccion" >
            <fieldset>
                <section>
                    <h3>ID: </h3> 
                    <p> <?= $cargo->getId() ?></p>
                </section>
                <section>
                    <h3>CÓDIGO CARGO: </h3> 
                    <p> <?= $cargo->getCodigocargo() ?></p>
                </section>
                <section>
                    <h3>NOMBRE CARGO: </h3> 
                    <p> <?= $cargo->getNombrecargo () ?></p>
                </section>
                <section>
                    <h3>DETALLE: </h3> 
                    <p> <?= $cargo->getDetalle() ?></p>
                </section>
            </fieldset>
        </div>
    </div>
    <?PHP
} elseif ($id == 4 && $permisos) {
    ?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <label style="font-size: 1em; " >Manuales y documentos <br> CARGO DFP – Dirección de Formación Profesional<br><br></label> 
            </div>
        </div>
    </div>
    <div id="conte_seccion" class="conte_seccion_icon tableIntT">
        <section>
            <div>
                <p>MANUAL CREACIÓN Y MODIFICACIÓN DE CARGOS</p><a href="Archivos/Ejemplos/MANUAL_VIRTUAL.pdf" target="_blank"><img src="img/icon/pdf.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>MANUAL INFORMACIÓN Y BLOQUEO DE CARGOS</p><a href="Archivos/Ejemplos/MANUAL_PRESENCIAL.pdf" target="_blank"><img src="img/icon/pdf.png" class="zoom" width=70" height=70"/></a>
            </div>
        </section>
    </div>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal tamanio" style="width: 100%; height: auto; margin-left: 0px;">
                <label style="font-size: 1em; " >Video de ayuda cargo/administrador DFP – Dirección de Formación Profesional<br><br></label> 
            </div>
        </div>
        <div style="width: auto">
            <fieldset>
                <legend title='PASO A PASO CARGO '>PASO A PASO OPCIONES CARGO</legend>
                <iframe width="560" height="315" src="https://sena4-my.sharepoint.com/:v:/g/personal/cfavella_sena_edu_co/EdbTJGz46ldFpiyTOV9ewocB26ZeVpQUqFxcY9_-pb7WhA?e=yZDY9D" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>            
            </fieldset>
        </div>
    </div>
    <?PHP
}elseif ($id == 5 && $permisos) {
    ?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/riesgo.png"/>
                <lablel>
                                    Se realizara la accion de "<?= $accion ?>" al cargo <?= $cargo->getNombrecargo() ?> de los módulos de adminitración de la Dirección de Formación Profesional.
                    </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>  
        <div>        
            <input type="hidden" value="<?= $cargo->getCodigocargo() ?>" name="id" id="id">
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type="submit" title="ACEPTA <?= $accion ?> EL ITEM ELEGIDO"  value="<?= $accion ?>" name="accionU" id="accionU" onclick="eliminar('aviso')">
        </div>
    </div> 
    <?PHP
}
?>