<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contratos
 *
 * @author Cristian Avella 23/05/2023 prueba de descarga
 */
class Contratos {

    //put your code here
    private $id;
    private $codigocontratos;
    private $nombrecontratos;
    private $detalle;

    function __construct($campo, $valor) {
        if ($campo != null) {
            if (is_array($campo)) {
                $this->cargarObjetoDeVector($campo);
            } else {
                $cadenaSQL = "select * from contratos where $campo = $valor";
                //print_r($cadenaSQL);
                $respuesta = ConectorBD::ejecutarQuery($cadenaSQL, null);
                if (count($respuesta) > 0) {
                    $this->cargarObjetoDeVector($respuesta[0]);
                }
            }
        }
    }

    private function cargarObjetoDeVector($vector) {
        $this->id = $vector[0];
        $this->codigocontratos = $vector[1];
        $this->nombrecontratos = $vector[2];
        $this->detalle = $vector[3];
    }

    public function getId() {
        return $this->id;
    }

    public function getCodigocontratos() {
        return $this->codigocontratos;
    }

    public function getNombrecontratos() {
        return $this->nombrecontratos;
    }

    public function getDetalle() {
        return $this->detalle;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setCodigocontratos($codigocontratos): void {
        $this->codigocontratos = $codigocontratos;
    }

    public function setNombrecontratos($nombrecontratos): void {
        $this->nombrecontratos = $nombrecontratos;
    }

    public function setDetalle($detalle): void {
        $this->detalle = $detalle;
    }

    public function __toString() {
        return $this->nombre;
    }

    public static function datos($filtro, $pagina, $limit) {
        $cadenaSQL = "select * from contratos ";
        if ($filtro != '') {
            $cadenaSQL .= " where $filtro";
        }
        $cadenaSQL .= " order by id asc offset $pagina limit $limit ";
        //print_r($cadenaSQL);
        return ConectorBD::ejecutarQuery($cadenaSQL, null);
    }

    public static function datosobjetos($filtro, $pagina, $limit) {
        $datos = Contratos::datos($filtro, $pagina, $limit);
        //print_r($datos);
        $lista = array();
        for ($i = 0; $i < count($datos); $i++) {
            $_lista = new Contratos($datos[$i], null);
            $lista[$i] = $_lista;
        }
        return $lista;
    }

    public static function count($filtro) {
        $cadena = 'select count(*) from CONTRATOS ';
        if ($filtro != '') {
            $cadena .= " where $filtro";
        }
        return ConectorBD::ejecutarQuery($cadena, null);
    }

    public static function listaopciones() {
        $lista = "";
        $si = self::datosobjetos(null, null, null);
        for ($i = 0; $i < count($si); $i++) {
            $obj = $si[$i];
            $lista .= "<option value='{$obj->getId()}'> {$obj->getId()} {$obj->getNombrecontratos()} </option>";
        }
        return $lista;
    }

    public function Adicionar() {
        $sql = "insert into CONTRATOS( codigocontratos,     nombrecontratos , detalle  ) values(
                '$this->codigocontratos',
                '$this->nombrecontratos',
                '$this->detalle'
             )";
        //print_r($sql);

        try {
            if (ConectorBD::ejecutarQuery($sql, null)) {
                $nuevo_query = str_replace("'", "|", $sql);
                $historico = new Historico(null, null);
                $historico->setIdentificacion($_SESSION["user"]);
                $historico->setTipo_historico("ADICIONAR");
                $historico->setHistorico(strtoupper($nuevo_query));
                $historico->setFecha("now()");
                $historico->setTabla("Contratos");
                $historico->grabar();
                return true;
            } else {
                return false;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    public function modificar($id) {
        $sql = "update CONTRATOS set id = '$this->id', codigocontratos = '$this->codigocontratos', nombrecontratos = '$this->nombrecontratos', detalle = '$this->detalle' where id = '$id' ";
        //print_r($sql);
        if (ConectorBD::ejecutarQuery($sql, null)) {
            return true;
        } else {
            return false;
        }
    }

    public function borrar() {
        //console.log(":D");
        $sql = "delete from CONTRATOS where id = '$this->id' ";
        try {
            if (ConectorBD::ejecutarQuery($sql, null)) {
                $nuevo_query = str_replace("'", "|", $sql);
                $historico = new Historico(null, null);
                $historico->setIdentificacion($_SESSION["user"]);
                $historico->setTipo_historico("MODIFICAR");
                $historico->setHistorico(strtoupper($nuevo_query));
                $historico->setFecha("now()");
                $historico->setTabla("Contratos");
                $historico->grabar();
                return true;
            } else {
                return false;
            }
        } catch (Exception $exc) {
            return false;
        }
    }
    
        public function bloqueo() {
        //console.log(":D");
        $sql = "delete from CONTRATOS where id = '$this->id' ";
        try {
            if (ConectorBD::ejecutarQuery($sql, null)) {
                $nuevo_query = str_replace("'", "|", $sql);
                $historico = new Historico(null, null);
                $historico->setIdentificacion($_SESSION["user"]);
                $historico->setTipo_historico("MODIFICAR");
                $historico->setHistorico(strtoupper($nuevo_query));
                $historico->setFecha("now()");
                $historico->setTabla("Contratos");
                $historico->grabar();
                return true;
            } else {
                return false;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

}
