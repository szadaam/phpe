<?php

//@PAGE

$template = $this->system->getTemplate();
$path = ABS_PATH . 'templates/' . $template . '/layouts/new-password.php';

if (SESSION_DATABASE == 1) {
  if ($this->session->isLoggedIn()) {
    $this->loadLayout($path);
  } else {
    F::redirect(BASE_URL . 'error');
  }
} else {
  if (isset($_SESSION['loggedin'])) {
    $this->loadLayout($path);
  } else {
    F::redirect(BASE_URL . 'error');
  }
}

