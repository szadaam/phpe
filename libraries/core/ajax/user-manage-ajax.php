<?php

// TODO: translations
// 
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

if (!$session->isLoggedIn()) {
  $system->response(['r' => -2]);
}

// user manages of its self

$user_id = $post['user_id'];
$user = $session->getUser();

if ($user->getId() != $user_id) {
  $system->response(['r' => -2]);
}

if ($user_id < 1) {

  $r['r'] = -1;
  $r['message'] = 'user_id not found';

  $system->response($r);
}

if (isset($post['manage_details'])) {

  $username = $post['username'];

  // username null

  if (trim($username) == '') {

    $r['r'] = -1;
    $r['message'] = 'username empty';

    $system->response($r);
  }

  // username duplicate


  $old_username = $user->getUsername();

  if ($old_username != $username) {

    $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
    $duplicate_u = Database::selectSingle($sql, [$username])['COUNT(*)'] > 0;

    $sql = "SELECT COUNT(*) FROM verification WHERE username = ?";
    $duplicate_v = Database::selectSingle($sql, [$username])['COUNT(*)'] > 0;

    $duplicate = $duplicate_u || $duplicate_v;

    if ($duplicate) {

      $r['r'] = -1;
      $r['message'] = T::translate('duplicate_username');

      $system->response($r);
    }
  }

  $sql = "UPDATE users SET username = ?, realname = ?, phone = ?, updated = ? WHERE id = ?";
  Database::update($sql, [$username, $post['realname'], $post['phone'], 1, $user_id]);

  $system->registerIp();
  $system->response(['r' => 1]);
}

if (isset($post['manage_email'])) {

  $system->require(ABS_PATH . 'libraries/core/classes/Mailer.php');
  $system->registerIp();

  $email = $post['email'];
  $old_email = $user->getEmail();
  $token = $system->generateToken();

  if ($email != $old_email) {

    $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
    $duplicate_u = Database::selectSingle($sql, [$email])['COUNT(*)'] > 0;

    $sql = "SELECT COUNT(*) FROM verification WHERE email = ?";
    $duplicate_v = Database::selectSingle($sql, [$email])['COUNT(*)'] > 0;

    $duplicate = $duplicate_u || $duplicate_v;

    if ($duplicate) {

      $r['r'] = -1;
      $r['message'] = T::translate('duplicate_email');

      $system->response($r);
    }
  }

  Users::updateTemporary($user_id, 'new_email', $email);

  $sql = "UPDATE users SET token = ? WHERE id = ?";
  Database::update($sql, [$token, $user_id]);

  $subject = PROJECT_NAME . ': új e-mail cím';
  $verification_link = BASE_URL . 'verification-email?id=' . $user_id . '&token=' . $token;

  $body = '<h3>Kedves ' . $user->getUsername() . '</h3>
          <p>Ezt a levelet azért kaptad, mert a <a href="' . BASE_URL . '">' . BASE_URL . '</a> oldalon erre az email címre lett igényelve a ' . $user->getUsername() . ' fiókhoz tartozó új email cím.</p>
          <p>Az alábbi hivatkozásra kattintva az említett művelet azonosításra kerül:</p>
          <a href="' . $verification_link . '">' . $verification_link . '</a>
          <p><b>Ha nem te igényelted ezt a műveletet, akkor ne tegyél semmit!</b></p>';

  $send = Mailer::send($email, $subject, $body);

  if ($send) {
    $r['r'] = 1;
  } else {

    $r['r'] = -1;
    $r['message'] = T::translate('mail_error');
  }

  $system->response($r);
}

if (isset($post['new_password'])) {
  
  // TODO: password backend check

  $sql = "SELECT salt FROM users WHERE id = ?";
  $salt = Database::selectSingle($sql, [$user_id])['salt'];
  $password = $system->salt($post['password'], $salt);

  $sql = "UPDATE users SET password = ? WHERE id = ?";
  Database::update($sql, [$password, $user_id], true);

  $system->response(['r' => 1]);
}