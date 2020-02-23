<?php

class Session {

  private $system;
  private $cookie;
  private $post;
  private $get;
  private $user;
  private $temporary;
  private $auth;
  private $value;
  private $created;
  private $lastLogin;
  private $lastAction;
  private $loggedIn;
  private $test;

  function __construct($system) {
    $this->createSession($system);
  }

  private function createSession($system) {

    $this->system = $system;
    $this->cookie = System::cookie();
    $this->post = System::post();
    $this->get = System::get();

    $this->system->debug('Session: createSession()');
    $this->system->debug('Session: get');
    $this->system->debug($this->get);
    $this->system->debug('Session: post');
    $this->system->debug($this->post);
    $this->system->debug('Session: cookie');
    $this->system->debug($this->cookie);

    if ($this->cookie == null) {
      $this->setSession();
    } else {
      $has_session = false;

      // search for existing session

      foreach ($this->cookie as $auth => $value) {

        // filter session cookies

        if (strlen($auth) == 8 && strlen($value) == 8) {

          $sql = "SELECT COUNT(*) FROM sessions WHERE auth = ? AND value = ?";
          $has_session = Database::selectSingle($sql, [$auth, $value])['COUNT(*)'] > 0;
        }
      }

      if ($has_session) {
        $this->startSession($auth, $value);
      } else {
        $this->regenerateSession();
      }
    }

    $this->createTestMode();

    $this->system->debug('Session: User');
    $this->system->debug($this->getUser());
  }

  private function sessionCleaner() {

    $half_hours_earlier = date('Y-m-d H:i:s', time() - 1800);

    if (CRONJOBS == 0) {

      $sql = "SELECT COUNT(*) FROM sessions WHERE last_action < ?";
      $rows_affected = Database::selectSingle($sql, [$half_hours_earlier])['COUNT(*)'];

      if ($rows_affected > 0) {

        $sql = "DELETE FROM sessions WHERE last_action < ?";
        Database::update($sql, [$half_hours_earlier], false, false);

        if (DEBUG == 1) {
          F::alertJS('sessionCleaner => rows affected: ' . $rows_affected);
        }
      }
    } else {
      // TODO: implement session cleaning with cronjob
    }
  }

  private function regenerateSession() {

    if ($this->system != null) {
      $this->system->debug('Session: regenerateSession()');
    }

    // if the session is not valid make it sync with the browser

    if ($this->system->isAjax()) {

      $r['r'] = 3;
      $this->system->response($r);
    }

    if ($this->cookie != null) {

      // remove old authentication cookies

      foreach ($this->cookie as $auth => $value) {

        if (F::contains($auth, 'hc_')) {

          F::deleteCookie($auth);
          unset($_COOKIE[$auth]);
          unset($this->cookie[$auth]);
        }
      }
    }

    $random_order = rand(1, DISTURB_LEVEL);

    if ($this->system != null) {
      $this->system->debug('System: [random_order] => ' . $random_order);
    }

    for ($i = 0; $i < DISTURB_LEVEL; $i++) {

      if ($i + 1 == $random_order) {
        $this->setSession();
      } else {

        $auth = 'hc_' . substr(md5(rand(0, 777777)), 0, 5);
        $value = substr(md5(rand(0, 777777)), 0, 8);

        $this->createCookie($auth, $value);
      }
    }

    $this->sessionCleaner();
  }

  private function createCookie($auth, $value) {

    $time = date("D, d, M Y h:i:s", time() + (86400 * 30)) . " GMT";
    $cookie_header = 'Set-Cookie: ' . $auth . '=' . $value . ';Expires=' . $time . '; path=/; httpOnly; samesite=Lax';

    header($cookie_header);
  }

  private function setSession() {

    if ($this->system != null) {
      $this->system->debug('System: setSession()');
    }

    $this->loggedIn = false;
    $auth = 'hc_' . substr(md5(rand(0, 777777)), 0, 5);
    $value = substr(md5(rand(0, 777777)), 0, 8);

    $this->createCookie($auth, $value);

    $address = System::getClientIp();
    $now = F::datetime();

    $sql = "INSERT INTO sessions (auth, value, address, created, last_action) VALUES (?, ?, ?, ?, ?)";
    Database::update($sql, [$auth, $value, $address, $now, $now], false, false);

    $this->auth = $auth;
    $this->value = $value;
  }

  // regenerate session when SESSION_TIMEOUT is bigger than actual time

  private function isSessionValid($lastAction) {

    $last_action = time() - strtotime($lastAction);

    if ($this->system != null) {
      $this->system->debug('Session: last action => ' . $last_action . 's');
    }

    return $last_action < SESSION_TIMEOUT;
  }

  private function startSession($auth) {

    $sql = "SELECT * FROM sessions WHERE auth = ?";
    $session_data = Database::selectSingle($sql, [$auth]);

    $this->auth = $auth;
    $this->value = $session_data['value'];
    $this->created = $session_data['created'];
    $this->lastLogin = $session_data['last_login'];
    $this->lastAction = $session_data['last_action'];
    $this->user = unserialize($session_data['user']);
    $this->temporary = unserialize($session_data['temporary']);
    $this->loggedIn = $session_data['loggedin'];

    if ($this->user != null) {

      $sql = "SELECT updated FROM users WHERE id = ?";
      $updated = Database::selectSingle($sql, [$this->user->getId()])['updated'];

      if ($updated == 1) {

        $this->system->debug('Session: *** User updated ***');

        $user_data = User::get($this->user->getId(), '*');
        $user = new User($user_data);

        $this->setUser($user);

        if (isset($this->system)) {
          $this->system->setSession($this);
        }
      }
    }

    if ($this->isSessionValid($this->lastAction)) {

      if ($this->system != null) {
        $this->system->debug('Session: session => ok');
      }

      $this->setLastAction(F::datetime(time()));
    } else {

      if ($this->system != null) {
        $this->system->debug('Session: session => regenerating');
      }

      $this->regenerateSession();
    }
  }

  private function createTestMode() {

    if (isset($this->post['test'])) {

      if (DEBUG == 0) {

        if ($this->isAjax()) {

          $r['r'] = -2;
          $this->system->response($r);
        } else {
          F::redirect('error', true);
        }
      }

      $this->test = $this->post['test'] == 1;

      if ($this->isTest()) {

        if (isset($this->post['postman_token'])) {

          if ($this->post['postman_token'] == POSTMAN_TOKEN) {

            $this->system->debug("Session: *** TEST MODE ***");

            if (isset($this->post['user_id'])) {

              $user = User::get($this->post['user_id'], '*');
              $this->setLoggedIn(true, $user);

              $this->system->debug("Session: *** LOGGED BY POSTMAN ***");
              $this->system->debug($user);

              $r['r'] = 1;
              $this->system->response($r);
            }
          }
        }
      }
    }
  }

  function setLastAction($lastAction) {

    $sql = "UPDATE sessions SET last_action = ? WHERE auth = ?";
    Database::update($sql, [$lastAction, $this->auth], false, false);

    $this->lastAction = $lastAction;
  }

  function isLoggedIn() {
    return $this->loggedIn;
  }

  function setLoggedIn($loggedIn, $user = null) {

    $this->loggedIn = $loggedIn;

    // login

    if ($this->isLoggedIn()) {

      if ($user == null) {
        exit("User is null.");
      }

      $logged_in = 1;

      $sql = "UPDATE sessions SET loggedin = ?, last_action = ? WHERE auth = ?";
      Database::update($sql, [$logged_in, F::datetime(), $this->auth], false, false);

      // set user

      if (isset($user['password'])) {
        unset($user['password']);
      }

      if (isset($user['salt'])) {
        unset($user['salt']);
      }

      if (isset($user['token'])) {
        unset($user['token']);
      }

      $this->user = new User($user);
      $this->setUser($this->user);

      $last_login = F::datetime();
      $user_id = $this->user->getId();

      $sql = "UPDATE users SET lastlogin = ? WHERE id = ?";
      Database::update($sql, [$last_login, $user_id]);
    } else {

      // logout

      $logged_in = 0;

      $sql = "UPDATE sessions SET loggedin = ? WHERE auth = ?";
      Database::update($sql, [$logged_in, $this->auth], false, false);
    }
  }

  function getUser() {
    return $this->user;
  }

  function setUser($user) {

    $this->system->debug('Session: setUser()');

    $this->user = $user;
    $updated = $this->user->isUpdated() ? 0 : 1;
    $this->user->setUpdated($updated);
    $user = serialize($this->user);

    $sql = "UPDATE sessions SET user = ? WHERE auth = ?";
    $update = Database::update($sql, [$user, $this->auth], true, false);

    return $update;
  }

  function isAjax() {
    return $this->system->isAjax();
  }

  function checkLogin() {

    if (!$this->isLoggedIn()) {
      F::redirect(BASE_URL . 'error');
    }
  }

  function getAuth() {
    return $this->auth;
  }

  function getTemporary($key = null) {
    return $key == null ? $this->temporary : $this->temporary[$key];
  }

  function setTemporary($temporary = null) {

    if ($temporary != null) {
      $this->temporary = $temporary;
    }

    $sql = "UPDATE sessions SET temporary = ? WHERE auth = ?";
    Database::update($sql, [serialize($this->temporary), $this->auth], true, false);
  }

  function addTemporary($key, $value) {

    $this->temporary[$key] = $value;
    $this->setTemporary();
  }

  function removeTemporary($key) {

    if (isset($this->temporary[$key])) {
      unset($this->temporary[$key]);
    }

    $this->setTemporary();
  }

  function destroy() {

    unset($_COOKIE[$this->auth]);
    setcookie($this->auth, null, -1, '/');

    foreach ($_COOKIE as $key => $value) {

      if (substr($key, 0, 3) == 'hc_') {

        unset($_COOKIE[$key]);
        setcookie($key, null, -1, '/');
      }
    }

    $sql = "DELETE FROM sessions WHERE auth = ?";
    Database::update($sql, [$this->auth], false, false);
  }

  function get() {
    return $this->get;
  }

  function post() {
    return $this->post;
  }

  function cookie() {
    return $this->cookie;
  }

  function isTest() {
    return $this->test;
  }

  function setTest($test) {
    $this->test = $test;
  }

}
