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
$URL = "View/Municipio/MunicipioModales.php" ;
$numeroPaginas = 0 ;
$id_espe  = "" ;
$filtro  = " departamento.id = municipio.id_departamento and municipio.id not in (0,1) " ;
$sedeGestion  = '' ;
$year= date('Y', time());

// verificar los permisos del usuario
$permisos = new Persona(" identificacion ", "'" . $_SESSION['user'] . "'");

// permisos desde Http validando los permisos de un usuario segun la tabla personamenu
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "Indicativa");

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
         $filtro.=" and ( municipio like '%". strtoupper($bucarPalabraClave)."%' or  codigo_municipio like '%". strtoupper($bucarPalabraClave)."%' or dane like '%". strtoupper($bucarPalabraClave)."%' or nom_departamento like '%". strtoupper($bucarPalabraClave)."%' )";
    }

    // obj para llenar las tablas
    $municipio = Municipio::datosobjetos( $filtro , $pagina, 20 );
    // numero de paginas para la paginacion
    $numeroPaginas = ceil(Municipio::count($filtro)[0][0] / 20);
    $var_add = Http::encryptIt("id=1&llave_Primaria=&user={$_SESSION["user"]}&accion=ADICIONAR");
    $var_pla = Http::encryptIt("id=3&llave_Primaria=&user={$_SESSION["user"]}&accion=SUBIR ARCHIVO");
    $var_ayu = Http::encryptIt("id=6&llave_Primaria=&user={$_SESSION["user"]}&accion=AYUDA");
?> 
    <!-- Inicio de html tablas -->
    <div class="botonMenu" style="font-weight: bolder; font-size: 2em; ">
        <button type='button' id='button' class="ele" title='Adicionar municipio' title="Adicionar" value="ADICIONAR" onclick="validarDatos(``, `I=<?= $var_add ?>`, `modalVentana`, `<?= $URL ?>`, event, 'ele')"><img src="img/icon/adds.png"/> ADICIONAR<br>MUNICIPIO</button>
        <button type='button' id='button' class="ele" title='Ayuda del sistema' onclick="validarDatos(``, `I=<?= $var_ayu ?>`, `modalVentana`, `<?= $URL ?>`, event, 'ele')"><img src="img/icon/ayu.png"/> AYUDA<br>MODULO</button>
    </div>  
         <table id="tableIntD" class="tableIntT sombra tableIntTa">
            <tr>
                <th class='noDisplay'>ID MUNICIPIO</th>
                <th>NOMBRE DEL MUNICIPIO</th>
                <th>ID REGIONAL</th>
                <th>NOMBRE REGIONAL</th>
                <th class="noDisplay">CODIGO DEL MUNICIPIO</th>
                <th class="noDisplay">DANE</th>
                <th>CODIGO REGIONAL MUNICIPIO</th>
                <th>ESTADO</th>
                <th colspan="2" >ACCION</th> 
            </tr> 
<?PHP            
    for ($j = 0; $j < count($municipio); $j++) 
    {
        $objetos = $municipio[$j];
        $var_mod = Http::encryptIt("id=1&llave_Primaria={$objetos->getId()}&user={$_SESSION["user"]}&accion=MODIFICAR");
        $var_blo = Http::encryptIt("id=5&llave_Primaria={$objetos->getId()}&user={$_SESSION["user"]}&accion=BLOQUEAR MUNICIPIO");
        $var_eli = Http::encryptIt("id=2&llave_Primaria={$objetos->getId()}&user={$_SESSION["user"]}&accion=ELIMINAR");
        $var_inf = Http::encryptIt("id=4&llave_Primaria={$objetos->getId()}&user={$_SESSION["user"]}&accion=INFORMACION");
        
        if( strtoupper( $objetos->getEstado() ) == 'I' )
        {
           $_estado = 'INACTIVO' ;
           $back = ' style = "background: #37B2B098" ' ;       
        } 
        else if( strtoupper( $objetos->getEstado() ) == 'A' )
        {   
           $_estado = 'ACTIVO' ;
           $back = '' ;
        }
?>
            <tr <?= $back?> >
                <td class='noDisplay' ><?= $objetos->getId() ?></td>
                <td><?= $objetos->getMunicipio() ?></td>
                <td><?= $objetos->getId_departamento() ?></td>
                <td><?= $objetos->getNom_departamento() ?></td>
                <td><?= $objetos->getCodigo_municipio() ?></td>
                <td class='noDisplay' ><?= $objetos->getDane() ?></td>
                <td class='noDisplay' ><?= $objetos->getCod_dpto_mpio() ?></td>
                <td><?= $_estado ?></td>
                <td>
                    <input type="button" id="button" name="1" onclick="validarDatos(``, `I=<?= $var_inf ?>`, `modalVentana`, `<?= $URL ?>`)" title="Información Elemento" value="INFORMACION">
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
        <input type="hidden" id="donde" value="Municipio">
        <input type="hidden" id="id_espe" value="<?= $id_espe ?>">
        <input type="hidden" id="numeroPaginas" value="<?= $numeroPaginas ?>">
        <input type="hidden" id="sedeGestion" value="<?= $sedeGestion ?>">
        <input type="hidden" id="bucarPalabraClave" value="<?= $bucarPalabraClave ?>">
       
    <!-- Fin de Html -->
<?PHP
}
?>