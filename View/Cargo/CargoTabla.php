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
$filtro = "";
$year = date('Y', time());

// bucarPalabraClave palabra clave que se busca asociada a ajax
$bucarPalabraClave = "";

// verificar los permisos del usuario

$permisos = new Persona(" identificacion ", "'" . $_SESSION['user'] . "'");

// permisos desde Http validando los permisos de un usuario segun la tabla personamenu
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "Eagle_admin");
if ($ingreso === false && $permisos->getIdTipo() !== "SA") {
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
        $filtro .= " ( codigocargo like '%" . strtoupper($bucarPalabraClave) . "%' or  nombrecargo like '%" . strtoupper($bucarPalabraClave) . "%' )";
    }

    // obj para llenar las tablas
    $cargo = Cargo::datosobjetos($filtro, $pagina, 20);
    // numero de paginas para la paginacion
    $numeroPaginas = ceil(Cargo::count($filtro)[0][0] / 20);
    // ecrypt codifica lo que enviamos por javascript   
    $var_add = Http::encryptIt("id=1&llave_Primaria=&user={$_SESSION["user"]}&accion=ADICIONAR");
    $var_ayu = Http::encryptIt("id=4&llave_Primaria=&user={$_SESSION["user"]}&accion=AYUDA");
    
    ?> 
    <div class="botonMenu" style="font-weight: bolder; font-size: 2em; ">
        <button type='button' id='button' class="ele" title='Adicionar nuevo'  onclick="validarDatos(``, `I=<?= $var_add ?>`, `modalVentana`, `View/Cargo/CargoModales.php`, event, 'ele')"><img src="img/icon/adds.png"/> ADICIONAR<br>CARGO</button>
        <button type='button' id='button' class="ele" title='Ayuda'  onclick="validarDatos(``, `I=<?= $var_ayu ?>`, `modalVentana`, `View/Cargo/CargoModales.php`, event, 'ele')"><img src="img/icon/ayu.png"/> AYUDA<br>MODULO</button>
    </div>
    <!-- Inicio de html tablas -->
    <table id="tableIntD" class="tableIntT sombra tableIntTa">
        <tr>
            <th>ID</th>
            <th>CÓDIGO CARGO</th>
            <th>NOMBRE CARGO</th> 
            <th>DETALLE</th>   
            <th colspan="2">ACCION</th>        
        </tr>
        <?PHP
        for ($i = 0; $i < count($cargo); $i++) {
            $objet = $cargo[$i];
            $var_mod = Http::encryptIt("id=1&llave_Primaria={$objet->getId()}&user={$_SESSION["user"]}&accion=MODIFICAR");
            $var_eli = Http::encryptIt("id=2&llave_Primaria={$objet->getId()}&user={$_SESSION["user"]}&accion=ELIMINAR");
            $var_blo = Http::encryptIt("id=5&llave_Primaria={$objet->getId()}&user={$_SESSION["user"]}&accion=BLOQUEAR");
            $var_inf = Http::encryptIt("id=3&llave_Primaria={$objet->getId()}&user={$_SESSION["user"]}&accion=INFORMACION");
            ?> 
            <tr>
                <td><?= $objet->getId() ?></td>
                <td> <?= $objet->getCodigocargo() ?></td>
                <td> <?= $objet->getNombrecargo() ?></td>
                <td> <?= $objet->getDetalle() ?></td>
                <td>
                    <input type="button" id="button" name="1" onclick="validarDatos(``, `I=<?= $var_inf ?>`, `modalVentana`, `View/Cargo/CargoModales.php`)" title="Información Elemento" value="INFORMACION">
                    <input type="button" id="button" name="1" onclick="validarDatos(``, `I=<?= $var_blo ?>`, `modalVentana`, `View/Cargo/CargoModales.php`)" title="Bloquear Elemento" value="BLOQUEAR">
                </td>
                <td>
                    <input type="button" id="button" name="3" onclick="validarDatos(``, `I=<?= $var_mod ?>`, `modalVentana`, `View/Cargo/CargoModales.php`)" title="Modificar Elemento" value="MODIFICAR">
                    <input type="button" id="button" name="3" onclick="validarDatos(``, `I=<?= $var_eli ?>`, `modalVentana`, `View/Cargo/CargoModales.php`)" title="Eliminar" value="ELIMINAR">
                </td>
            </tr>
        <?PHP
    }
    ?>
        <input type="hidden" id="donde" value="Cargo">
        <input type="hidden" id="id_espe" value="">
        <input type="hidden" id="numeroPaginas" value="<?= $numeroPaginas ?>">
        <input type="hidden" id="sedeGestion" value="">
        <input type="hidden" id="bucarPalabraClave" value="<?= $bucarPalabraClave ?>">
    </table>  

    <div id='formDetalle' style="display: none"></div>
    <!-- Fin de Html -->
    <?PHP
}
?>