<?PHP
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

// require auntomatico encuentra todas las clases/Model qeu se solicitan en el Controlador
require_once dirname(__FILE__) . "/../../autoload.php";

$datos=new Persona("identificacion","'{$_SESSION['user']}'");
?>
<main>
    <section class="presentacion conteiner">
        <a onclick="validarDatos('','user=<?=$_SESSION['user']?>&id=7','modalVentana','View/Usuario/UsuarioFormulario.php')"><img src="<?=$datos->getImagen()?>" class="Image" ></a>
        <h2>
            <?=$datos->getNombre()?> <?=$datos->getApellido()?>
        </h2>
        <h3>
            <?=$datos->getCorreo()?> 
        </h3>
        <br>
        <br>
        <div>
                 <input type="button" name="1" value="Actualizar" title="Modificar Mi Usuario" onclick="validarDatos('','user=<?=$_SESSION['user']?>&sede=<?=$_SESSION['sede']?>&id=2&accion=MODIFICAR&donde=inicio.php?CONTENIDO=View/Usuario/Usuario.php','modalVentana','View/Usuario/UsuarioFormulario.php')"/>
                 <input type="button" name="2" value="Contraseña" title="Modificar Mi Password" onclick="validarDatos('','user=<?=$_SESSION['user']?>&id=1&accion=PASSWORD','modalVentana','View/Usuario/UsuarioFormulario.php')"/>
                 <input type="button" name="3" value="Detalle" title="Informacion Usuario" onclick="validarDatos('','user=<?=$_SESSION['user']?>&id=4','modalVentana','View/Usuario/UsuarioFormulario.php')"/>
        </div>
    </section>
</main>


