<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Select
 *
 * @author FELIPE
 */
class Select {
    //put your code here
    public static function listaopciones( $id , $select = '' , $query = null ){ 
        $lista='<option value="" > OPCIONES </option>';
        $seleccion='';
        $si = self::listas( $id , $query );
        for ($i = 0; $i < count($si); $i++) {
            if( $si[$i][0]==$select && $select != '' )
            {
                $seleccion='selected';
            }
            else
            {
                $seleccion='';
            }
            $lista.="<option value='{$si[$i][0]}' $seleccion> {$si[$i][1]} </option>";
        }
    return $lista;
    } 
     public static function listas( $id , $query = null ){
        switch ( $id )
        {
            case 1 :
                return ConectorBD::ejecutarQuery( $query , null ) ;
            break; 
            case 2 :
                return ConectorBD::ejecutarQuery( $query , null ) ; //tenía eagle_admin
            break; 
            case 3 :
                return array( array('1' , 'A DISTANCIA') ,
                              array('2' , 'PRESENCIAL') 
                    ) ;
            break; 
            case 4 :
                $tipo = array() ;
                if( trim( $query ) == 'ABIERTA' || trim( $query ) == ''  )
                {
                     array_push($tipo , array('ABIERTA DE FORMACION' , 'ABIERTA DE FORMACION') ) ;
                }
                if( trim( $query ) == 'ESPECIAL' || trim( $query ) == ''  )
                {
                     array_push($tipo , array('ESPECIAL EMPRESARIAL' , 'ESPECIAL EMPRESARIAL') ) ;
                     array_push($tipo , array('ESPECIAL SOCIAL' , 'ESPECIAL SOCIAL') ) ;                
                }
                return $tipo;
            break; 
            case 5 :
                return array( array('15' , '15 APREDICES') ,
                              array('20' , '20 APREDICES') ,
                              array('25' , '25 APREDICES') ,
                              array('30' , '30 APREDICES') ,
                              array('35' , '35 APREDICES') 
                    ) ;
            break; 
            case 6 :
                return array( array('s' , 'SI') ,
                              array('n' , 'NO') 
                    ) ;
            break; 
            case 7 :
                date_default_timezone_set('America/Bogota');
            
                $month= date('m', time());
                $months = array() ;

                if( ( $month >= 1 && $month <= 3 && !is_numeric( $query ) ) || ( trim( $query ) != '' && trim( $query ) == 'T' ) || ( is_numeric( $query ) && $query == 1 ) )
                {
                     array_push($months , array('1' , 'ENERO') ) ;
                     array_push($months , array('2' , 'FEBRERO') ) ;
                     array_push($months , array('3' , 'MARZO') ) ;
                }
                if( ( $month >= 4 && $month <= 6 && !is_numeric( $query ) ) || ( trim( $query ) != '' && trim( $query ) == 'T' ) || ( is_numeric( $query ) && $query == 2 ) )
                {
                    array_push($months , array('4' , 'ABRIL') ) ;
                    array_push($months , array('5' , 'MAYO') ) ;
                    array_push($months , array('6' , 'JUNIO') ) ;
                }
                if( ( $month >= 7 && $month <= 9 && !is_numeric( $query ) ) || ( trim( $query ) != '' && trim( $query ) == 'T' ) || ( is_numeric( $query ) && $query == 3 ) )
                {
                    array_push($months , array('7' , 'JULIO') ) ;
                    array_push($months , array('8' , 'AGOSTO') ) ;
                    array_push($months , array('9' , 'SEPTIEMBRE') ) ;

                }
                if( ( $month >= 10 && $month <= 12 && !is_numeric( $query ) ) || ( trim( $query ) != '' && trim( $query ) == 'T' ) || ( is_numeric( $query ) && $query == 4 ) )
                {
                    array_push($months , array('10' , 'OCTUBRE') ) ;
                    array_push($months , array('11' , 'NOVIEMBRE') ) ;
                    array_push($months , array('12' , 'DICIEMBRE') ) ;
                }
                     
                return $months ;
            break;
            case 8 :
                return array( array('MADRUGADA' , 'MADRUGADA') ,
                              array('DIURNA' , 'DIURNA') , 
                              array('NOCTURNA' , 'NOCTURNA') ,
                              array('MIXTA' , 'MIXTA') 
                    ) ;
            break; 
            case 9 :
                return ConectorBD::ejecutarQuery( $query , 'registro' ) ;
            break;
            case 10 :
                return array( array('si' , 'SI') ,
                              array('no' , 'NO') 
                    ) ;
            break;
            case 11 :
                return array( array('A' , 'ACTIVO') ,
                              array('I' , 'INACTIVO') 
                    ) ;
            break;
            case 12 :
                return array( array('1trimestre' , 'PRIMER TRIMESTRE') ,
                              array('2trimestre' , 'SEGUNDO TRIMESTRE') ,
                              array('3trimestre' , 'TERCER TRIMESTRE') ,
                              array('4trimestre' , 'CUARTO TRIMESTRE') ,
                              array('5trimestre' , 'OFERTA ESPECIAL') 
                    ) ;
            break;
        }
    } 
    public static function validar( $variable /*VARIABLE QUE LLEGA*/ ,
                                    $tipo /*TIPO DE VARIABLE QUE LLEGA*/ , 
                                    $tamanio = 250 /*TAMAÑO DEL TEXTO LLEGA*/,
                                    $nombre /*NOMBRE PARA DAR RESPUESTA*/ , 
                                    $array = null /*SI NO ES NULO BUSCA EN EL ARRAY DE ESTE ARCHIVO O COMODIN PARA COMPARAR*/,
                                    $bd = '' /*BASE DE DATOS DE BUSQUEDA*/,
                                    $tabla = '' /*SI NO ES NULO BUSCA EN EL ARRAY DE ESTE ARCHIVO*/) 
    {
        switch (strtoupper( $tipo ) )
        {
            case 'TEXT' ;
                if( $variable  != '' && strlen( $variable ) <= $tamanio )
                {
                    return true;
                }
                else
                {
                    print_r(strtoupper( "  ERROR EN EL CAMPO $nombre NO DEBE ESTAR VACIO Y MINIMO DE CARACTERES $tamanio " ) );
                    return false;
                }
            break;    
            case 'NUMERIC' ;
                if( is_numeric( $variable ) )
                {
                    return true;
                }
                else
                {
                    print_r(strtoupper( "  ERROR EN EL CAMPO $nombre DEBE SER NUMERICO " ) );
                    return false;
                }
            break;    
            case 'ARRAY' ;
                if( $variable != '' || is_array( $variable ) ) 
                {
                    if( $variable != '' && !is_array( $variable ) && $bd === '' ) 
                    {
                        $lista = self::listas( $array ) ;
                        for ($i = 0; $i < count($lista); $i++) 
                        {
                            if( $lista[$i][0] == $variable )
                            {
                                return true;
                            }
                        }
                    }
                    elseif( $variable != '' && !is_array( $variable ) && $bd !== '' )
                    {
                        if ( !empty( ConectorBD::ejecutarQuery( " select  * from $tabla where $array" , $bd ) ) )
                        {
                            return true ;
                        }
                        else 
                        {
                            print_r(strtoupper( "  ERROR EN EL CAMPO $nombre NO EXISTE " ) );
                            return false ;
                        }       
                    }
                }
                else
                {
                    print_r(strtoupper( "  ERROR EN EL CAMPO $nombre VALIDAR INFORMACION " ) );
                    return false;
                }
            break;    
            case 'DATE' ;
                if( $variable != '' )
                {
                    try  
                    {
                        list( $anio , $mes , $dia ) = explode( '-' , $variable ) ;
                        if( strlen($anio) == 4 && strlen($mes) <= 2 && strlen($dia) <= 2 )
                        {
                            if( $variable >= $array )
                            {
                                return true;
                            }
                            else  
                            {
                                print_r(strtoupper( "  ERROR EN EL CAMPO $nombre VALIDAR INFORMACION " ) );
                                return false;
                            }
                        }
                        else  
                        {
                            print_r(strtoupper( "  ERROR EN EL CAMPO $nombre VALIDAR INFORMACION " ) );
                            return false;
                        }
                    }
                    catch ( Exception $exc ) 
                    {
                        print_r(strtoupper( "  ERROR EN EL CAMPO $nombre VALIDAR INFORMACION " ) );
                        return false;
                    }
                }
                else  
                {
                    print_r(strtoupper( "  ERROR EN EL CAMPO $nombre VALIDAR INFORMACION " ) );
                    return false;
                }
            break;    
            case 'FILE' ;
                if( isset( $variable ) && $variable['name'] != '' )
                {
                    if( pathinfo(strtoupper($variable['name']), PATHINFO_EXTENSION) == strtoupper($array) ) 
                    {
                        return true;
                    }
                    else 
                    {
                        print_r(strtoupper( "  ERROR EN EL CAMPO $nombre EXTENCION DEL ARCHIVO NO CORRESPONDE " ) );
                        return false;
                    }
                }
                else
                {
                    print_r(strtoupper( "  ERROR EN EL CAMPO $nombre SE ENCIUENTRA SIN INFORMACION" ) );
                    return false ;
                }    
            break;    
        }        
    }
    
    public static function consultaId( $identificador , $tabla , $datos , $db )
    {
        $id = ConectorBD::ejecutarQuery( " select $identificador from $tabla where $datos " , $db )[0][0] ;
        return $id;
    }
}