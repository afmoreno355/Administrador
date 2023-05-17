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
                <img src="img/icon/gestionar.png"/><label class="where">Módulo Indicativa – Dirección de Formación Profesional</label></div>
            <br><br>
            <label style="font-size: 1em; " >Tabla Programas</label>  
            <label style="font-size: 1em; " id="aviso" class="aviso" ></label> 
            <label style="font-size: 1em; " id="aviso2" class="aviso" ><?= $programa->getId_programa() ?></label> 
        </div> 
        <div>
            <fieldset>
                <legend title='CÓDIGO DE PROGRAMA'>CÓDIGO DE PROGRAMA</legend>
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
                <legend title='NIVEL DE FORMACIÓN'>NIVEL DE FORMACIÓN</legend>
                <select  required name='nivel_formacion' id="nivel_formacion">
                    <?= Select::listaopciones( 2 , $programa->getNivel_formacion() , "select nivel_formacion , nivel_formacion from programas group by nivel_formacion ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='DURACIÓN DEL PROGRAMA'>DURACIÓN DEL PROGRAMA</legend>
                <input type="number" value='<?= $programa->getDuracion() ?>' required name='duracion' id="duracion">
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='RED DE CONOCIMIENTO'>RED DE CONOCIMIENTO</legend>
                <input list="red_conocimientos" value='<?= $programa->getRed_conocimiento() ?>' required name='red_conocimiento' id="red_conocimiento">
                <datalist id="red_conocimientos">
                    <?= Select::listaopciones( 9 , $programa->getRed_conocimiento() , "select id_red , concat(id_red , ' ' , red) from red_conocimiento ;" )?>
                </datalist>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='LINEA TECNOLÓGICA'>LINEA TECNOLÓGICA</legend>
                <select required name='linea_tecnologica' id="linea_tecnologica">
                    <?= Select::listaopciones( 9 , $programa->getLinea_tecnologica() , "select id , ( id , nombre )  from  linea_tecnologica ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='SEGMENTO'>SEGMENTO</legend>
                <select required name='segmento' id="segmento">
                    <?= Select::listaopciones( 2 , $programa->getSegmento() , "select segmento , segmento from programas group by segmento ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='MODALIDAD'>MODALIDAD</legend>
                <select required name='modalidad' id='modalidad'>
                    <?= Select::listaopciones( 2 , $programa->getModalidad() , "select modalidad , modalidad from programas where modalidad is not null and modalidad <> '' group by modalidad ;" )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='FIC'>FIC</legend>
                <select required name='fic' id='fic'>
                    <?= Select::listaopciones( 10 , $programa->getFic()  )?>
                </select>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend title='ACTIVO'>ACTIVO</legend>
                <select required name='activo' id='activo'>
                    <?= Select::listaopciones( 10 , $programa->getActivo()  )?>
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
                    Se realizará la acción "<?= $accion ?>" a Indicativa <?=$llave_Primaria?> cargado en el módulo de la Dirección de Formación Profesional.
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
                <img src="img/icon/excel.png"/><label class="where">Módulo Indicativa Centros de Formación –  Dirección de Formación Profesional</div>
            </label><br><br>
            <label style="font-size: 1em; " >Archivos Planos para carga de programas </label>   
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
                    Indicativa Programas – Dirección de Formación Profesional
                </label>
            </div><br><br>
            <label style="font-size: 1em; " id="aviso"></label>  
        </div>
        <div class="nuevaseccion" >
            <fieldset>
                <section>
                    <h3>CÓDIGO DEL PROGRAMA: </h3> 
                    <p> <?= $programa->getId_programa() ?></p>
                </section>
                <section>
                    <h3>NOMBRE DEL PROGRAMA: </h3> 
                    <p> <?= $programa->getNombre_programa() ?></p>
                </section>
                <section>
                    <h3>DURACIÓN PROGRAMA: </h3> 
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
                <img src="img/icon/excel.png"/><label class="where">Módulo Indicativa Centros de Formación –  Dirección de Formación Profesional</div>
            </label><br><br>
            <label style="font-size: 1em; " >Bloqueo de programa</label>   
            <label style="font-size: 1em; " id="aviso"></label>   
        </div>   
        <div>   
            <fieldset>
                <legend title='ACTIVO'>ACTIVO</legend>
                <select required name='activo' id='activo'>
                    <?= Select::listaopciones( 10 , $programa->getActivo()  )?>
                </select>
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
elseif ($id == 6 && $permisos)
{
    if  ( !empty($resultadosReporte=ConectorBD::ejecutarQuery( 
                "
                    SELECT 
                        id_programa  ,     
                        nombre_programa ,                      
                        nivel_formacion ,
                        t1.red ,                                         
                        t2.nombre ,   
                        segmento , 
                        duracion , 
                        case
                            when  tipo_esp = 'T' then 'TITULADA'
                            when  tipo_esp = 'C' then 'COMPLEMENTARIA'                                        
                        else
                            ''
                        end 
                            as 
                                tipo_esp , 
                            modalidad ,                                  
                        lower(fic) ,                                           
                        lower(activo)
                    from
                        programas,
                        dblink('dbname=registro port=5432 user=felipe password=utilizar' , 
                               'select  id_red , red from red_conocimiento') 
                            as 
                                t1  ( id_red int , red  text ),	
                         dblink('dbname=registro port=5432 user=felipe password=utilizar' , 
                               'select  id , nombre from linea_tecnologica') 
                            as 
                                t2  ( id int , nombre  text )
                    where
                            programas.red_conocimiento = t1.id_red
                        and   
                            programas.linea_tecnologica = t2.id
                    order by
                        tipo_esp
                    desc    
                    ;         
                " , 
                'eagle_admin' )
               )
        )
    {    
        $lista = "CODIGO PROGRAMA ; NOMBRE DEL PROGRAMA ; NIVEL DE FORMACION ; RED DE CONOCIMIENTO ; LINEA TECNOLOGICA ; SEGMENTO ; DURACION ; TIPO DE FORMACION ; MODALIDAD ; FIC ; PROGRAMA ACTIVO " ; 
        for ($j = 0; $j < count($resultadosReporte); $j++) 
        {
            $lista .= "\n{$resultadosReporte[$j][0]};" ;
            $lista .= str_replace(['&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;','Á','É','Í','Ó','Ú','Ñ'], ['A','E','I','O','U','A','E','I','O','U','N'], $resultadosReporte[$j][1] ) . ";" ; 
            $lista .= "{$resultadosReporte[$j][2]};" ;
            $lista .= "{$resultadosReporte[$j][3]};" ;
            $lista .= "{$resultadosReporte[$j][4]};" ;
            $lista .= "{$resultadosReporte[$j][5]};" ;
            $lista .= "{$resultadosReporte[$j][6]};" ;
            $lista .= "{$resultadosReporte[$j][7]};" ;
            $lista .= "{$resultadosReporte[$j][8]};" ;
            $lista .= "{$resultadosReporte[$j][9]};" ;
            $lista .= "{$resultadosReporte[$j][10]};" ;
        }
    }
    print_r( strtoupper( $lista ) ) ;
}