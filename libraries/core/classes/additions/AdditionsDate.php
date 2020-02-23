<?php

class Date {

  public static function datedayToDb($time = null) {

    if ($time == null) {
      $time = time();
    }

    return date('Y-m-d', $time) . ' ' . DAY_START . ':00:00';
  }

  public static function getYear($time = null) {

    if ($time == null) {
      $time = time();
    } else {
      $time = strtotime($time);
    }

    return date('Y', $time);
  }

  public static function compareDates($datetimeA, $datetimeB) {

    $time1 = strtotime($datetimeA);
    $time2 = strtotime($datetimeB);

    // if A is bigger than B the result is 1

    if ($time1 < $time2) {
      return -1;
    } else if ($time1 == $time2) {
      return 0;
    } else {
      return 1;
    }
  }

  public static function datetimeDefault() {
    return '0000-00-00 00:00:00';
  }

  public static function dateday($time = null) {

    if ($time == null) {
      $time = time();
    }

    return date('Y-m-d', $time);
  }

  public static function dateyear($time = null) {

    if ($time == null) {
      $time = time();
    }

    return date('Y', $time);
  }

  public static function getMonth($time = null) {

    if ($time == null) {
      $time = time();
    } else {
      $time = strtotime($time);
    }

    return date('m', $time);
  }

  public static function getDay($time = null) {

    if ($time == null) {
      $time = time();
    } else {
      $time = strtotime($time);
    }

    return date('d', $time);
  }

  public static function getHour($time = null) {

    if ($time == null) {
      $time = time();
    } else {
      $time = strtotime($time);
    }

    return date('H', $time);
  }

  public static function getMinute($time = null) {

    if ($time == null) {
      $time = time();
    } else {
      $time = strtotime($time);
    }

    return date('i', $time);
  }

}
