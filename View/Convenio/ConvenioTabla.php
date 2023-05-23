<?PHP 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

// require auntomatico encuentra todas las clases/Model qeu se solicitan en el Controlador
require_once __DIR__ . "/../../autoload.php";

// filtro se usa para realizar las consultas de busqueda 
$filtro = "";
$URL = "View/Convenio/ConvenioModales.php" ;
$year= date('Y', time());

// bucarPalabraClave palabra clave que se busca asociada a ajax
$bucarPalabraClave = "";

// verificar los permisos del usuario

$permisos = new Persona(" identificacion ", "'" . $_SESSION['user'] . "'");
// permisos desde Http validando los permisos de un usuario segun la tabla personamenu


$ingreso = Http::permisos($permisos->getId(), $permisos->getIdTipo(), null);


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
       $filtro.=" (id like '%". strtoupper($bucarPalabraClave)."%' or  area like '%". strtoupper($bucarPalabraClave)."%' )";
    }

        // obj para llenar las tablas
    $convenio = Convenio::datosobjetos($filtro , $pagina, 20);
    // numero de paginas para la paginacion
    $numeroPaginas = ceil(Convenio::count($filtro)[0][0] / 20);
    // ecrypt codifica lo que enviamos por javascript

    // Encripta la información para enviarla
    $var_add = Http::encryptIt("id=1&llave_Primaria=&user={$_SESSION["user"]}&accion=ADICIONAR");
    $var_ayu = Http::encryptIt("id=4&llave_Primaria=&user={$_SESSION["user"]}&accion=AYUDA");
    
?> 
<!-- Código para los botones-->
    <div class="botonMenu" style="font-weight: bolder; font-size: 2em; ">
<button type='button' id='button' class="ele" title='Adicionar nuevo'  onclick="validarDatos(``, `I=<?= $var_add ?>`, `modalVentana`, `<?= $URL ?>`, event, 'ele')"><img src="img/icon/adds.png"/> ADICIONAR<br>MENU</button>
<button type='button' id='button' class="ele" title='Ayuda'  onclick="validarDatos(``, `I=<?= $var_ayu ?>`, `modalVentana`, `<?= $URL ?>`, event, 'ele')"><img src="img/icon/ayu.png"/> AYUDA<br>MODULO</button>
</div>
    <!-- Inicio de html tablas -->
    <table id="tableIntD" class="tableIntT sombra tableIntTa">
        <tr>
            <th>ID</th>
            <th>AREA</th>
            <th>ABOGADO</th>
            <th>TÉCNICO EXPERTO</th>
            <th>TÉCNICO ECONÓMICO</th>
            <th>MES</th>
            <th>ESTADO</th> 
            <th>ACCIÓN</th>
            <th></th>       
        </tr>
<?PHP
    for ($i = 0; $i < count($convenio); $i++) {
        $object = $convenio[$i];
        $id = $object->getId();
        $sql = "select e.estado_completado
                        from estado_solicitudes e inner join solicitudes s
                        on e.id_estado = s.id_estado where s.id_solicitud = $id";
        $completado = ConectorBD::ejecutarQuery($sql, ' convenios ')[0][0];
        if ( $completado ) {
            $accion = "DETALLE";
            $name = 3;
        } else {
            $accion = "COMPLETAR";
            $name = 1;
        }
        $http = Http::encryptIt("id=1&llave_Primaria={$object->getId()}&user={$_SESSION["user"]}&accion=$accion");
?> 
            <tr>
                <td> <?= $object->getId() ?></td>
                <td> <?= $object->getArea() ?></td>
                <td> <?= $object->getAbogado() ?></td>
                <td> <?= $object->getTecnicoExperto() ?> </td>
                <td> <?= $object->getTecnicoEconomico() ?> </td>
                <td> <?= $object->getMes() ?> </td>
                <td> <?= $object->getEstado() ?></td>
                <td>
                    <input type="button" id="button" name="<?= $name ?>" onclick="validarDatos(``, `I=<?= $http ?>`, `modalVentana`, `<?= $URL ?>`)" title="Información Elemento" value="<?= $accion ?>">
                </td>
            </tr>
<?PHP
    }
?>
        <input type="hidden" id="donde" value="Menu">
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