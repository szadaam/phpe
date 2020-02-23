<?php

class Logger {

  public static function log($query, $params) {

    global $session;

    $user_id = 0;

    if ($session != null) {

      if ($session->isLoggedIn()) {
        $user = $session->getUser();
      } else {
        $user = [];
      }
      
      if (!is_array($user)) {
        $user_id = $user->getId();
      }
    }

    $created = F::datetime();
    $explode = explode('?', $query);

    $i = 1;

    foreach ($params as $param) {
      $explode[$i] = $param . $explode[$i];
      $i++;
    }

    $query = '';
    foreach ($explode as $value) {
      $query .= $value;
    }

    $sql = "INSERT INTO logs (user_id, created, query) VALUES (?, ?, ?)";
    Database::update($sql, [$user_id, $created, $query], true, false);
  }

  public static function outgoingMail($email, $subject, $body) {

    $now = F::datetime();
    
    $sql = "INSERT INTO outgoing_mails (email, subject, body, created) VALUES (?, ?, ?, ?)";
    return Database::update($sql, [$email, $subject, $body, $now], true, false);
  }

}
