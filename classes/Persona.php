<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Persona
 *
 * @author Felipe Moreno
 */
class Persona {

    //put your code here
    private $id;
    private $nombre;
    private $apellido;
    private $tel;
    private $correo;
    private $celular;
    private $dependencia;
    private $idsede;
    private $idTipo;
    private $jefeACargo;
    private $password;
    private $imagen;

    function __construct($campo, $valor) {
        if ($campo != null) {
            if (is_array($campo)) {
                $this->cargarObjetoDeVector($campo);
            } else {
                $cadenaSQL = "select * from persona where $campo=$valor";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL, null);
                //print_r($cadenaSQL);
                if (count($resultado) > 0)
                    $this->cargarObjetoDeVector($resultado[0]);
            }
        }
    }

    private function cargarObjetoDeVector($vector) {

        $this->id = $vector[0];
        $this->nombre = $vector[1];
        $this->apellido = $vector[2];
        $this->tel = $vector[3];
        $this->correo = $vector[4];
        $this->celular = $vector[5];
        $this->dependencia = $vector[6];
        $this->idsede = $vector[7];
        $this->idTipo = $vector[8];
        $this->jefeACargo = $vector[9];
        $this->password = $vector[10];
        $this->imagen = $vector[11];
    }

    function getImagen() {
        return $this->imagen;
    }

    function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    function getId() {
        return trim($this->id);
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellido() {
        return $this->apellido;
    }

    function getTel() {
        return $this->tel;
    }

    function getCorreo() {
        return $this->correo;
    }

    function getCelular() {
        return $this->celular;
    }

    function getDependencia() {
        return $this->dependencia;
    }

    function getidsede() {
        return $this->idsede;
    }

    function getIdTipo() {
        return $this->idTipo;
    }

    function IdTipo() {
        if ($this->idTipo != 'SA') {
            return new Cargo("codigocargo", "'" . $this->idTipo . "'");
        } else {
            return 'SUPER ADMINISTRADOR';
        }
    }

    function getJefeACargo() {
        return $this->jefeACargo;
    }

    function getPassword() {
        return $this->password;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    function setTel($tel) {
        $this->tel = $tel;
    }

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setCelular($celular) {
        $this->celular = $celular;
    }

    function setDependencia($dependencia) {
        $this->dependencia = $dependencia;
    }

    function setidsede($idsede) {
        $this->idsede = $idsede;
    }

    function setIdTipo($idTipo) {
        $this->idTipo = $idTipo;
    }

    function setJefeACargo($jefeACargo) {
        $this->jefeACargo = $jefeACargo;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    public function __toString() {
        return trim($this->nombre) . " " . trim($this->apellido);
    }

    public static function datos($filtro, $pagina, $limit){
        $cadenaSQL="select * from persona ";
         if($filtro!=''){
            $cadenaSQL.=" where $filtro";
        } 
        $cadenaSQL.=" order by identificacion asc offset $pagina limit $limit ";
        return ConectorBD::ejecutarQuery($cadenaSQL, 'eagle_admin');          
    }
    
    public static function datosobjetos($filtro, $pagina, $limit){
        $datos= self::datos($filtro, $pagina, $limit);
        $lista=array();
        for ($i = 0; $i < count($datos); $i++) {
            $dato=new self($datos[$i], null);
            $lista[$i]=$dato;
        }
        return $lista;
    }
    
    public static function count($filtro) {
        $cadena='select count(*) from persona '; 
        if($filtro!=''){
            $cadena.=" where $filtro";
        } 
        return ConectorBD::ejecutarQuery($cadena, 'eagle_admin');        
    }
    
     public function borrar() {
        $cadenaSQL = "delete from persona where identificacion ='" . $this->id . "'";
        if (ConectorBD::ejecutarQuery($cadenaSQL, null)) {
             //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "/", $cadenaSQL);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION['user']);
            $historico->setTipo_historico("ELIMINAR");
            $historico->setHistorico($nuevo_query);
            $historico->setTabla("PERSONA");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }

    public function grabar() {
        $persona = '';
        $dependencias = '';
        $value = '';
        $value2 = '';
        if ( $this->idsede != '' ) {
            $persona = ' idsede, ';
            $value = "'$this->idsede',";
        }
        if ( $this->dependencia != '0' ) {
            $dependencias = ' dependencia, ';
            $value2 = "'$this->dependencia',";
        }
        $cadenaSQL = "insert into persona(identificacion,nombres,apellidos,telefono,correoinstitucional,celular,$persona $dependencias"
                . "idtipo,jefeacargo,password,imagen) values('{$this->id}','{$this->nombre}','{$this->apellido}','{$this->tel}','{$this->correo}',"
                . "'{$this->celular}', $value $value2 '{$this->idTipo}','{$this->jefeACargo}','{$this->password}','{$this->imagen}')";
        if (ConectorBD::ejecutarQuery($cadenaSQL, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "/", $cadenaSQL);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION['user']);
            $historico->setTipo_historico("GRABAR");
            $historico->setHistorico($nuevo_query);
            $historico->setTabla("PERSONA");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }

    public function modificar($id) {
        $cadenaSQL = "update persona set identificacion ='{$this->id}',  nombres ='{$this->nombre}', apellidos ='{$this->apellido}', telefono ='{$this->tel}', "
                . "correoinstitucional='{$this->correo}', celular ='{$this->celular}', dependencia ='{$this->dependencia}', idsede ='{$this->idsede}', "
                . "idtipo ='{$this->idTipo}', jefeacargo ='{$this->jefeACargo}' where identificacion ='" . $id . "'";
        if (ConectorBD::ejecutarQuery($cadenaSQL, null)) {
            //Historico de las acciones en el sistemas de informacion
            $nuevo_query = str_replace("'", "/", $cadenaSQL);
            $historico = new Historico(null, null);
            $historico->setIdentificacion($_SESSION['user']);
            $historico->setTipo_historico("MODIFICAR");
            $historico->setHistorico($nuevo_query);
            $historico->setTabla("PERSONA");
            $historico->grabar();
            return true;
        } else {
            return false;
        }
    }

    public function modicarUsuarioFinal($id) {
        $cadenaSQL = "update persona set identificacion ='{$this->id}',  nombres ='{$this->nombre}', apellidos ='{$this->apellido}', telefono ='{$this->tel}', "
                . "correoinstitucional='{$this->correo}', celular ='{$this->celular}',codigodependencia='{$this->idsede}', idtipo ='{$this->idTipo}' where identificacion ='" . $id . "'";
        ConectorBD::ejecutarQuery($cadenaSQL, null);
    }
    
    public function modificarImagen() {
        $cadena = "update persona set imagen='$this->imagen' where identificacion='$this->id';";
        ConectorBD::ejecutarQuery($cadena, null);
    }

    public function modificarPassword() {
        $cadenaSQL = "update persona set password='$this->password' where identificacion='$this->id'";
        if (ConectorBD::ejecutarQuery($cadenaSQL, null) !== false) {
            return true;
        } else {
            return false;
        }
    }
}
