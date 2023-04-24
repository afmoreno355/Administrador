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
    
    if( $permisos->getIdTipo() != 'SA' && $permisos->getIdTipo() != "AI" && $permisos->getIdTipo() != "AV"  && $permisos->getIdTipo() != "Rc" && $permisos->getIdTipo() != "Ra" )
    {
         $filtro .= " and id = '" . ( $regional_centro = new Sede( ' codigosede ' , $_SESSION['sede'] ) )->getId_departamento() . "' ";   
    }

    // obj para llenar las tablas
    $regional = Regional::datosobjetos($filtro , $pagina, 20);
    // numero de paginas para la paginacion
    $numeroPaginas = ceil(Regional::count($filtro)[0][0] / 20);
    // ecrypt codifica lo que enviamos por javascript    
?> 
    <!-- Inicio de html tablas -->
    <table id="tableIntD" class="tableIntT sombra tableIntTa">
        <tr>
            <th>CODIGO DE REGIONAL</th>
            <th>NOMBRE DE REGIONAL</th>
            <th>ACCION</th>           
        </tr>
<?PHP
    for ($i = 0; $i < count($regional); $i++) {
        $objet = $regional[$i];
        $var_pdf = Http::encryptIt( "id=1&llave_Primaria={$objet->getCod()}&user={$_SESSION["user"]}&accion=VISOR PDF&pagina=0" );
        $var_car = Http::encryptIt( "id=2&llave_Primaria={$objet->getCod()}&user={$_SESSION["user"]}&accion=CARGAR PDF&pagina=0" );
        $var_apr = Http::encryptIt( "id=3&llave_Primaria={$objet->getCod()}&user={$_SESSION["user"]}&accion=APROBAR&pagina=0" );
?> 
            <tr>
                <td><?= $objet->getCod() ?></td>
                <td> <?= $objet->getNombre() ?></td>
                <td>
                    <a onclick="validarDatos(``, `I=<?= $var_pdf ?>`, `modalVentana`, `View/Regional/RegionalModales.php`)" title="PDF Cargado por la Regional"><img src="img/icon/pdf.png" style="width: 30px; height: 30px"/></a>
                    <?PHP
                    if( $permisos->getIdTipo() === "IR" )
                    {
                    ?> 
                        <a onclick="validarDatos(``, `I=<?= $var_car ?>`, `modalVentana`, `View/Regional/RegionalModales.php`)" title="PDF a Cargar por la Regional"><img src="img/icon/adds.png" style="width: 30px; height: 30px"/></a>
                        <a onclick="validarDatos(``, `I=<?= $var_apr ?>`, `modalVentana`, `View/Regional/RegionalModales.php`)" title="Aprobar el centro"><img src="img/icon/aprobar.png" style="width: 30px; height: 30px"/></a>
                    <?PHP
                    }
                    ?>
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