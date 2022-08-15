<?php

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        use PHPMailer\PHPMailer\SMTP;

        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        include "credenciales.php";
        include "conn.php";

        echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n"; 
        $nombre     =  "refu";//$_POST['nombre'];
        $correo     =  "joserefugioriveramendoza@gmail.com";//$_POST['correo'];
        $mensaje    =  "Hola";//$_POST['mensaje'];
        $token = "wwwwww";//bin2hex(openssl_random_pseudo_bytes(16));

        // $directorio = "../img/";

        // if (!is_dir($directorio)) {
        //     mkdir($directorio, 0755,true);
        // }

        // if (move_uploaded_file($_FILES['foto']['tmp_name'],$directorio . $_FILES['foto']['name'])) {
        //     $imagen_url = $_FILES['foto']['name'];
        // } else {
        //     $respuesta = array(
        //         'respuesta' => error_get_last()
        //     );
        // }

        $mail = new PHPMailer();

        // ionos hosting mail data
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   
        //$mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.office365.com";
        $mail->Port = 587;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        $mail->Username = $correoserv; //Correo de donde enviaremos los correos
        $mail->Password = $passserv; // Password de la cuenta de envío
        
        $mail->setFrom($correoserv, 'nombre');
    
        $mail->addAddress($correo);
    
        $message  = "<html><body>";
    
        $message .= "<p>Hola mi nombre es <b>$nombre</b> el motivo de mi mensaje es el siguiente: <br> $mensaje </p><br/>";

        $message .="<a href='http://localhost/envio_correos-main/includes/verify-email.php?tokenver=".$token."'>Click Aqui para verificar email.</a>";
        
        $message .= "</body></html>";
        
        // HTML email ends here
    
        //$mail->addAttachment($directorio . $_FILES['foto']['name'], $imagen_url);
    
        $mail->Subject = "mensaje";
        $mail->Body    = $message;
        $mail->AltBody    = "mensaje";
    
        //send the message, check for errors
        if (!$mail->send()) {
            $respuesta = array(
                'response'=>'error',
                'message' => $mail->ErrorInfo
            );
        } else {
            

            try{
                $stmt=$conn->prepare("INSERT INTO verify (email,token) VALUES (?,?)");
                $stmt->bind_param("ss",$correo,$token);
                $stmt->execute();
                $respuesta = array(
                    'response' => 'ok',
                    'message'=>'El correo para '.$correo.', fue enviado con exito.'
                );
            }catch(Exception $e){
                $respuesta = array(
                    'response'=>'error',
                    'message' => $e
                );
            }
        }


        // codificamos la respuesta en formato JSON para atender con petición AJAX
        die(json_encode($respuesta));
?>