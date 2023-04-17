<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__) . "/../../classes/ConectorBD.php";
require_once dirname(__FILE__) . "/../../classes/persona.php";
require_once dirname(__FILE__) . "/../../classes/Sede.php";
require_once dirname(__FILE__) . "/../../classes/Cargo.php";

session_start();

$check = '';

foreach ($_POST as $key => $value)
    ${$key} = $value;

if ($id == 1) {
    $imagen = new Persona('identificacion', "'" . $user . "'");
    ?>
    <label id='modalesT' class='modalesT'>Cambiar Contraseña</label><br><br>                
    <table>
        <tr>
            <td>
                <label id='aviso'></label>                  
            </td>
        </tr>
        <tr>
            <td>
                <label>Contraseña Nueva</label><br>
                <input type='password' maxlength='500' required  id='passN'><br><br>
            </td>               
        </tr>
        <tr>
            <td>
                <label>Confirmar Contraseña</label><br>
                <input type='password' maxlength='500' required  name='rpassword' id='passC'><br><br>
            </td>               
        </tr>
        <tr>
            <td>
                <input type='hidden' name ='id' value='<?= $imagen->getId() ?>' S/>
                <input type='button' name ='accionU' id='accionU' value='<?= $accion ?>' onclick="dondeIrModalF('inicio.php?CONTENIDO=View/Usuario/UsuarioModificar.php')"/>
                <input type='reset' name='limpiarU'  value='LIMPIAR'/><br><br>
            </td>
        </tr>
    </table>

<?php
} elseif ($id == 2) {

    $add_Dep = '';

    if ($user != ' ') {
        $datos = new Persona(' identificacion ', "'$user'");
        if( $datos->getIdTipo()=='CO' || $datos->getIdTipo()=='SC' || $datos->getIdTipo()=='AS' || $datos->getIdTipo()=='RT' || $datos->getIdTipo()=='RJ'   )
        {
            $val1='';
            $val2='';
            $val3='';
            $val4='';
            $val5='';
            $val6='';
            $val7='';
            $val8='';
            $val9='';
            $val10='';
            $val11='';
            if( $datos->getDependencia()== 'ENI' )
            {
                $val1=' selected ';
            }
            elseif( $datos->getDependencia()== 'ADMIN. EDUCATIVA' )
            {
                $val2=' selected ';
            }
            elseif( $datos->getDependencia()== 'GEST. ESTRATEGICA Y ADMINISTRATIVA' )
            {
                $val3=' selected ';
            }
            elseif( $datos->getDependencia()== 'DESPACHO' )
            {
                $val4=' selected ';
            }
            elseif( $datos->getDependencia()== 'SENNOVA' )
            {
                $val5=' selected ';
            }
            elseif( $datos->getDependencia()== 'EJEC. DE LA FORM. PROF' )
            {
                $val6=' selected ';
            }
            elseif( $datos->getDependencia()== 'BIENESTAR' )
            {
                $val7=' selected ';
            }
            elseif( $datos->getDependencia()== 'GEST. CURRICULAR' )
            {
                $val8=' selected ';
            }
            elseif( $datos->getDependencia()== 'COORDINACION NAL. DE EMPRENDIMIENTO' )
            {
                $val9=' selected ';
            }
            elseif( $datos->getDependencia()== 'NAL. APE' )
            {
                $val10=' selected ';
            }
            elseif( $datos->getDependencia()== 'EJEC. FORMACION VIRTUAL' )
            {
                $val11=' selected ';
            }
            $add_Dep = "  
            <label>DEPENDENCIA</label><br>
            <select name='dependencia' id='dependencia'>
                <option value=''>DEPENDENCIA</option>
                <option value='ENI' $val1>ENI</option>
                <option value='ADMIN. EDUCATIVA' $val2>ADMIN. EDUCATIVA</option>
                <option value='GEST. ESTRATEGICA Y ADMINISTRATIVA' $val3>GEST. ESTRATEGICA Y ADMINISTRATIVA</option>
                <option value='DESPACHO' $val4>DESPACHO</option> 
                <option value='SENNOVA' $val5>SENNOVA</option>
                <option value='EJEC. DE LA FORM. PROF' $val6>EJEC. DE LA FORM. PROF</option>
                <option value='BIENESTAR' $val7>BIENESTAR</option> 
                <option value='GEST. CURRICULAR' $val8>GEST. CURRICULAR</option>
                <option value='COORDINACION NAL. DE EMPRENDIMIENTO' $val9>COORDINACION NAL. DE EMPRENDIMIENTO</option>
                <option value='NAL. APE' $val10>NAL. APE</option>
                <option value='EJEC. FORMACION VIRTUAL' $val11>EJEC. FORMACION VIRTUAL</option>
            </select><br><br>"; 
        }
        else 
        {
           $add_Dep = "<input type='hidden' id='dependencia' value='0' name='dependencia'>"; 
        }
    } else {
        $datos = new Persona(null, null);
    }
    if ($check != '') {
        $addMenu1 = "<tr>
                    <td>
                        <label>Tipo de Usuario</label><br>
                        <select id='perfil' required name='perfil' required onclick='codigocargo(this.value)'>
                             " . Cargo::listaopciones($user) . "
                        </select><br><br>
                    </td>
                </tr>";
        $addMenu2="<tr>
                    <td>
                        <label>Centro</label><br>
                        <input list='codigos' name='sede' id='sede' value='{$datos->getidsede()}'>
                        <datalist id='codigos'>" .
                Sede::listaopciones()
                . "</datalist><br><br>
                    </td>
                </tr>";
    } else {
        $addMenu1 = "<input type='hidden' id='perfil' value='{$datos->getIdTipo()}' name='perfil'>";
        $addMenu2 = "<input type='hidden' id='sede' value='{$datos->getidsede()}' name='sede'>";
    }
    ?>

    <label id='modalesT' class='modalesT'>CREAR O MODIFICAR DATOS DE PERFIL</label>                 
    <table>
        <tr>
            <td>   
                <label id='aviso'></label>                  
            </td>
        </tr>
        <tr>
            <td>
                <label>Identificacion Nit</label><br>
                <input type='number' maxlength='20' required value='<?= trim($datos->getId()) ?>' name='id' id='identificacion'><br><br>
            </td>
        </tr>
        <tr>
            <td>
                <label>Nombres</label><br>
                <input type='text' maxlength='50' required value='<?= trim($datos->getNombre()) ?>' name='nombre' id='nombres'><br><br>
            </td>
        </tr>
        <tr>
            <td>
                <label>Apellidos</label><br>
                <input type='text' maxlength='50' required value='<?= trim($datos->getApellido()) ?>' name='apellido' id='apellidos'><br><br>
            </td>
        </tr>
    <?= $addMenu1 ?>
        <tr>
            <td id="dependencias">
    <?=$add_Dep?>
            </td>
        </tr>
    <?= $addMenu2 ?>
        <tr>
            <td>
                <label>Telefono</label><br>
                <input type='tel' maxlength='10' required value='<?= trim($datos->getTel()) ?>' name='telefono' id='telefono'><br><br>
            </td>
        </tr>
        <tr>
            <td>
                <label>Celular</label><br>
                <input type='text' maxlength='80' required value='<?= trim($datos->getCelular()) ?>' name='celular' id='celular'><br><br>
            </td>
        </tr>
        <tr>
            <td>
                <label>Email Institucional</label><br>
                <input type='email' maxlength='200' required value='<?= trim($datos->getCorreo()) ?>' name='email' id='email'><br><br>
            </td>
        </tr>   
        <tr>
            <td rowspan='2' style='text-align: center;'>
                <input type='hidden' value='<?= trim($datos->getId()) ?>' name='identificacion'>
                <input type='hidden' name='usuario' value='<?= trim($user) ?>' />
                <input type='hidden' value='<?= $donde ?>' name='donde'>
                <input type='button' value='<?= $accion ?>' name='accionU' id='accionU' onclick="dondeIrModal('inicio.php?CONTENIDO=View/Usuario/UsuarioModificar.php')">
                <input type='reset' name='limpiarU'  value='LIMPIAR'/><br><br>
            </td>

        </tr>
    </table>

<?php
} elseif ($id == 4) {
    $datos = new Persona(' identificacion ', "'$user'");
    ?>

    <label id='modalesT' class='modalesT'>INFORMACION DEL USUARIO</label>                   
    <table>
        <tr>
            <td>
                Identificacion :<br>
    <?= $datos->getId() ?>
            </td>
        </tr>
        <tr>
            <td>
                Nombre Completo :<br>
    <?= strtoupper($datos->getNombre() . " " . $datos->getApellido()) ?>
            </td>
        </tr>
        <tr>
            <td>
                Telefono :  <br>
    <?= strtoupper($datos->getTel()) ?>
            </td>
        </tr>               
        <tr>
            <td>
                Celular :  <br>
    <?= strtoupper($datos->getCelular()) ?>
            </td>
        </tr>               
        <tr>
            <td>
                Email :  <br>
    <?= strtoupper($datos->getCorreo()) ?>
            </td>
        </tr>               
        <tr>
            <td>
                Centro :  <br>
    <?= strtoupper($datos->getidsede()) ?>
            </td>
        </tr>               
        <tr>
            <td>
                Tipo de Usario :  <br>
    <?= strtoupper($datos->IdTipo()) ?>
            </td>
        </tr>                               
    </table> <br><br>  

<?php } elseif ($id == 5) {
    ?> 
    <label id='modalesT' class='modalesT'>ELIMINAR USUARIO</label>                  
    <table>
        <tr>
            <td>
                Accion:<br>
                Esta Seguro de Elimira al Usuario con Indentificacion N° <?= $user ?><br><br>
            </td>
        </tr>                              
        <tr>
            <td>
                <input type="hidden"  name="id" value="<?= $user ?>"/> 
                <input type="button"  name="accionU" id="accionU" value="BORRAR" onclick="dondeIrModales('inicio.php?CONTENIDO=View/Usuario/UsuarioModificar.php')"/> 
            </td>
        </tr>                
    </table>    
<?php
} elseif ($id == 6) {

    print_r(Dependencia::listaDependencia($area));
} elseif ($id == 7) {
    $imagen = new Persona('identificacion', "'" . $user . "'");
    ?>
    <label id='modalesT' class='modalesT'>MODIFICAR IMAGEN DE PERFIL</label><br><br>                
    <table>
        <tr>
            <td>
                <label id='aviso'></label>                  
            </td>
        </tr>
        <tr>
            <td>
                <label> Mi Foto</label><br>
                <img src='<?= $imagen->getImagen() ?>' style='width: 200px; height: 200px'>
            </td>
        </tr>
        <tr>
            <td>
                <label> Archivo Nuevo </label><br>
                <input type='file' name ='Foto' value="" id="Foto" required /><br><br>
            </td>
        </tr>
        <tr>
            <td>
                <input type='button' name ='accionU' id='accionU' value='ADD FOTO' onclick="dondeIrModalIm('inicio.php?CONTENIDO=View/Usuario/UsuarioModificar.php')"/>
                <input type='reset' name='limpiarU'  value='LIMPIAR'/><br><br>
            </td>
        </tr>
    </table>

<?php } elseif ($id == 8) { ?>
    <div class="carga_Documento">
        <div class="contenido">  
            <div class="where_title where_modal">
                <img src="img/icon/gestion.png"/><div class="where">ADICIONAR ARCHIVOS PLANOS DE PERSONA</div>
            </div>
            <label id="aviso"></label>  
        </div>           
        <div>     
            <fieldset>
                <legend title='PLANO DE USUARIOS A CARGAR '>A PLANO CARGAR</legend>
                <input type='file' required  value='' name='personaplano' id='personaplano' onchange="PlanoPersona('View/Usuario/UsuarioModificar.php', `tablaRespuesta`)">
            </fieldset>
        </div>
        <div  class="contenido" >     
            <label class="label2"> -El sistema identifica para realizar el cargue de la información los siguientes separadores (signos) como coma ',', punto y coma ';', punto '.' y porcentaje '%' si es necesario puede igresar aqui un nuevo separador o palabra clave :) - </label>
        </div>
        <div>     
            <fieldset>
                <legend title='NUEVO SEPARADOR'>NUEVO SEPARADOR</legend>
                <input type='text'  value='' name='separador' id='separador' onkeyup="PlanoPersona('View/Usuario/UsuarioModificar.php', `tablaRespuesta`)">
            </fieldset>
        </div>
        <div>        
            <input type="hidden" value="<?= $_SESSION['user'] ?>" name="id" id="id">
            <input type="submit" title="ENVIAR LA INFORMACION PARA GUARDAR A LA BASE DE DATOS"  value="<?= $accion ?>" name="accionU" id="accionU" onclick="envio();">
            <input type="reset" title="LIMPIAR LAS CASILLAS PARA VOLVER A HACER UN INTENTO DE ENVIO" name="limpiarU"  value="LIMPIAR"/>
        </div>
        <div class="contenido">  
            <div class="contenido_check">
                <div class="column_check" id="identificacion"><div>IDENTIFICACION</div></div>
                <div class="column_check" id="nombres"><div>NOMBRES</div></div>
                <div class="column_check" id="apellido"><div>APELLIDOS</div></div>
                <div class="column_check" id="correo"><div>CORREO</div></div>
                <div class="column_check" id="celular"><div>CELULAR</div></div>
                <div class="column_check" id="telefono"><div>TELEFONO</div></div>
                <div class="column_check" id="id_sede"><div>SEDE</div></div>
                <div class="column_check" id="id_tipo"><div>ROL</div></div>
                <div class="column_check" id="dependencia"><div>DEPENDENCIA</div></div>
            </div>
        </div>
        <div class="contenido contenido_modal">  
            <label id="aviso2"></label>  
            <DIV style="overflow: scroll; width: auto; height: auto">
                <div class="tableIntD" id="tablaRespuesta"></div> 
            </DIV>
        </div>
    </div>
<?php
}?>