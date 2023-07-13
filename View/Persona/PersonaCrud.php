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
$lista = '' ;
$fecha_Campania = date('Y-m-d H:i:s');
$filascount = 0;
$arraycount = 0;
$comodin = '';
$lista_Titulo = '';
$errores = '';
$array_session = [];

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
                Select::validar( $idtipo , 'ARRAY' , null , 'CAMPO ROL' ,  " codigocargo = '$idtipo' where codigocargo <> 'SA' " , 'eagle_admin' , 'cargo' ) &&
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
        elseif (!isset($accionU))
        {
            print_r('hola');
            $cero = false;
            $nombrefoto = dirname(__FILE__)."/../../Archivos/TMP/" . $fecha . $_FILES['personaplano']['name'];
            $url_Foto = "Archivos/TMP/" . $_FILES['personaplano']['name'];
            if (copy($_FILES['personaplano']['tmp_name'], $nombrefoto))
            {
                $filascount = 1;
                if (pathinfo(strtoupper($_FILES['personaplano']['name']), PATHINFO_EXTENSION) == 'DOC')
                {
                    $fileHandle = fopen($nombrefoto, "r");
                    $line = @fread($fileHandle, filesize($nombrefoto));
                    $line = preg_replace("[\n|\r|\n\r]", "-_-" . chr(0x0D), $line);
                    $lines = explode(chr(0x0D), $line);
                    $outtext = "";
                    foreach ($lines as $thisline)
                    {
                        $pos = strpos($thisline, chr(0x00));
                        if (($pos !== FALSE) || (strlen($thisline) == 0)) 
                        {

                        } 
                        else
                        {
                            $outtext .= $thisline . " ";
                        }
                    }
                    $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\;\n\-\?\n\%\r\t@\/\_\(\)]/", "", $outtext);
                    $nuevoNombre3 = explode("-_-", $outtext);
                    if ($separador == '')
                    {
                        if (count(explode(';', $nuevoNombre3[0])) >= 5) 
                        {
                            $comodin = ';';
                        } 
                        elseif (count(explode(',', $nuevoNombre3[0])) >= 5) 
                        {
                            $comodin = ',';
                        }
                        elseif (count(explode('%', $nuevoNombre3[0])) >= 5) 
                        {
                            $comodin = '%';
                        } 
                        elseif (count(explode('.', $nuevoNombre3[0])) >= 5)
                        {
                            $comodin = '.';
                        }
                    } else 
                    {
                        $comodin = $separador;
                    }

                    if ($comodin != '') 
                    {
                        for ($i = 0; $i < count($nuevoNombre3); $i++)
                        {
                            $cortar[] = explode($comodin, $nuevoNombre3[$i]);
                            $lista .= "<tr>";
                            for ($j = 0; $j < count($cortar[$i]); $j++) 
                            {
                                if (count($cortar[$i]) > 3) {
                                    if ($arraycount < count($cortar[$i])) 
                                    {
                                        $arraycount = count($cortar[$i]);
                                    }
                                    $lista .= "<td>";
                                    $lista .= trim($cortar[$i][$j]);
                                    $lista .= "</td>";
                                    $array_session[$i][$j] = trim($cortar[$i][$j]);
                                }
                            }
                            $lista .= "</tr>";
                        }
                        $_SESSION['archivo'] = $array_session;
                    }
                } 
                elseif (pathinfo(strtoupper($_FILES['personaplano']['name']), PATHINFO_EXTENSION) == 'CSV') 
                {
                    if (($gestor = fopen($nombrefoto, "r")) !== FALSE) {
                        if ($separador == '') 
                        {
                            if (count($nuevoNombre3 = fgetcsv($gestor, 0, ";")) >= 5) 
                            {
                                $comodin = ';';
                            }
                            elseif (count($nuevoNombre3 = fgetcsv($gestor, 0, ",")) >= 5) 
                            {
                                $comodin = ',';
                            } 
                            elseif (count($nuevoNombre3 = fgetcsv($gestor, 0, "%")) >= 5) 
                            {
                                $comodin = '%';
                            } 
                            elseif (count($nuevoNombre3 = fgetcsv($gestor, 0, ".")) >= 5) 
                            {
                                $comodin = '.';
                            }
                        } 
                        else 
                        {
                            $comodin = $separador;
                        }
                    }
                    fclose($gestor);
                    if (($gestor = fopen($nombrefoto, "r")) !== FALSE) 
                    {
                        if ($comodin != '') 
                        {
                            while (($nuevoNombre3 = fgetcsv($gestor, 0, $comodin)) !== FALSE) 
                            {
                                $lista .= "<tr>";
                                for ($j = 0; $j < count($nuevoNombre3); $j++) 
                                {
                                    if (count($nuevoNombre3) > 3) 
                                    {
                                        if ($arraycount < count($nuevoNombre3)) 
                                        {
                                            $arraycount = count($nuevoNombre3);
                                        }
                                        if ($cero === false) 
                                        {
                                            $cero = true;
                                            $filascount = 0;
                                        }
                                        $lista .= "<td>";
                                        $lista .= trim($nuevoNombre3[$j]);
                                        $lista .= "</td>";
                                        $array_session[$filascount][$j] = trim($nuevoNombre3[$j]);
                                    }
                                }
                                $lista .= "</tr>";
                                $filascount = $filascount + 1;
                            }
                        }
                    }
                    $_SESSION['archivo'] = $array_session;
                    fclose($gestor);
                }
                $lista_Titulo .= "<tr>";
                for ($k = 0; $k < $arraycount; $k++) 
                {
                    $lista_Titulo .= "<td>";
                    $lista_Titulo .= "<input type='checkbox' value='$k' class='column' onclick='validarColumn(event)'/>";
                    $lista_Titulo .= "</td>";
                }
                $lista_Titulo .= "</tr>";
                $lista = '<table class="tableIntT sombra tableIntTa">' . $lista_Titulo . $lista;
                $lista .= '</table>';
               
                unlink($nombrefoto);
                print_r($lista);
                ob_end_flush();
            }
        }
    }
}