<?php

/* @var $this System */

$this->loadLibrary('admin');

if ($this->session->isLoggedIn()) {

  $user = $this->session->getUser();

  Permissions::requireLevel($user, 0);

  $data['user'] = $this->session->getUser();
  $data['sidebar_path'] = ABS_PATH . 'templates/admin/layouts/admin-sidebar-main.php';
  $data['navbar_path'] = ABS_PATH . 'templates/admin/layouts/admin-navbar.php';

  $bodyLayout = ABS_PATH . 'templates/admin/layouts/admin-' . $this->get('p') . '.php';
} else {

  $data['sidebar_path'] = ABS_PATH . 'templates/admin/layouts/admin-sidebar-loggedout.php';
  $data['navbar_path'] = ABS_PATH . 'templates/admin/layouts/admin-navbar-loggedout.php';

  $bodyLayout = ABS_PATH . 'templates/admin/layouts/admin-loggedout.php';
}

if (!file_exists($bodyLayout)) {
  $bodyLayout = ABS_PATH . 'templates/admin/layouts/admin-main.php';
}

$this->setTemplate('admin');
$this->setData($data);
$this->setBodyLayout($bodyLayout);
