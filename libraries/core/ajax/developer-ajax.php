<?php

// +Snippet ajax_core_session
// 
// loader

require_once 'loader.php';
require_once $root . 'config/paths-config.php';

// load core library

require_once ABS_PATH . 'config/core-config.php';
require_once ABS_PATH . 'libraries/core/classes/autoload_ajax.php';

// initilaze

$system = new System(true);
$session = $system->getSession();
$post = $session->post();

// -Snippet ajax_core_session
//
// MAIN

if (!$session->isLoggedIn()) {

  $r['r'] = -2;
  $system->response($r);
}

$user = $session->getUser();
$requireLevel = Permissions::requireLevel($user, 0, true);

if (!$requireLevel) {

  $r['r'] = -2;
  $system->response($r);
}

// AJAX LISTENERS

if (isset($post['get_session'])) {

  if (DEBUG == 1) {
    print_r($system->getSession());
  }
}

/*
 * jelez regisztrációkor ha offenzív gyanús szavakat talál
 * jelenleg magyar nyelve állítva
 */

if (LANGUAGE == 'HUN') {

  if (isset($post['add_offensive_word'])) {

    $language = $post['language'];
    $word = $post['word'];

    $word = str_replace('0', 'o', $word);
    $word = str_replace('1', 'i', $word);
    $word = str_replace('4', 'a', $word);
    $word = str_replace('ö', 'o', $word);
    $word = str_replace('ü', 'u', $word);
    $word = str_replace('ó', 'o', $word);
    $word = str_replace('é', 'e', $word);
    $word = str_replace('á', 'a', $word);
    $word = str_replace('ű', 'u', $word);
    $word = preg_replace('/[^a-zA-Z0-9\']/', '', $word);
    $word = strtolower($word);

    $result = OffensiveWordsFilter::add($word, $language);

    echo $result;
  }
}

if (isset($post['update_user'])) {

  $user->setUpdated(1);
  echo 'success';
}

if (isset($post['get_user'])) {
  print_r($user);
}

// POSTMAN

if (isset($post['test_send_mail'])) {
  
  $system->require(ABS_PATH . 'libraries/core/classes/Mailer.php');
  
  $send = Mailer::send($post['email'], $post['subject'], $post['body']);
  $r['r'] = $send ? 1 : -1;
  
  $system->response($r);
}