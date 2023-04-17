<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author Felipe Moreno
 */
class Usuario {
    public static function modal($accion, $id, $usuario, $menu){
        
       $soporte=new Soporte(' numeroticket ', $id);
       $inventario=new Inventario(' id ', $id);
       if ($inventario->getId()=='' && $id!=0 && $soporte->getCodElemento()!='otro') {
          $inventario=new Inventario(' codelemeto ', "'".$soporte->getCodElemento()."'"); 
       } elseif($id==0 || $soporte->getCodElemento()=='otro') {
          $inventario=new Inventario(null,null);
          $inventario->setCalificadores("ELEMENTO");
       }
      $lista="<section id='registrar'><label for='cerrar' id='btnCerrar'></label>" 
              ." <div class='formulario' id='formulario'><form name='formulariosoporte' enctype='multipart/form-data' class='form-registro' method='post' action='main.php?CONTENIDO=layouts/options/usuarioFinalSoporte.php'>"
              ."<div id='nombreModal' class='nombreModal'>
                    <h1 id='nombre' class='nombre'>".$accion." UN TICKET A ".$inventario->getCalificadores()."</h1></div>";
      if ($accion=='ADICIONAR' || $accion=='ADICIONAR.') {
          $lista.="<label class='text-input'>Fecha de Solicitud (*)</label>"
                ."<input type='date' name='fechasolicitud' value='' maxlength='25' placeholder='' class='media' required>";
      } else {
          $lista.="<input type='hidden' name='fechasolicitud' value='{$soporte->getFechaSolicitud()}'>";
      }         
       $lista.="<label class='text-input'>Fecha Posible para Soporte (*)</label><input type='date' name='fechasoporte' value='".substr($soporte->getFechaSolicitud(),0,10)."' maxlength='25' placeholder='' class='media' required>";
       
       if ($accion=='ADICIONAR') {
          $lista.="<input type='hidden' name='BusquedaElemento' value='".trim($inventario->getCodelemento())."'>";
      } elseif ($accion=='MODIFICAR'){
         $lista.="<input type='hidden' name='BusquedaElemento' value='".$soporte->getCodElemento()."'>";
      } elseif ($accion=='ADICIONAR.') {
         $lista.="<label id='unol' class='text-input'>Elemento de Inventario (*)</label><input onkeyup='activar()' id='uno' list='BusquedaElemento' name='BusquedaElemento' value='' type='text'>".Inventario::listaopciones('BusquedaElemento',null);
         $lista.="<label id='dosl' class='text-input'>Otro servicio (*)</label><input type='checkbox' onclick='activar()' id='dos' name='BusquedaElemento' value='otro' >".Inventario::listaopciones('BusquedaElemento',null);
      } 
       
       $lista.="<label class='text-input'>Detalle (*)</label><input type='textarea' value='{$soporte->getDetalle()}' name='detalle' maxlength='25' placeholder='' class='media' value='' required></li>"
              ."<label class='text-input'>Descripcion (*)</label><textarea rows='5' cols='60' name='Descripcion' value='' placeholder='' style='width: 91.2%' required>{$soporte->getDescripcionDetalle()}</textarea>"
              ."<label class='text-input'>Tipo Mantenimiento (*)</label><select name='tipoM' class='media'>".TipoTicket::listaopciones($soporte->Tipo()).""  
              ."</select><label class='text-input'>Administrador Ticket (*)</label><select name='adminT' class='media'>".AdminTicket::listaopciones($soporte->Admin()).""  
              ."</select><label class='text-input'>Prioridad (*)</label><select name='prioridad' class='media'>".Prioridad::listaopciones($soporte->PrioridadRequerimiento()).""
              ."</select><label class='text-input'>Evidencia </label>"
              ."<FIELDSET><LEGEND>AGREGAR REGISTRO FOTOGRAFICO</LEGEND><LABEL>JPG, PNG:<input type='file' name='AddFoto' id='AddFoto' value=''>"
              ."</LABEL></FIELDSET>";
           
       $lista.= "<input type='hidden' value='$usuario' name='Persona'>"
               . "<input type='hidden' value='$id' name='id'>"
               . "<input type='hidden' value='1' name='estado'>"
               . "<input type='hidden' name='usuario' value='".$usuario."'>"
               . "<input type='hidden' name='menu' value='".$menu."'>"
               . "<input type='hidden' name='donde' value='layouts/options/misTickets'>"
               . "<input type='submit' name='accion' value='".$accion."' required />"
               . "<input type='reset' name='limpiar' value='Limpiar' />"                  
              ."</form></div></section>";
             
      return $lista;
  }
  
   public static function modaldos($accion, $elemento, $estado, $usuario, $menu){
    
    $inventario=new Inventario('id', $elemento);
   $cadena= ConectorBD::ejecutarQuery("select * from prestamos where idinventario=$elemento and idtipoprestamo='Express' order by id desc limit 1;", null);
   if ($cadena!=null) {
     if (trim($cadena[0][7])=='Prestamo') {
       $accion='ESTADO';
       $estado='Cuentadante';
   }  
   }
   
    $lista="<section id='registrar'>	
        <div class='formulario_edit' id='formulario'>		
        <form class='form-registro' method=post action='main.php?CONTENIDO=layouts/options/usuarioFinalPrestamo.php'>
           <div id='nombreModal' class='nombreModal'>
                    <h1 id='nombre' class='nombre'>Inventario:: ".$inventario->getCalificadores()." </h1></div> 
            <h2 class='form-titulo'>".$accion."</h2> <center><h4></h4></center>
                        <br>
            
            <label class='text-input'>Codigo Serial de Prestamo</label>            
            <input type='text' name='id' class='media' required disabled value=''>";  
    
    if ($accion!=='ESTADO') {
        $lista.="<label class='text-input'>Fecha Devolución</label>            
            <input type='date' name='fechad' placeholder='Fecha Devolucion' class='media' value='' required>";
    }
            
        $lista.="<label class='text-input'>Observaciones</label>            
            <textarea class='textarea' name='observacion'  style='width: 595px'></textarea>";
                                      
        if ($accion=='ADICIONAR') {
            $lista.="<label class='text-input'>Identificacion Cuentadante</label>            
            <input list='Personac' name='Personac' type='text' value='' placeholder='CUENTADANTE'>"                  
              ."".Persona::listaopciones(null, 'Personac',null);
        } else {
             $lista.="<input type='hidden' name='Personac' value='{$cadena[0][6]}' />";
			 
             $lista.="<input type='hidden' name='idprestamo' value='{$cadena[0][0]}' />";
        } 
         $lista.="<input type='hidden' name='tipop' value='Express' />
            <input type='hidden' name='estado' value='$estado' />
            <input type='hidden' name='donde' value='layouts/options/usuarioFinal'>
            <input type='hidden' name='menu' value='$menu' />
            <input type='hidden' name='usuario' value='$usuario' />
            <input type='hidden' name='id' value='$elemento' />
            <input type='submit' name='accion' value='$accion' />
            <input type='reset' name='limpiar' value='Cancelar Solicitud' />  
        </form>    
    </div>
</section>";
   
    return $lista;
}

public static function modalusuario(){
        $lista="<section id='registrar'>  
    <div class='formulario' id='formulario'>		
        <form name='formulario' class='form-registro' method='post' action='indexModificar.php'>
            
            <label class='text-input'>Nombre Usuario</label>
            <label class='text-input'>password Anterior</label>
           <input type='text' value='' name='usuarioMod' maxlength='25' class='media' required>  
           <input type='password' name='passwordA' placeholder='&#128274; Contraseña' class='media' required>           
             
            <label class='text-input'>Password Nuevo</label>
            <label class='text-input'>Password</label><br>
            
            <input type='password' id='pass' name='password' placeholder='&#128274; Contraseña' onkeyup='color();' class='media' required>           
            <input type='password' id='passn' onkeyup='validar();' name='passwordN' onkeypress='verificar();' placeholder='&#128274; Verifique contraseña' class='media' required>
            <p style='width: 200px; margin-top: 25px ; margin-left: 460px ; height: 20px' id='aviso'></p>
            <table>
            <tr>
            <td id='color'></td>  <td id='color1'></td>  <td id='color2'></td><td id='color3'></td>  <td id='color4'></td>  <td id='color5'></td>
            </tr>
            </table><br>
            <input id='accion' type='submit' name='accion' value='ACTUALIZAR' disabled />
            <input type='reset' name='limpiar' value='LIMPIAR' />                   
        </form>    
    </div>
</section
";       
        return $lista;
    }
    
    public static function getRealIP(){

    if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    {
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"]))
    {
        return $_SERVER["HTTP_FORWARDED"];
    }
    else
    {
        return $_SERVER["REMOTE_ADDR"];
    }

}

public static function modalRecClave(){
        $lista="<section id='registrar'>  
    <div class='formulario' id='formulario'>		
        <form name='formulario' class='form-registro' method='post' action='indexModificar.php'>
            
            <label class='text-input'>Identificacion</label>
            <label class='text-input'>Correo De Recuperacion</label>
            
           <input type='text' name='identificacionrec' placeholder='Mi Identificacion' class='media' required>           
           <input type='mail' name='mailins' placeholder='Mi Mail Institucional' class='media' required>           
             
            
            <input type='submit' name='accion' value='RECUPERAR'/>
            <input type='reset' name='limpiar' value='LIMPIAR' />                   
        </form>    
    </div>
</section
";       
        return $lista;
    }

}
