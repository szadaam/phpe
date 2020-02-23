<?php

switch ($template) {
  case 'admin': $this->loadLayout(ABS_PATH . 'libraries/admin/layouts/footer.php');
    break;
  default: $this->loadLayout(ABS_PATH . 'templates/' . $template . '/layouts/footer.php');
    break;
}