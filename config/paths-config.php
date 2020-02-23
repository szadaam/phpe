<?php

if($domain == '') {
  $domain = getenv('DOMAIN');
}

if($root == '') {
  $root = getenv('ABS_PATH');
}

// cookie security configuration

if ($_SERVER['SERVER_PORT'] == '80') {
  
  if (getenv('HTTPS') == 'on') {
    header('Location: https://' . $domain);
  }
}

header('X-Frame-Options: SAMEORIGIN');

$http_pre = $_SERVER['SERVER_PORT'] == '80' ? 'http://' : 'https://';

// !!! set this in .htaccess in project root directory !!!

define('BASE_URL', $http_pre . $domain);

// path of project

define('ABS_PATH', $root);

// servers hostname

define('HOST_MACHINE', 'sztefanov-1');


