<?php
  if(!isset($_SESSION)) {
      session_start();
  }
  if(!isset($_SESSION["loggedin"])) {
    $_SESSION["loggedin"] = false;
  }
  $link = $_SERVER['REQUEST_URI'];
  $link = substr($link, strrpos($link, "/")+1, strpos($link, ".php")-strrpos($link, "/")+3);
?>
<nav class="navbar sticky-top navbar-expand-lg navbar-light justify-content-center">
  <div class="container-fluid">
    <a class="navbtn navbar-brand" href="../index.php">Galeria</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>
