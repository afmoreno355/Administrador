<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Regional
 *
 * @author dmelov
 */
class Regional {
    //put your code here
     //put your code here
    private $cod;
    private $nombre;
    function __construct($campo, $valor) {
        if ($campo!=null){
            if (is_array($campo)) {
                $this->objeto($campo);
            }else{
                $cadenaSQL="select * from departamento where $campo = $valor";
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
        $this->cod=$vector[0];
        $this->nombre=$vector[1];
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
        $cadenaSQL="select * from departamento ";
         if($filtro!=''){
            $cadenaSQL.=" where $filtro";
        } 
        $cadenaSQL.=" order by id asc offset $pagina limit $limit ";
        //print_r($cadenaSQL);
        return ConectorBD::ejecutarQuery($cadenaSQL, null);          
    }
    
    public static function datosobjetos($filtro, $pagina, $limit){
        $datos= self::datos($filtro, $pagina, $limit);
        $lista=array();
        for ($i = 0; $i < count($datos); $i++) {
            $regional=new Regional($datos[$i], null);
            $lista[$i]=$regional;
        }
        return $lista;
    }
    
     public static function count($filtro) {
        $cadena='select count(*) from departamento '; 
        if($filtro!=''){
            $cadena.=" where $filtro";
        } 
        return ConectorBD::ejecutarQuery($cadena, 'eagle_admin');        
    }
    
    public static function listaopciones(){ 
        $lista='';
        $si= ConectorBD::ejecutarQuery("select codigosede,nombresede,bd,imagen,nom_departamento from  sede, departamento where departamento.id=sede.departamento", null);
        for ($i = 0; $i < count($si); $i++) {
            $lista.="<option value='{$si[$i][0]}'> {$si[$i][4]} {$si[$i][0]} {$si[$i][1]}</option>";
        }
    return $lista;
    } 
}
