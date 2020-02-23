<?php

require_once ABS_PATH . 'libraries/core/phpmailer/PHPMailerAutoload.php';
require_once ABS_PATH . 'config/mailer-config.php';

// ???: a templatek jöjjenek tábláról?

class Mailer {

  private static function filter($string) {

    $string = str_replace('<script>', '', $string);
    $string = str_replace('</script>', '', $string);

    return $string;
  }

  public static function send($email, $subject, $body) {

    // filter strings

    $body = self::filter($body);
    $subject = self::filter($subject);

    // add mail header and mail footer

    $mail = new PHPMailer;
    $mail->SMTPDebug = SMTP_DEBUG; // Enable verbose debug output
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom(SMTP_USERNAME, PROJECT_NAME);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;

    if ($mail->send()) {
      return Logger::outgoingMail($email, $subject, $body);
    } else {
      return false;
    }
  }

}
