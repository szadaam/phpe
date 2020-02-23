<?php

class Users {

  public static function getUsers($columns = null, $order_by = null) {
    $sql = "SELECT id FROM users";

    if ($order_by != null) {
      $sql .= " ORDER BY " . $order_by;
    }

    $rows = Database::select($sql);
    $users = [];

    foreach ($rows as $row) {
      $user = self::getUser($row['id'], $columns);
      array_push($users, $user);
    }

    return $users;
  }

  public static function getUser($user_id, $columns = null) {

    $user_data = User::get($user_id, $columns);
    $user = new User($user_data);

    return $user;
  }

  public static function getTemporary($user_id) {

    $temporary = [];

    $sql = "SELECT temporary FROM users WHERE id = ?";
    $user = Database::selectSingle($sql, [$user_id]);

    if (isset($user['temporary'])) {
      $temporary = unserialize($user['temporary']);
    }

    return $temporary;
  }

  public static function setTemporary($user_id, $temporary) {

    $sql = "UPDATE users SET temporary = ? WHERE id = ?";
    Database::update($sql, [serialize($temporary), $user_id], true);
  }

  public static function updateTemporary($user_id, $key, $value) {

    $temporary = self::getTemporary($user_id);
    $temporary[$key] = $value;

    self::setTemporary($user_id, $temporary);
  }

  public static function removeTemporary($user_id, $key) {

    $temporary = self::getTemporary($user_id);

    if (isset($temporary[$key])) {
      unset($temporary[$key]);
    }

    self::setTemporary($user_id, $temporary);
  }

}
