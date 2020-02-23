<?php

class T {

  public static function translate($name) {
    $table = 'translations_' . strtolower(LANGUAGE);
    $sql = "SELECT value FROM " . $table . " WHERE name = ?";
    $translation = Database::selectSingle($sql, [$name])['value'];
    
    if($translation == null) {
      $translation = 'null';
    }
    
    return $translation;
  }

}