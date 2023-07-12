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
            $campo = ' identificacion ' ;
            $valor = "'$id'" ;
        }
        else
        {
           $campo = null ;
           $valor = null ; 
        }
        $Persona = new Persona( $campo, $valor );
        if ($accion == "ADICIONAR" || $accion == "MODIFICAR") 
        {
            if (
                Select::validar( $identificacion , 'NUMERIC' , NULL , 'CAMPO IDENTIFICACION' ) &&
                Select::validar( $nombres , 'TEXT' , 50 , 'CAMPO NOMBRES' ) &&
                Select::validar( $apellidos , 'TEXT' , 50 , 'CAMPO APELLIDOS' ) &&
                Select::validar( $telefono , 'NUMERIC' , NULL , 'CAMPO TELEFONO' ) &&
                Select::validar( $celular , 'NUMERIC' , NULL , 'CAMPO CELULAR' ) &&
                Select::validar( $correoinstitucional , 'TEXT' , 80 , 'CAMPO CORREO' ) &&
                Select::validar( $idtipo , 'ARRAY' , null , 'CAMPO ROL' ,  " codigocargo = '$idtipo' " , 'eagle_admin' , 'cargo' ) &&
                Select::validar( $centro , 'ARRAY' , null , 'CAMPO CENTRO' ,  " codigosede = '$centro' " , 'eagle_admin' , 'sede' ) &&
                Select::validar( $dependencia , 'ARRAY' , null , 'CAMPO DEPENDENCIA' , 13 ) 
                )
            {
                
                $Persona->setId(trim($identificacion));
                $Persona->setNombre(strtoupper(trim($nombres)));
                $Persona->setApellido(strtoupper(trim($apellidos)));
                $Persona->setTel(trim($telefono));
                $Persona->setCorreo(strtoupper(trim($correoinstitucional)));
                $Persona->setCelular(trim($celular));
                $Persona->setidsede(trim($centro));
                $Persona->setIdTipo(trim($idtipo));
                $Persona->setJefeACargo('1085264553');
                $Persona->setDependencia($dependencia);  
                $Persona->setPassword(password_hash(md5(trim($identificacion)), PASSWORD_DEFAULT, ['cost'=> 12]));
                $Persona->setImagen('img/defecto/persona.jpg');

                if ($accion == "ADICIONAR") 
                {
                    if ( $Persona->Adicionar() )
                    {
                        $id = ConectorBD::ejecutarQuery("select identificacion from Persona where identificacion = '{$Persona->getId()}'  ; ", null)[0][0];
                        print_r("Se ha cargado en el módulo, Usuario adicionado <|> id Usuario $id");
                    }
                    else
                    {
                        print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                    }
                }
                elseif ($accion == "MODIFICAR") 
                {
                    if ( $Persona->Modificar( $id ) )
                    {
                        print_r("Se ha cargado en el módulo, Usuario modificado ");
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
            $Persona->setId($id);
            if ($Persona->borrar()) 
            {
                print_r("** EL USUARIO FUE ELIMINADO **");
            } 
            else 
            {
                print_r("** EL USUARIO NO SE PUDO ELIMINAR **");
            }
        }
    }
}