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

date_default_timezone_set("America/Bogota");
$fecha = date("YmdHis");
$fecha_documento = date("Y-m-d H:i:s");
$anio_documento = date("Y");
$mes_documento = date("m");

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
        if ($accion == "METAS")
        {
            
            $nuevoNombreCopy= "F:/wamp64/www/Virtual/Archivos/METAS$fecha.csv";
            $anioFin = $anio_envio;
            ConectorBD::ejecutarQuery( " delete from meta where anio = '$anio_envio' " , null ) ;
            if( copy( $_FILES['documento']['tmp_name'] , $nuevoNombreCopy ) )
            {
            $contador=1;
                if (($gestor = fopen($nuevoNombreCopy, "r")) !== FALSE)
                {
                    while (($nuevoNombre3 = fgetcsv($gestor, 0, ";")) !== FALSE) 
                    {
                        if($contador>=5)
                        {
                            if($contador==5 && trim(strtoupper($nuevoNombre3[1]))=='REGIONAL' && trim(strtoupper($nuevoNombre3[3]))=='CENTRO' && trim(strtoupper($nuevoNombre3[4]))=='EDUCACION SUPERIOR'){
                                $validar=true;
                            } 
                            if( $contador>=8 && $validar==true )
                            {
                                //especializacion t presencial
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz) from pe04 where sede='$nuevoNombre3[2]' and tipo = 'ESPECIALIZACION TECNOLOGICA' and (modalidad = 'PRESENCIAL' OR modalidad = 'A DISTANCIA') and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO' ) and fecha_fin>='NOW()'  group by tipo",null)))
                                {
                                   $valorActivosETP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosETP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[5]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','ESPECIALIZACION_TECNOLOGICA_PRESENCIAL',$valorActivosETP,$replace,0,'{$nuevoNombre3[2]}')", null);
                               
                                //especializacion t virtual
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz),  modalidad from pe04 where sede='$nuevoNombre3[2]' and tipo = 'ESPECIALIZACION TECNOLOGICA' and modalidad = 'VIRTUAL' and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO' ) and fecha_fin>='NOW()' group by tipo, modalidad order by modalidad asc;",null)))
                                {
                                   $valorActivosETV=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosETV=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[7]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','ESPECIALIZACION_TECNOLOGICA_VIRTUAL',$valorActivosETP,$replace,0,'{$nuevoNombre3[2]}')", null);
                            
                                //tecnologia presencial
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz) from pe04 where sede='$nuevoNombre3[2]' and tipo = 'TECNOLOGO' and (modalidad = 'PRESENCIAL' OR modalidad = 'A DISTANCIA') and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO' ) and fecha_fin>='NOW()' group by tipo",null)))
                                {
                                   $valorActivosTecP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosTecP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[11]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','TECNOLOGO_PRESENCIAL',$valorActivosTecP,$replace,0,'{$nuevoNombre3[2]}')", null);
                            
                                 //tecnologia virtual
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz),  modalidad from pe04 where sede='$nuevoNombre3[2]' and tipo = 'TECNOLOGO' and modalidad = 'VIRTUAL'  and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO' ) and fecha_fin>='NOW()' group by tipo, modalidad order by modalidad asc;",null)))
                                {
                                   $valorActivosTecP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosTecP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[13]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','TECNOLOGO_VIRTUAL',$valorActivosTecP,$replace,0,'{$nuevoNombre3[2]}')", null);
                            
                                 //operario
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz) from pe04 where sede='$nuevoNombre3[2]' and tipo = 'OPERARIO'   and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO' ) and fecha_fin>='NOW()' group by tipo;",null)))
                                {
                                   $valorActivosTecP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosTecP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[19]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','OPERARIO_PRESENCIAL',$valorActivosTecP,$replace,0,'{$nuevoNombre3[2]}')", null);
                                
                                 //auxiliar
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz) from pe04 where sede='{$nuevoNombre3[2]}' and tipo = 'AUXILIAR'  and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO') and fecha_fin>='NOW()' group by tipo ;",null)))
                                {
                                   $valorActivosTecP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosTecP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[21]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','AUXILIAR_PRESENCIAL',$valorActivosTecP,$replace,0,'{$nuevoNombre3[2]}')", null);
                                
                                 //tecnico presencial
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz) from pe04 where sede='$nuevoNombre3[2]' and tipo = 'TECNICO' and (modalidad = 'PRESENCIAL' OR modalidad = 'A DISTANCIA') and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO' ) and fecha_fin>='NOW()' group by tipo",null)))
                                {
                                   $valorActivosTecP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosTecP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[25]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','TECNICO_LABORAL',$valorActivosTecP,$replace,0,'{$nuevoNombre3[2]}')", null);
                                
                                 //tecnico virtual
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz) from pe04 where sede='$nuevoNombre3[2]' and tipo = 'TECNICO' and (modalidad = 'VIRTUAL') and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO' ) and fecha_fin>='NOW()' group by tipo",null)))
                                {
                                   $valorActivosTecP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosTecP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[27]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','TECNICO_LABORAL_VIRTUAL',$valorActivosTecP,$replace,0,'{$nuevoNombre3[2]}')", null);
                                
                                 //tecnico ampliacion
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz) from pe04 where sede='$nuevoNombre3[2]' and tipo = 'TECNICO'  and programa_especial  in ('INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA') and fecha_fin>='NOW()' group by tipo ;",null)))
                                {
                                   $valorActivosTecP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosTecP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[29]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','TECNICO_INTEGRACION',$valorActivosTecP,$replace,0,'{$nuevoNombre3[2]}')", null);
                                
                                //MIPYMES-PND
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), programa_especial, SUM(total_aprendiz) from pe04 where sede='$nuevoNombre3[2]'  and  programa_especial IN('FORMACION ESPECIAL MIPYMES-PND') and fecha_fin>='NOW()' group by  programa_especial",null)))
                                {
                                   $valorActivosTecP=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosTecP=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[49]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','FORMACION_ESPECIAL_MIPYMES-PNDE',$valorActivosTecP,$replace,0,'{$nuevoNombre3[2]}')", null);
                                
                                if(!empty($cursos_esp = ConectorBD::ejecutarQuery("select count(sede), tipo, SUM(total_aprendiz),  modalidad from pe04 where sede='$nuevoNombre3[2]' and tipo = 'PROFUNDIZACION TECNICA' and programa_especial not in ( 'PROGRAMA DE BILINGUISMO' , 'INTEGRACION CON LA EDUCACION MEDIA GRADO 9' , 'INTEGRACION CON LA EDUCACION MEDIA TECNICA' , 'INTEGRACION CON LA EDUCACION MEDIA ACADEMICA' , 'FORMACION ESTRATEGIA DUAL' , 'FORMACION ESPECIAL MIPYMES-PND' , 'FORMACION COMPLEMENTARIA' , 'AMPLIACION DE COBERTURA' , 'SER' , 'SER POSCONFLICTO' ) and fecha_fin>='NOW()' group by tipo, modalidad order by modalidad asc;",null)))
                                {
                                   $valorActivosETV=$cursos_esp[0][2];
                                }
                                else
                                {
                                    $valorActivosETV=0;
                                }
                                $replace= str_replace(".", "", $nuevoNombre3[33]);
                                if(!is_numeric($replace))
                                {
                                    $replace=0;
                                }
                                ConectorBD::ejecutarQuery("insert into meta(anio, nombre_tipo, aprediz_activo, meta_concertada, meta_nacional, sede) values('$anioFin','PROFUNDIZACION_TECNICA',$valorActivosETP,$replace,0,'{$nuevoNombre3[2]}')", null);
                            
                            }    
                        }   
                        $contador++;
                    }
                    fclose($gestor);
                }    
                print_r(" Se ha cargado en el modulo , METAS actualizadas ");
                unlink($nuevoNombreCopy) ;   
            }
            
            
        }
        else if ($accion == "PE04")
        {
            
            $nuevoNombreCopy= "F:/wamp64/www/Virtual/Archivos/PE04$fecha.csv";
            if( copy( $_FILES['documento']['tmp_name'] , $nuevoNombreCopy ) )
            {
                if( ConectorBD::ejecutarQuery( " copy public.pe04 from '$nuevoNombreCopy' DELIMITER ';' CSV HEADER ;  " , null ) )
                {
                   print_r( "Se ha cargado en el modulo , PE04 actualizado "   ) ;
                }
                unlink($nuevoNombreCopy) ; 
            }
        }
    }
}
