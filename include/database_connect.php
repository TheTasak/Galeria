<?php
  $mysqli = mysqli_connect("localhost", "root", "", "tasarz_4ta");
  if(mysqli_connect_errno()) {
     echo "Błąd połączenia nr: " . mysqli_connect_errno();
     echo "Opis błędu: " . mysqli_connect_error();
     exit();
  }
?>
