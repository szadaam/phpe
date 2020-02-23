<?php

// FUNCTIONS

function get_env($htaccess_path) {

  $domain = '';
  $abs_path = '';
  $error_location = '';
  $https = '';

  $htaccess = file_get_contents($htaccess_path);
  $e = explode("\n", $htaccess);

  for ($i = 0; $i < count($e); $i++) {

    $line = $e[$i];
    $e1 = explode(' ', $line);

    if ($e1[0] == 'SetEnv') {

      if (count($e1) == 3) {

        $key = $e1[1];
        $value = $e1[2];

        switch ($key) {

          case 'DOMAIN':

            $domain = $value;
            break;

          case 'ABS_PATH':

            $abs_path = $value;
            break;

          case 'ERROR_LOCATION':

            $error_location = $value;
            break;

          case 'HTTPS':

            $https = $value;
            break;
        }

        $complete = $domain != '' &&
                $abs_path != '' &&
                $error_location != '' &&
                $https != '';

        if ($complete) {
          break;
        }
      }
    }
  }

  $data = [
      'domain' => $domain,
      'abs_path' => $abs_path,
      'error_location' => $error_location,
      'https' => $https
  ];

  return $data;
}

// MAIN

if (getenv('DOMAIN') == '') {

  $htaccess_path = implode('/', array_slice(explode('/', __FILE__), 0, count(explode('/', __FILE__)) - count(explode('/', $_SERVER['DOCUMENT_ROOT'])))) . '/.htaccess';
  $env = get_env($htaccess_path);
  
  $root = $env['abs_path'];
  $domain = $env['domain'];
}