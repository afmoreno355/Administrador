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

            if (
                 Select::validar( $nombre , 'TEXT' , 250 , 'NOMBRE' ) &&
                 Select::validar( $pnombre , 'TEXT' , 250 , 'PNOMBRE' ) &&
                 Select::validar( $icono, 'TEXT' , 250 , 'ÍCONO' )
                )
            {
                $menu->setNombre( $nombre ) ;
                $menu->setPnombre( $pnombre ) ;
                $menu->setIcono( $icono ) ;
                $imagen = $_FILES['imagen'] ;

                if ($accion == "ADICIONAR") 
                {
                    if ( isset( $imagen ) && $imagen['name'] != '' )
                    {
                        if ( Select::validar( $imagen, 'FILE', null, 'IMAGEN', 'PNG' ) )
                        {
                            if ( $menu->Adicionar() ) {
                                $srcfile=$_FILES['imagen']['tmp_name'];
                                $dstfile='/var/www/eagle/adminV2/file2/Joe.png';
                                if (!copy($srcfile, $dstfile) ){
                                    print_r(":(");
                                }
                                print_r("Se ha cargado en el modulo, Menú adicionado <|> id menú $id");
                            } else {
                                print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                            }
                        }
                    }
                    else
                    {
                        if ( $menu->Adicionar() )
                        {
                            print_r("Se ha cargado en el modulo, Menú adicionado <|> id menú $id");
                        }
                        else
                        {
                            print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                        }
                    }
                }
                elseif ($accion == "MODIFICAR") 
                {
                    if ( Select::validar( $id, 'NUMERIC', null, 'ID' ) )
                    {
                        if ( isset( $imagen ) && $imagen['name'] != '' )
                        {
                            if ( Select::validar( $imagen, 'FILE', null, 'IMAGEN', 'PNG' ) )
                            {
                                if ( $menu->Modificar( $id ) ) {

                                    print_r("Se ha cargado en el modulo, Menú modificado <|> id menú $id");
                                } else {
                                    print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                                }
                            }
                        }
                        else
                        {
                            if ( $menu->Modificar( $id ) )
                            {
                                print_r("Se ha cargado en el modulo, Menú modificado <|> id menú $id");
                            }
                            else
                            {
                                print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                            }
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
        /*elseif ( $accion == "SUBIR ARCHIVO" )
        {
            if(  Select::validar( $_FILES['archivo'] , 'FILE' , null , 'ARCHIVO' , 'CSV' ) )
            {
               if ( copy( $_FILES['archivo']['tmp_name'] , $ruta_Plano = "F:/wamp64/www/Virtual/Archivos/" . "Registro" . "_"  . $_SESSION['user'] . "_" . $fecha . "." . pathinfo( strtolower( $_FILES['archivo']['name'] ) , PATHINFO_EXTENSION ) ) )
               {
                    if ( ( $gestor = fopen( $ruta_Plano , "r" ) ) !== FALSE )
                    {
                        $contador = 1;
                        while ( ( $nuevoNombre3 = fgetcsv( $gestor , 0 , ";" ) ) !== FALSE ) 
                        {
                            if ( $contador >= 2 )
                            {
                                if ( Select::validar( $id_programa , 'NUMERIC' , null , 'CAMPO PROGRAMA' ) &&
                                    Select::validar( $nombre_programa , 'TEXT' , 500 , 'CAMPO NOMBRE DE PROGRAMA' ) &&
                                    Select::validar( $nivel_formacion , 'ARRAY' , null , 'CAMPO NIVEL DE FORMACION' ,  " nivel_formacion = '$nivel_formacion' " , null , 'programas' ) &&
                                    Select::validar( $red_conocimiento , 'ARRAY' , null , 'CAMPO RED DE CONOCIMIENTO' ,  " id_red = '$red_conocimiento' " , 'registro' , 'red_conocimiento' ) &&
                                    Select::validar( $linea_tecnologica , 'ARRAY' , null , 'CAMPO LINEA TECNOLOGICA' ,  " id = '$linea_tecnologica' " , 'registro' , 'linea_tecnologica' ) &&
                                    Select::validar( $segmento , 'ARRAY' , null , 'CAMPO SEGMENTO' ,  " segmento = '$segmento' " , null , 'programas' ) &&
                                    Select::validar( $modalidad , 'ARRAY' , null , 'CAMPO MODALIDAD' ,  " modalidad = '$modalidad' " , null , 'programas' ) &&
                                    Select::validar( $fic , 'ARRAY' , NULL , 'CAMPO FIC' , 10 ) &&
                                    Select::validar( $activo , 'ARRAY' , NULL , 'CAMPO ACTIVO' , 10 ) &&
                                    Select::validar( $duracion , 'NUMERIC' , null , 'CAMPO DURACION' )
                                    )
                                {                                
                                    $programa->setId_programa( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $id_programa ) ) ) ;
                                    $programa->setNombre_programa( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $nombre_programa) ) ) ;
                                    $programa->setNivel_formacion(str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $nivel_formacion) ) ) ;
                                    $programa->setRed_conocimiento( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $red_conocimiento) ) ) ;
                                    $programa->setLinea_tecnologica( str_replace( $nombreTilde , $nombreSinTilde , strtoupper(  $linea_tecnologica) ) ) ;
                                    $programa->setSegmento( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $segmento ) ) ) ;
                                    $programa->setDuracion( str_replace( $nombreTilde , $nombreSinTilde , strtoupper( $duracion) ) );
                                    $programa->setFic($fic);
                                    $programa->setActivo($activo);
                                    $programa->setModalidad( str_replace( $nombreTilde , $nombreSinTilde , strtoupper ( $modalidad) ) ) ;
                                    $programa->setTipo_esp('T');                                   
                                }
                            }
                            $contador += 1;
                        }
                    }   
                    print_r("Se ha cargado en el modulo");
                    unlink( $ruta_Plano );
               }
               else
               {
                    print_r(strtoupper( "  ERROR EN EL CAMPO ARCHIVO PROBLEMA CARGADO AL SERVIDOR " ) );
               }
            }
            print_r("Se ha cargado en el modulo , indicativa Creada " ) ;
        }/** SUBIR ARCHIVO*/
    }
}
