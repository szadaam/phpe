<?php

class System {

  private $request;
  private $template;
  private $bodyLayout;
  private $data;
  private $direct;
  private $session;
  private $debug;
  private $jsGlobals;
  private $head;
  private $footer;
  private $runtime_start;
  private $runtime_end;

  function __construct($ajax = false) {
    $this->createSystem($ajax);
  }

  private function createSystem($ajax) {

    $this->runtime_start = round(microtime(true) * 1000);
    $this->ajax = $ajax;
    $this->debug = [];

    $this->createErrorHandling();

    if (DEBUG == 1) {

      $this->debug('System: headers =>');

      if (getallheaders()) {
        $this->debug(getallheaders());
      }
    }

    $this->session = new Session($this);

    if (!$this->isAjax()) {

      $this->direct = false;
      $this->template = TEMPLATE;
      $this->head = [];
      $this->footer = [];

      $this->createJsGlobals();
      $this->loadCore();
      $this->createRequest();
    }
  }

  private function createErrorHandling() {

    register_shutdown_function(function() {

      $err = error_get_last();
      $handle_error = true;

      if ($err['message'] == "Directive 'track_errors' is deprecated") {
        $handle_error = false;
      }

      if ($handle_error) {
        if (DEBUG == 1) {

          $debug = $this->debug;
          $c = count($debug) - 1;

          while (is_array($debug[$c])) {
            $c--;
          }

          $e = '  <b>Error: </b>' . $err['message'] . "\n\n";
          $e .= '  <b>Error in : </b>line <b>' . $err['file'] . '</b> on ' . $err['line'] . "\n\n";
          $e .= '  <b>Error location: </b>' . $debug[$c] . "\n\n";

          if (!is_null($err)) {

            echo '<pre>';
            echo $e;
            echo '</pre>';

            F::consoleLogJS($e);
          }
        } else {

          if (!is_null($err)) {
            F::redirect('error');
          }
        }
      }
    });
  }

  // process the url coming from the client's browser

  private function createRequest() {

    $this->debug('System: createRequest()');

    $r = $this->get('r');
    $request = explode('?r=', $r)[0];
    $additional_gets = explode('&', $r);

    foreach ($additional_gets as $element) {

      $key_value = explode('=', $element);
      $key = $key_value[0];

      if (isset($key_value[1])) {
        $value = $key_value[1];
      } else {
        $value = null;
      }

      if ($key != null && $value != null) {
        // filtered
        $_GET[$key] = $value;
      }
    }

    if ($request != null) {

      $this->request = $request;
      $backend_file = ABS_PATH . 'requests/' . $this->request . '-backend.php';

      if (!file_exists($backend_file)) {
        $this->debug('System: *file not found* => ' . ABS_PATH . 'backend/' . $this->request . '.php');
        $this->request = 'main';
      }
    } else {
      $this->request = 'main';
    }

    if ($this->request == 'main') {
      $backend_file = ABS_PATH . 'requests/main-backend.php';
    }

    $this->debug('System: request => ' . $this->request);
    $this->debug('System: backend => ' . $backend_file);

    require_once $backend_file;
  }

  // load core libraries

  private function loadCore() {

    $this->debug('System: loadCore()');
    require_once ABS_PATH . 'libraries/core/includes/autoload.php';
  }

  // if all set up, the application starts printing HTML to the clients browser 

  public function run() {

    $this->debug('System: run()');

    new Page($this);

    // calculate runtime

    $this->runtime_end = round(microtime(true) * 1000);
    $runtime = intval($this->runtime_end) - intval($this->runtime_start);
    $this->debug('System: runtime => ' . $runtime . 'ms');

    if (DEBUG == 1) {

      echo '<pre id="debug-container" style="display: none">';
      echo "\n" . '==========================================' . "\n";
      echo '  DEBUG';
      echo "\n" . '==========================================' . "\n";
      print_r($this->debug);
      echo "\n" . '==========================================' . "\n";
      echo '</pre>';
    }

    F::consoleLogJS('runtime => ' . $runtime . 'ms');
  }

  // ajax response 

  public function response($r = []) {

    $this->debug('System: response()');

    if (DEBUG == 1) {

      // calculate runtime

      $this->runtime_end = round(microtime(true) * 1000);
      $runtime = intval($this->runtime_end) - intval($this->runtime_start);
      $this->debug('System: runtime => ' . $runtime . 'ms');

      $r['debug'] = $this->debug;
    }

    exit(json_encode($r));
  }

  public function loadLibrary($library) {

    $this->debug('System: library loaded => ' . $library);
    require_once ABS_PATH . 'libraries/' . $library . '/includes/autoload.php';
  }

  // DEBUG FUNCTIONS
  // write messages if DEBUG > 0 in "config/project.php"

  public function debug($expression) {

    if (DEBUG == 1) {
      array_push($this->debug, $expression);
    }
  }

  // GLOBAL FUNCTIONS

  public static function server($key = null) {

    if ($key == null) {
      $result = filter_input_array(INPUT_SERVER);
    } else {
      $result = filter_input(INPUT_SERVER, $key);
    }
  }

  public static function request($key = null) {

    if ($key == null) {
      $result = filter_input_array(INPUT_REQUEST);
    } else {
      $result = filter_input(INPUT_REQUEST, $key);
    }

    return $result;
  }

  public static function session($key = null) {

    if ($key == null) {
      $result = filter_input_array(INPUT_SESSION);
    } else {
      $result = filter_input(INPUT_SESSION, $key);
    }

    return $result;
  }

  public static function post($key = null) {

    if ($key == null) {
      $result = filter_input_array(INPUT_POST);
    } else {
      $result = filter_input(INPUT_POST, $key);
    }

    return $result;
  }

  public static function get($key = null) {

    if ($key == null) {
      $result = filter_input_array(INPUT_GET);
    } else {
      $result = filter_input(INPUT_GET, $key);
    }

    return $result;
  }

  public static function cookie($key = null) {

    if ($key == null) {
      $result = filter_input_array(INPUT_COOKIE);
    } else {
      $result = filter_input(INPUT_COOKIE, $key);
    }

    return $result;
  }

  //PLAIN OBJECT GETTERS AND SETTERS

  function getSession() {
    return $this->session;
  }

  function setSession($session) {
    $this->session = $session;
  }

  function getRequest($add = '') {
    return $this->request . $add;
  }

  function getHead() {
    return $this->head;
  }

  function getFooter() {
    return $this->footer;
  }

  function getTemplate() {
    return $this->template;
  }

  function setTemplate($template) {
    $this->template = $template;
    if ($template != 'default') {
      $this->debug('System: template => ' . $template);
    }
  }

  function getData($key = null) {

    $data = null;

    if ($key != null) {

      if (isset($this->data[$key])) {
        $data = $this->data[$key];
      }
    } else {
      $data = $this->data;
    }

    return $data;
  }

  function setData($data) {

    $this->debug('System: setData()');
    $this->debug($data);

    $this->data = $data;
  }

  function getDirect() {
    return $this->direct;
  }

  function setDirect($direct) {

    $this->direct = $direct;

    if ($direct) {
      $this->debug('System: exception => direct page');
    }
  }

  function isAjax() {
    return $this->ajax;
  }

  function getBodyLayout() {
    return $this->bodyLayout;
  }

  function setBodyLayout($bodyLayout) {

    $this->debug('System: bodylayout => ' . $bodyLayout);
    $this->bodyLayout = $bodyLayout;
  }

  // CUSTOM FUNCTIONS

  function addHead($html) {
    array_push($this->head, $html);
  }

  function addFooter($html) {
    array_push($this->footer, $html);
  }

  function require($file) {

    $this->debug('System: require => ' . $file);

    require_once $file;
  }

  function getDebug() {
    return $this->debug;
  }

  // system essential variables to html so we can read them with javascript

  private function createJsGlobals() {

    $user = $this->session->getUser();
    $user_id = $user == null ? 0 : $user->getId();

    $h = '<input type="hidden" name="debug" value="' . DEBUG . '">
          <input type="hidden" name="baseUrl" value="' . BASE_URL . '">
          <input type="hidden" name="isMobile" value="' . F::isMobile() . '">
          <input type="hidden" name="userId" value="' . $user_id . '">
          <input type="hidden" name="tSuccess" value="' . T::translate('alert_success') . '">
          <input type="hidden" name="tError" value="' . T::translate('alert_error') . '">';

    $this->jsGlobals = $h;
  }

  public function jsGlobals() {
    echo '<form id="js-globals">' . $this->jsGlobals . '</form>';
  }

  public function addJsGlobals($config) {

    $html = '';

    foreach ($config as $name => $value) {
      $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '">';
    }

    $this->jsGlobals = $this->jsGlobals . $html;
  }

  public static function generateSalt() {

    $salt = '';

    for ($i = 0; $i < 128; $i++) {

      // printable charachters (33 -> 126) on ASCII table without space(32)  

      $value = rand(33, 126);
      $salt .= chr($value);
    }

    return $salt;
  }

  // salting $password_sha (pushes sha512.js charachters)

  public static function salt($password_sha, $salt) {

    $password = '';

    for ($i = 0; $i < 128; $i++) {

      $ord_salt = ord($salt[$i]);
      $ord_pw_sha = ord($password_sha[$i]);
      $ord_pw = $ord_pw_sha + $ord_salt;
      $char_printable = false;

      while ($char_printable == false) {

        if ($ord_pw > 32 && $ord_pw < 127) {

          $char_printable = true;
          break;
        }

        $ord_pw -= 94;
      }

      $password .= chr($ord_pw);
    }

    return $password;
  }

  private static function clientIpE() {

    $ipaddress = '';

    if (getenv('HTTP_CLIENT_IP')) {
      $ipaddress = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('HTTP_X_FORWARDED')) {
      $ipaddress = getenv('HTTP_X_FORWARDED');
    } else if (getenv('HTTP_FORWARDED_FOR')) {
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } else if (getenv('HTTP_FORWARDED')) {
      $ipaddress = getenv('HTTP_FORWARDED');
    } else if (getenv('REMOTE_ADDR')) {
      $ipaddress = getenv('REMOTE_ADDR');
    } else {
      $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
  }

  private static function clientIpS() {

    $server = System::server();
    $ipaddress = '';

    if ($server['HTTP_CLIENT_IP'] != null) {
      $ipaddress = $server['HTTP_CLIENT_IP'];
    } else if ($server['HTTP_X_FORWARDED_FOR'] != null) {
      $ipaddress = $server['HTTP_X_FORWARDED_FOR'];
    } else if ($server['HTTP_X_FORWARDED'] != null) {
      $ipaddress = $server['HTTP_X_FORWARDED'];
    } else if ($server['HTTP_FORWARDED_FOR'] != null) {
      $ipaddress = $server['HTTP_FORWARDED_FOR'];
    } else if ($server['HTTP_FORWARDED'] != null) {
      $ipaddress = $server['HTTP_FORWARDED'];
    } else if ($server['REMOTE_ADDR'] != null) {
      $ipaddress = $server['REMOTE_ADDR'];
    } else {
      $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
  }

  public static function getClientIp() {

    $ipaddress = self::clientIpE();

    if ($ipaddress == 'UNKNOWN') {
      $ipaddress = self::clientIpS();
    }

    return $ipaddress;
  }

  public static function generateFileName() {

    $now = time();
    $year = date('Y', $now);
    $month = date('m', $now);
    $file_name = substr(md5(rand(0, 7777)), 0, 7);
    $file_path = UPLOADS_PATH . $year . '/' . $month . '/' . $file_name;

    while (file_exists($file_path)) {

      $file_name = substr(md5(rand(0, 7777)), 0, 7);
      $file_path = UPLOADS_PATH . $year . '/' . $month . '/' . $file_name;
    }

    return $file_name;
  }

  public function isIpBlocked() {

    $ipaddress = System::getClientIp();
    $seconds = IPBAN * 60;
    $time = F::datetime(time() - $seconds);

    $sql = "SELECT COUNT(*) FROM ipsregistered WHERE created >= ? AND ipaddress = ?";
    $ipsregistered = Database::selectSingle($sql, [$time, $ipaddress])['COUNT(*)'];

    return $ipsregistered > IPREGISTER;
  }

  public function registerIp($ipaddress = null) {

    if ($ipaddress == null) {
      $ipaddress = self::getClientIp();
    }

    $created = F::datetime();

    $sql = "INSERT INTO ipsregistered (ipaddress, created) VALUES (?, ?)";
    Database::update($sql, [$ipaddress, $created], false, false);
  }

  public static function generateToken() {
    return md5(uniqid(rand(), true));
  }

  public static function scanDir($path) {

    $files = [];

    if (is_dir($path)) {

      $dir = scandir($path);

      foreach ($dir as $file) {
        if ($file != '.' && $file != '..' && $file != 'cover') {
          array_push($files, $file);
        }
      }

      rsort($files);
    }

    return $files;
  }

  public static function clearDirectory($path) {

    $files = self::scanDir($path);

    foreach ($files as $file) {

      $target = $path . $file;

      if (!is_dir($target)) {
        unlink($target);
      }
    }
  }

  public static function deleteDirectory($path) {

    System::clearDirectory($path);

    if (is_dir($path)) {
      rmdir($path);
    }
  }

}
