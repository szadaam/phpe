<?php

// +Snippet ajax_core_session
//
// loader

require_once 'loader.php';
require_once $root . 'config/paths-config.php';

// load core library

require_once ABS_PATH . 'config/core-config.php';
require_once ABS_PATH . 'libraries/core/classes/autoload_ajax.php';

// initilaze

$system = new System(true);
$session = $system->getSession();
$post = $session->post();

// -Snippet ajax_core_session

require_once ABS_PATH . 'config/login-config.php';

if ($system->isIpBlocked()) {

  $r['r'] = -2;
  $r['error'] = T::translate('ip_blocked');

  $system->response($r);
}

$system->registerIp();

if (isset($post['register'])) {

  $system->require(ABS_PATH . 'libraries/core/classes/Mailer.php');

  $errors = [];
  $email = $post['email'];
  $password_sha = $post['password_sha'];
  $username = $post['username'];
  $salt = $system->generateSalt();
  $token = $system->generateToken();
  $password = $system->salt($password_sha, $salt);

  if (REGISTRATION_VERIFICATION == 1) {

    $email_users_query = "SELECT COUNT(*) FROM users WHERE email = ?";
    $username_users_query = "SELECT COUNT(*) FROM users WHERE username = ?";
    $email_verification_query = "SELECT COUNT(*) FROM verification WHERE email = ?";
    $username_verification_query = "SELECT COUNT(*) FROM verification WHERE username = ?";

    // if mail exists inform the email holder someone is trying to register

    $email_check = Database::selectSingle($email_users_query, [$email])['COUNT(*)'] == 0 && Database::selectSingle($email_verification_query, [$email])['COUNT(*)'] == 0;
    $username_check = Database::selectSingle($username_users_query, [$username])['COUNT(*)'] == 0 && Database::selectSingle($username_verification_query, [$username])['COUNT(*)'] == 0;

    // TODO: add translation

    if (!$email_check) {

      $error['message'] = T::translate('duplicate_email');
      $error['target'] = 'register-email';

      array_push($errors, $error);
    }

    if (!$username_check) {

      $error['message'] = T::translate('duplicate_username');
      $error['target'] = 'register-username';

      array_push($errors, $error);
    }
    
    // TODO: password backend check

    if (!empty($errors)) {

      $r['r'] = -1;
      $r['errors'] = $errors;

      $system->response($r);
    }

    // register account for verification

    $sql = "INSERT INTO verification (username, email, salt, token, password) VALUES (?, ?, ?, ?, ?)";
    Database::update($sql, [$username, $email, $salt, $token, $password]);

    $link = BASE_URL . 'verification?email=' . $email . '&token=' . $token;
    $activation_link = '<a href="' . $link . '">' . $link . '</a></p>';

    // email configuration

    $subject = 'registration';
    $body = '<h3>Hello ' . $username . ',</h3><p>Köszönjük a regisztrációdat a Lidl húsvéti nyereményjátékára.</p><p>A regisztráció befejezéséhez kattints az alábbi hivatkozásra. <br>' . $activation_link;

    $mail_header = 'Fejléc ide<hr>';
    $mail_footer = '<hr>Lábléc ide';

    $body = $mail_header . $body . $mail_footer;
    $send = Mailer::send($email, $subject, $body);

    if ($send) {
      $r['r'] = 1;
    } else {

      $error['target'] = '';
      $error['message'] = T::translate('mail_error');

      $r['r'] = -1;
      $r['errors'] = [$error];
    }

    $system->response($r);
  }
} else {

  // REGISTRATION_VERIFICATION == 0
//  $ipsregistered_query = "SELECT COUNT(*) FROM ipsregistered WHERE ipaddress = ? AND created = ?";
//  $ipsregistered_count = Database::selectSingle($ipsregistered_query, [$ipaddress, F::datetime($time_earlier)])['COUNT(*)'];
//
//  $ipblocked = $ipsregistered_count >= IPREGISTER;
//
//  if (!$ipblocked) {
//
//    $email_users_query = "SELECT COUNT(*) FROM users WHERE email = ?";
//    $username_users_query = "SELECT COUNT(*) FROM users WHERE username = ?";
//
//    $email_verification_query = "SELECT COUNT(*) FROM verification WHERE email = ?";
//    $username_verification_query = "SELECT COUNT(*) FROM verification WHERE username = ?";
//
//    // if mail exists inform the email holder someone is trying to register
//
//    $email_check = Database::selectSingle($email_users_query, [$email])['COUNT(*)'] == 0 && Database::selectSingle($email_verification_query, [$email])['COUNT(*)'] == 0;
//    $username_check = Database::selectSingle($username_users_query, [$username])['COUNT(*)'] == 0 && Database::selectSingle($username_verification_query, [$username])['COUNT(*)'] == 0;
//
//    if (!$email_check) {
//      $response = 'duplicate_email';
//    }
//
//    if (!$username_check) {
//      $response = 'duplicate_username';
//    }
//
//    $duplicate = $response == 'duplicate_username' || $response == 'duplicate_email';
//
//    if (!$duplicate) {
//      // !!!: user delete from verification
//      // register account for verification
//
//      $sql = "INSERT INTO users (username, email, salt, token, password) VALUES (?, ?, ?, ?, ?)";
//      Database::update($sql, [$username, $email, $salt, $token, $password], true);
//
//      echo 'success';
//    }
//  } else {
//    $response = 'ipblocked';
//  }
//  echo $response;
}


/*
 * !!!: verifikációra rákötni
 * 
 * !!!: offenzív szavak szűrése
 * trim()
 * strtolower()
 * replace 1 -> i
 * replace 3 -> e
 * if contains(offensive_word)
 * str_replace(offensive_word -> '')
 */

  