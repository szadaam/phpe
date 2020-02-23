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

if (isset($post['authenticate'])) {

  $success = true;

  if (!$session->isLoggedIn()) {
    $success = false;
  }

  $user = $session->getUser();
  
  if($user->getId() != $post['user_id']) {
    $success = false;
  }

  $r['r'] = $success ? 1 : -2;
  $system->response($r);
}