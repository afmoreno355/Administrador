
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
class Sesion {

    private $estado;
    private $fecha;
    private $id_sesion;
    private $identificacion;
    private $token1;
    private $token2;
    private $token3;

    // constructor multifuncional segun el tipo de elemento que recibe realiza una busqueda, funciona como constructor vacio o recibe un array.
    function __construct($campo, $valor) {
        if ($campo != NULL) {
            if (is_array($campo)) {
                $this->cargarObjetoDeVector($campo);
            } else {
                $cadenaSQL = "select estado,fecha,id,identificacion,token1,token2,token3 from  sesion  where $campo = $valor";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL, null);
                if (count($resultado) > 0) {
                    $this->cargarObjetoDeVector($resultado[0]);
                }
            }
        }
    }

    //organiza el array que recibe el constructor  pero se debe colocar la posicion de la columna en el vector 
    private function cargarObjetoDeVector($vector) {
        $this->estado = $vector[0];
        $this->fecha = $vector[1];
        $this->id_sesion = $vector[2];
        $this->identificacion = $vector[3];
        $this->token1 = $vector[4];
        $this->token2 = $vector[5];
        $this->token3 = $vector[6];
    }

    // get and set 
    function getEstado() {
        return $this->estado;
    }

    function setEstado($variable) {
        $this->estado = $variable;
    }

    function getFecha() {
        return $this->fecha;
    }

    function setFecha($variable) {
        $this->fecha = $variable;
    }

    function getId_sesion() {
        return $this->id_sesion;
    }

    function setId_sesion($variable) {
        $this->id_sesion = $variable;
    }

    function getIdentificacion() {
        return $this->identificacion;
    }

    function setIdentificacion($variable) {
        $this->identificacion = $variable;
    }

    function getToken1() {
        return $this->token1;
    }

    function setToken1($variable) {
        $this->token1 = $variable;
    }

    function getToken2() {
        return $this->token2;
    }

    function setToken2($variable) {
        $this->token2 = $variable;
    }

    function getToken3() {
        return $this->token3;
    }

    function setToken3($variable) {
        $this->token3 = $variable;
    }

    // metodo magico
    function __toString() {
        //return $this->;
    }

    //datos hace la consulta sql.
    public static function datos($filtro, $pagina, $limit) {
        $cadenaSQL = "select estado,fecha,id_sesion,identificacion,token1,token2,token3 from  sesion  ";
        if ($filtro != null) {
            $cadenaSQL .= " where " . $filtro;
        }
        $cadenaSQL .= " order by id_sesion desc ";
        if ($pagina != null && $limit != null) {
            $cadenaSQL .= " offset $pagina limit $limit ";
        }
        return ConectorBD::ejecutarQuery($cadenaSQL, null);
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
        return ConectorBD::ejecutarQuery("select count(*) from  sesion  $filtro", null);
    }

    // guardar elementos en la base de datos
    public function grabar() {
        $cadenaSQL = "insert into  sesion(estado,fecha,identificacion,token1,token2,token3)  values('$this->estado','$this->fecha','$this->identificacion','$this->token1','$this->token2','$this->token3')";
        //print_r($cadenaSQL);
        if (ConectorBD::ejecutarQuery($cadenaSQL, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $cadenaSQL);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("GRABAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("SESION");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }

    // borrar elementos en la base de datos
    public function borrar() {
        $cadenaSQL = "delete from  sesion  where id_sesion = '$this->id_sesion'";
        if (ConectorBD::ejecutarQuery($cadenaSQL, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $cadenaSQL);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("ELIMINAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("SESION");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }

    // modificar elementos en la base de datos, identificador es el codigo o llave primaria a modificar 
    public function modificar($identificador) {
        $cadenaSQL = "update  sesion  set fecha = '$this->fecha',token1 = '$this->token1',token2 = '$this->token2',token3 = '$this->token3' where identificacion = '$identificador'";
        //print_r($cadenaSQL);
        if (ConectorBD::ejecutarQuery($cadenaSQL, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $cadenaSQL);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("MODIFICAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("SESION");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }

    // para datalist listas de busqueda 
    public static function listaopciones() {
        $lista = "";
        $si = self::datosobjetos(null, null, null);
        for ($i = 0; $i < count($si); $i++) {
            $obj = $si[$i];
            $lista .= "<option value='{$obj->getId_sesion()}'> palabras clave </option>";
        }
        return $lista;
    }

}
