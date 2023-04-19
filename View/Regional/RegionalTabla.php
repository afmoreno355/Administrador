<?PHP
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

// require auntomatico encuentra todas las clases/Model qeu se solicitan en el Controlador
require_once dirname(__FILE__) . "/../../autoload.php";

// filtro se usa para realizar las consultas de busqueda 
$filtro = " id NOT IN ('1', '2')";

// bucarPalabraClave palabra clave que se busca asociada a ajax
$bucarPalabraClave = "";

// verificar los permisos del usuario

$permisos = new Persona(" identificacion ", "'" . $_SESSION['user'] . "'");

// permisos desde Http validando los permisos de un usuario segun la tabla personamenu
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "Indicativa");

if ($ingreso === false && $permisos->getIdTipo() !== "SA" ) {
    print_r("NO TIENE PERMISO PARA ESTE MENU");
    //header("Location: index");
} elseif ($_SESSION["token1"] === $_COOKIE["token1"] && $_SESSION["token2"] === $_COOKIE["token2"]) {
    // variable variable trae las variables que trae POST
    foreach ($_POST as $key => $value)
        ${$key} = $value;

    // Evita que ingresen ' a la base de datos
    $bucarPalabraClave = str_replace("'", "", $bucarPalabraClave);
       
    // evalua si existe bucarPalabraClave y nos crea la cadena de busqueda
    if ($bucarPalabraClave != "") {
        if (is_numeric( $bucarPalabraClave ) ) 
        {
            $filtro.=" and ( id = $bucarPalabraClave )";
        }
        else  
        {
            $filtro.="  and ( nom_departamento like '%". strtoupper($bucarPalabraClave)."%' )";
        }
    }

    // obj para llenar las tablas
    $regional = Regional::datosobjetos($filtro , $pagina, 20);
    // numero de paginas para la paginacion
    $numeroPaginas = ceil(Regional::count($filtro)[0][0] / 20);
    // ecrypt codifica lo que enviamos por javascript    
    $var_add = Http::encryptIt("id=1&llave_Primaria=&user={$_SESSION["user"]}&accion=ADICIONAR");
    $var_ayu = Http::encryptIt("id=4&llave_Primaria=&user={$_SESSION["user"]}&accion=AYUDA");

?> 
     <div class="botonMenu" style="font-weight: bolder; font-size: 2em; ">
        <button type='button' id='button' class="ele" title='Adicionar nuevo'  onclick="validarDatos(``, `I=<?= $var_add ?>`, `modalVentana`, `View/Regional/RegionalModales.php`, event, 'ele')"><img src="img/icon/adds.png"/> ADICIONAR<br>REGIONAL</button>
        <button type='button' id='button' class="ele" title='Ayuda'  onclick="validarDatos(``, `I=<?= $var_ayu ?>`, `modalVentana`, `View/Regional/RegionalModales.php`, event, 'ele')"><img src="img/icon/ayu.png"/> AYUDA<br>MODULO</button>
    </div>  
    <!-- Inicio de html tablas -->
    <table id="tableIntD" class="tableIntT sombra tableIntTa">
        <tr>
            <th>CODIGO DE REGIONAL</th>
            <th>NOMBRE DE REGIONAL</th>
            <th colspan="2">ACCION</th>           
        </tr>
<?PHP
    for ($i = 0; $i < count($regional); $i++) {
        $objet = $regional[$i];
        $var_mod = Http::encryptIt("id=1&llave_Primaria={$objet->getCod()}&user={$_SESSION["user"]}&accion=MODIFICAR");
        $var_eli = Http::encryptIt("id=2&llave_Primaria={$objet->getCod()}&user={$_SESSION["user"]}&accion=ELIMINAR");
        $var_inf = Http::encryptIt("id=3&llave_Primaria={$objet->getCod()}&user={$_SESSION["user"]}&accion=INFORMACION");
?> 
            <tr>
                <td><?= $objet->getCod() ?></td>
                <td> <?= $objet->getNombre() ?></td>
                <td>
                    <input type="button" id="button" name="1" onclick="validarDatos(``, `I=<?= $var_inf ?>`, `modalVentana`, `View/Regional/RegionalModales.php`)" title="Información Elemento" value="INFORMACION">
                </td>
                <td>
                    <input type="button" id="button" name="3" onclick="validarDatos(``, `I=<?= $var_mod ?>`, `modalVentana`, `View/Regional/RegionalModales.php`)" title="Modificar Elemento" value="MODIFICAR">
                    <input type="button" id="button" name="3" onclick="validarDatos(``, `I=<?= $var_eli ?>`, `modalVentana`, `View/Regional/RegionalModales.php`)" title="Eliminar" value="ELIMINAR">
                </td>
            </tr>
<?PHP
    }
?>
        </table> 
        <input type="hidden" id="donde" value="Regional">
        <input type="hidden" id="id_espe" value="">
        <input type="hidden" id="numeroPaginas" value="<?= $numeroPaginas ?>">
        <input type="hidden" id="sedeGestion" value="">
        <input type="hidden" id="bucarPalabraClave" value="<?= $bucarPalabraClave ?>">
    
    <div id='formDetalle' style="display: none"></div>
    <!-- Fin de Html -->
<?PHP
}
?>