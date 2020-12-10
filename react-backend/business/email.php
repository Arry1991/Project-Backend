<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/SMTP.php";
require_once "PHPMailer/Exception.php";

$mail = new PHPMailer();

//smtp settings
$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = "phpseniorproject@gmail.com";
$mail->Password = "Z2\$fg56?as!";
$mail->Port = 587;
$mail->SMTPSecure = "tls";

// //email setting
// $mail->setFrom('phpseniorproject@gmail.com', 'Email Test');
// $mail->addAddress('phpseniorproject@gmail.com');               // Name is optional


// //the subject and email
// $mail->Subject = 'Test subject';
// $mail->Body    = 'This a php test';

// //send the mail
// $mail->send();
