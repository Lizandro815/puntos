<?php
/* 
Se encarga de enviar los correos electronicos
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    function enviarEmail($email, $asunto, $cuerpo)
    {
        require_once __DIR__ . '/../config/config_mailer.php';
        require_once __DIR__ . '/../../public/phpmailer/src/Exception.php';
        require_once __DIR__ . '/../../public/phpmailer/src/PHPMailer.php';
        require_once __DIR__ . '/../../public/phpmailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP(); //Enviar usando SMTP
            $mail->Host = MAIL_HOST; //Configura el servidor SMTP para enviar
            $mail->SMTPAuth = true; //Habilitar autenticación SMTP
            $mail->Username = MAIL_USER; //nombre de usuario SMTP
            $mail->Password = MAIL_PASS; //Contraseña SMTP
            $mail->SMTPSecure = 'ssl';  //Habilitar cifrado TLS implícito //ENCRYPTION_SMTPS
            $mail->Port = MAIL_PORT; //Puerto TCP al que conectarse; use 587 si ha configurado `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->isHTML(true); //Establecer formato de correo electrónico en HTML   
            //correo emisor
            $mail->setFrom(MAIL_USER, 'fidelizacion');
            //corro receptor o destino
            $mail->addAddress($email);

            //Contenido
            $mail->Subject = $asunto; //titulo del correo

            $mail->Body = $cuerpo;

            $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');
            $mail->send(); //envia el correo


        } catch (Exception $e) {
            echo "Error al enviar el correo electronico con lo detalles de la compra: {$mail->ErrorInfo}";
        }
    }
}
