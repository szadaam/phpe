<?php

$domain = getenv('DOMAIN');
$root = getenv('ABS_PATH');

if (getenv('DOMAIN') == '') {
  
  $domain = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $root = substr(__FILE__, 0, strlen(__FILE__) - strlen('loader-root.php'));
}

// security headers

header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
//header('Content-Security-Policy: default-src ' . $domain . '; img-src *');
