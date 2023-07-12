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
        $Sede = new Sede($v1_A, $v2_A );
        if ($accion == "ADICIONAR" || $accion == "MODIFICAR") 
        {
            if ( Select::validar( $codsede , 'NUMERIC' , null , 'CAMPO CODIGO DE SEDE' ) &&
                 Select::validar( $nombresede , 'TEXT' , '100' , 'CAMPO MONBRE DE SEDE' ) &&
                 Select::validar( $id_departamento , 'ARRAY' , null , 'CAMPO REGIONAL' ,  " id = '$id_departamento' " , 'eagle_admin' , 'departamento' )
                )
            {
                $Sede->setCod( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $codsede ) ) ) ;
                $Sede->setNombre( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $nombresede ) ) ) ;
                $Sede->setId_departamento( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $id_departamento ) ) ) ;
                
                if ($accion == "ADICIONAR") 
                {
                    if ($Sede->Adicionar()) 
                    {
                        print_r("Se ha cargado en el módulo , Sede Creada <|> código Sede $codsede" ) ;
                    } 
                    else 
                    {
                        print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                    }
                }
                elseif ($accion == "MODIFICAR") 
                {
                    if ($Sede->Modificar($id)) 
                    {
                        print_r("Se ha cargado en el módulo , Sede Modificado");
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
            $Sede->setCod($id);
            if ($Sede->borrar()) 
            {
                print_r("** LA SEDE FUE ELIMINADA **");
            } 
            else 
            {
                print_r("** LA SEDE NO SE PUDO ELIMINAR **");
            }
        }
    }
}
