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
    $menu = new Menu( ' id ' , $llave_Primaria_Contructor);


if ($id == 1 && $permisos)
{
    if( !isset($modalidadE) )
    {
        $modalidadE = "&modalidadE=''" ;
    }
    else
    {
        $modalidadE = "&modalidadE=$modalidadE" ;
    }

    $var_inf = Http::encryptIt("id=2&llave_Primaria=&user={$_SESSION["user"]}&accion=INFORME$modalidadE");

    ?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="<?PHP if($accion == 'BORRAR TODO'){  print_r('img/icon/borrar.png'); } else { print_r('img/icon/send.png'); }?>"/>
                <lable>
                    Se realizara la accion "<?= $accion ?>" a Indicativa Total cargado en el modulo de la Dirección de Formación Profesional.
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div> 
        <div>
            <fieldset>
                <legend title='ACCION'>ACCION</legend>
                TODO <br> <input type='radio' required checked value='TODO'  name='anio_envio' id='anio_envio3' "><br>
                <?= $fecha_vigencia ?> <br> <input type='radio' required checked value='<?= $fecha_vigencia ?>'  name='anio_envio' id='anio_envio1' "><br>
                <?= ( $fecha_vigencia + 1 ) ?> <br> <input type='radio' required value='<?= ( $fecha_vigencia + 1 ) ?>'  name='anio_envio' id='anio_envio2' ><br>
            </fieldset>
        </div> 
        <div>        
            <input type="submit" title="ACEPTA <?= $accion ?> EL ITEM ELEGIDO"  value="<?= $accion ?>" name="accionU" id="accionU" onclick="envioInfoeT( null , `<?= $var_inf ?>`  ,  'tablareporte' , `View/Menu/MenuModales.php` ,  event , 'ele' )" >
        </div>
    </div>    
<?PHP
}
elseif ($id == 2 && $permisos)
{
    if( $anio_envio != 'TODO' )
    {
        $anio_envio = " and vigencia='$anio_envio' " ;
    }
    else
    {
        $anio_envio = '' ;
    }
    
    if( $modalidadE == 'PRESENCIAL' )
    {
        $modalidadE = " and indicativa.id_modalidad in ('1' , '2') " ;
    }
    else if( $modalidadE == 'VIRTUAL' )
    {
        $modalidadE = " and indicativa.id_modalidad in ('3') " ;
    }
    else
    {
        $modalidadE = "";
    }
    $lista = "Registro Indicativa;centro;nombre sede;codigo regional;nombre regional;vigencia;oferta;Codigo del Programa;nombre del programa;Nivel de Formación;tipo de oferta;modalidad;red;mes de inicio;cupos;anio termina;curso;ambiente requiere;gira tecnica;programa fic;fecha" ;
    if( !empty($resultadosReporte=ConectorBD::ejecutarQuery("select id_indicativa, cod_centro, nombresede, departamento, nom_departamento, vigencia, oferta, indicativa.id_programa, nombre_programa, nivel_formacion, formacion, t6.metodologia, red, inicio, cupos, anio_termina, n_p_especial, ambiente_requiere, gira_tecnica, programa_fic, fecha from indicativa, grupo, dblink('dbname=eagle_admin port=5432 user=felipe password=utilizar' , 'select id_programa , nombre_programa , nivel_formacion , red_conocimiento , linea_tecnologica from programas') as t2 (id_programa text, nombre_programa text, nivel_formacion text, red_conocimiento text, linea_tecnologica text ), dblink('dbname=eagle_admin port=5432 user=felipe password=utilizar' , 'select codigosede, nombresede, departamento, nom_departamento from sede, departamento where sede.departamento=departamento.id') as t3 (codigosede text, nombresede text, departamento text, nom_departamento text ), dblink('dbname=registro port=5432 user=felipe password=utilizar' , 'select id_red, red from red_conocimiento') as t4 (id_red text, red text ),  dblink('dbname=registro port=5432 user=felipe password=utilizar' , 'select id_modalidad, metodologia from  modalidad ') as t6 (id_modalidad int, metodologia text )  where t2.id_programa=indicativa.id_programa and t3.codigosede=cod_centro and t2.red_conocimiento =t4.id_red  and indicativa.programa_especial=grupo.id and t6.id_modalidad=indicativa.id_modalidad $anio_envio $modalidadE ;" , null)))
    {    
        for ($j = 0; $j < count($resultadosReporte); $j++) 
        {
            $lista .= "\n{$resultadosReporte[$j][0]};" ;
            $lista .= "{$resultadosReporte[$j][1]};" ;
            $lista .= "{$resultadosReporte[$j][2]};" ;
            $lista .= "{$resultadosReporte[$j][3]};" ;
            $lista .= "{$resultadosReporte[$j][4]};" ;
            $lista .= "{$resultadosReporte[$j][5]};" ;
            $lista .= "{$resultadosReporte[$j][6]};" ;
            $lista .= "{$resultadosReporte[$j][7]};" ;
            $lista .= str_replace(['&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;'], ['A','E','I','O','U'], $resultadosReporte[$j][8] ) .";" ;
            $lista .= "{$resultadosReporte[$j][9]};" ;
            $lista .= "{$resultadosReporte[$j][10]};" ;
            $lista .= "{$resultadosReporte[$j][11]};" ;
            $lista .= "{$resultadosReporte[$j][12]};" ;
            $lista .= "{$resultadosReporte[$j][13]};" ;
            $lista .= "{$resultadosReporte[$j][14]};" ;
            $lista .= "{$resultadosReporte[$j][15]};" ;
            $lista .= "{$resultadosReporte[$j][16]};" ;
            $lista .= "{$resultadosReporte[$j][17]};" ;
            $lista .= "{$resultadosReporte[$j][18]};" ;
            $lista .= "{$resultadosReporte[$j][19]};" ;
            $lista .= "{$resultadosReporte[$j][20]};" ;
        } 
    }
    print_r( strtoupper( $lista ) ) ;
}
elseif ($id == 3 && $permisos)
{

?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/excel.png"/>
                <lable>
                    Se realizara la accion cargar "<?= $accion ?>" a Indicativa Total recuerde que las METAS se actualizan con el pe04 que se encuentre activo, cargado en el modulo de la Dirección de Formación Profesional.
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div> 
        <div>
            <fieldset>
                <legend title='ACCION'>ACCION</legend>
                <?= $fecha_vigencia ?> <br> <input type='radio' required checked value='<?= $fecha_vigencia ?>'  name='anio_envio' id='anio_envio1' "><br>
                <?= ( $fecha_vigencia + 1 ) ?> <br> <input type='radio' required value='<?= ( $fecha_vigencia + 1 ) ?>'  name='anio_envio' id='anio_envio2' ><br>
            </fieldset>
        </div> 
        <div>
            <fieldset>
                <legend title='ARCHIVO METAS'>ARCHIVO METAS</legend>
                <input type='file' value='' required name='documento' id="documento">
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?=$_SESSION['user']?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar( "aviso" )'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
        </div>
    </div>    
<?PHP
}
elseif ($id == 4 && $permisos ) {
    ?>
    <div id="conte_seccion" class="conte_seccion_icon tableIntT">
        <section>
            <div>
                <p>ARCHIVO CARGA PLANO CSV PE04</p><a href="Archivos/Ejemplos/PE04.csv"><img src="img/icon/excel.png" class="zoom" width=70" height=70"/></a>
            </div>
            <div>
                <p>ARCHIVO CARGA PLANO CSV METAS</p><a href="Archivos/Ejemplos/METAS.csv"><img src="img/icon/excel.png" class="zoom" width=70" height=70"/></a>
            </div>
        </section>
    </div>
<?PHP
}
elseif ($id == 5 && $permisos)
{

?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal" style="width: 100%; height: auto; margin-left: 0px;">
                <img src="img/icon/excel.png"/>
                <lable>
                    Se realizara la accion cargar "<?= $accion ?>" a Indicativa Total cargado en el modulo de la Dirección de Formación Profesional.
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>  
        <div>
            <fieldset>
                <legend title='ARCHIVO METAS'>ARCHIVO PE04</legend>
                <input type='file' value='' required name='documento' id="documento">
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $accion ?>" name="accion" id="accion">
            <input type='hidden' value='<?=$_SESSION['user']?>' name='personaGestion' id='personaGestion'>
            <input type="submit" value='<?= $accion ?>' name='accionU' id='accionU' onclick='cargar( "aviso" )'>
            <input type="reset" name="limpiarU"  value="LIMPIAR"/>
        </div>
    </div>    
<?PHP
}