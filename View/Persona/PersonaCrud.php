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
$_menu_nuevo = '' ;
$_array_user = array() ; 

date_default_timezone_set("America/Bogota");
$fecha = date("YmdHis");

// variable variable trae las variables que trae POST
foreach ($_POST as $key => $value)
    ${$key} = $value;

$session = new Sesion(" identificacion ", "'{$_SESSION["user"]}'");
$persona = new Persona( " identificacion ", "'{$_SESSION["user"]}'" );
$empresa = ConectorBD::ejecutarQuery( " select * from empresa; " , null ) ;

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
        $PersonaMenu = new PersonaMenu( null , null );
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
                
                for ($m = 0; $m < count( $menu ); $m++) 
                {
                    $_menu_nuevo .= "{$menu[$m]}<|" ;
                }
                $PersonaMenu->setIdentificacion( $identificacion ) ;
                $PersonaMenu->setPersonamenu( $_menu_nuevo ) ;
                if ($accion == "ADICIONAR") 
                {
                    if ( $Persona->Adicionar() )
                    {
                        $id = ConectorBD::ejecutarQuery("select identificacion from Persona where identificacion = '{$Persona->getId()}'  ; ", null)[0][0];
                        if( $PersonaMenu->Adicionar() )
                        {
                            print_r("Se ha cargado en el módulo, Usuario adicionado <|> id Usuario $id");
                            require './../Mail/Mail.php'; 
                            mailer("{$Persona->getCorreo()}",
                                "<body style='width: 100%; height: auto; position: absolute;'>
                                    <p style='width: 90%;height: auto;position: relative;padding: 5px;font-weight: bold; margin-left: 5%;'>
                                            Estimado Usuario cordial saludo,
                                    </p>
                                    <br>
                                    <p style='width: 90%;height: auto;position: relative;padding: 5px;margin-left: 5%;'>
                                           La Direccion de Formacion Profesional le informa que ha creado un usuario en el Sistema de Gestion de Informacion de la DFP, por tanto lo invitamos a que ingrese en el siguiente enlace: http://dfp.senaedu.edu.co/modulos_gestion/ para que ingrese con su numero de Cedula o Correo Institucional, la contrase&ntilde;a por defecto será su numero de cedula. 
                                    </p>

                                    <div style='width: 90%;height: auto;position: relative;padding: 5px;margin-left: 5%;'>
                                        <table style='border-collapse: collapse;'>
                                            <tr>
                                                <td rowspan='3' ><img src='{$empresa[0][2]}' width='100px' height='100px'></td><td style='border-right: 1px solid orange; color:  orange; font-weight: bold; font-size: 1.4em;'>Sistema de Gestion   </td><td> Direccion Formacion Profesional</td>
                                            </tr>
                                            <tr>
                                                <td style='border-right: 1px solid orange'>De Informacion - DFP   </td><td style='color:  orange; font-weight: bold; font-size: 1.4em;'> www.sena.edu.co</td>
                                            </tr>       
                                        </table>        
                                    </div>
                                </body>", 
                            "USUARIO CREADO {$empresa[0][1]}");
                        }
                        else 
                        {
                            $Persona->borrar( $identificacion ) ;
                            print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                        }
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
                        if( $PersonaMenu->Modificar( $identificacion ) )
                        {
                            print_r("Se ha cargado en el módulo, Usuario modificado ");
                            require './../Mail/Mail.php';  
                            mailer("{$Persona->getCorreo()}", 
                                "<body style='width: 100%; height: auto; position: absolute;'>
                                    <p style='width: 90%;height: auto;position: relative;padding: 5px;font-weight: bold; margin-left: 5%;'>
                                            Estimado Usuario cordial saludo,
                                    </p>
                                    <br>
                                    <p style='width: 90%;height: auto;position: relative;padding: 5px;margin-left: 5%;'>
                                           La Direccion de Formacion Profesional le informa que ha modificado su usuario en el Sistema de Gestion de Informacion de la DFP, por tanto lo invitamos a que ingrese en el siguiente enlace: http://dfp.senaedu.edu.co/modulos_gestion/ para que ingrese con su numero de Cedula o Correo electronico y su Contrase&ntilde;a no ha sido modificada. 
                                    </p>

                                    <div style='width: 90%;height: auto;position: relative;padding: 5px;margin-left: 5%;'>
                                        <table style='border-collapse: collapse;'>
                                            <tr>
                                                <td rowspan='3' ><img src='{$empresa[0][2]}' width='100px' height='100px'></td><td style='border-right: 1px solid orange; color:  orange; font-weight: bold; font-size: 1.4em;'>Sistema de Gestion   </td><td> Direccion Formacion Profesional</td>
                                            </tr>
                                            <tr>
                                                <td style='border-right: 1px solid orange'>De Informacion - DFP   </td><td style='color:  orange; font-weight: bold; font-size: 1.4em;'> www.sena.edu.co</td>
                                            </tr>       
                                        </table>        
                                    </div>
                                </body>", 
                            "USUARIO MODIFICADO {$empresa[0][1]}");
                        }
                        else 
                        {
                            $Persona->borrar( $identificacion ) ;
                            print_r("** ERROR INESPERADO VUELVE A INTENTAR **");
                        }
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
        elseif ($accion == 'A. PLANOS') 
        {
            if ($column != '')
            {
                for ($i = 1; $i < count($_SESSION['archivo']); $i++) 
                {
                    if ($identificacion != '' && $identificacion != ' ' && isset($identificacion) && is_numeric($identificacion)) 
                    {
                        $Persona->setId(trim(strtoupper($_SESSION['archivo'][$i][$identificacion])));
                        $val0 = true;
                    }
                    if ($nombres != '' && isset($nombres) && is_numeric($nombres)) 
                    {
                        $Persona->setNombre(trim(strtoupper($_SESSION['archivo'][$i][$nombres])));
                        $val1 = true;
                    }
                    if ($apellidos != '' && isset($apellidos) && is_numeric($apellidos))
                    {
                        $Persona->setApellido(trim(strtoupper($_SESSION['archivo'][$i][$apellidos])));
                        $val2 = true;
                    }
                    if ($correo != '' && isset($correo) && is_numeric($correo)) {
                        $Persona->setCorreo(trim(strtoupper($_SESSION['archivo'][$i][$correo])));
                        $val3 = true;
                    }
                    if ($celular != '' && isset($celular) && is_numeric($celular)) {
                        $Persona->setCelular(strtoupper($_SESSION['archivo'][$i][$celular]));
                        $val4 = true;
                    }
                    if ($telefono != '' && isset($telefono) && is_numeric($telefono)) 
                    {
                        $Persona->setTel(strtoupper($_SESSION['archivo'][$i][$telefono]));
                        $val5 = true;
                    }
                    if ($sede != '' && isset($sede) && is_numeric($sede)) 
                    {
                        $Persona->setidsede(trim(strtoupper($_SESSION['archivo'][$i][$sede])));
                        $val6 = true;
                    }
                    if ($rol != '' && isset($rol) && is_numeric($rol)) 
                    {
                        $Persona->setIdTipo(trim(strtoupper($_SESSION['archivo'][$i][$rol])));
                        $val7 = true;
                    }
                    if (isset($dependencia) ) 
                    {
                        $Persona->setDependencia(trim(strtoupper($_SESSION['archivo'][$i][$dependencia])));
                        $val8 = true;
                    }
                    if (isset($val0) && isset($val1) && isset($val2) && isset($val3) && isset($val4) && isset($val5) && isset($val6) && isset($val7) && isset($val8)) 
                    {
                        $Persona->setImagen('img/defecto/persona.jpg');
                        $Persona->setPassword(password_hash(md5(trim($Persona->getId())), PASSWORD_DEFAULT, ['cost' => 12]));
                        $string='';
                        if ($Persona->Adicionar()) 
                        {
                            $bn_todo = true;
                            array_push( $_array_user , $Persona->getCorreo() ) ;
                            $menuAcc=new PersonaMenu(null,null);
                            if($Persona->getIdTipo()=='A'){  
                                $string='1<|3<|4<|';    
                            } elseif($Persona->getIdTipo()=='RC'){  
                                $string='1<|2<|3<|';    
                            } elseif ($Persona->getIdTipo()=='GR') {
                                $string='1<|3<|';    
                            } elseif ($Persona->getIdTipo()=='AI') {
                                $string='1<|2<|3<|4<|';    
                            } elseif ($Persona->getIdTipo()=='GI') {
                                $string='1<|3<|4<|';    
                            } elseif ($Persona->getIdTipo()=='IR') {
                                $string='1<|3<|4<|';    
                            } elseif ($Persona->getIdTipo()=='VI') {
                                $string='1<|3<|4<|';    
                            }  elseif ($Persona->getIdTipo()=='CO') {
                                $string='1<|5<|';    
                            }  elseif ($Persona->getIdTipo()=='SC') {
                                $string='1<|2<|5<|';    
                            }  elseif ($Persona->getIdTipo()=='AC') {
                                $string='1<|6<|';    
                            }  elseif ($Persona->getIdTipo()=='AA') {
                                $string='1<|2<|6<|';    
                            }  elseif ($Persona->getIdTipo()=='RJ' || $Persona->getIdTipo()=='RT' || $Persona->getIdTipo()=='VA' || $Persona->getIdTipo()=='AS') {
                                $string='1<|6<|';    
                            }  elseif ($Persona->getIdTipo()=='CA') {
                                $string='1<|2<|9<|';    
                            }  elseif ($Persona->getIdTipo()=='RA' || $Persona->getIdTipo()=='RS' || $Persona->getIdTipo()=='CD' || $Persona->getIdTipo()=='VC' || $Persona->getIdTipo()=='VB' || $Persona->getIdTipo()=='RM' ) {
                                $string='1<|9<|';    
                            }  elseif ($Persona->getIdTipo()=='AP' ) {
                                $string='1<|10<|';    
                            }  elseif ($Persona->getIdTipo()=='Cc' || $Persona->getIdTipo()=='Ca' ) {
                                $string='1<|11<|';    
                            }  elseif ($Persona->getIdTipo()=='Ua' || $Persona->getIdTipo()=='Aa' ) {
                                $string='1<|12<|';    
                            }    

                            $menuAcc->setIdentificacion(trim($Persona->getId()));
                            $menuAcc->setPersonamenu($string);        
                            $menuAcc->Adicionar();
                            $_SESSION['aviso']="EL USUARIO CON CEDULA Y CORREO ".trim($Persona->getId())."  ".strtoupper(trim($Persona->getCorreo()))." FUE CREADO CON EXITO"; 
                        }
                        else
                        {
                            $errores .= ' ERROR FILA ' . ($i + 1) . '<br>';
                        }
                    }
                }
            }
            if (isset($bn_todo)) 
            {
                print_r("Todo salio bn <3 <br>Archivo guardado<br> $errores");
                 require './../Mail/Mail.php'; 
                            mailer($_array_user,
                                "<body style='width: 100%; height: auto; position: absolute;'>
                                    <p style='width: 90%;height: auto;position: relative;padding: 5px;font-weight: bold; margin-left: 5%;'>
                                            Estimado Usuario cordial saludo,
                                    </p>
                                    <br>
                                    <p style='width: 90%;height: auto;position: relative;padding: 5px;margin-left: 5%;'>
                                           La Direccion de Formacion Profesional le informa que ha creado un usuario en el Sistema de Gestion de Informacion de la DFP, por tanto lo invitamos a que ingrese en el siguiente enlace: http://dfp.senaedu.edu.co/modulos_gestion/ para que ingrese con su numero de Cedula o Correo Institucional, la contrase&ntilde;a por defecto será su numero de cedula. 
                                    </p>

                                    <div style='width: 90%;height: auto;position: relative;padding: 5px;margin-left: 5%;'>
                                        <table style='border-collapse: collapse;'>
                                            <tr>
                                                <td rowspan='3' ><img src='{$empresa[0][2]}' width='100px' height='100px'></td><td style='border-right: 1px solid orange; color:  orange; font-weight: bold; font-size: 1.4em;'>Sistema de Gestion   </td><td> Direccion Formacion Profesional</td>
                                            </tr>
                                            <tr>
                                                <td style='border-right: 1px solid orange'>De Informacion - DFP   </td><td style='color:  orange; font-weight: bold; font-size: 1.4em;'> www.sena.edu.co</td>
                                            </tr>       
                                        </table>        
                                    </div>
                                </body>", 
                            "USUARIO CREADO {$empresa[0][1]}");                
            } 
            else
            {
                print_r($errores);
            }
        }
        elseif (!isset($accionU))
        {
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