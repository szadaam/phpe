<?php

class AdditionsString {

  public static function strposAll($haystack, $needle) {

    $offset = 0;
    $allpos = array();

    while (($pos = strpos($haystack, $needle, $offset)) !== false) {
      
      $offset = $pos + 1;
      $allpos[] = $pos;
    }

    return $allpos;
  }

}
