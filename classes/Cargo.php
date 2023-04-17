<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cargo
 *
 * @author Cristian Avella
 */
class Cargo {
    //put your code here
    private $id;
    private $codigocargo;
    private $nombrecargo;
    private $detalle;
    
    function __construct($campo, $valor) {
        if ($campo!=null){
            if (is_array($campo)) {
                $this->objeto($campo);
            }else{
                $cadenaSQL="select * from CARGO where $campo = $valor";
                $respuesta= ConectorBD::ejecutarQuery($cadenaSQL, null);
                if (count($respuesta)>0 ){ $this->objeto($respuesta[0]);}
            }
        }
    }

    private function objeto($vector){
        $this->id=$vector[0];
        $this->codigocargo=$vector[1];
        $this->nombrecargo=$vector[2];
        $this->detalle=$vector[3];
    }
    
    public function getId() {
        return $this->id;
    }

    public function getCodigocargo() {
        return $this->codigocargo;
    }

    public function getNombrecargo() {
        return $this->nombrecargo;
    }

    public function getDetalle() {
        return $this->detalle;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setCodigocargo($codigocargo): void {
        $this->codigocargo = $codigocargo;
    }

    public function setNombrecargo($nombrecargo): void {
        $this->nombrecargo = $nombrecargo;
    }

    public function setDetalle($detalle): void {
        $this->detalle = $detalle;
    }

        
    public function __toString() {
        return $this->nombre;
    }
   
    public static function datos($filtro, $pagina, $limit){
        $cadenaSQL="select * from CARGO ";
         if($filtro!=''){
            $cadenaSQL.=" where $filtro";
        } 
        $cadenaSQL.=" order by id asc offset $pagina limit $limit ";
        //print_r($cadenaSQL);
        return ConectorBD::ejecutarQuery($cadenaSQL, null);          
    }
    
    public static function datosobjetos($filtro, $pagina, $limit){
        $datos= Cargo::datos($filtro, $pagina, $limit);
        //print_r($datos);
        $lista=array();
        for ($i = 0; $i < count($datos); $i++) {
            $_lista=new Cargo($datos[$i], null);
            $lista[$i]=$_lista;
        }
        return $lista;
    }
    
     public static function count($filtro) {
        $cadena='select count(*) from CARGO '; 
        if($filtro!=''){
            $cadena.=" where $filtro";
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
    
}
