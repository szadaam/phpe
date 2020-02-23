<?php

date_default_timezone_set('Europe/Berlin');

// select debug mode

define('DEBUG', 1);
define('REDIRECT', 1);

// POSTMAN TESTING ONLY FOR DEV

define('POSTMAN_TOKEN', '6e39bf1e24915377f7dea9a2c20886ddc7d176e006f776165c556f253e86da70f9bcc79007693f02661b0ba1cb21807021615932ee45d291a011083b54243f46');

// PROJECT

define('PROJECT_NAME', 'phpe');
define('ADMIN_EMAIL', 'adam@sztefanov.com');

// projects default template

define('TEMPLATE', 'admin');

// project language

define('LANGUAGE', 'ENG');

// SESSION
// 
// defines how many cookies the application use to authenticate the session
define('DISTURB_LEVEL', 1);
define('SESSION_DATABASE', 1);
define('CRONJOBS', 0);

// defines the allowed length of inactivity is  before the session times out

define('SESSION_TIMEOUT', 30 * 60); // minutes * seconds
// SECURITY
// 
// actions allowed per minute
define('IPREGISTER', 33);
define('IPBAN', 60);
