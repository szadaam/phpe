<?php

if ($this->isIpBlocked()) {
  F::redirect('error', true);
}

$get = $this->get();
$user_id = $get['id'];
$token = $get['token'];

$sql = "SELECT token FROM users WHERE id = ?";
$user_token = Database::selectSingle($sql, [$user_id])['token'];

if ($user_token == $token) {

  $temporary = Users::getTemporary($user_id);

  if (!isset($temporary['new_email'])) {

    $temporary['error']['message'] = T::translate('alert_error');
    $temporary['error']['fade_time'] = 10000;

    $this->session->setTemporary($temporary);
    F::redirect(BASE_URL, true);
  }

  $sql = "UPDATE users SET email = ? WHERE id = ?";
  Database::update($sql, [$temporary['new_email'], $user_id]);
  Users::removeTemporary($user_id, 'new_email');

  $sql = "UPDATE users SET token = ? WHERE id = ?";
  Database::update($sql, [$this->generateToken(), $user_id]);

  $temporary['success']['message'] = 'A fiókhoz tartozó e-mail cím megváltoztatásra került.';
  $temporary['success']['fade_time'] = 10000;

  $this->session->setTemporary($temporary);
  $this->session->getUser()->setUpdated(true);
  F::redirect(BASE_URL, true);
} else {

  $this->registerIp();
  F::redirect('error', true);
}