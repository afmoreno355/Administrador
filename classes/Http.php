<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of http
 *
 * @author FELIP
 */
class Http {

    //put your code here

    function __construct() {
        
    }

    public static function url() {
        $menu[] = array('URL' => "inicio#MI_USUARIO", 'DONDE' => 'View/Usuario/UsuarioTabla.php', 'NOMBRE' => 'MI_USUARIO');
        $menu[] = array('URL' => "inicio#PROGRAMA", 'DONDE' => 'View/Programa/ProgramaTabla.php', 'NOMBRE' => 'PROGRAMA');
        $menu[] = array('URL' => "inicio#SEDE", 'DONDE' => 'View/Sede/SedeTabla.php', 'NOMBRE' => 'SEDE');
        $menu[] = array('URL' => "inicio#REGIONAL", 'DONDE' => 'View/Regional/RegionalTabla.php', 'NOMBRE' => 'REGIONAL');
        $menu[] = array('URL' => "inicio#USUARIOS", 'DONDE' => 'View/Persona/PersonaTabla.php', 'NOMBRE' => 'USUARIOS');
        $menu[] = array('URL' => "inicio#CARGO", 'DONDE' => 'View/Cargo/CargoTabla.php', 'NOMBRE' => 'CARGO');
        
        return json_encode($menu);
    }
    
    public static function encryptIt($q) {
        $qEncoded = base64_encode($q);
        return( $qEncoded );
    }

    public static function decryptIt($q) {
        $qDecoded = base64_decode($q);
        $nuevoArray = [];
        $cortarCadena = explode('&', $qDecoded);
        for ($i = 0; $i < count($cortarCadena); $i++) {
            list($nombre, $valor) = explode("=", $cortarCadena[$i]);
            $nuevoArray += [$nombre => $valor];
        }
        return $nuevoArray;
    }
    
    public static function permisos($user , $rol , $url) {
        if( $user == $_SESSION['user'] )
        {
            if( $rol == $_SESSION['rol'])
            {

                if( $rol !== 'SA' )
                {
                    $persona_Menus = new Personamenu( 'identificacion' , "'$user'" );
                    if( !empty($persona_Menus) )
                    {
                        $menus_Permisos = explode( '<|' , $persona_Menus->getMenu() );
                        if(count($menus_Permisos) > 0)
                        {
                            foreach ($menus_Permisos as $key => $value) 
                            {
                                if( (new Menu( ' id ' , $value ))->getNombre() === $url )
                                {
                                    return true;
                                }
                            }
                        }  
                    }
                }
                elseif ( $rol === 'SA' ) 
                {
                     return true;
                }
            }
        }
        return false;
    }
    
    public static function js() {
        $javascript = '' ;
        $JS = scandir(dirname(__FILE__).'/../../js');
        for ($i = 0; $i < count($JS); $i++) {
            if( $JS[$i] !== '.'  && $JS[$i] !== '..'  )
            {
                $javascript .= "<script src='js/{$JS[$i]}'></script>";
            }
            
        }
        return $javascript;
    }
}
