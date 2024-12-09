<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email,$nombre,$token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];


        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com','uptask.com');

        $mail->Subject = 'Cofirma tu cuenta';
        
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Has Creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace </p>";
        $contenido .= "<p>Presiona aqui: <a href = '".$_ENV['APP_URL']."/confirmar?token=".$this->token."'>Confirmar Cuenta</a></p>";//al hacer deployment se debe cambiae el localhost por el domiio
        $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar Email
        $mail->send();
    }
    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];


        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com','uptask.com');

        $mail->Subject = 'Reestablece tu password';
        
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Sigue el siguiente enlace para reestablecer tu Password </p>";
        $contenido .= "<p>Presiona aqui: <a href ='".$_ENV['APP_URL']."/reestablecer?token=".$this->token."'>Reestablecer Password</a></p>";//al hacer deployment se debe cambiae el localhost por el domiio
        $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar Email
        $mail->send();
    }
}