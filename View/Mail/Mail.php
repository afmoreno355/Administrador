<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

date_default_timezone_set("America/Bogota");
$fecha = date("YmdHis");
$fecha_Mail = date("Y-m-d H:i:s");

$fecha_Inactivo = date("Y-m-d",strtotime($fecha_Mail."- 90 days")); 
$fecha_Bloqueo = date("Y-m-d",strtotime($fecha_Mail."- 120 days")); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once dirname(__FILE__) . "/../../Librerias/src/Exception.php";
require_once dirname(__FILE__) . "/../../Librerias/src/PHPMailer.php";
require_once dirname(__FILE__) . "/../../Librerias/src/SMTP.php";


function mailer($Mail = '' , $MESSAJE = '' , $TITULO = '' )
{
    $empresa = ConectorBD::ejecutarQuery( " select * from empresa; " , null ) ;
    $mail = new PHPMailer(true); 
    try {
      //Server settings
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // smtp.live.com  Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = "{$empresa[0][6]}";                 // SMTP username
        $mail->Password = "{$empresa[0][7]}";                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                      // TCP port to connect to
        //Recipients
        $mail->setFrom( "{$empresa[0][6]}" , " {$empresa[0][1]}");
        if( is_array( $Mail ) )
        {
            //print_r($Mail);
            for($l = 0; $l < count($Mail); $l++)
            {
                if( is_array( $Mail[$l] )  )
                {
                    $mail->addAddress( trim( $Mail[$l][0] ) );
                }
                else
                {
                    $mail->addAddress( trim( $Mail[$l] ) );
                }
            }
        }
        else 
        {
            $mail->addAddress("$Mail");
        }
        //$mail->addAddress("afmoreno355@misena.edu.co");
        /* Attachments
          $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
          $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
       
          //Content */
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Asunto: ' . $TITULO;
        $mail->Body = $MESSAJE;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();

        //echo 'UN MENSAJE FUE ENVIADO AL USUARIO <br>';
    } 
    catch (Exception $e) 
    {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    } 
}
