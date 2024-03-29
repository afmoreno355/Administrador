<?PHP
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

// require auntomatico encuentra todas las clases/Model qeu se solicitan en el Controlador
require_once dirname(__FILE__) . "/../../autoload.php";

date_default_timezone_set('America/Bogota');
// bucarPalabraClave palabra clave que se busca asociada a ajax
$bucarPalabraClave = "";
$URL = "View/Programa/ProgramaModales.php";
$numeroPaginas = 0 ;
$id_espe  = "" ;
$filtro  = " tipo_esp = 'T' " ;
$sedeGestion  = '' ;
$year= date('Y', time());

// verificar los permisos del usuario
$permisos = new Persona(" identificacion ", "'" . $_SESSION['user'] . "'");

// permisos desde Http validando los permisos de un usuario segun la tabla personamenu
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "PROGRAMA");

if ( $ingreso === false && $permisos->getIdTipo() !== "SA" ) {
    print_r("NO TIENE PERMISO PARA ESTE MENU");
    //header("Location: index");
} elseif ($_SESSION["token1"] === $_COOKIE["token1"] && $_SESSION["token2"] === $_COOKIE["token2"]) {
    // variable variable trae las variables que trae POST
    foreach ($_POST as $key => $value)
        ${$key} = $value;
        

    $bucarPalabraClave = str_replace("'", "", $bucarPalabraClave);
    // evalua si existe bucarPalabraClave y nos crea la cadena de busqueda
    if($bucarPalabraClave!='')
    {
         $filtro.=" and ( id_programa like '%". strtoupper($bucarPalabraClave)."%' or  nombre_programa like '%". strtoupper($bucarPalabraClave)."%' )";
    }

    // obj para llenar las tablas
    $programa = Programa::datosobjetos( $filtro , $pagina, 20 );
    // numero de paginas para la paginacion
    $numeroPaginas = ceil(Programa::count($filtro)[0][0] / 20);
    $var_add = Http::encryptIt("id=1&llave_Primaria=&user={$_SESSION["user"]}&accion=ADICIONAR");
    $var_des = Http::encryptIt("id=6&llave_Primaria=&user={$_SESSION["user"]}&accion=DESCARGAR ARCHIVO");
    $var_car = Http::encryptIt("id=3&llave_Primaria=&user={$_SESSION["user"]}&accion=CARGAR ARCHIVO");
    $var_ayu = Http::encryptIt("id=7&llave_Primaria=&user={$_SESSION["user"]}&accion=AYUDA");
?> 
    <!-- Inicio de html tablas -->
    <div class="botonMenu" style="font-weight: bolder; font-size: 2em; ">
        <button type='button' id='button' class="ele" title='Adicionar programa' title="Adicionar" value="ADICIONAR" onclick="validarDatos(``, `I=<?= $var_add ?>`, `modalVentana`, `<?= $URL ?>`, event, 'ele')"><img src="img/icon/adds.png"/> ADICIONAR<br>PROGRAMA</button>
        <button type='button' id='button' class="ele" title='Archivo de desacarga' onclick="reporte(`` , `I=<?= $var_des ?>`, `formDetalle`, `<?= $URL ?>`, event, 'ele' , 'PROGRAMAS EN SISTEMA')"><img src="img/icon/excel.png"/> DESCARGAR<br>PROGRAMAS</button>
        <button type='button' id='button' class="ele" title='Archivo de cargue de programas' onclick="validarDatos(``, `I=<?= $var_car ?>`, `modalVentana`, `<?= $URL ?>`, event, 'ele')"><img src="img/icon/excel.png"/> CARGAR<br>PROGRAMAS</button>
    </div>  
         <table id="tableIntD" class="tableIntT sombra tableIntTa">
            <tr>
                <th class='noDisplay'>CÓDIGO DE PROGRAMA</th>
                <th>NIVEL DE FORMACIÓN</th>
                <th>NOMBRE DEL PROGRAMA</th>
                <th class="noDisplay">DURACIÓN (Meses)</th>
                <th class="noDisplay">MODALIDAD</th>
                <th>ESTADO</th>
                <th colspan="2" >ACCIÓN</th> 
            </tr> 
<?PHP            
    for ($j = 0; $j < count($programa); $j++) 
    {
        $objetos = $programa[$j];
        $var_mod = Http::encryptIt("id=1&llave_Primaria={$objetos->getId_programa()}&user={$_SESSION["user"]}&accion=MODIFICAR");
        $var_blo = Http::encryptIt("id=5&llave_Primaria={$objetos->getId_programa()}&user={$_SESSION["user"]}&accion=BLOQUEAR PROGRAMA");
        $var_eli = Http::encryptIt("id=2&llave_Primaria={$objetos->getId_programa()}&user={$_SESSION["user"]}&accion=ELIMINAR");
        $var_inf = Http::encryptIt("id=4&llave_Primaria={$objetos->getId_programa()}&user={$_SESSION["user"]}&accion=INFORMACION");
        
        if( strtoupper( $objetos->getActivo() ) == 'NO' )
        {
           $_estado = 'INACTIVO' ;
           $back = ' style = "background: #37B2B098" ' ;       
        } 
        else
        {   
           $_estado = 'ACTIVO' ;
           $back = '' ;
        }
?>
            <tr <?= $back?> >
                <td class='noDisplay' ><?= $objetos->getId_programa() ?></td>
                <td><?= $objetos->getNivel_formacion() ?></td>
                <td><?= $objetos->getNombre_programa() ?></td>
                <td class='noDisplay' ><?= $objetos->getDuracion() ?></td>
                <td class='noDisplay' ><?= $objetos->getModalidad() ?></td>
                <td><?= $_estado ?></td>
                <td>
                    <input type="button" id="button" name="1" onclick="validarDatos(``, `I=<?= $var_inf ?>`, `modalVentana`, `<?= $URL ?>`)" title="Información Elemento" value="INFORMACIÓN">
                    <input type="button" id="button" name="1" onclick="validarDatos(``, `I=<?= $var_blo ?>`, `modalVentana`, `<?= $URL ?>`)" title="Información Elemento" value="BLOQUEAR">
                </td>
                <td>
                    <input type="button" id="button" name="3" onclick="validarDatos(``, `I=<?= $var_mod ?>`, `modalVentana`, `<?= $URL ?>`)" title="Modificar Elemento" value="MODIFICAR">
                    <input type="button" id="button" name="3" onclick="validarDatos(``, `I=<?= $var_eli ?>`, `modalVentana`, `<?= $URL ?>`)" title="Eliminar" value="ELIMINAR">
                </td>
            </tr> 
         
<?php
    }
?>   
        </table>       
         
        <div id='formDetalle' style="display: none"></div>
        <input type="hidden" id="donde" value="Programa">
        <input type="hidden" id="id_espe" value="<?= $id_espe ?>">
        <input type="hidden" id="numeroPaginas" value="<?= $numeroPaginas ?>">
        <input type="hidden" id="sedeGestion" value="<?= $sedeGestion ?>">
        <input type="hidden" id="bucarPalabraClave" value="<?= $bucarPalabraClave ?>">
       
    <!-- Fin de Html -->
<?PHP
}
?>