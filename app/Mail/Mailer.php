<?php

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;


class Mailer {

  private function getMail() {
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 0; 
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sistemaeventosfei@gmail.com';
    $mail->Password = 'qhlp wjxl vdxb hhps';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('sistemaeventosfei@gmail.com', 'Sistema de Administracion de Eventos de la FEI');
    
    return $mail;
  }

  static public function sendEmail(User $to, Mail $mail) {

    $instance = new self();

    $client = $instance->getMail();
    $client->addAddress($to->email, $to->nombres . " " . $to->apellidoPaterno); 
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Adjuntar cosas:
    // $mail->addAttachment('/var/tmp/file.tar.gz');
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');

    $client->isHTML(true);
    $client->Subject = $mail->subject;
    $client->Body    = $mail->body;
    $client->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $client->send();
  
  }


}


