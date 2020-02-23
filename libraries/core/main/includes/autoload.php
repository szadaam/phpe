<?php

// @SYSTEM
// core footer
$session = $this->session;
if ($session->isLoggedin()) {
  $user = $session->getUser();
}


$this->addFooter('<script src="' . BASE_URL . 'libraries/core/main/functions.js"></script>');
$this->addFooter('<script src="' . BASE_URL . 'libraries/core/main/core.js"></script>');

if (isset($user)) {
  if (Permissions::isGranted($user, 0)) {
    $this->addFooter('<script src="' . BASE_URL . 'libraries/core/main/developer.js"></script>');
  }
}

