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
            if (
                Select::validar( $nombre , 'TEXT' , 250 , 'NOMBRE' ) &&
                Select::validar( $pnombre , 'TEXT' , 250 , 'RUTA' ) &&
                Select::validar( $_FILES['imagen'], 'FILE', null, 'IMAGEN', 'PNG' )
                )
            {
                $ruta_Icon = "img/enlace/" . "_"  . $_SESSION['user'] . "_" . $fecha . "." . pathinfo( strtolower( $_FILES['imagen']['name'] ) , PATHINFO_EXTENSION ) ;
                if ( copy( $_FILES['imagen']['tmp_name'] , dirname(__FILE__)."/../../".$ruta_Icon) )
                {
                    $menu->setNombre( str_replace($nombreTilde, $nombreSinTilde, strtoupper( $nombre ) ) ) ;
                    $menu->setPnombre( str_replace($nombreTilde, $nombreSinTilde, strtoupper( $pnombre ) ) ) ;
                    $menu->setIcono( $ruta_Icon ) ;

                    if ($accion == "ADICIONAR") 
                    {
                        if ( $menu->Adicionar() )
                        {
                            $id = ConectorBD::ejecutarQuery("select id from menu where nombre = '{$menu->getNombre()}' and pnombre = '{$menu->getPnombre()}' and icono = '{$menu->getIcono()}' ; ", null)[0][0];
                            print_r("Se ha cargado en el módulo, Menú adicionado <|> id menú $id");
                        }
                        else
                        {
                            print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                        }
                    }
                    elseif ($accion == "MODIFICAR") 
                    {
                        if ( $menu->Modificar( $id ) )
                        {
                            print_r("Se ha cargado en el módulo, Menú modificado ");
                        }
                        else
                        {
                            print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                        }
                    }   
                }
            }
        }
        elseif ($accion == "ELIMINAR")
        {
            $menu->setId($id);
            if ($menu->borrar()) 
            {
                print_r("** EL MENÚ FUE ELIMINADO **");
            } 
            else 
            {
                print_r("** EL MENÚ NO SE PUDO ELIMINAR **");
            }
        }
    }
}