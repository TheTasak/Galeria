function checkRegisterFormSubmit() {
  let username = String(document.forms["register-form"]["username"].value);
  let password = String(document.forms["register-form"]["password"].value);
  let confirm_password = String(document.forms["register-form"]["confirm-password"].value);
  let warning_div = document.getElementById("register-form").getElementsByClassName("warning-form-div")[0];
  let username_warning = /^[A-Za-z0-9]{8,16}$/.test(username);
  let password_warning = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/.test(password);
  let password_validate = password == confirm_password;
  if(!username_warning) {
    warning_div.innerHTML = "Nazwa użytkownika musi mieć od 8 do 16 znaków i zawierać tylko litery i cyfry";
  } else if(!password_warning) {
    warning_div.innerHTML = "Hasło musi mieć od 8 do 20 znaków i zawierać minimum 1 dużą literę, 1 małą literę i 1 cyfrę";
  } else if(!password_validate) {
    warning_div.innerHTML = "Hasła muszą się zgadzać";
  } else {
    warning_div.innerHTML = "";
  }
  let validate = (username_warning && password_warning && password_validate);
  return validate;
}
function checkLoginFormSubmit() {
  let username = String(document.forms["login-form"]["username"].value);
  let password = String(document.forms["login-form"]["password"].value);
  let warning_div = document.getElementById("login-form").getElementsByClassName("warning-form-div")[0];
  let username_warning = (username != "");
  let password_warning = (password != "");
  if(!username_warning) {
    warning_div.innerHTML = "Wpisz nazwę użytkownika";
  } else if(!password_warning) {
    warning_div.innerHTML = "Wpisz hasło";
  } else {
    warning_div.innerHTML = "";
  }
  let validate = (username_warning && password_warning);
  return validate;
}
function checkAlbumSubmit() {
  let title = String(document.forms["album-form"]["title"].value).trim();
  let warning_div = document.getElementById("album-form").getElementsByClassName("warning-form-div")[0];

  let title_length_warning = title.length < 100;
  let title_empty_warning = title.length != 0;
  if(!title_length_warning) {
    warning_div.innerHTML = "Tytuł musi mieć co najwyżej 100 znaków";
  } else if(!title_empty_warning) {
    warning_div.innerHTML = "Tytuł nie może być pusty";
  } else {
    warning_div.innerHTML = "";
  }
  let validate = (title_empty_warning && title_length_warning);
  return validate;
}
function checkImageSubmit() {
  let filename = String(document.forms["image-form"]["filename"].value).trim();
  filename = filename.substr(filename.indexOf('.') + 1);
  let description = String(document.forms["image-form"]["textarea"].value).trim();
  let warning_div = document.getElementById("image-form").getElementsByClassName("warning-form-div")[0];
  let filename_warning = filename == "png" || filename == "jpg" || filename == "gif" || filename == "jpeg";
  let description_length_warning = description.length < 255;
  if(!filename_warning) {
    warning_div.innerHTML = "Plik nie jest plikiem graficznym";
  } else if(!description_length_warning) {
    warning_div.innerHTML = "Opis nie może być dłuższy niż 255 znaków";
  } else {
    warning_div.innerHTML = "";
  }
  let validate = (filename_warning && description_length_warning);
  return validate;
}
function checkChangeAccountData() {
  let new_password = String(document.forms["change-data-form"]["password-new"].value).trim();
  let new_email = String(document.forms["change-data-form"]["email"].value).trim();
  let warning_div = document.getElementById("change-data-form").getElementsByClassName("warning-form-div")[0];

  let password_or_email_warning = new_password.length != 0 || new_email.length != 0;
  let password_warning = new_password.length != 0 ? /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/.test(new_password) : 1;
  if(!password_or_email_warning) {
    warning_div.innerHTML = "Nowe hasło lub nowy e-mail nie mogą być puste";
  } else if(!password_warning) {
    warning_div.innerHTML = "Hasło musi mieć od 8 do 20 znaków i zawierać minimum 1 dużą literę, 1 małą literę i 1 cyfrę";
  } else {
    warning_div.innerHTML = "";
  }
  let validate = (password_or_email_warning && password_warning);
  return validate;
}
