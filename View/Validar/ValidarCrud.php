<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

require_once dirname(__FILE__) . "/../../autoload.php";

date_default_timezone_set("America/Bogota");
$fecha = date("YmdHis");
$fecha_Validar = date("Y-m-d H:i:s");
$menu1 = "";
$menu2 = "";
$time = time()+(60*60*24*30);

foreach ($_POST as $key => $value)
    ${$key} = $value;

if (isset($accion) && $accion == 'INICIAR') {
    $persona = new Persona(" (identificacion  ", " '$usuario') or (correoinstitucional = '" . strtoupper($usuario) . "') ");
    $npass = md5($pass);
    if ($persona->getId() != ''  && password_verify($npass, $persona->getPassword() ) ) {
        //create table empresa (id_empresa serial primary key ,nombre varchar(500) , icono varchar(200) ,token1 varchar(500),token2 varchar(500));
        $empresa = ConectorBD::ejecutarQuery("select id_empresa,nombre,icono,token1,token2 from empresa where nombre = '$MiEmpresa' ", null);
        if( count($empresa) == 1 && $empresa[0][0] == 1 )
        {
            $_SESSION['MiEmpresa'] = $empresa[0][1];
            $_SESSION['Icon'] = $empresa[0][2];
            $session = new Sesion(' identificacion ', "'{$persona->getId()}'");
            if( $session->getId_sesion() == '' )
            {
                $session->setFecha('now()');
                $session->setToken1('');
                $session->setToken2('');
                $session->setToken3('');
                $session->setEstado('A');
                $session->setIdentificacion($persona->getIdentificacion());
                $session->grabar();
                $activo = true;
                $_SESSION['ultima_sesion'] = 'PRIMERA SESION '.$fecha_Validar;
            }
            else
            {
                if($session->getEstado() == 'I' )
                {
                    $mensaje_sesion = "SU SESION ESTA INACTIVA";
                    $activo = false ;
                }
                elseif($session->getEstado() == 'B' )
                {
                    $mensaje_sesion = "SU USUARIO ESTA BLOQUEADO";
                    $activo = false ;
                }
                else if($session->getEstado() == 'A' )
                {
                    $activo = true ;
                    $_SESSION['ultima_sesion'] = 'ULTIMA SESION '.$session->getFecha();
                }
            }
           if($activo == true )
           {
                $_SESSION['sesion'] = $usuario . "-" . date('YmdHis');
                if ($persona->getId() == '1085264709' || $persona->getIdTipo() == 'SA') {
                    $_SESSION['user'] = $persona->getId();
                    setcookie('user', $persona->getId(), $time, '/');
                    $_SESSION['foto'] = $persona->getImagen();
                    $datosM = Menu::datosobjetos(null, 0, 100);
                    for ($j = 0; $j < count($datosM); $j++) {
                        $objet = $datosM[$j];
                        $menu1 .= "<a id='" . str_replace(' ', '_', strtoupper($objet->getNombre() ) ) . "' onclick='action( event , `menua` )' class='menua' href='#" .  str_replace( ' ' , '_' , ucfirst( strtolower( $objet->getNombre() ) ) ) . "' ><i class='{$objet->getIcono()}'>_</i> {$objet->getNombre()}</a>";    
                        $menu2 .= "<a onclick='action( event , `menua` )' href='#" .  str_replace( ' ' , '_' , ucfirst( strtolower( $objet->getNombre() ) ) ) . "' title='" . strtoupper($objet->getNombre()) . "' id='" . str_replace(' ', '_', strtoupper($objet->getNombre() ) ) . "' onmouseover='hover(event)' onmouseout='nohover(event)' class='menua' ><pre>" . strtoupper($objet->getNombre()) . "  <i class='{$objet->getIcono()}'></i>  </pre></a>";
                    }
                    $_SESSION['sede'] = '';
                    $_SESSION['banner'] = 'http://dfp.senaedu.edu.co/modulos_gestion/img/banner2.jpg';
                    $_SESSION['rol'] = 'SA';
                    setcookie('rol', 'SA', $time, '/');
                    $_SESSION['miMenu1'] = $menu1;
                    $_SESSION['miMenu2'] = $menu2;
                    print_r(json_encode( array("user" => $persona->getId(), "correo" => "{$persona->getCorreo()}", "empresa" => "{$empresa[0][1]}", "token1" => "{$empresa[0][3]}", "token2" => "{$empresa[0][4]}" ) ) );
                } else {
                    $_SESSION['banner'] = 'http://dfp.senaedu.edu.co/modulos_gestion/img/banner2.jpg';
                    $_SESSION['foto'] = $persona->getImagen();
                    $_SESSION['user'] = $persona->getId();
                    setcookie('user', $persona->getId(), $time, '/');
                    $_SESSION['rol'] = $persona->getIdTipo();
                    setcookie('rol', $persona->getIdTipo(), $time, '/');
                    $_SESSION['sede'] = $persona->getidsede();
                    $cortarMenu = ConectorBD::ejecutarQuery("select menu from personamenu where identificacion='{$persona->getId()}';", null);
                    $cadenaCorte = explode("<|", $cortarMenu[0][0]);
                    for ($l = 0; $l < count($cadenaCorte)-1; $l++) {
                        $objet = new Menu('id', $cadenaCorte[$l]);
                        $menu1 .= "<a id='" . str_replace(' ', '_', strtoupper($objet->getNombre() ) ) . "'  onclick='action( event , `menua` )' class='menua' href='#". str_replace( ' ' , '_' , ucfirst( strtolower( $objet->getNombre() ) ) ) . "' ><i class='{$objet->getIcono()}'>_</i> {$objet->getNombre()}</a>";    
                        $menu2 .= "<a onclick='action( event , `menua` )' href='#". str_replace( ' ' , '_' , ucfirst( strtolower( $objet->getNombre() ) ) ) . "' title='" . strtoupper($objet->getNombre()) . "' id='" . str_replace(' ', '_', strtoupper($objet->getNombre() ) ) . "' onmouseover='hover(event)' onmouseout='nohover(event)' class='menua' ><pre>" . strtoupper($objet->getNombre()) . "  <i class='{$objet->getIcono()}'></i>  </pre></a>";
                    }
                    $_SESSION['miMenu1'] = $menu1;
                    $_SESSION['miMenu2'] = $menu1;
                    print_r(json_encode( array("user" => $persona->getId(), "correo" => "{$persona->getCorreo()}", "empresa" => "{$empresa[0][1]}", "token1" => "{$empresa[0][3]}", "token2" => "{$empresa[0][4]}" ) ) );
                }
            }
            else
            {
                 print_r($mensaje_sesion);
            }
        }
        else
        {
             print_r("HAY MAS DE UNA EMPRESA EN EL SISTEMA");
        }
    } else {
       session_unset();
       session_destroy();
       setcookie('user', '', time()-1, '/');
       setcookie('rol', '', time()-1, '/');
       setcookie('token1', '', time()-1, '/');
       setcookie('token2', '', time()-1, '/');
       print_r("USUARIO O CONTRASEÑA INCORRECTA");
    }
} elseif (isset($accion) && $accion == 'SALIR') {
    session_unset();
    session_destroy();
    //DONDE
}
else
{
    print_r('Algo Salio Mal');
}
