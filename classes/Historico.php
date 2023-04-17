
<?PHP

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tabla
 *
 * @author FELIPE
 */
class Historico {

    private $historico;
    private $id_historico;
    private $identificacion;
    private $tabla;
    private $tipo_historico;
    private $fecha;

    // constructor multifuncional segun el tipo de elemento que recibe realiza una busqueda, funciona como constructor vacio o recibe un array.
    function __construct($campo, $valor) {
        if ($campo != NULL) {
            if (is_array($campo)) {
                $this->cargarObjetoDeVector($campo);
            } else {
                $cadenaSQL = "select historico,id_historico,identificacion,tabla,tipo_historico,fecha from  historico  where $campo = $valor";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL, null);
                if (count($resultado) > 0) {
                    $this->cargarObjetoDeVector($resultado[0]);
                }
            }
        }
    }

    //organiza el array que recibe el constructor  pero se debe colocar la posicion de la columna en el vector 
    private function cargarObjetoDeVector($vector) {
        $this->historico = $vector[0];
        $this->id_historico = $vector[1];
        $this->identificacion = $vector[2];
        $this->tabla = $vector[3];
        $this->tipo_historico = $vector[4];
        $this->fecha = $vector[5];
    }

    // get and set 
    function getHistorico() {
        return $this->historico;
    }

    function setHistorico($variable) {
        $this->historico = $variable;
    }

    function getId_historico() {
        return $this->id_historico;
    }

    function setId_historico($variable) {
        $this->id_historico = $variable;
    }

    function getIdentificacion() {
        return $this->identificacion;
    }

    function Identificacion() {
        $nombre_Persona = new Persona( ' identificacion ' , "'$this->identificacion'");
        $nombre_apellido = ( $nombre_Persona->getIdentificacion() != '' ) ? "{$nombre_Persona->getNombres()} {$nombre_Persona->getApellidos()}" : 'EL USUARIO YA NO EXISTE' ;
        return $nombre_apellido ;
    }

    function setIdentificacion($variable) {
        $this->identificacion = $variable;
    }

    function getTabla() {
        return $this->tabla;
    }

    function setTabla($variable) {
        $this->tabla = $variable;
    }

    function getTipo_historico() {
        return $this->tipo_historico;
    }

    function setTipo_historico($variable) {
        $this->tipo_historico = $variable;
    }
    
    function getFecha() {
        return $this->fecha;
    }

    function setFecha($variable) {
        $this->fecha = $variable;
    }

    
    //datos hace la consulta sql.
    public static function datos($filtro, $pagina, $limit) {
        $cadenaSQL = "select historico,id_historico,identificacion,tabla,tipo_historico,fecha from  historico  ";
        if ($filtro != null) {
            $cadenaSQL .= " where " . $filtro;
        }
        $cadenaSQL .= " order by id_historico asc ";
        if($pagina != null && $limit != null)
        {
            $cadenaSQL .= " offset $pagina limit $limit ";
        }      
        return ConectorBD::ejecutarQuery($cadenaSQL, 'eagle_admin'); 
    }

    //convierte los array de datos en objetos enviando las posiciones al constructor 
    public static function datosobjetos($filtro, $pagina, $limit) {
        $datos = self::datos($filtro, $pagina, $limit);
        $listas = array();
        for ($i = 0; $i < count($datos); $i++) {
            $clase = new self($datos[$i], null);
            $listas[$i] = $clase;
        }
        return $listas;
    }

    // nos debuelve la cantidad de filas que existen en la tabla para hacer la paginacion.
    public static function count($filtro) {
        $filtro = ( $filtro != "") ? " where $filtro " : "";
        return ConectorBD::ejecutarQuery("select count(*) from  historico  $filtro", 'eagle_admin');
    }

    // guardar elementos en la base de datos
    public function grabar() {
        $cadenaSQL = "insert into historico(historico,identificacion,tabla,tipo_historico,fecha)  values('".strtoupper($this->historico)."','$this->identificacion','".strtoupper($this->tabla)."','$this->tipo_historico','now()')";
        if (ConectorBD::ejecutarQuery($cadenaSQL,'eagle_admin')) {
            return true;
        } else {
            return false;
        }
    }

    // borrar elementos en la base de datos
    public function borrar() {
        $cadenaSQL = "delete from  historico  where id_historico = '$this->id_historico'";
        if (ConectorBD::ejecutarQuery($cadenaSQL, 'eagle_admin')) {

            return true;
        } else {
            return false;
        }
    }

    // modificar elementos en la base de datos, identificador es el codigo o llave primaria a modificar 
    public function modificar($identificador) {
        $cadenaSQL = "update  historico  set historico = '$this->historico',id_historico = '$this->id_historico',identificacion = '$this->identificacion',tabla = '$this->tabla',tipo_historico = '$this->tipo_historico', fecha='now()' where id_historico = '$this->id_historico'";
        if (ConectorBD::ejecutarQuery($cadenaSQL, 'eagle_admin')) {
            return true;
        } else {
            return false;
        }
    }

}
