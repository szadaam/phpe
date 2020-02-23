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
  $system->response(['r' => -2]);
}

if (isset($post['login'])) {

  $username = $post['username'];
  $password_sha = $post['password'];
  $password = '';

  // translations

  $t = [
      'ip_blocked' => T::translate('ip_blocked'),
      'password_error' => T::translate('password_error'),
      'username_error' => T::translate('username_error'),
      'ip_blocked' => T::translate('ip_blocked')
  ];

  // form validation

  if ($username == '') {

    $r['r'] = -1;
    $r['error'] = 'Az űrlap nincs kitöltve.';

    $system->response($r);
  }

  // ip check

  if ($system->isIpBlocked()) {

    $r['r'] = -2;
    $r['error'] = $t['ip_blocked'];

    $system->response($r);
  }

  // get user

  $sql = "SELECT * FROM users WHERE username = ?";
  $user = Database::selectSingle($sql, [$username]);

  if (empty($user)) {

    $system->registerIp();

    $r['r'] = -2;
    $r['error'] = $t['username_error'];

    $system->response($r);
  }

  // login attempts

  $ip = $system->getClientIp();
  $seconds = LOGIN_BAN * 60;
  $time = F::datetime(time() - $seconds);

  $sql = "SELECT COUNT(*) FROM login_attempts WHERE timecreated >= ? AND user_id = ?";
  $login_attempts = Database::selectSingle($sql, [$time, $user['id']])['COUNT(*)'];

  if ($login_attempts >= LOGIN_ATTEMPTS) {

    // TODO: add to translation
    $r['r'] = -2;
    $r['error'] = 'Túl sok hibás próbálkozás ' . LOGIN_BAN . ' percen belül';

    $system->response($r);
  }

  // password check

  $password = $system->salt($password_sha, $user['salt']);

  if ($password != $user['password']) {

    $ip = $system->getClientIp();
    $now = F::datetime();

    $sql = "INSERT INTO login_attempts (ipaddress, user_id, timecreated) VALUES (?, ?, ?)";
    Database::update($sql, [$ip, $user['id'], $now]);

    $r['r'] = -2;
    $r['error'] = $t['password_error'];

    $system->response($r);
  } else {

    $session->setLoggedIn(true, $user);
    $system->response(['r' => 3]);
  }
}

if (isset($post['logout'])) {

  $session->setLoggedIn(false);
  $system->response(['r' => 1]);
}

if (isset($post['forgotten_password'])) {

  $email = $post['email'];

  // user exist

  $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
  $user_exist = Database::selectSingle($sql, [$email])['COUNT(*)'] > 0;

  if (!$user_exist) {

    $r['r'] = -1;
    $r['message'] = T::translate('email_not_exist');

    $system->response($r);
  }

  $system->require(ABS_PATH . 'libraries/core/classes/Mailer.php');
  $system->registerIp();

  $sql = "SELECT id FROM users WHERE email = ?";
  $user_id = Database::selectSingle($sql, [$email])['id'];

  $user = Users::getUser($user_id, '*');
  $token = $system->generateToken();

  $sql = "UPDATE users SET token = ? WHERE id = ?";
  Database::update($sql, [$token, $user_id]);

  $subject = PROJECT_NAME . ': új jelszó igénylése';
  $verification_link = BASE_URL . 'verification-password?id=' . $user_id . '&token=' . $token;

  $body = '<h3>Kedves ' . $user->getUsername() . '</h3>
          <p>Ezt a levelet azért kaptad, mert a <a href="' . BASE_URL . '">' . BASE_URL . '</a> oldalon erre az email címre lett igényelve a ' . $user->getUsername() . ' fiókhoz tartozó új jelszó.</p>
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
  