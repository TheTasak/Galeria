<?php
  session_start();
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm-password"]);
    $email = trim($_POST["email"]);

    $username_exists = false;

    require("include/database_connect.php");

    if(!empty($username) && !empty($password) && !empty($confirm_password) && !empty($email)) {
      $sql = "SELECT id FROM uzytkownicy WHERE login = ?";
      if($stmt = mysqli_prepare($mysqli, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = trim($_POST["username"]);
        if(mysqli_stmt_execute($stmt)){
          mysqli_stmt_store_result($stmt);
          if(mysqli_stmt_num_rows($stmt)){
              $username_exists = true;
          }
        } else {
            echo "Coś poszło nie tak. Spróbuj jeszcze raz.";
        }
        mysqli_stmt_close($stmt);
      }
      if($username_exists == false) {
        $sql = "INSERT INTO uzytkownicy (login, haslo, email) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
          mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);
          $param_username = $username;
          $param_password = password_hash($password, PASSWORD_DEFAULT);
          $param_email = $email;

          if(mysqli_stmt_execute($stmt)){
              $_SESSION["loggedin"] = true;
              $_SESSION["username"] = $username;

              $query = "SELECT LAST_INSERT_ID();";
              $result = mysqli_query($mysqli, $query);
              $last_insert_id = $result->fetch_assoc();
              $last_insert_id = reset($last_insert_id);

              $_SESSION["id"] = $last_insert_id;
              $_SESSION["active"] = true;
              $_SESSION["role"] = "uzytkownik";
              header("location: rejestracja-ok.php");
          } else {
              header("location: logrej.php");
          }
          mysqli_stmt_close($stmt);
        }
      } else {
        header("location: logrej.php");
      }
      mysqli_close($mysqli);
    }
  }
?>
