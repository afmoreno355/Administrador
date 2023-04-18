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
$year= date('Y', time());

// bucarPalabraClave palabra clave que se busca asociada a ajax
$bucarPalabraClave = "";

// verificar los permisos del usuario

$permisos = new Persona(" identificacion ", "'" . $_SESSION['user'] . "'");

// permisos desde Http validando los permisos de un usuario segun la tabla personamenu
$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), "Eagle_admin");

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
       $filtro.=" and ( codigosede like '%". strtoupper($bucarPalabraClave)."%' or  nombresede like '%". strtoupper($bucarPalabraClave)."%' or  nom_departamento like '%". strtoupper($bucarPalabraClave)."%' )";
    }

    // obj para llenar las tablas
    $sede = Sede::datosobjetos($filtro , $pagina, 20);
    // numero de paginas para la paginacion
    $numeroPaginas = ceil(Sede::count($filtro)[0][0] / 20);
    // ecrypt codifica lo que enviamos por javascript   
    
?> 
    <div class="botonMenu" style="font-weight: bolder; font-size: 2em; ">
        <button type='button' id='button' class="ele" title='Cargar PE04' value="PE04" onclick="validarDatos(``, `I=<?= '' ?>`, `modalVentana`, `View/Cargo/CargoModales.php`, event, 'ele')"><img src="img/icon/excel.png"/> CARGAR<br>Cargos</button>
        <button type='button' id='button' class="ele" title='Cargar metas' value="METAS" onclick="validarDatos(``, `I=<?= '' ?>`, `modalVentana`, `View/Cargo/CargoModales.php`, event, 'ele')"><img src="img/icon/excel.png"/> CARGAR<br>Metas cargos</button>
        <button type='button' id='button' class="ele" title='Cargar metas' value="METAS" onclick="validarDatos(``, `I=<?= '' ?>`, `modalVentana`, `View/Cargo/CargoModales.php`, event, 'ele')"><img src="img/icon/excel.png"/> CARGAR<br>Boton 3</button>
    </div>  
    <!-- Inicio de html tablas -->
    <table id="tableIntD" class="tableIntT sombra tableIntTa">
        <tr>
            <th>CÓDIGO CENTRO</th>
            <th>NOMBRES REGIONAL</th>
            <th>NOMBRES CENTRO</th> 
            <th></th>           
        </tr>
<?PHP
    for ($i = 0; $i < count($sede); $i++) {
        $objet = $sede[$i];
        $var_ges = "id=1&sedeGestion={$objet->getCod()}&user={$_SESSION["user"]}&accion=GESTIONAR&pagina=0";
?> 
            <tr>
                <td><?= $objet->getCod() ?></td>
                <td> <?= $objet->getDepartamento() ?></td>
                <td> <?= $objet->getNombre() ?></td>
                <td> 
                    <a onclick="botoncolor(`VIRTUAL`, `<?= $var_ges ?>`, `tableIntT`, `View/Virtual/VirtualTabla.php`)" title="Indicativa Virtual" ><img src="img/icon/virtual.png" style="width: 30px; height: 30px"/></a>
                    <a onclick="botoncolor(`PRESENCIAL`, `<?= $var_ges ?>`, `tableIntT`, `View/Presencial/PresencialTabla.php`)" title="Indicativa Presencial" ><img src="img/icon/presencial.png" style="width: 30px; height: 30px"/></a>
                </td>
            </tr>
<?PHP
    }
?>
        <input type="hidden" id="donde" value="Sede">
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