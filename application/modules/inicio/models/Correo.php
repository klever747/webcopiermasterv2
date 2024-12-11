<?php



defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require  'assets/php-mailer/Exception.php';
require 'assets/php-mailer/PHPMailer.php';
require 'assets/php-mailer/SMTP.php';
class Correo extends My_Model {

    function __construct() {
        parent::__construct();
    }

    public function sendEmail($datos_correo = false){
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';  //'ssl://smtp.hostinger.com'                   //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'kleverpardo747@gmail.com'; //'info@copiermastercyg.com.ec';                    //SMTP username
            $mail->Password   = 'rifoelcxykhcbggk';//'Homero@69';                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($datos_correo['correo'], $datos_correo['nombre']);
            $mail->addAddress('copiermastersd@gmail.com', 'COPIERMASTER');     //Add a recipient
            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Correo Copiermaster';
            $mail->Body    = '<div>
                Hola que tal mi nombre es '.$datos_correo['nombre'].'
                <p>Soy de la ciudad de '.$datos_correo['ciudad'].'</p>
                <p>Mi número de telefono es: '.$datos_correo['telefono'].'</p>
                <p>Pertenesco a la empresa '.$datos_correo['empresa'].'</p>
                <p>'.$datos_correo['comentarios'].'</p>
            </div>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
           // $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
  
}
