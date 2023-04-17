
<link rel="stylesheet" href="css/menu.css">

<label class="fas fa-bars menuI" for="chequear"></label>
<input type="checkbox" name="chek" id='chequear' onclick='menudes();' style="display: none">
<div class="menu" id="divmenu1" style=" margin-top: 0px;">
    <nav id="nav" class="navDisplay">
        <?=$_SESSION['miMenu1']?>
        <a id="CERRAR" onclick="validarDatos('','','modalVentana','View/Menu/MenuFormulario.php' , event , 'menua' )">CERRAR SESION</a>
     </nav>
</div>