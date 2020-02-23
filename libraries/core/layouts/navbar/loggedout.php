<!-- Navbar -->
<?php

$request = $this->system->getRequest();

$is_admin = $request == 'admin.php';

if(!$is_admin) {
  require_once ABS_PATH . 'templates/' . TEMPLATE . '/layouts/navbar-loggedout.php';
}

?>
<!-- /Navbar -->