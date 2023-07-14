
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
class Personamenu {

    private $id;
    private $identificacion;
    private $personamenu;

    // constructor multifuncional segun el tipo de elemento que recibe realiza una busqueda, funciona como constructor vacio o recibe un array.
    function __construct($campo, $valor) {
        if ($campo != NULL) {
            if (is_array($campo)) {
                $this->cargarObjetoDeVector($campo);
            } else {
                $cadenaSQL = "select id,identificacion,menu from  personamenu  where $campo = $valor";
                //print_r($cadenaSQL);
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL, 'eagle_admin');
                if (count($resultado) > 0) {
                    $this->cargarObjetoDeVector($resultado[0]);
                }
            }
        }
    }

    //organiza el array que recibe el constructor  pero se debe colocar la posicion de la columna en el vector 
    private function cargarObjetoDeVector($vector) {
        $this->id = $vector[0];
        $this->identificacion = $vector[1];
        $this->personamenu = $vector[2];
    }

    // get and set 
    function getId() {
        return $this->id;
    }

    function setId($variable) {
        $this->id = $variable;
    }

    function getIdentificacion() {
        return $this->identificacion;
    }
    
    function Identificacion() {
        return new Persona( ' identificacion ' , "'$this->identificacion'" );
    }

    function setIdentificacion($variable) {
        $this->identificacion = $variable;
    }

    function getPersonamenu() {
        return $this->personamenu;
    }

    function setPersonamenu($variable) {
        $this->personamenu = $variable;
    }

    //datos hace la consulta sql.
    public static function datos($filtro, $pagina, $limit) {
        $cadenaSQL = "select id,identificacion,menu from  personamenu  ";
        if ($filtro != null) {
            $cadenaSQL .= " where " . $filtro;
        }
        $cadenaSQL .= ' order by id asc' ;
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
    
     public function Adicionar() {
        $sql="insert into personamenu (identificacion, menu) values (
                '$this->identificacion',
                '$this->personamenu'
             )";
        //print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("ADICIONAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("PERSONAMENU");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
    
    public function Modificar( $id ) {
        $sql="update personamenu set
             menu = '$this->personamenu'
             where identificacion = '$id' ";
        //print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("MODIFICAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("PERSONAMENU");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
    
    public function Borrar() {
        $sql="delete from personamenu where identificacion = '$this->id' ";
        //print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("ELIMINAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("PERSONAMENU");
            $historico->grabar();
            return true;
        } 
        return false;
    }
}
