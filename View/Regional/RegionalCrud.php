<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__) . "/../../autoload.php";
//
// Iniciamos sesion para tener las variables
if( !isset($_SESSION["user"]) )
{
    session_start();
}

date_default_timezone_set("America/Bogota");
$fecha = date("YmdHis");
$fecha_documento = date("Y-m-d H:i:s");
$anio_documento = date("Y");
$mes_documento = date("m");

// variable variable trae las variables que trae POST
foreach ($_POST as $key => $value)
    ${$key} = $value;


$session = new Sesion(" identificacion ", "'{$_SESSION["user"]}'");
$persona = new Persona( " identificacion ", "'{$_SESSION["user"]}'" );
$token1 = $session->getToken1();
$token2 = $session->getToken2();

if ($_SESSION["token1"] !== $_COOKIE["token1"] && $_SESSION["token2"] !== $_COOKIE["token2"]) {
    print_r("NO TIENE PERMISO PARA REALIZAR ESTA ACCION");
    //header("Location: index");
} elseif ($_SESSION["token1"] === $_COOKIE["token1"] && $_SESSION["token2"] === $_COOKIE["token2"] && password_verify(md5($token1 . $token2), $session->getToken3())) {
    if (isset($accion)) {
        if ($accion == "ELIMINAR")
        {
            $documento->setId_documento($id);
            if ($documento->borrar()) 
            {
                print_r("** EL DOCUMENTO FUE ELIMINADA **");
            } 
            else 
            {
                print_r("** EL DOCUMENTO NO SE PUDO ELIMINAR **");
            }
        }
        elseif ($accion == "CARGAR PDF")
        {
            if (!isset($_FILES['documento']) || pathinfo(strtoupper($_FILES['documento']['name']), PATHINFO_EXTENSION) == 'PDF')
            {
                if (!isset($_FILES['documento']) || copy( $_FILES['documento']['tmp_name'], "F:/wamp64/www/Virtual/Archivos/pdf/" . $id . "_" . $mes_documento . "_" . $anio_documento . "." . pathinfo( strtolower($_FILES['documento']['name']), PATHINFO_EXTENSION ) ) ) ;
                {
                    if (isset($_FILES['documento'])) 
                    {
                        //print_r( " insert into evidencia values( '$id' , '$anio_documento' , 'Archivos/" . $id . "_" . $mes_documento . "_" . $anio_documento . "." . pathinfo( strtolower($_FILES['documento']['name']), PATHINFO_EXTENSION )."' ) ;" );
                        if ( ConectorBD::ejecutarQuery( " insert into evidencia values( '$id' , '$anio_documento' , 'Archivos/pdf/" . $id . "_" . $mes_documento . "_" . $anio_documento . "." . pathinfo( strtolower($_FILES['documento']['name']), PATHINFO_EXTENSION )."' ) ;" ,  null ) ) 
                        {
                           print_r("Se ha cargado en el modulo , Documento cargado "  ) ;
                        } 
                        else 
                        {
                            print_r("** EL ARCHIVO NO PUDO SER CARGADO  **");
                        }
                    }   
                }
            }
        }
        elseif ($accion == "APROBAR")
        {
            $regional = new Regional( null , null ) ;
            $regional->setCod($id);
            if ( $regional->estado( $modalidad ) ) 
            {
                print_r("** EL ESTADO DE LA INDICATIVA A CAMBIADO **");
            } 
            else 
            {
                print_r("** EL ESTADO DE LA INDICATIVA NO PUDO SER CAMBIADO **");
            }
        }        
    }
}
