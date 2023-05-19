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
        $menu = new Menu( $campo, $valor ) ;
        if ($accion == "ADICIONAR" || $accion == "MODIFICAR") 
        {
            if ($accion == "ADICIONAR")
            {
                $id = 0;
            }

            if (
                 Select::validar( $id, 'NUMERIC', null, 'ID') &&
                 Select::validar( $nombre , 'TEXT' , 250 , 'NOMBRE' ) &&
                 Select::validar( $pnombre , 'TEXT' , 250 , 'PNOMBRE' ) &&
                 Select::validar( $icono, 'TEXT' , 250 , 'ÍCONO' )
                )
            {
                $menu->setNombre( $nombre ) ;
                $menu->setPnombre( $pnombre ) ;
                $menu->setIcono( $icono ) ;

                $imagen = $_FILES['imagen'] ;
                $cargarImagen = isset( $imagen ) && $imagen['name'] != '' ;
                $imagen_destino = '/srv/http/www/adminV2/img/icon/'.$icono.'.png';

                if ($cargarImagen)
                {
                    if ( Select::validar( $imagen, 'FILE', null, 'IMAGEN', 'PNG' ) )
                    {
                        if ( $menu->AdicionarModificar( $id ) )
                        {
                            if ( !copy($imagen['tmp_name'], $imagen_destino) )
                            {
                                print_r(" No se ha cargado la imagen correctamente. ");
                            }

                            if ( $id == 0 )
                            {
                                $menuNuevo = new Menu(' nombre ', "'$nombre'");
                                $id = $menuNuevo->getId();
                            }

                            print_r(" Se ha cargado menú en el módulo <|> id $id ");

                        } else
                        {
                            print_r( " ERROR INESPERADO, VUELVA A INTENTAR. " );
                        }
                    }
                } else {
                    if ( $menu->AdicionarModificar( $id ) )
                    {
                        if ( $id == 0)
                        {
                            $menuNuevo = new Menu(' nombre ', "'$nombre'");
                            $id = $menuNuevo->getId();
                        }

                        print_r(" Se ha cargado menú en el módulo <|> id $id ");
                    } else {
                        print_r( " ERROR INESPERADO, VUELVA A INTENTAR. " );
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
