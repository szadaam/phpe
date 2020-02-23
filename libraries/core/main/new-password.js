$(document).on("submit", "#new-password-form", function (e) {

  e.preventDefault();

  let password1 = $("#password1").val();
  let password2 = $("#password2").val();

  let error = [];
  
  let valid = true;

  if (password1 != password2) {
    error.push("A jelszavak nem egyeznek");
    valid = false;
  }

  let password_check = validate_password(password1);
  let password_valid = password_check == "success" ? true : false;

  if (!password_valid) {
    valid = false;
    error.push(password_check);
  }

  if (valid) {
    let postData = {
      new_password: true,
      password: sha512($("#password1").val())
    };
    $.ajax({
      type: "POST",
      url: "libraries/core/ajax/register.php",
      data: postData,
      success: function (response) {
        response = response.trim();
        console.log(response);
        if (response === "success") {
          alert("Jelszó sikeresen megváltoztatva");
          redirect(jsGlobals.baseUrl);
        }
      }
    });
  } else {
    // hibaüzenet kiírása
    // !!!:
    console.error(error);
  }

});


