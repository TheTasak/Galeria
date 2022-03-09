<?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    require("include/database_connect.php");

    if(!empty($username) && !empty($password)) {
      $sql = "SELECT id, login, haslo, aktywny, uprawnienia FROM uzytkownicy WHERE login = ?";
      if($stmt = mysqli_prepare($mysqli, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        if(mysqli_stmt_execute($stmt)){
          mysqli_stmt_store_result($stmt);
          if(mysqli_stmt_num_rows($stmt) == 1){
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $active, $role);
            if(mysqli_stmt_fetch($stmt)){
                if(password_verify($password, $hashed_password)){
                  session_start();
                  $_SESSION["loggedin"] = true;
                  $_SESSION["id"] = $id;
                  $_SESSION["username"] = $username;
                  $_SESSION["active"] = $active;
                  $_SESSION["role"] = $role;

                  header("location: index.php");
                } else {
                  echo "Nieprawidłowa nazwa użytkownika lub hasło.";
                  print(" <a href='logrej.php'>Wróć do strony logowania</a>");
                }
            }
          } else {
            echo "Nieprawidłowa nazwa użytkownika lub hasło.";
            print(" <a href='logrej.php'>Wróć do strony logowania</a>");
          }
        } else {
            echo "Coś poszło nie tak. Spróbuj jeszcze raz.";
            print(" <a href='logrej.php'>Wróć do strony logowania</a>");
        }
        mysqli_stmt_close($stmt);
      }
      mysqli_close($mysqli);
    }
  }
?>
