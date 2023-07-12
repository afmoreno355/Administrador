<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__) . "/../../autoload.php";
//
// Iniciamos sesion para tener las variables
//prueba para guardar en repositorio
if( !isset($_SESSION["user"]) )
{
    session_start();
}

//prueba de descarga de modificaciones

date_default_timezone_set("America/Bogota");
$fecha = date("YmdHis");

// variable variable trae las variables que trae POST
foreach ($_POST as $key => $value)
    ${$key} = $value;

$nombreTilde = array("á", "é", "í", "ó", "ú", "ñ", ".", "", "Á", "É", "Í", "Ó", "Ú", "Ñ", ".", "");
$nombreSinTilde = array("&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;", "", "", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;", "", "");
$nombreSinTilde_Nuevo = array("A", "E", "I", "O", "U", "N", "", "", "A", "E", "I", "O", "U", "N", "", "");

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
            $v1_A = ' id ' ;
            $v2_A = "'$id'" ;
        }
        else
        {
           $v1_A = null ;
           $v2_A = null ; 
        }
        $Regional = new Regional($v1_A, $v2_A );
        if ($accion == "ADICIONAR" || $accion == "MODIFICAR") 
        {
            if ( Select::validar( $codigo , 'NUMERIC' , null , 'CAMPO CODIGO DEL REGIONAL' ) &&
                 Select::validar( $nom_departamento , 'TEXT' , 100 , 'CAMPO NOMBRE DE REGIONAL' )
                )
            {
                $Regional->setCod( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $codigo ) ) ) ;
                $Regional->setNombre( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $nom_departamento ) ) ) ;
                
                if ($accion == "ADICIONAR") 
                {
                    if ($Regional->Adicionar()) 
                    {
                        print_r("Se ha cargado en el módulo , Regional Creada <|> código Regional $codigo" ) ;
                    } 
                    else 
                    {
                        print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                    }
                }
                elseif ($accion == "MODIFICAR") 
                {
                    if ($Regional->Modificar($id)) 
                    {
                        print_r("Se ha cargado en el módulo , Regional Modificado");
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
            $Regional->setCod($id);
            if ($Regional->borrar()) 
            {
                print_r("** LA REGIONAL FUE ELIMINADA **");
            } 
            else 
            {
                print_r("** LA REGIONAL NO SE PUDO ELIMINAR **");
            }
        }
    }
}
