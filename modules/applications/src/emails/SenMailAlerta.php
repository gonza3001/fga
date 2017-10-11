<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/10/2017
 * Time: 05:08 PM
 */


require_once('../../../../plugins/PhpMail/class.phpmailer.php');
include("../../../../plugins/PhpMail/class.smtp.php");


$email_user = "agomez.barron@gmail.com";
$email_password = "algo#123";
$the_subject = "Phpmailer prueba by Alejandro Gomez";
$address_to = "sistemas03@prestamoexpress.com.mx";
$from_name = "Alejandro Gomez";
$phpmailer = new PHPMailer();
// ---------- datos de la cuenta de Gmail -------------------------------
$phpmailer->Username = $email_user;
$phpmailer->Password = $email_password;
//-----------------------------------------------------------------------
// $phpmailer->SMTPDebug = 1;
$phpmailer->SMTPSecure = 'ssl';
$phpmailer->Host = "smtp.gmail.com"; // GMail
$phpmailer->Port = 465;
$phpmailer->IsSMTP(); // use SMTP
$phpmailer->SMTPAuth = true;
$phpmailer->setFrom($phpmailer->Username,$from_name);
$phpmailer->AddAddress($address_to); // recipients email
$phpmailer->Subject = $the_subject;
$phpmailer->Body .=utf8_decode("<h1 style='color:#3498db;'>Notificación de Prueba !</h1>");
$phpmailer->Body .= "<p>Mensaje personalizado</p>";
$phpmailer->Body .= "<p>Fecha y Hora: ".date("d-m-Y h:i:s")."</p>";
$phpmailer->IsHTML(true);

if (!$phpmailer->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
    //Section 2: IMAP
    //Uncomment these to save your message in the 'Sent Mail' folder.
    #if (save_mail($mail)) {
    #    echo "Message saved!";
    #}
}