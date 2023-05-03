
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
class Menu {

    private $icono;
    private $id;
    private $nombre;
    private $pnombre;

    // constructor multifuncional segun el tipo de elemento que recibe realiza una busqueda, funciona como constructor vacio o recibe un array.
    function __construct($campo, $valor) {
        if ($campo != NULL) {
            if (is_array($campo)) {
                $this->cargarObjetoDeVector($campo);
            } else {
                $cadenaSQL = "select icono,id,nombre,pnombre from  menu  where $campo = $valor";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL, null);
                if ( !empty($resultado) && count($resultado) > 0) {
                    $this->cargarObjetoDeVector($resultado[0]);
                }
            }
        }
    }

    //organiza el array que recibe el constructor  pero se debe colocar la posicion de la columna en el vector 
    private function cargarObjetoDeVector($vector) {
        $this->icono = $vector[0];
        $this->id = $vector[1];
        $this->nombre = $vector[2];
        $this->pnombre = $vector[3];
    }


    // get and set 
    function getIcono() {
        return $this->icono;
    }

    function setIcono($variable) {
        $this->icono = $variable;
    }

    function getId() {
        return $this->id;
    }

    function setId($variable) {
        $this->id = $variable;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setNombre($variable) {
        $this->nombre = $variable;
    }

    function getPnombre() {
        return $this->pnombre;
    }

    function setPnombre($variable) {
        $this->pnombre = $variable;
    }

    //datos hace la consulta sql.
    public static function datos($filtro, $pagina, $limit) {
        $cadenaSQL = "select icono,id,nombre,pnombre from  menu  ";
        if ($filtro != null) {
            $cadenaSQL .= " where " . $filtro;
        }
        $cadenaSQL .= ' order by id asc' ;
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

    public static function count($filtro) {
        $cadena = 'select count(*) from menu';
        if ($filtro != null) {
            $cadena.= " where " . $filtro;
        }
        return ConectorBD::ejecutarQuery($cadena, null);
    }


    public function Adicionar() {
        $sql="insert into programas( id_programa ,     nombre_programa,     nivel_formacion ,    
                                    duracion ,     red_conocimiento ,     linea_tecnologica ,     
                                    tipo_esp ,     segmento ,     modalidad,     fic,     activo  ) values(
                '$this->id_programa',
                '$this->nombre_programa',
                '$this->nivel_formacion',
                '$this->duracion',
                '$this->red_conocimiento',
                '$this->linea_tecnologica',
                '$this->tipo_esp',
                '$this->segmento',
                '$this->modalidad',
                '$this->fic',
                '$this->activo'
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
            $historico->setTabla("PROGRAMA");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
    
    public function Modificar( $id ) {
        $sql="update programas set
                nombre_programa = '$this->nombre_programa'
              , nivel_formacion = '$this->nivel_formacion'
              , duracion = '$this->duracion'
              , red_conocimiento = '$this->red_conocimiento'
              , linea_tecnologica = '$this->linea_tecnologica'
              , segmento = '$this->segmento'
              , id_programa = '$this->id_programa'
              , modalidad = '$this->modalidad'
              , fic = '$this->fic'
              , activo = '$this->activo'
               where id_programa = '$id' ";
        //print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("MODIFICAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("PROGRAMA");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
    
    public function Borrar() {
        $sql="delete from programas where id_programa = '$this->id_programa' ";
        if (ConectorBD::ejecutarQuery($sql, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("ELIMINAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("PROGRAMA");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }



}


