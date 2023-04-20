<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Programa
 *
 * @author FELIPE
 */
class Programa {
    //put your code here
    private $id_programa ;
    private $nombre_programa;
    private $nivel_formacion ;
    private $duracion ;
    private $red_conocimiento ;
    private $linea_tecnologica ;
    private $tipo_esp ;
    private $segmento ;
    private $modalidad;
    private $fic;
    private $activo;
    

    function __construct($campo, $valor) {
        if ($campo!=null){
            if (is_array($campo)) {
                $this->objeto($campo);
            }else{
                $cadenaSQL="select * from programas where $campo = $valor ";
                //print_r($cadenaSQL);
                $respuesta= ConectorBD::ejecutarQuery($cadenaSQL, 'eagle_admin');
                if ( count($respuesta) > 0 )
                {
                    $this->objeto($respuesta[0]);
                }
            }
        }
    }

    private function objeto($vector){
        $this->id_programa=$vector[0];
        $this->nombre_programa=$vector[1];
        $this->nivel_formacion=$vector[2];
        $this->duracion=$vector[3];
        $this->red_conocimiento=$vector[4];
        $this->linea_tecnologica=$vector[5];
        $this->tipo_esp=$vector[6];
        $this->segmento=$vector[7];
        $this->modalidad=$vector[8];
        $this->fic=$vector[9];
        $this->activo=$vector[10];
    }
    
    public function getId_programa() {
        return $this->id_programa;
    }

    public function getNombre_programa() {
        return $this->nombre_programa;
    }

    public function getNivel_formacion() {
        return $this->nivel_formacion;
    }

    public function getDuracion() {
        return $this->duracion;
    }

    public function getRed_conocimiento() {
        return $this->red_conocimiento;
    }

    public function getLinea_tecnologica() {
        return $this->linea_tecnologica;
    }

    public function getTipo_esp() {
        return $this->tipo_esp;
    }

    public function getSegmento() {
        return $this->segmento;
    }

    public function getModalidad() {
        return $this->modalidad;
    }

    public function getFic() {
        return $this->fic;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function setId_programa($id_programa): void {
        $this->id_programa = $id_programa;
    }

    public function setNombre_programa($nombre_programa): void {
        $this->nombre_programa = $nombre_programa;
    }

    public function setNivel_formacion($nivel_formacion): void {
        $this->nivel_formacion = $nivel_formacion;
    }

    public function setDuracion($duracion): void {
        $this->duracion = $duracion;
    }

    public function setRed_conocimiento($red_conocimiento): void {
        $this->red_conocimiento = $red_conocimiento;
    }

    public function setLinea_tecnologica($linea_tecnologica): void {
        $this->linea_tecnologica = $linea_tecnologica;
    }

    public function setTipo_esp($tipo_esp): void {
        $this->tipo_esp = $tipo_esp;
    }

    public function setSegmento($segmento): void {
        $this->segmento = $segmento;
    }

    public function setModalidad($modalidad): void {
        $this->modalidad = $modalidad;
    }

    public function setFic($fic): void {
        $this->fic = $fic;
    }

    public function setActivo($activo): void {
        $this->activo = $activo;
    }

        
    public static function datos($filtro, $pagina, $limit) {
        $cadenaSQL = " select * from programas ";
        if ($filtro != '' ) 
        {
            $cadenaSQL .= " where $filtro ";
        }
        $cadenaSQL .= " order by id_programa desc ";
        if ($pagina != null && $limit != null) 
        {
            $cadenaSQL .= " offset $pagina limit $limit ";
        }
        //print_r($cadenaSQL);
        return ConectorBD::ejecutarQuery( $cadenaSQL, 'eagle_admin' );
    }
    
     public static function count($filtro)
    {
        $cadena='select count(*) from programas where' . $filtro ; 
        //print_r($cadena);
        return ConectorBD::ejecutarQuery($cadena, 'eagle_admin');        
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

    public static function listaopciones() 
    {
        $lista = "";
        $si = self::datosobjetos(null, null, null);
        for ($i = 0; $i < count($si); $i++) 
        {
            $obj = $si[$i];
            $lista .= "<option value='{$obj->getId_programa()}'> {$obj->getId_programa()} {$obj->getNombre_programa()} </option>";
        }
        return $lista;
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
    if (ConectorBD::ejecutarQuery($sql, 'eagle_admin')) {
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
        if (ConectorBD::ejecutarQuery($sql, 'eagle_admin')) {
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
        if (ConectorBD::ejecutarQuery($sql, 'eagle_admin')) {
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
