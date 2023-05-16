<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sede
 *
 * @author Felipe Moreno
 */
class Sede {
    //put your code here
    private $cod;
    private $nombre;
    private $bd;
    private $imagen;
    private $departamento;
    
    function __construct($campo, $valor) {
        if ($campo!=null){
            if (is_array($campo)) {
                $this->objeto($campo);
            }else{
                $cadenaSQL="select * from sede , departamento where departamento=id  AND $campo = $valor";
                $respuesta= ConectorBD::ejecutarQuery($cadenaSQL, null);
                if ($respuesta>0 || $valor!=null) $this->objeto ($respuesta[0]);
            }
        }
    }

    private function objeto($vector){
        $this->cod=$vector[0];
        $this->nombre=$vector[1];
        $this->bd=$vector[2];
        $this->imagen=$vector[3];
        $this->departamento=$vector[6];
    }
    
    function getDepartamento() {
        return $this->departamento;
    }

    function setDepartamento($departamento){
        $this->departamento = $departamento;
    }

    function getImagen() {
        return $this->imagen;
    }

    function setImagen($imagen){
        $this->imagen = $imagen;
    }

    function getBd() {
        return $this->bd;
    }

    function setBd($bd){
        $this->bd = $bd;
    }

    function getCod() {
        return $this->cod;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function __toString() {
        return $this->nombre;
    }
   
    public static function datos($filtro, $pagina, $limit){
        $cadenaSQL="select * from sede , departamento where departamento=id ";
         if($filtro!=''){
            $cadenaSQL.=" and $filtro";
        } 
        $cadenaSQL.=" order by codigosede asc offset $pagina limit $limit ";
        return ConectorBD::ejecutarQuery($cadenaSQL, null);          
    }
    
    public static function datosobjetos($filtro, $pagina, $limit){
        $datos= Sede::datos($filtro, $pagina, $limit);
        $lista=array();
        for ($i = 0; $i < count($datos); $i++) {
            $sede=new Sede($datos[$i], null);
            $lista[$i]=$sede;
        }
        return $lista;
    }
    
     public static function count($filtro) {
        $cadena='select count(*) from sede , departamento where departamento=id '; 
        if($filtro!=''){
            $cadena.=" and $filtro";
        } 
        return ConectorBD::ejecutarQuery($cadena, null);        
    }
    
    public static function listaopciones( $id , $select = '' ){ 
        $lista='';
        $seleccion='';
        $si = self::listas( $id );
        for ($i = 0; $i < count($si); $i++) {
            print_r($select);
            print_r($si[$i][0]);
            if( $si[$i][0]==$select && $select != '' )
            {
                $seleccion='selected';
            }
            else
            {
                $seleccion='';
            }
            $lista.="<option value='{$si[$i][0]}' $seleccion> {$si[$i][1]} </option>";
        }
    return $lista;
    } 
    
    public static function listas( $id ){
        switch ( $id )
        {
            case 1 :
                return ConectorBD::ejecutarQuery("select codigosede,nombresede from sede ", null);
            break;    
            case 3 :
                return ConectorBD::ejecutarQuery("select id , municipio from municipio ", null);
            break; 
        }
    } 

}

  
  
  

