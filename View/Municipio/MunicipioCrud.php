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
$fecha_indicativas = date("Y-m-d H:i:s");
$fecha_indicativa_comp = date("Y-m-d");
$anio_indicativa = date("Y");

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
            $v1_A = ' municipio.id ' ;
            $v2_A = "'$id'" ;
        }
        else
        {
           $v1_A = null ;
           $v2_A = null ; 
        }
        $municipio = new Municipio($v1_A, $v2_A );
        if ($accion == "ADICIONAR" || $accion == "MODIFICAR") 
        {
            if ( Select::validar( $municipios, 'TEXT' , 100 , 'CAMPO NOMBRE DE MUNICIPIO' ) &&
                 Select::validar( $id_departamento , 'ARRAY' , null , 'CAMPO REGIONAL' ,  " id = '$id_departamento' " , 'eagle_admin' , 'departamento' ) &&
                 Select::validar( $activo , 'ARRAY' , NULL , 'CAMPO ACTIVO' , 11 ) &&
                 Select::validar( $codigo_municipio , 'NUMERIC' , null , 'CAMPO CODIGO MUNICIPIO' ) &&
                 Select::validar( $dane , 'NUMERIC' , null , 'CAMPO CODIGO DANE' ) &&
                 Select::validar( $cod_dpto_mpio , 'NUMERIC' , null , 'CAMPO CODIGO REGIONAL MUNICIPIO' ) 
                )
            {
                $municipio->setMunicipio( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $municipios ) ) ) ;
                $municipio->setId_departamento( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $id_departamento ) ) ) ;
                $municipio->setEstado( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $activo ) ) ) ;
                $municipio->setCodigo_municipio( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $codigo_municipio ) ) ) ;
                $municipio->setDane( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $dane ) ) ) ;
                $municipio->setCod_dpto_mpio( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $cod_dpto_mpio ) ) ) ;
                
                if ($accion == "ADICIONAR") 
                {
                    if ($municipio->Adicionar()) 
                    {
                        $_id_municipio = ConectorBD::ejecutarQuery( " select id from municipio where municipio = '{$municipio->getMunicipio()}' and id_departamento= '{$municipio->getId_departamento()}' and codigo_municipio = '{$municipio->getCodigo_municipio()}' and cod_dpto_mpio = '{$municipio->getCod_dpto_mpio()}' and dane = '{$municipio->getDane()}' " , 'eagle_admin' ) ;
                        print_r("Se ha cargado en el módulo , Municipio Creada <|> id {$_id_municipio[0][0]}  " ) ;
                    } 
                    else 
                    {
                        print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                    }
                }
                elseif ($accion == "MODIFICAR") 
                {
                    if ($municipio->Modificar($id)) 
                    {
                        print_r("Se ha cargado en el módulo , Municipio Modificada");
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
            $municipio->setId($id);
            if ($municipio->borrar()) 
            {
                print_r("** EL MUNICIPIO FUE ELIMINADA **");
            } 
            else 
            {
                print_r("** EL MUNICIPIO NO SE PUDO ELIMINAR **");
            }
        }
        elseif ( $accion == "BLOQUEAR MUNICIPIO" )
        {
            if( Select::validar( $activo , 'ARRAY' , NULL , 'CAMPO ACTIVO' , 11 ) )
            {
                $municipio->setId($id);
                $municipio->setEstado( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $activo ) ) ) ;
                if ( $municipio->ActivarDesactivar() ) 
                {
                    print_r("Se ha cargado en el módulo , Programa Municipio");
                } 
                else 
                {
                    print_r("** EL MUNICIPIO NO SE PUDO BLOQUEAR **");
                }
            }
        }   
        elseif ( $accion == "CARGAR ARCHIVO" )
        {
            if(  Select::validar( $_FILES['archivo'] , 'FILE' , null , 'ARCHIVO' , 'CSV' ) )
            {
               if ( copy( $_FILES['archivo']['tmp_name'] , $ruta_Plano = "F:/wamp64/www/Virtual/Archivos/" . "Registro_municipio" . "_"  . $_SESSION['user'] . "_" . $fecha . "." . pathinfo( strtolower( $_FILES['archivo']['name'] ) , PATHINFO_EXTENSION ) ) )
               {
                    if ( ( $gestor = fopen( $ruta_Plano , "r" ) ) !== FALSE )
                    {
                        $contador = 1;
                        while ( ( $nuevoNombre3 = fgetcsv( $gestor , 0 , ";" ) ) !== FALSE ) 
                        {
                            if ( $contador >= 2 )
                            {
                                if( strtoupper( $nuevoNombre3[6] ) == 'ACTIVO' ) 
                                {
                                    $nuevoNombre3[6] = 'A' ;
                                }
                                else if( strtoupper( $nuevoNombre3[6] ) == 'INACTIVO' ) 
                                {
                                    $nuevoNombre3[6] = 'I' ;
                                }
                                if( Select::validar( $nuevoNombre3[1] , 'TEXT' , 100 , 'CAMPO NOMBRE DE MUNICIPIO' ) &&
                                    Select::validar( $nuevoNombre3[2] , 'ARRAY' , null , 'CAMPO REGIONAL' ,  " id = '$nuevoNombre3[2]' " , 'eagle_admin' , 'departamento' ) &&
                                    Select::validar( $nuevoNombre3[6] , 'ARRAY' , NULL , 'CAMPO ACTIVO' , 11 ) &&
                                    Select::validar( $nuevoNombre3[3] , 'NUMERIC' , null , 'CAMPO CODIGO MUNICIPIO' ) &&
                                    Select::validar( $nuevoNombre3[4] , 'NUMERIC' , null , 'CAMPO CODIGO DANE' ) &&
                                    Select::validar( $nuevoNombre3[5] , 'NUMERIC' , null , 'CAMPO CODIGO REGIONAL MUNICIPIO' ) 
                                    )
                                {                                
                                    $municipio->setId( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $nuevoNombre3[0] ) ) ) ;
                                    $municipio->setMunicipio( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $nuevoNombre3[1] ) ) ) ;
                                    $municipio->setId_departamento( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $nuevoNombre3[2] ) ) ) ;
                                    $municipio->setEstado( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $nuevoNombre3[6] ) ) ) ;
                                    $municipio->setCodigo_municipio( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $nuevoNombre3[3] ) ) ) ;
                                    $municipio->setDane( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $nuevoNombre3[4] ) ) ) ;
                                    $municipio->setCod_dpto_mpio( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $nuevoNombre3[5] ) ) ) ;
                                    if( Select::validar( $nuevoNombre3[0] , 'ARRAY' , null , 'CAMPO IDENTIFICADOR EN SISTEMA' ,  " id = '$nuevoNombre3[0]' " , 'eagle_admin' , 'municipio' ) )
                                    {
                                        $municipio->Modificar( $nuevoNombre3[0] ) ;
                                    }
                                    else 
                                    {
                                        $municipio->Adicionar() ;
                                    }
                                }
                            }
                            $contador += 1;
                        }
                    }   
                    print_r("Se ha cargado en el módulo");
                    unlink( $ruta_Plano );
               }
               else
               {
                    print_r(strtoupper( "  ERROR EN EL CAMPO ARCHIVO PROBLEMA CARGADO AL SERVIDOR " ) );
               }
            }
            print_r("Se ha cargado en el módulo , indicativa Creada " ) ;
        }
    }
}
