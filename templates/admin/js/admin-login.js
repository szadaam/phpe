/* global jsGlobals */

// !!!: obcusfate

// login

window.onload = function () {

  // login

  let login_form = document.getElementById("login-form");

  if (login_form != null) {
    login_form.addEventListener("submit", function (e) {

      e.preventDefault();

      let ajax_url = jsGlobals.baseUrl + "libraries/core/ajax/login-ajax.php";
      let postData = {
        login: true,
        username: document.getElementById("login-username").value,
        password: sha512(document.getElementById("login-password").value)
      };

      let xhttp = new XMLHttpRequest();

      xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

          let r = parse_response(this.responseText);
          let error_message = "";

          switch (r.r) {

            case 1:
              redirect(jsGlobals.baseUrl);
              break;

            case 2:
              error_message = document.getElementById("ip-blocked").value;
              alertError(error_message);
              break;

            case 3:
              $("#login-password").addClass("error-border");
              error_message = document.getElementById("password-error").value;
              alertError(error_message);
              break;

            default:
              console.error(this.responseText);
              alertError();
              break;
          }
        }
      };

      xhttp.open("POST", ajax_url, true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send(post(postData));
    });
  }

  // register

  let register_form = document.getElementById("register-form");

  if (register_form != null) {
    register_form.addEventListener("submit", function (e) {

      e.preventDefault();

      let valid = true;

      let username = document.getElementById("register-username").value;
      let email = document.getElementById("register-email").value;
      let password1 = document.getElementById("register-password1").value;
      let password2 = document.getElementById("register-password2").value;

      let error = [];

      if (password1 != password2) {
        error_message = document.getElementById("password-mismatch");
        error.push(error_message);
        valid = false;
      }

      let password_check = validate_password(password1);
      let password_valid = password_check == "success" ? true : false;

      if (!password_valid) {
        valid = false;
        error.push(password_check);
      }

      let email_valid = validate_email(email) == "success" ? true : false;

      if (!email_valid) {
        valid = false;
        let error_message = document.getElementById("email-format").value;
        error.push("Az megadott e-mail cím formátuma helytelen");
      }

      if (valid) {

        let postData = {
          register: true,
          username: username,
          email: email,
          password_sha: sha512(password1)
        };

        let ajax_url = jsGlobals.baseUrl + "libraries/core/ajax/register.php";

        let xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            let response = this.responseText;

            if (response == "success") {
              alertSuccess("Registration successful");
              $("#register-form")[0].reset();
            } else {
              // get translation
              let error_message = "";

              switch (response) {
                case "duplicate_email":
                  error_message = document.getElementById("duplicate-email").value;
                  alertError(error_message);
                  break;
                case "duplicate_username":
                  error_message = document.getElementById("duplicate-username").value;
                  alertError(error_message);
                  break;
                case "mail_error":
                  error_message = document.getElementById("mail-error").value;
                  alertError(error_message);
                  break;
                default:
                  console.error(response);
                  break;
              }
            }
          }
        };

        xhttp.open("POST", ajax_url, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(post(postData));

      } else {
        let error_time = error.length * 3000;
        let error_html = "";

        error.forEach(function (e) {
          error_html += e + "<br>";
        });
        alertError(error_html, error_time);
      }
    });
  }


  // TODO: FORGOTTEN PASSWORD
  // Forgotten password

  let forgotten_form = document.getElementById("forgotten-form");

  if (forgotten_form != null) {
    forgotten_form.addEventListener("submit", function (e) {
      e.preventDefault();

      let postData = {
        forgotten_password: true,
        email: document.getElementById("forgotten-email").value
      };

      let ajax_url = jsGlobals.baseUrl + "libraries/core/ajax/login.php";

      let xhttp = new XMLHttpRequest();

      xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          let response = this.responseText;

          if (response == "success") {
            let success_message = document.getElementById("forgotten-success").value;
            alertSuccess(success_message);
          } else {
            let error_message = "";
            switch (response) {
              case "email_not_exist":
                error_message = document.getElementById("email-not-exist").value;
                alertError(error_message);
                break;
              case "mail_error":
                error_message = document.getElementById("mail-error").value;
                alertError(error_message);
                break;
              default:
                console.error(response);
                break;
            }
          }
        }
      };

      xhttp.open("POST", ajax_url, true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send(post(postData));
    });
  }
};