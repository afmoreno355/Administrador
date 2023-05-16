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
    private $id_departamento;
    private $departamento;
    
    function __construct($campo, $valor) {
        if ($campo!=null){
            if (is_array($campo)) {
                $this->objeto($campo);
            }else{
                $cadenaSQL="select * from sede , departamento where departamento=id  AND $campo = $valor";
                //print_r($cadenaSQL);
                $respuesta= ConectorBD::ejecutarQuery($cadenaSQL, 'eagle_admin');
                if ( count($respuesta) > 0 )
                {
                    $this->objeto ($respuesta[0]);
                }
            }
        }
    }

    private function objeto($vector){
        $this->cod=$vector[0];
        $this->nombre=$vector[1];
        $this->bd=$vector[2];
        $this->imagen=$vector[3];
        $this->id_departamento=$vector[5];
        $this->departamento=$vector[6];
    }
    
    public function getId_departamento() {
        return $this->id_departamento;
    }

    public function setId_departamento($id_departamento): void {
        $this->id_departamento = $id_departamento;
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
        //print_r($cadenaSQL);
        return ConectorBD::ejecutarQuery($cadenaSQL, 'eagle_admin');          
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
        return ConectorBD::ejecutarQuery($cadena, 'eagle_admin');        
    }
    
}

  
  
  

