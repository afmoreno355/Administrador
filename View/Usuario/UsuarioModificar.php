<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// require auntomatico encuentra todas las clases/Model qeu se solicitan en el Controlador
require_once dirname(__FILE__) . '/../../classes/Persona.php';
require_once dirname(__FILE__) . '/../../classes/Sesion.php';
require_once dirname(__FILE__) . '/../../classes/ConectorBD.php';
require_once dirname(__FILE__) . '/../../classes/Historico.php';
require_once dirname(__FILE__) . '/../../classes/PersonaMenu.php';

// Iniciamos sesion para tener las variables

date_default_timezone_set("America/Bogota");
$fecha = date('YmdHis');
$fecha_Campania = date('Y-m-d H:i:s');
$filascount = 0;
$arraycount = 0;
$comodin = '';
$lista_Titulo = '';
$errores = '';
$lista = '';
$array_session = [];

// variable variable trae las variables que trae POST
foreach ($_POST as $key => $value)
    ${$key} = $value;

if( !isset($_SESSION['user']) )
{
    session_start();
}    
$session = new Sesion(' identificacion ', "'{$_SESSION['user']}'");
$token1 = $session->getToken1();
$token2 = $session->getToken2();

if ($_SESSION["token1"] !== $_COOKIE["token1"] && $_SESSION["token2"] !== $_COOKIE["token2"]) {
    print_r("NO TIENE PERMISO PARA REALIZAR ESTA ACCION");
    //header("Location: index");
} elseif ($_SESSION["token1"] === $_COOKIE["token1"] && $_SESSION["token2"] === $_COOKIE["token2"] && password_verify( md5( $token1 . $token2 ), $session->getToken3() ) ) {
    if (isset($accionU)) {
        $persona = new Persona(null, null);
        if ($accionU == 'ADICIONAR' || $accionU == 'MODIFICAR') {
            if ($accionU == 'ADICIONAR')
            {
                $persona->setId(trim($id));
                $persona->setNombre(strtoupper(trim($nombre)));
                $persona->setApellido(strtoupper(trim($apellido)));
                $persona->setTel(trim($telefono));
                $persona->setCorreo(strtoupper(trim($email)));
                $persona->setCelular(trim($celular));
                $persona->setidsede(trim($sede));
                $persona->setIdTipo(trim($perfil));
                $persona->setJefeACargo('1085264553');
                $persona->setDependencia($dependencia);  
                $persona->setPassword(password_hash(md5(trim($id)), PASSWORD_DEFAULT, ['cost'=> 12]));
                $persona->setImagen('img/defecto/persona.jpg');
                if($persona->grabar())
                {
                    include_once 'Recuperar.php'; 
                    mailer("{$persona->getCorreo()}",
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
                                                                <td rowspan='3' ><img src='https://ci6.googleusercontent.com/proxy/w5tmDxdrznIEn-19skJuYtYPgr31tuYkJUzpvzKVtG2lt_Jlvc17aieJrsOH5MjTEu4efJwsZuAdNWAbultq0IOQjBgHAxCiAg=s0-d-e1-ft#http://dfp.senaedu.edu.co/indicativa/img/logo/sena.png' width='100px' height='100px'></td><td style='border-right: 1px solid orange; color:  orange; font-weight: bold; font-size: 1.4em;'>Sistema de Gestion   </td><td> Direccion Formacion Profesional</td>
                                                        </tr>
                                                        <tr>
                                                                <td style='border-right: 1px solid orange'>De Informacion - DFP   </td><td style='color:  orange; font-weight: bold; font-size: 1.4em;'> www.sena.edu.co</td>
                                                        </tr>       
                                        </table>        
                                </div>
                            </body>", 
                            'USUARIO CREADO SGI-DFP');
                    $_SESSION['aviso']="EL USUARIO CON CEDULA Y CORREO ".trim($id)."  ".strtoupper(trim($email))." FUE AGREGADO CON EXITO"; 
                    $menuAcc=new PersonaMenu(null,null);
                    if($persona->getIdTipo()=='SA'){           
                        $string='1<|2<|3<|4<|8<|';
                    } elseif($persona->getIdTipo()=='A'){  
                        $string='1<|3<|4<|';    
                    } elseif($persona->getIdTipo()=='RC'){  
                        $string='1<|2<|3<|';    
                    } elseif ($persona->getIdTipo()=='GR') {
                        $string='1<|3<|';    
                    } elseif ($persona->getIdTipo()=='AI' || $persona->getIdTipo()=='AV') {
                        $string='1<|2<|3<|4<|';    
                    } elseif ($persona->getIdTipo()=='GI') {
                        $string='1<|3<|4<|';    
                    } elseif ($persona->getIdTipo()=='IR') {
                        $string='1<|3<|4<|';    
                    } elseif ($persona->getIdTipo()=='VI') {
                        $string='1<|3<|4<|';    
                    }  elseif ($persona->getIdTipo()=='CO') {
                        $string='1<|5<|';    
                    }  elseif ($persona->getIdTipo()=='SC') {
                        $string='1<|2<|5<|';    
                    }  elseif ($persona->getIdTipo()=='AC') {
                        $string='1<|6<|';    
                    }  elseif ($persona->getIdTipo()=='AA') {
                        $string='1<|2<|6<|';    
                    }  elseif ($persona->getIdTipo()=='RJ' || $persona->getIdTipo()=='RT' || $persona->getIdTipo()=='Ra' || $persona->getIdTipo()=='VA' || $persona->getIdTipo()=='AS') {
                        $string='1<|6<|'; 
                    }  elseif ($persona->getIdTipo()=='CA') {
                        $string='1<|2<|9<|';    
                    }  elseif ($persona->getIdTipo()=='RA' || $persona->getIdTipo()=='RS' || $persona->getIdTipo()=='CD' || $persona->getIdTipo()=='VC' || $persona->getIdTipo()=='VB' || $persona->getIdTipo()=='RM' ) {
                        $string='1<|9<|';  
                    }  elseif ($persona->getIdTipo()=='AP' ) {
                        $string='1<|10<|';    
                    }  elseif ($persona->getIdTipo()=='Cc' || $persona->getIdTipo()=='Rc' || $persona->getIdTipo()=='Ca' ) {
                        $string='1<|11<|';    
                    }  elseif ($persona->getIdTipo()=='Ua' || $persona->getIdTipo()=='Aa' ) {
                        $string='1<|12<|';    
                    }  
                    $menuAcc->setIdentificacion(trim($id));
                    $menuAcc->setMenu($string);        
                    $menuAcc->grabar();
                }
                else
                {
                  $_SESSION['aviso']="EL USUARIO CON CEDULA Y CORREO ".trim($id)."  ".strtoupper(trim($email))." NO SE PUDO AGREGAR"; 
                }
                header("location: $donde");
            }elseif ( 'MODIFICAR' )
            {
                $persona=new Persona(null,null);
                $persona->setId(trim($id));
                $persona->setNombre(strtoupper(trim($nombre)));
                $persona->setApellido(strtoupper(trim($apellido)));
                $persona->setTel($telefono);
                $persona->setCorreo(strtoupper(trim($email)));
                $persona->setCelular(trim($celular));
                $persona->setidsede(trim($sede));
                $persona->setIdTipo(trim($perfil));
                $persona->setJefeACargo('10000000');
                $persona->setDependencia($dependencia);  
                if($persona->modificar(trim($identificacion)))
                {
                    include_once 'Recuperar.php'; 
                    mailer("{$persona->getCorreo()}", 
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
                                                                <td rowspan='3' ><img src='https://ci6.googleusercontent.com/proxy/w5tmDxdrznIEn-19skJuYtYPgr31tuYkJUzpvzKVtG2lt_Jlvc17aieJrsOH5MjTEu4efJwsZuAdNWAbultq0IOQjBgHAxCiAg=s0-d-e1-ft#http://dfp.senaedu.edu.co/indicativa/img/logo/sena.png' width='100px' height='100px'></td><td style='border-right: 1px solid orange; color:  orange; font-weight: bold; font-size: 1.4em;'>Sistema de Gestion   </td><td> Direccion Formacion Profesional</td>
                                                        </tr>
                                                        <tr>
                                                                <td style='border-right: 1px solid orange'>De Informacion - DFP   </td><td style='color:  orange; font-weight: bold; font-size: 1.4em;'> www.sena.edu.co</td>
                                                        </tr>       
                                        </table>        
                                </div>
                            </body>", 
                            'USUARIO MODIFICADO SGI-DFP');
                    ConectorBD::ejecutarQuery("delete from personamenu where identificacion='".trim($id)."'", null);
                    $menuAcc=new PersonaMenu(null,null);
                    if($persona->getIdTipo()=='SA'){           
                        $string='1<|2<|3<|4<|8<|';
                    } elseif($persona->getIdTipo()=='A'){  
                        $string='1<|3<|4<|';    
                    } elseif($persona->getIdTipo()=='RC'){  
                        $string='1<|2<|3<|';    
                    } elseif ($persona->getIdTipo()=='GR') {
                        $string='1<|3<|';    
                    } elseif ($persona->getIdTipo()=='AI' || $persona->getIdTipo()=='AV') {
                        $string='1<|2<|3<|4<|';    
                    } elseif ($persona->getIdTipo()=='GI') {
                        $string='1<|3<|4<|';    
                    } elseif ($persona->getIdTipo()=='IR') {
                        $string='1<|3<|4<|';    
                    } elseif ($persona->getIdTipo()=='VI') {
                        $string='1<|3<|4<|';    
                    }  elseif ($persona->getIdTipo()=='CO') {
                        $string='1<|5<|';    
                    }  elseif ($persona->getIdTipo()=='SC') {
                        $string='1<|2<|5<|';    
                    }  elseif ($persona->getIdTipo()=='AC') {
                        $string='1<|6<|';    
                    }  elseif ($persona->getIdTipo()=='AA') {
                        $string='1<|2<|6<|';    
                    }  elseif ($persona->getIdTipo()=='RJ' || $persona->getIdTipo()=='RT' || $persona->getIdTipo()=='Ra' || $persona->getIdTipo()=='VA' || $persona->getIdTipo()=='AS') {
                        $string='1<|6<|';    
                    }  elseif ($persona->getIdTipo()=='CA') {
                        $string='1<|2<|9<|';    
                    }  elseif ($persona->getIdTipo()=='RA' || $persona->getIdTipo()=='RS' || $persona->getIdTipo()=='CD' || $persona->getIdTipo()=='VC' || $persona->getIdTipo()=='VB' || $persona->getIdTipo()=='RM' ) {
                        $string='1<|9<|';  
                    }  elseif ($persona->getIdTipo()=='AP' ) {
                        $string='1<|10<|';    
                    }  elseif ($persona->getIdTipo()=='Cc' || $persona->getIdTipo()=='Rc' || $persona->getIdTipo()=='Ca' ) {
                        $string='1<|11<|';    
                    } elseif ($persona->getIdTipo()=='Ua' || $persona->getIdTipo()=='Aa' ) {
                        $string='1<|12<|';    
                    }   
                    $menuAcc->setIdentificacion(trim($id));
                    $menuAcc->setMenu($string);        
                    $menuAcc->grabar();
                    $_SESSION['aviso']="EL USUARIO CON CEDULA Y CORREO ".trim($id)."  ".strtoupper(trim($email))." FUE MODIFICADO CON EXITO"; 
                }
                else
                {
                     $_SESSION['aviso']="EL USUARIO CON CEDULA Y CORREO ".trim($id)."  ".strtoupper(trim($email))." NO PUDO SER MODIFICADO"; 
                }                
                
                header("location: $donde");
            }         
        } elseif ($accionU == 'BORRAR') {
            $persona=new Persona(null,null); 
            $persona->setId(trim($id));
            if ($persona->borrar()) {
                ConectorBD::ejecutarQuery("delete from personamenu where identificacion='".trim($id)."'", null);
                $_SESSION['aviso']="EL USUARIO CON CEDULA ".trim($id)." FUE ELIMINADO CON EXITO"; 
            } else {
                 $_SESSION['aviso']="EL USUARIO CON CEDULA ".trim($id)." NO SE PUDO ELIMINAR "; 
            }
             header("location: inicio.php?CONTENIDO=View/Usuario/Usuarios.php");
             ob_end_flush();
        }elseif($accionU == 'ADD FOTO'){
            $Persona=new Persona(null,null); 
            $Persona->setId($_SESSION['user']);
            $nombrefoto="img/persona/".$_FILES['Foto']['name'];
            copy($_FILES['Foto']['tmp_name'], $nombrefoto);    
            $Persona->setImagen($nombrefoto); 
            $Persona->modificarImagen();
            $_SESSION['aviso']="EL USUARIO CON CEDULA ".trim($id)." FUE MODIFICADO CON EXITO"; 
            header("location: inicio.php?CONTENIDO=View/Usuario/Usuario.php");
            ob_end_flush();
        }elseif( $accionU == 'PASSWORD'){
            $Persona=new Persona(null,null); 
            $Persona->setId(trim($id));
            $Persona->setPassword(password_hash(md5($rpassword), PASSWORD_DEFAULT, ['cost'=> 12]));
            $Persona->modificarPassword();
            $_SESSION['aviso']="EL USUARIO CON CEDULA ".trim($id)." FUE MODIFICADO CON EXITO"; 
            header("location: inicio.php?CONTENIDO=View/Usuario/Usuario.php");
            ob_end_flush();
        } elseif ($accionU == 'A. PLANOS') {
            if ($column != '') {
                for ($i = 1; $i < count($_SESSION['archivo']); $i++) {
                    if ($identificacion != '' && $identificacion != ' ' && isset($identificacion) && is_numeric($identificacion)) {
                        $persona->setId(trim(strtoupper($_SESSION['archivo'][$i][$identificacion])));
                        $val0 = true;
                    }
                    if ($nombres != '' && isset($nombres) && is_numeric($nombres)) {
                        $persona->setNombre(trim(strtoupper($_SESSION['archivo'][$i][$nombres])));
                        $val1 = true;
                    }
                    if ($apellidos != '' && isset($apellidos) && is_numeric($apellidos)) {
                        $persona->setApellido(trim(strtoupper($_SESSION['archivo'][$i][$apellidos])));
                        $val2 = true;
                    }
                    if ($correo != '' && isset($correo) && is_numeric($correo)) {
                        $persona->setCorreo(trim(strtoupper($_SESSION['archivo'][$i][$correo])));
                        $val3 = true;
                    }
                    if ($celular != '' && isset($celular) && is_numeric($celular)) {
                        $persona->setCelular(strtoupper($_SESSION['archivo'][$i][$celular]));
                        $val4 = true;
                    }
                    if ($telefono != '' && isset($telefono) && is_numeric($telefono)) {
                        $persona->setTel(strtoupper($_SESSION['archivo'][$i][$telefono]));
                        $val5 = true;
                    }
                    if ($sede != '' && isset($sede) && is_numeric($sede)) {
                        $persona->setidsede(trim(strtoupper($_SESSION['archivo'][$i][$sede])));
                        $val6 = true;
                    }
                    if ($rol != '' && isset($rol) && is_numeric($rol)) {
                        $persona->setIdTipo(trim($_SESSION['archivo'][$i][$rol]));
                        $val7 = true;
                    }
                    if (isset($dependencia) ) {
                        $persona->setDependencia(trim(strtoupper($_SESSION['archivo'][$i][$dependencia])));
                        $val8 = true;
                    }
                    if (isset($val0) && isset($val1) && isset($val2) && isset($val3) && isset($val4) && isset($val5) && isset($val6) && isset($val7) && isset($val8)) {
                        $persona->setImagen('img/defecto/persona.jpg');
                        $persona->setPassword(password_hash(md5(trim($persona->getId())), PASSWORD_DEFAULT, ['cost' => 12]));
                        if ($persona->grabar()) {
                            $bn_todo = true;
                            $menuAcc=new PersonaMenu(null,null);
                            if($persona->getIdTipo()=='SA'){           
                                $string='1<|2<|3<|4<|8<|';
                            } elseif($persona->getIdTipo()=='A'){  
                                $string='1<|3<|4<|';    
                            } elseif($persona->getIdTipo()=='RC'){  
                                $string='1<|2<|3<|';    
                            } elseif ($persona->getIdTipo()=='GR') {
                                $string='1<|3<|';    
                            } elseif ($persona->getIdTipo()=='AI' || $persona->getIdTipo()=='AV') {
                                $string='1<|2<|3<|4<|';    
                            } elseif ($persona->getIdTipo()=='GI') {
                                $string='1<|3<|4<|';    
                            } elseif ($persona->getIdTipo()=='IR') {
                                $string='1<|3<|4<|';    
                            } elseif ($persona->getIdTipo()=='VI') {
                                $string='1<|3<|4<|';    
                            } elseif ($persona->getIdTipo()=='CO') {
                                $string='1<|5<|';    
                            } elseif ($persona->getIdTipo()=='SC') {
                                $string='1<|2<|5<|';    
                            } elseif ($persona->getIdTipo()=='AC') {
                                $string='1<|6<|';    
                            } elseif ($persona->getIdTipo()=='AA') {
                                $string='1<|2<|6<|';    
                            } elseif ($persona->getIdTipo()=='RJ' || $persona->getIdTipo()=='RT' || $persona->getIdTipo()=='Ra' || $persona->getIdTipo()=='VA' || $persona->getIdTipo()=='AS') {
                                $string='1<|6<|';    
                            }elseif ($persona->getIdTipo()=='CA') {
                                $string='1<|2<|9<|';    
                            } elseif ($persona->getIdTipo()=='RA' || $persona->getIdTipo()=='RS' || $persona->getIdTipo()=='CD' || $persona->getIdTipo()=='VC' || $persona->getIdTipo()=='VB' || $persona->getIdTipo()=='RM' ) {
                                $string='1<|9<|';    
                            } elseif ($persona->getIdTipo()=='AP' ) {
                                $string='1<|10<|';    
                            } elseif ($persona->getIdTipo()=='Cc'  || $persona->getIdTipo()=='Rc' || $persona->getIdTipo()=='Ca' ) {
                                $string='1<|11<|';    
                            } elseif ($persona->getIdTipo()=='Ua' || $persona->getIdTipo()=='Aa' ) {
                                $string='1<|12<|';    
                            }   
                                    $menuAcc->setIdentificacion(trim($persona->getId()));
                            $menuAcc->setMenu($string);        
                            $menuAcc->grabar();
                            require_once dirname(__FILE__) ."/../../Recuperar.php"; 
                            mailer("{$persona->getCorreo()}",
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
                                                                <td rowspan='3' ><img src='https://ci6.googleusercontent.com/proxy/w5tmDxdrznIEn-19skJuYtYPgr31tuYkJUzpvzKVtG2lt_Jlvc17aieJrsOH5MjTEu4efJwsZuAdNWAbultq0IOQjBgHAxCiAg=s0-d-e1-ft#http://dfp.senaedu.edu.co/indicativa/img/logo/sena.png' width='100px' height='100px'></td><td style='border-right: 1px solid orange; color:  orange; font-weight: bold; font-size: 1.4em;'>Sistema de Gestion   </td><td> Direccion Formacion Profesional</td>
                                                        </tr>
                                                        <tr>
                                                                <td style='border-right: 1px solid orange'>De Informacion - DFP   </td><td style='color:  orange; font-weight: bold; font-size: 1.4em;'> www.sena.edu.co</td>
                                                        </tr>       
                                        </table>        
                                </div>
                            </body>", 
                                    'USUARIO CREADO SGI-DFP');
                        } else {
                            $errores .= ' ERROR FILA ' . ($i + 1) . '<br>';
                        }
                    }
                }
            }
            if (isset($bn_todo)) {
                print_r("Todo salio bn <3 <br>Archivo guardado<br> $errores");
            } else {
                print_r($errores);
            }
        }
    } elseif (!isset($accionU)) {
        $cero = false;
        $nombrefoto = "F:\wamp64\www\materiales/Archivos/TMP/" . $fecha . $_FILES['personaplano']['name'];
        $url_Foto = "Archivos/TMP/" . $_FILES['personaplano']['name'];
        if (copy($_FILES['personaplano']['tmp_name'], $nombrefoto)) {
            $filascount = 1;
            if (pathinfo(strtoupper($_FILES['personaplano']['name']), PATHINFO_EXTENSION) == 'DOC') {
                $fileHandle = fopen($nombrefoto, "r");
                $line = @fread($fileHandle, filesize($nombrefoto));
                $line = preg_replace("[\n|\r|\n\r]", "-_-" . chr(0x0D), $line);
                $lines = explode(chr(0x0D), $line);
                $outtext = "";
                foreach ($lines as $thisline) {
                    $pos = strpos($thisline, chr(0x00));
                    if (($pos !== FALSE) || (strlen($thisline) == 0)) {
                        
                    } else {
                        $outtext .= $thisline . " ";
                    }
                }
                $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\;\n\-\?\n\%\r\t@\/\_\(\)]/", "", $outtext);
                $nuevoNombre3 = explode("-_-", $outtext);
                if ($separador == '') {
                    if (count(explode(';', $nuevoNombre3[0])) >= 5) {
                        $comodin = ';';
                    } elseif (count(explode(',', $nuevoNombre3[0])) >= 5) {
                        $comodin = ',';
                    } elseif (count(explode('%', $nuevoNombre3[0])) >= 5) {
                        $comodin = '%';
                    } elseif (count(explode('.', $nuevoNombre3[0])) >= 5) {
                        $comodin = '.';
                    }
                } else {
                    $comodin = $separador;
                }

                if ($comodin != '') {
                    for ($i = 0; $i < count($nuevoNombre3); $i++) {
                        $cortar[] = explode($comodin, $nuevoNombre3[$i]);
                        $lista .= "<tr>";
                        for ($j = 0; $j < count($cortar[$i]); $j++) {
                            if (count($cortar[$i]) > 3) {
                                if ($arraycount < count($cortar[$i])) {
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
            } elseif (pathinfo(strtoupper($_FILES['personaplano']['name']), PATHINFO_EXTENSION) == 'CSV') {

                if (($gestor = fopen($nombrefoto, "r")) !== FALSE) {
                    if ($separador == '') {
                        if (count($nuevoNombre3 = fgetcsv($gestor, 0, ";")) >= 5) {
                            $comodin = ';';
                        } elseif (count($nuevoNombre3 = fgetcsv($gestor, 0, ",")) >= 5) {
                            $comodin = ',';
                        } elseif (count($nuevoNombre3 = fgetcsv($gestor, 0, "%")) >= 5) {
                            $comodin = '%';
                        } elseif (count($nuevoNombre3 = fgetcsv($gestor, 0, ".")) >= 5) {
                            $comodin = '.';
                        }
                    } else {
                        $comodin = $separador;
                    }
                }
                fclose($gestor);
                if (($gestor = fopen($nombrefoto, "r")) !== FALSE) {
                    if ($comodin != '') {
                        while (($nuevoNombre3 = fgetcsv($gestor, 0, $comodin)) !== FALSE) {
                            $lista .= "<tr>";
                            for ($j = 0; $j < count($nuevoNombre3); $j++) {
                                if (count($nuevoNombre3) > 3) {
                                    if ($arraycount < count($nuevoNombre3)) {
                                        $arraycount = count($nuevoNombre3);
                                    }
                                    if ($cero === false) {
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
            for ($k = 0; $k < $arraycount; $k++) {
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