<?php

if ($this->isIpBlocked()) {
  F::redirect('error', true);
}

$get = $this->get();

$email = $get['email'];
$token = $get['token'];

$sql = "SELECT token FROM verification WHERE email = ?";
$token2 = Database::selectSingle($sql, [$email])['token'];

if ($token == $token2) {

  $sql = "SELECT * FROM verification WHERE email = ?";
  $user = Database::selectSingle($sql, [$email]);

  $username = $user['username'];
  $salt = $user['salt'];
  $password = $user['password'];
  $usergroups = serialize([]);
  $now = F::datetime();

  $sql = "INSERT INTO users (username, email, salt, token, password, membersince, usergroups) VALUES (?, ?, ?, ?, ?, ?, ?)";
  Database::update($sql, [$username, $email, $salt, $token, $password, $now, $usergroups], true);

  $sql = "DELETE FROM verification WHERE email = ?";
  Database::update($sql, [$email]);

  $temporary['success']['message'] = 'Felhasználói fiók sikeresen aktiválva.';
  $temporary['success']['fade_time'] = 10000;

  $this->session->setTemporary($temporary);

  F::redirect(BASE_URL, true);
} else {

  $this->registerIp();
  F::redirect('error', true);
}

