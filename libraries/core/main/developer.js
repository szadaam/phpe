// Developer functions

function getSession() {

  let postData = {
    get_session: true
  };

  $.ajax({
    type: "POST",
    url: AJAX_URL,
    data: postData,
    success: function (response) {
      response = response.trim();
      console.log(response);
    }
  });

  return "getting session...";
}

function updateUser() {

  $.ajax({
    type: "POST",
    url: AJAX_URL,
    data: {update_user: true},
    success: function (response) {
      if (response == "success") {
        console.log("done.");
      }
    }
  });

  return "requested";
}

function getUser() {

  $.ajax({
    type: "POST",
    url: AJAX_URL,
    data: {get_user: true},
    success: function (response) {
      console.log(response);
    }
  });

  return "requested";
}

const AJAX_URL = jsGlobals.baseUrl + "libraries/core/ajax/developer-ajax.php";