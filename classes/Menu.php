
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
    private $imagen;

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

    function getImagen() {
        return $this->imagen;
    }

    function setImagen($variable) {
        $this->imagen = $variable;
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
        $sql="insert into menu (nombre, pnombre, icono) values (
                '$this->nombre',
                '$this->pnombre',
                '$this->icono'
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
            $historico->setTabla("MENU");
            $historico->grabar();
            $this->CopiarImagen();
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
            $this->CopiarImagen();
            return true;
        } else {
            return false;
        }
    }

    public function CopiarImagen() {

        $cargarImagen = isset( $this->imagen ) && $this->imagen['name'] != '';
        $destino = __DIR__.'/../img/icon/'.$this->icono.'.png'; // La carpeta debe tener permisos

        if ( $cargarImagen ) {
            if (
                Select::validar( $this->imagen, 'FILE', null, 'IMAGEN', 'PNG' ) &&
                copy($this->imagen['tmp_name'], $destino)
               )
               {
                $historico = new Historico(null, null);
                $historico->setIdentificacion($_SESSION["user"]);
                $historico->setTipo_historico("AGREGAR_IMAGEN_ÍCONO_MENÚ");
                $historico->setFecha("now()");
                $historico->grabar();
               } else {
                print_r(" No se ha cargado la imagen correctamente. ");
               }
        }
    }

    public function AdicionarModificar( $id ) {
        if ( $id == 0 ) {
            return $this->Adicionar();
        }
        return $this->Modificar( $id ) ;
    }
    
    public function Borrar() {
        $sql="delete from menu where id = '$this->id' ";
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


