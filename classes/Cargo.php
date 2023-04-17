<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cargo
 *
 * @author Felipe Moreno
 */
class Cargo {
    //put your code here
     private $id;
     private $cod;
     private $nombre;
     private $detalle;
    
    function __construct($campo, $valor) {
        if ($campo!=null){
            if (is_array($campo)) {
                $this->objeto($campo);
            }else{
                $cadenaSQL="select * from cargo where $campo = $valor"; 
                $respuesta= ConectorBD::ejecutarQuery($cadenaSQL, null);
                if ($respuesta>0 && $valor!=null) $this->objeto ($respuesta[0]);
            }
        }
    }

    private function objeto($vector){
        $this->id=$vector[0];
        $this->cod=$vector[1];
        $this->nombre=$vector[2];
        $this->detalle=$vector[3];
    }
    
    function getDetalle() {
        return $this->detalle;
    }

    function setDetalle($detalle){
        $this->detalle = $detalle;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
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
    
    public function grabar(){        
    $cadenaSQL="insert into cargo(codigocargo,nombrecargo, detalle) values('$this->cod','$this->nombre','$this->detalle')";
    ConectorBD::ejecutarQuery($cadenaSQL, null);
} 

    public function borrar(){
    $cadenaSQL="delete from cargo where id = $this->id";
    ConectorBD::ejecutarQuery($cadenaSQL, null);
} 

    public function modificar($cod){
    $cadenaSQL="update cargo set codigocargo = '$this->cod', nombrecargo = '$this->nombre' where codigocargo ='".trim($cod)."'";
    ConectorBD::ejecutarQuery($cadenaSQL, null);
} 

    public static function datos($filtro){
        $cadenaSQL="select * from cargo ";
        if ($filtro!=null) {
            $cadenaSQL.=" where $filtro";
        }
        return ConectorBD::ejecutarQuery($cadenaSQL, null);          
    }
    
    public static function datosobjetos($filtro){
        $datos= Cargo::datos($filtro);
        $lista=array();
        for ($i = 0; $i < count($datos); $i++) {
            $cargo=new Cargo($datos[$i], null);
            $lista[$i]=$cargo;
        }
        return $lista;
    }
    
     public static function datos2($filtro, $pagina, $limit){
        $cadenaSQL="select * from cargo where codigocargo<>'SA' $filtro";
        $cadenaSQL.=" order by codigocargo asc offset $pagina limit $limit ";
        return ConectorBD::ejecutarQuery($cadenaSQL, null);          
    }
    
    public static function datosobjetos2($filtro, $pagina, $limit){
        $datos= Cargo::datos2($filtro, $pagina, $limit);
        $lista=array();
        for ($i = 0; $i < count($datos); $i++) {
            $cargo=new Cargo($datos[$i], null);
            $lista[$i]=$cargo;
        }
        return $lista;
    }
    
     public static function count($filtro) {
        return ConectorBD::ejecutarQuery("select count(*) from cargo where codigocargo<>'SA' ".$filtro, null);        
    }
    
    public static function listaopciones($user){
        
        if( empty( $cargo = ConectorBD::ejecutarQuery( "select idtipo from persona where identificacion='$user'", null ) ) && $user=='' )
        {
            $consulta=null;
        }
        else
        {
            $persona = ( new Persona( ' identificacion ', "'{$_SESSION['user']}'" ) )->getIdTipo();
            if($persona == 'RC')
            {
                $consulta = " codigocargo IN ( 'RC' , 'GR', 'VI' ) ";
            }
            elseif ($persona == 'AI' || $persona == 'AV')
            {
                $consulta = " codigocargo IN ( 'IR' , 'GI', 'AI', 'AV', 'VI' ) ";
            }
            elseif($persona == 'A')
            {
                $consulta = " codigocargo IN ( 'I' , 'AC' ) ";
            }
            elseif($persona == 'SC')
            {
                $consulta = " codigocargo IN ( 'SC' , 'CO' ) ";
            }
            elseif($persona == 'AA')
            {
                $consulta = " codigocargo IN ( 'AC' , 'AA' , 'RJ' , 'RT' , 'VA' , 'AS' , 'Ra' ) ";
            }
            elseif($persona == 'CA')
            {
                $consulta = " codigocargo IN ( 'CA' , 'CD' , 'RA' , 'RS' , 'VC' , 'VB' ) ";
            }
            elseif($persona == 'Ca')
            {
                $consulta = " codigocargo IN ( 'Ca' , 'Cc' , 'Rc' ) ";
            }
            elseif($persona == 'AP')
            {
                $consulta = " codigocargo IN ( 'AP' ) ";
            }
            else
            {
                $consulta = null;   
            }
        }
        
        $resultado= Cargo::datosobjetos($consulta);

        $lista="<option value=''>Lista Perfiles</option>";
        for ($i = 0; $i < count($resultado); $i++) {
            $selected='';
            
            $si=$resultado[$i];
            if(!empty($cargo) && trim($cargo[0][0])==trim($si->getCod())){
                $selected='selected';
            }
            $lista.="<option value='{$si->getCod()}' $selected>{$si->getNombre()}</option>";
        } 
        return $lista;
    }
    
     public static function modal($accion, $id, $usuario, $menu){    
         if ($accion=='MODIFICAR') {
           $cargo=new Cargo(' codigocargo', "'".trim($id)."'");  
         }ELSE{
            $cargo=new Cargo(NULL, NULL);  
         }
      
      $lista="<section id='registrar'>
    <div class='formulario' id='formulario'>        
        <form class='form-registro' method=post action='main.php?CONTENIDO=layouts/options/configuracion/cargoModificar.php'>            
            <h2 class='form-titulo'>Adicionar Cargo</h2>  
             <label class='text-input'>Codigo</label>
        <label class='text-input' >Cargo</label> 
            <input type='text' name='codigoCargo' value='{$cargo->getCod()}' class='media' required autofocus>
            <input type='text' name='nombre' value='".trim($cargo->getNombre())."' class='media' required>           
            <input type='hidden' name='accion' value='{$accion}'/>
            <input type='hidden' name='menu' value='{$menu}'/>
            <input type='hidden' name='id' value='{$id}'/>
            <input type='hidden' name='usuario' value='$usuario' />
            <input type='submit' name='guardar' value='Registrar' />
            <input type='reset' name='limpiar' value='Limpiar' />             
        </form>  
    </div>
</section>";
      
      return $lista;
  }
  
}
