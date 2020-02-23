<?php

/* @var $this System */

$this->loadLibrary('fontawesome');
$this->loadLibrary('admin');

$data['sidebar_path'] = ABS_PATH . 'templates/admin/layouts/admin-sidebar-loggedout.php';
$data['navbar_path'] = ABS_PATH . 'templates/admin/layouts/admin-navbar-loggedout.php';

$bodyLayout = ABS_PATH . 'templates/admin/layouts/admin-loggedout.php';

$this->setData($data);
$this->setBodyLayout($bodyLayout);
