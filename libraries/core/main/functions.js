// FUNCTIONS

function debug() {

  let debug_content = $("#debug-container").html();
  if (debug_content == undefined) {
    return null;
  } else {
    console.log(debug_content.replaceExtended("&gt;", ">"));
  }

  return "done.";
}

// core functions

function getJsGlobals() {

  let globals = $('#js-globals').serializeArray().reduce(function (obj, item) {

    if (isNaN(parseFloat(item.value))) {
      obj[item.name] = item.value;
    } else {
      obj[item.name] = parseFloat(item.value);
    }

    return obj;
  }, {});

  globals.isMobile = globals.isMobile != "";

  return globals;
}

function isset(e) {
  return typeof e != "undefined";
}

function is_empty(array) {
  return array.length == 0;
}

function redirect(url) {
  window.location.replace(url);
}

function redirect_timeout(url, timeout) {

  setTimeout(function () {
    window.location.replace(url);
  }, timeout);

}

// prepare header for ajax call

function post(data) {

  let i = 0;
  let result = "";

  for (const [key, value] of Object.entries(data)) {

    if (i > 0) {
      result += "&";
    }

    result += key + "=" + value;
    i++;
  }

  return result;
}

function validate_email(email) {

  let result = {};

  if (email.length == 0) {

    result.r = false;
    result.message = "Az e-mail cím mező nem maradhat üresen.";

    return result;
  }

  if (email.length > 100) {

    result.r = false;
    result.message = "Az e-mail címed nem lehet több mint 100 karakter.";

    return result;
  }

  let open = false;
  let word = "";

  for (let i = 0; i < email.length; i++) {

    let a = email[i].charCodeAt();

    if (a == 34) {
      if (!open) {
        word = "";
        open = true;
      } else {
        word = "";
        open = false;
      }
    } else {
      word += email[i];
    }

    let v0 = a == 42 || a == 43 || a == 61 || a == 94 || a == 95 || a == 96;
    let v1 = a >= 33 && a <= 39;
    let v2 = a >= 45 && a <= 57;
    let v3 = a >= 63 && a <= 90;
    let v4 = a >= 97 && a <= 126;
    let legal = false;

    if (v0 || v1 || v2 || v3 || v4 || open) {
      legal = true;
    } else {
      legal = false;
    }
    if (!legal) {

      result.r = false;
      result.message = "Illegális karakter az e-mail címben.";

      return result;
    }
  }

  let error = "Az e-mail cím formátuma helytelen.";
  let address = email.split("@");
  let local = "";

  if (address.length == 1) {

    result.r = false;
    result.message = error;

    return result;
  } else {

    for (let i = 0; i < address.length - 1; i++) {

      let part = address[i];

      if (i < address.length - 2) {
        part += "@";
      }

      local += part;
    }
  }

  let local_ok = local.length == 0
          || local.length > 64
          || local.indexOf("..") != -1
          || word_open(local);

  if (local_ok) {
    result.r = false;
    result.message = error;

    return result;
  }

  let domain = address[address.length - 1];

  if (domain.length == 0 || domain.indexOf(".") == -1 || domain.indexOf("..") != -1) {

    result.r = false;
    result.message = error;

    return result;
  }

  let host = domain.split(".").pop();

  if (word_open(host)) {

    result.r = false;
    result.message = error;

    return result;
  }

  let country = domain.split(".")[domain.split(".").length - 1];

  if (country.length < 2 || country.indexOf('"') == 1) {

    result.r = false;
    result.message = error;

    return result;
  }

  result.r = true;
  result.message = "success";

  return result;
}

function validate_password(password) {

  let result = {};
  let jsGlobals = getJsGlobals();
  let numbers_valid = false;
  let special_valid = false;
  let uppercases_valid = false;

  if (password.length < jsGlobals.passwordLength) {

    result.r = false;
    result.message = "The password must be minimum " + jsGlobals.passwordLength + " charachters long.";

    return result;
  }

  //numbers

  if (jsGlobals.passwordNumbers > 0) {

    let pattern = "1234567890";
    let count = 0;

    for (let i = 0; i < password.length; i++) {

      let contains = pattern.includes(password[i]);

      if (contains) {

        if (count >= jsGlobals.passwordNumbers - 1) {

          numbers_valid = true;
          break;
        } else {
          count++;
        }
      }
    }

    if (!numbers_valid) {

      result.r = false;
      result.message = "The password must contain at least " + jsGlobals.passwordNumbers + " numeric charachter.";

      return result;
    }
  }

  // special charachters

  if (jsGlobals.passwordSpecialchars > 0) {

    pattern = ",.-§'+!%/=()_:?$ß;<>*÷{}[]Đäđ|Ä€";
    count = 0;

    for (let i = 0; i < password.length; i++) {

      let contains = pattern.includes(password[i]);

      if (contains) {

        if (count >= jsGlobals.passwordSpecialchars - 1) {

          special_valid = true;
          break;
        } else {
          count++;
        }
      }
    }

    if (!special_valid) {

      result.r = false;
      result.message = "The password must contain at least " + jsGlobals.passwordSpecialchars + " special charachters. The special charachters are: ,.-§'+!%/=()_:?$ß;<>*÷";

      return result;
    }
  }

  // uppercases

  if (jsGlobals.passwordUppercases > 0) {

    pattern = "QWERTZUIOPASDFGHJKLYXCVBNM";
    count = 0;

    for (let i = 0; i < password.length; i++) {

      let contains = pattern.includes(password[i]);

      if (contains) {

        if (count >= jsGlobals.passwordUppercases - 1) {
          uppercases_valid = true;
          break;
        } else {
          count++;
        }
      }
    }

    if (!uppercases_valid) {

      result.r = false;
      result.message = "The password must contain at least " + jsGlobals.passwordUppercases + " uppercases.";

      return result;
    }
  }

  result.r = true;
  result.message = "success";

  return result;
}

function word_open(word) {

  if (word.length > 0) {

    let one_word = word[0].charCodeAt() == 34 && word[word.length - 1].charCodeAt() == 34;

    if (one_word) {
      return false;
    }
  }

  let open = false;
  let part = "";

  for (let i = 0; i < word.length; i++) {

    if (word[i].charCodeAt() == 34) {

      if (!open) {

        open = true;
        part = "";
      } else {

        open = false;
        part = "";
      }
    }

    part += word[i];
  }

  return open;
}

// e metódus meghívásával lekérhető hogy mobil eszközről böngészünk-e

function isMobile() {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function isFireFox() {
  return navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
}

// EXTENSIONS

String.prototype.replaceExtended = function (search, replacement) {
  return this.split(search).join(replacement);
};


