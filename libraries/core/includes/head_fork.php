<?php

switch ($template) {
  case 'admin': $this->loadLayout(ABS_PATH . 'libraries/admin/layouts/head.php');
    break;
  default: $this->loadLayout(ABS_PATH . 'templates/' . $template . '/layouts/head.php');
    break;
}