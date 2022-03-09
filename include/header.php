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
    <a class="navbtn navbar-brand" href="index.php">Galeria</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <?php print($link == "dodaj-album.php" ? "<strong>" : " "); ?>
          <a class="nav-link" href="dodaj-album.php">Załóż album</a>
          <?php print($link == "dodaj-album.php" ? "</strong>" : " "); ?>
        </li>
        <li class="nav-item">
          <?php print($link == "dodaj-foto.php" ? "<strong>" : " "); ?>
          <a class="nav-link" href="dodaj-foto.php">Dodaj zdjęcie</a>
          <?php print($link == "dodaj-foto.php" ? "</strong>" : " "); ?>
        </li>
        <li class="nav-item">
          <?php print($link == "top-foto.php" ? "<strong>" : " "); ?>
          <a class="nav-link" href="top-foto.php">Najlepiej oceniane</a>
          <?php print($link == "top-foto.php" ? "</strong>" : " "); ?>
        </li>
        <li class="nav-item">
          <?php print($link == "nowe-foto.php" ? "<strong>" : " "); ?>
          <a class="nav-link" href="nowe-foto.php">Najnowsze</a>
          <?php print($link == "nowe-foto.php" ? "</strong>" : " "); ?>
        </li>
        <?php
          if($_SESSION["loggedin"] == true) {
            print("<li class='nav-item'><a class='nav-link' href='konto.php'>" . ($link == "konto.php" ? "<strong>" : " ") . "Moje konto" . ($link == "konto.php" ? "</strong>" : " ") ."</a></li>");
          }
          if($_SESSION["loggedin"] == false) {
            print("<li class='nav-item'><a class='nav-link' href='logrej.php'>" . ($link == "logrej.php" ? "<strong>" : " ") . "Zaloguj się" . ($link == "logrej.php" ? "</strong>" : " ") ."</a></li>");
            print("<li class='nav-item'><a class='nav-link' href='logrej.php'>" . ($link == "logrej.php" ? "<strong>" : " ") . "Rejestracja" . ($link == "logrej.php" ? "</strong>" : " ") ."</a></li>");
          }
          if($_SESSION["loggedin"] == true) {
            print("<li class='nav-item'><a class='nav-link' href='wyloguj.php'>" . ($link == "wyloguj.php" ? "<strong>" : " ") . "Wyloguj się" . ($link == "wyloguj.php" ? "</strong>" : " ") ."</a></li>");
          }
          if($_SESSION["loggedin"] == true) {
            if($_SESSION["role"] == "moderator" || $_SESSION["role"] == "administrator") {
              print("<li class='nav-item'><a class='nav-link' href='administrator/index.php'>" . ($link == "administrator/index.php" ? "<strong>" : " ") . "Panel administratora" . ($link == "administrator/index.php" ? "</strong>" : " ") ."</a></li>");
            }
          }
        ?>
      </ul>
    </div>
  </div>
</nav>
