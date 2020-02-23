<?php

class SnippetsCore {

  public static function login() {
    ?>
    <!-- #login-form -->
    <form id="login-form">
      <div class="form-group">
        <label>Username: </label>
        <input id="login-username" class="form-control" type="text" required>
      </div>
      <div class="form-group">
        <label>Password: </label>
        <input id="login-password" class="form-control" type="password" required>
      </div>
      <button class="btn btn-block btn-info">Login</button>
    </form>
    <!-- /login -->
    <?php
  }

  public static function register() {
    ?>
    <!-- register -->
    <form id="register-form">
      <div class="form-group">
        <label>Username: </label>
        <input id="register-username" class="form-control" type="text" required>  
      </div>
      <div class="form-group">
        <label>E-mail: </label>
        <input id="register-email" class="form-control" type="text" required>
      </div>
      <div class="form-group">
        <label>Password: </label>
        <input id="register-password1" class="form-control" type="password" required>
      </div>
      <div class="form-group">
        <label>Password: </label>
        <input id="register-password2" class="form-control" type="password" required>
      </div>
      <button class="btn btn-block btn-success">Register</button>
    </form>
    <!-- /register -->
    <?php
  }

}
