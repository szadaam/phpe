<?php

if ($this->isIpBlocked()) {
  F::redirect('error', true);
}

$get = $this->get();
$user_id = $get['id'];
$token = $get['token'];

$sql = "SELECT username, email, token, salt FROM users WHERE id = ?";
$user = Database::selectSingle($sql, [$user_id]);
$user_token = $user['token'];

if ($user_token == $token) {

  $this->registerIp();

  $password = substr($this->generateToken(), 0, 7);
  $password_salted = $this->salt(hash("sha512", $password), $user['salt']);

  $sql = "UPDATE users SET password = ? WHERE id = ?";
  Database::update($sql, [$password_salted, $user_id], true);

  $subject = PROJECT_NAME . ': új jelszó';

  $body = '<h3>Kedves ' . $user['username'] . '</h3>
          <p>Ezt a levelet azért kaptad, mert a <a href="' . BASE_URL . '">' . BASE_URL . '</a> oldalon erre az email címre lett igényelve a ' . $user['username'] . ' fiókhoz tartozó új jelszó.</p>
          <p>Az azonosítás sikeres volt.</p>
          <p><b>Az új jelszó:</b> ' . $password . '</p>';

  $send = Mailer::send($user['email'], $subject, $body);
  
  $sql = "UPDATE users SET token = ? WHERE id = ?";
  Database::update($sql, [$this->generateToken(), $user_id]);

  $temporary['success']['message'] = 'A fiókhoz tartozó jelszó megváltoztatásra került. Az új jelszót a fiókodhoz tartozó e-mail címre küldtük meg.';
  $temporary['success']['fade_time'] = 10000;

  $this->session->setTemporary($temporary);
  F::redirect(BASE_URL, true);
} else {

  $this->registerIp();
  F::redirect('error', true);
}