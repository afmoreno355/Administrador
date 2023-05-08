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

$nombreTilde = array("á", "é", "í", "ó", "ú", "ñ", ".", "", "Á", "É", "Í", "Ó", "Ú", "Ñ", ".", "");
$nombreSinTilde = array("&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;", "", "", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;", "", "");
$nombreSinTilde_Nuevo = array("A", "E", "I", "O", "U", "N", "", "", "A", "E", "I", "O", "U", "N", "", "");

date_default_timezone_set("America/Bogota");
$fecha = date("YmdHis");
/*$fecha_indicativas = date("Y-m-d H:i:s");
$fecha_indicativa_comp = date("Y-m-d");
$anio_indicativa = date("Y");
$acceso_Tipo_Usuario = ConectorBD::ejecutarQuery( " select validar from indicativa  WHERE cod_centro = '{$_SESSION['sede']}' and vigencia ='$anio_indicativa' and id_modalidad = '3' group by validar ; " ,  null ) ;
/** */

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
        if( $id != '' )
        {
            $campo = ' id ' ;
            $valor = "'$id'" ;
        }
        else
        {
           $campo = null ;
           $valor = null ; 
        }
        $menu = new Menu( $campo, $valor );
        if ($accion == "ADICIONAR" || $accion == "MODIFICAR") 
        {
            if ($accion == "ADICIONAR") {
                $id = 0;
            }
            if ( Select::validar( $id , 'NUMERIC' , null, 'ID' ) &&
                 Select::validar( $nombre , 'TEXT' , 250 , 'NOMBRE' ) &&
                 Select::validar( $pnombre , 'TEXT' , 250 , 'PNOMBRE' ) &&
                 Select::validar( $icono, 'TEXT' , 250 , 'ÍCONO' )
                )
            {
                //$menu->setId( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $id ) ) ) ;
                $menu->setPnombre( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $pnombre) ) ) ;
                $menu->setNombre( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $nombre) ) ) ;
                $menu->setIcono( $icono ) ;
                if ($accion == "ADICIONAR") 
                {
                    if ($menu->Adicionar()) 
                    {
                        print_r("Se ha cargado en el modulo, registro Cargo Creado <|> id menú $id" ) ;
                    } 
                    else 
                    {
                        print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                    }
                }
                elseif ($accion == "MODIFICAR") 
                {
                    if ($menu->Modificar($id)) 
                    {
                        print_r("Se ha cargado en el modulo, Cargo Modificado  <|> id menú $id");
                    }
                    else 
                    {
                        print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                    }
                }   
            }
        }
        elseif ($accion == "ELIMINAR")
        {
            $menu->setId($id);
            if ($menu->borrar()) 
            {
                print_r("** EL CARGO FUE ELIMINADO **");
            } 
            else 
            {
                print_r("** EL CARGO NO SE PUDO ELIMINAR **");
            }
        }
        elseif ( $accion == "BLOQUEAR" )
        {
            print_r("Te he bloqueado");
        }
    }
}