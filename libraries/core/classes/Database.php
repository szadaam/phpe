<?php

require_once ABS_PATH . 'config/mysql-config.php';

$pdo = Database::connect();

$pdo->exec("SET NAMES 'utf8';");
$pdo->exec("SET CHARACTER SET UTF8");

class Database {

  private static function avoidTags($params, $allowtags) {

    $result = [];

    foreach ($params as $param) {

      // no scripts allowed at all

      if (F::contains(strtolower($param), '<script')) {
        $param = '';
      }

      // iframes are only from youtube.com allowed at the moment

      if (F::contains(strtolower($param), '<iframe')) {

        $null = true;
        $exp = explode(' ', $param);

        foreach ($exp as $value) {
          if (substr($value, 0, 3) == 'src') {
            if (substr($value, 0, 22) == 'src="//www.youtube.com') {
              $null = false;
            }
          }
        }

        if ($null) {
          $param = '';
        }
      }

      array_push($result, $param);
    }

    return $result;
  }

  public static function connect() {

    $dsn = 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB . ';charset=' . MYSQL_CHARSET;
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new PDO($dsn, MYSQL_USERNAME, MYSQL_PASSWORD, $opt);
  }

  public static function selectSingle($sql, $params = null) {

    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
  }

  public static function select($sql, $params = null) {

    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  // ($allowtags = true) => when special characters expected for example: serialization

  public static function update($sql, $params = null, $allowtags = false, $log = true) {

    global $pdo;

    if ($params != null) {

      $params = self::avoidTags($params, $allowtags);

      if (DEBUG == 1) {
        $exec = $pdo->prepare($sql)->execute($params);
      } else {

        try {
          $exec = $pdo->prepare($sql)->execute($params);
        } catch (Exception $ex) {
          F::redirect(BASE_URL . 'error');
        }
      }
    } else {

      if (DEBUG == 1) {

        try {
          $exec = $pdo->prepare($sql)->execute($params);
        } catch (Exception $ex) {
          F::redirect(BASE_URL . 'error');
        }
      }
    }

    $last_insert_id = $pdo->lastInsertId();

    if ($log) {
      Logger::log($sql, $params);
    }

    if (substr($sql, 0, strlen('INSERT')) == 'INSERT') {
      return $last_insert_id;
    } else {
      return $exec;
    }
  }

  public static function exec($sql) {

    global $pdo;

    $exec = $pdo->prepare($sql)->execute();
  }

  public static function getTableSchema($select_columns_data, $table_name) {

    $select = implode(", ", $select_columns_data);

    $sql = "SELECT " . $select . " FROM " . $table_name . " LIMIT 1";
    $table_schema_data = self::selectSingle($sql);
    $table_schema = [];

    foreach ($table_schema_data as $key => $value) {
      array_push($table_schema, $key);
    }

    return $table_schema;
  }

}
