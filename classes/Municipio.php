<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Municipio
 *
 * @author afmorenor
 */
class Municipio {
    //put your code here
    private $id ;
    private $municipio;
    private $id_departamento ;
    private $codigo_municipio ;
    private $dane ;
    private $cod_dpto_mpio ;
    private $estado ;
    private $nom_departamento ;
    
      

    function __construct($campo, $valor) {
        if ($campo!=null){
            if (is_array($campo)) {
                $this->objeto($campo);
            }else{
                $cadenaSQL="select municipio.id , municipio , departamento.id , codigo_municipio , dane , cod_dpto_mpio , estado , nom_departamento  from municipio , departamento  where departamento.id = municipio.id_departamento and $campo = $valor ";
                //print_r($cadenaSQL);
                $respuesta= ConectorBD::ejecutarQuery($cadenaSQL, null);
                if ( count($respuesta) > 0 )
                {
                    $this->objeto($respuesta[0]);
                }
            }
        }
    }

    private function objeto($vector){
        $this->id=$vector[0];
        $this->municipio=$vector[1];
        $this->id_departamento=$vector[2];
        $this->codigo_municipio=$vector[3];
        $this->dane=$vector[4];
        $this->cod_dpto_mpio=$vector[5];
        $this->estado=$vector[6];
        $this->nom_departamento=$vector[7];
    }
    
    public function getNom_departamento() {
        return $this->nom_departamento;
    }

    public function setNom_departamento($nom_departamento): void {
        $this->nom_departamento = $nom_departamento;
    }

        
    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado): void {
        $this->estado = $estado;
    }

    public function getId() {
        return $this->id;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function getId_departamento() {
        return $this->id_departamento;
    }

    public function getCodigo_municipio() {
        return $this->codigo_municipio;
    }

    public function getDane() {
        return $this->dane;
    }

    public function getCod_dpto_mpio() {
        return $this->cod_dpto_mpio;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setMunicipio($municipio): void {
        $this->municipio = $municipio;
    }

    public function setId_departamento($id_departamento): void {
        $this->id_departamento = $id_departamento;
    }

    public function setCodigo_municipio($codigo_municipio): void {
        $this->codigo_municipio = $codigo_municipio;
    }

    public function setDane($dane): void {
        $this->dane = $dane;
    }

    public function setCod_dpto_mpio($cod_dpto_mpio): void {
        $this->cod_dpto_mpio = $cod_dpto_mpio;
    }
          
    public static function datos($filtro, $pagina, $limit) {
        $cadenaSQL = " select municipio.id , municipio , departamento.id , codigo_municipio , dane , cod_dpto_mpio , estado , nom_departamento from municipio , departamento ";
        if ($filtro != '' ) 
        {
            $cadenaSQL .= " where $filtro ";
        }
        $cadenaSQL .= " order by municipio.id desc ";
        if ($pagina != null && $limit != null) 
        {
            $cadenaSQL .= " offset $pagina limit $limit ";
        }
        //print_r($cadenaSQL);
        return ConectorBD::ejecutarQuery( $cadenaSQL, null );
    }
    
     public static function count($filtro)
    {
        $cadena='select count(*) from municipio , departamento where' . $filtro ; 
        //print_r($cadena);
        return ConectorBD::ejecutarQuery($cadena, null);        
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
        $sql="insert into municipio( municipio , id_departamento , codigo_municipio , dane , cod_dpto_mpio , estado ) values(
                '$this->municipio',
                '$this->id_departamento',
                '$this->codigo_municipio',
                '$this->dane',
                '$this->cod_dpto_mpio',
                '$this->estado'
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
            $historico->setTabla("MUNICIPIO");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
    
    public function Modificar( $id ) {
        $sql="update municipio set
                municipio = '$this->municipio'
              , id_departamento = '$this->id_departamento'
              , codigo_municipio = '$this->codigo_municipio'
              , dane = '$this->dane'
              , cod_dpto_mpio = '$this->cod_dpto_mpio'
              , estado = '$this->estado'
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
            $historico->setTabla("MUNICIPIO");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
    
    public function Borrar() {
        $sql="delete from municipio where id = $this->id ";
        //print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "|", $sql);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION["user"]);
            $historico->setTipo_historico("ELIMINAR");
            $historico->setHistorico(strtoupper($nuevo_query));
            $historico->setFecha("now()");
            $historico->setTabla("MUNICIPIO");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }
}
