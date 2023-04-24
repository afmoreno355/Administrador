<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$permisos = new Persona(' identificacion ', "'".$_SESSION['user']."'");

if( $permisos->getIdTipo()!='SA' && $permisos->getIdTipo()!='AI' && $permisos->getIdTipo()!='AA' && $permisos->getIdTipo()!='Ca' ){   
          header("location: inicio.php?CONTENIDO=View/Usuario/Usuario.php");
}
?>
<div class="tituloDonde">
   <div>Modulos DFP</div><br> 
   <label>USUARIOS :: GESTION DE USUARIOS DEL SISTEMA  </label>  
</div>

<table class="tableIntT" style="background: transparent; top: -40px">     
      <tr>
        <td  colspan="3" class="noHover">
            <button class="fas fa-angle-double-left" name="Atras" id="Atras" title="Pag Atras" onclick="Atras('inicio.php?CONTENIDO=View/Usuario/Usuarios.php','sede=<?=$_SESSION['sede']?>&bucarPalabraClave='+document.getElementById('bucarPalabraClave').value,'tableIntT','View/Usuario/UsuariosTabla.php');"></button>
            <label class="pag" name="pag" id="pag">1</label>
            <button class="fas fa-angle-double-right" name="Adelante" id="Adelante" title="Pag Adelante" onclick="Adelante('inicio.php?CONTENIDO=View/Usuario/Usuarios.php', document.getElementById('numeroPaginas').value,'sede=<?=$_SESSION['sede']?>&bucarPalabraClave='+document.getElementById('bucarPalabraClave').value,'tableIntT','View/Usuario/UsuariosTabla.php');"></button>
        </td>  
     </tr>  
</table>

<table id="tableIntT" class="tableIntT sombra tableIntTa">
    
</table>

<script src="./js/material.js?2"> </script>
<script src="./js/persona.js?2"> </script>

<script>

window.addEventListener('load',idexistentesMat('','pagina=0&sede=<?=$_SESSION['sede']?>','tableIntT','View/Usuario/UsuariosTabla.php'));
// funciones en menu genericas y en materiales 
</script>
