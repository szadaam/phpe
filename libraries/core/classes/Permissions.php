<?php

class Permissions {

  public static function isGranted($user, $level) {

    $granted = false;
    $maxlevel = $user->getMaxLevel();

    if ($maxlevel > -1 && $maxlevel <= $level) {
      $granted = true;
    }

    return $granted;
  }

  public static function requireLevel($user, $level, $ajax = false) {
    
    $granted = self::isGranted($user, $level);
    
    if(!$ajax && !$granted) {
      F::redirect('error', true);
    }
    
    return $granted;
  }

}
