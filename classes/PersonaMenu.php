<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonaMenu
 *
 * @author FELIP
 */
class PersonaMenu {
    //put your code here
    private $id;
    private $identificacion;
    private $menu;
    
    function __construct($campo , $valor) {
     if ($campo!=null){
            if (is_array($campo)) {
                $this->objeto($campo);
            }else{
                $cadenaSQL="select * from personamenu where $campo = $valor";
                //print_r($cadenaSQL);
                $respuesta= ConectorBD::ejecutarQuery($cadenaSQL, null);
                if (count($respuesta)>0) $this->objeto($respuesta[0]);
            }
        }
    }

    private function objeto($vector){
        $this->id=$vector[0];
        $this->identificacion=$vector[1];
        $this->menu=$vector[2];                     
    }
    
    function getId() {
        return $this->id;
    }

    function getIdentificacion() {
        return $this->identificacion;
    }

    function getMenu() {
        return $this->menu;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

    function setMenu($menu) {
        $this->menu = $menu;
    }
    
    public function grabar() {
       ConectorBD::ejecutarQuery("insert into personamenu(identificacion, menu) values('$this->identificacion', '$this->menu')", null);      
    }


}
