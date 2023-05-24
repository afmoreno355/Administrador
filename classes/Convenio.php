
<?PHP

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tabla
 *
 * @author Dibier
 */
class Convenio {

    private $id;
    private $nombre;
    private $area;
    private $abogado;
    private $tecnicoExperto;
    private $mes;
    private $estado;

    // constructor multifuncional segun el tipo de elemento que recibe realiza una busqueda, funciona como constructor vacio o recibe un array.
    function __construct($campo, $valor) {
        if ($campo != NULL) {
            if (is_array($campo)) {
                $this->cargarObjetoDeVector($campo);
            } else {
                $cadenaSQL = "select id,
                                     nombre,
                                     area,
                                     abogado,
                                     tecnico_experto,
                                     mes,
                                     estado
                              from convenios where $campo = $valor";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL, ' convenios ');
                if ( !empty($resultado) && count($resultado) > 0) {
                    $this->cargarObjetoDeVector($resultado[0]);
                }
            }
        }
    }

    //organiza el array que recibe el constructor  pero se debe colocar la posicion de la columna en el vector 
    private function cargarObjetoDeVector($vector) {
        $this->id = $vector[0];
        $this->nombre = $vector[1];
        $this->area = $vector[2];
        $this->abogado = $vector[3];
        $this->tecnicoExperto = $vector[4];
        $this->mes = $vector[5];
        $this->estado = $vector[6];
    }


    // get and set

    function getId() {
        return $this->id;
    }

    function setId($variable) {
        $this->id= $variable;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setNombre($variable) {
        $this->nombre= $variable;
    }

    function getArea() {
        return $this->area;
    }

    function setArea($variable) {
        $this->id = $area;
    }

    function getAbogado() {
        return $this->abogado;
    }

    function setAbogado($variable) {
        $this->abogado = $variable;
    }

    function getTecnicoExperto() {
        return $this->tecnicoExperto;
    }

    function setTecnicoExperto($variable) {
        $this->tecnicoExperto = $variable;
    }

    function getTecnicoEconomico() {
        return $this->tecnicoEconomico;
    }

    function setTecnicoEconomico($variable) {
        $this->tecnicoEconomico = $variable;
    }

    function getMes() {
        return $this->mes;
    }

    function setMes($variable) {
        $this->mes = $variable;
    }

    function getEstado() {
        return $this->estado;
    }

    function setEstado($variable) {
        $this->estado = $variable;
    }

    //datos hace la consulta sql.
    public static function datos($filtro, $pagina, $limit) {
        $cadenaSQL = "select id,
                             nombre,
                             area,
                             abogado,
                             tecnico_experto,
                             mes,
                             estado
                      from convenios ";
        if ($filtro != null) {
            $cadenaSQL .= " where " . $filtro;
        }
        $cadenaSQL .= ' order by id asc' ;
        if ($pagina != null && $limit != null) {
            $cadenaSQL .= " offset $pagina limit $limit ";
        }
        return ConectorBD::ejecutarQuery($cadenaSQL, ' convenios ');
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
        $cadena = 'select count(*) from convenios';
        if ($filtro != null) {
            $cadena.= " where " . $filtro;
        }
        return ConectorBD::ejecutarQuery($cadena, ' convenios ');
    }


    public function Adicionar() {
        $sqlSolicitud="insert into solicitudes 
                                    (area_competente,
                                     abogado,
                                     tecnico_experto,
                                     tecnico_economico,
                                     mes,
                                     estado)
                           values ('$this->id',
                                   '$this->area',
                                   '$this->abogado',
                                   '$this->tecnico_experto',
                                   '$this->tecnico_economico',
                                   '$this->mes',
                                   '$this->estado')";
        //print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("ADICIONAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("MENU");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
    
    public function Modificar( $id ) {
        $sql="update menu set
             nombre = '$this->nombre',
             pnombre = '$this->pnombre',
             icono = '$this->icono'
               where id = '$id' ";
        //print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("MODIFICAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("MENU");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
    
    public function Borrar() {
        $sql="delete from menu where id = '$this->id' ";
        print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("ELIMINAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("MENU");
            $historico->grabar();
            return true;
        } 
        return false;
    }



}


