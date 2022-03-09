<?php
  if(!isset($_SESSION)) {
      session_start();
  }
  require("include/database_connect.php");
  if($_SESSION["loggedin"] == false) {
    header("location: logrej.php");
  }
  if(isset($_GET["dane"]) || !isset($_SESSION["konto_button"])) {
    $_SESSION["konto_button"] = "dane";
  } elseif(isset($_GET["albumy"])) {
    $_SESSION["konto_button"] = "albumy";
  } elseif(isset($_GET["zdjecia"])) {
    $_SESSION["konto_button"] = "zdjecia";
  } elseif(isset($_GET["konto"])) {
    $_SESSION["konto_button"] = "konto";
  }
  if(isset($_POST["password-old"])) {
    $query = "SELECT haslo FROM uzytkownicy WHERE id=" . $_SESSION["id"] . ";";
    $result = mysqli_query($mysqli, $query);
    $hashed_password = trim($result->fetch_assoc()["haslo"]);
    $password = trim($_POST["password-old"]);
    if(password_verify($password, $hashed_password)) {
      if(!empty(trim($_POST["password-new"]))) {
        $query = "UPDATE uzytkownicy SET haslo='" . password_hash(trim($_POST["password-new"]), PASSWORD_DEFAULT) . "' WHERE id=" . $_SESSION["id"] . ";";
        mysqli_query($mysqli, $query);
      } elseif (!empty(trim($_POST["email"]))) {
        $query = "UPDATE uzytkownicy SET email='" . trim($_POST["email"]) . "' WHERE id=" . $_SESSION["id"] . ";";
        mysqli_query($mysqli, $query);
      }
    }
  }
  if(isset($_POST["title"])) {
    $tytul = $_POST["title"];
    $tytul = addslashes($tytul);
    $query = "UPDATE albumy SET tytul='" . $tytul . "' WHERE id=" . $_POST["album-id"] . ";";
    mysqli_query($mysqli, $query);
  }
  if(isset($_POST["desc"])) {
    $opis = $_POST["desc"];
    $opis = addslashes($opis);
    $query = "UPDATE zdjecia SET opis='" . $opis . "' WHERE id=" . $_POST["zdjecie-id"] . ";";
    mysqli_query($mysqli, $query);
  }
  if(isset($_POST["delete-album"]) && isset($_GET["album"])) {
    //#######################################################################
    //USUWANIE SCIEZKI ALBUMU
    array_map('unlink', glob("photo/" . $_GET["album"] ."/*"));
    rmdir("photo/" . $_GET["album"]);
    //#######################################################################

    $query = "SELECT id FROM zdjecia WHERE id_albumu=" . $_GET["album"] . ";";
    $result = mysqli_query($mysqli, $query);
    $zdjecia = array();
    while($temp = $result->fetch_assoc()) {
      if(null !== reset($temp)){
        $zdjecia[] = $temp["id"];
      }
    }
    for($i = 0; $i < count($zdjecia); $i++) {
      $query = "DELETE FROM zdjecia_oceny WHERE id_zdjecia=" . $zdjecia[$i] . ";";
      mysqli_query($mysqli, $query);

      $query = "DELETE FROM zdjecia_komentarze WHERE id_zdjecia=" . $zdjecia[$i] . ";";
      mysqli_query($mysqli, $query);
    }
    $query = "DELETE FROM zdjecia WHERE id_albumu=" . $_GET["album"] . ";";
    mysqli_query($mysqli, $query);

    $query = "DELETE FROM albumy WHERE id=" . $_GET["album"] . ";";
    mysqli_query($mysqli, $query);
    header("location: konto.php");
  }
  if(isset($_POST["delete-photo"]) && isset($_GET["zdjecie"])) {
    $query = "SELECT id_albumu FROM zdjecia WHERE id=" . $_GET["zdjecie"] . ";";
    $result = mysqli_query($mysqli, $query);
    $result = $result->fetch_assoc();

    unlink("photo/" . $result["id_albumu"] . "/" . $_GET["zdjecie"]);
    unlink("photo/" . $result["id_albumu"] . "/" . $_GET["zdjecie"] . "-min");

    $query = "DELETE FROM zdjecia_oceny WHERE id_zdjecia=" . $_GET["zdjecie"] . ";";
    mysqli_query($mysqli, $query);

    $query = "DELETE FROM zdjecia_komentarze WHERE id_zdjecia=" . $_GET["zdjecie"] . ";";
    mysqli_query($mysqli, $query);

    $query = "DELETE FROM zdjecia WHERE id=" . $_GET["zdjecie"] . ";";
    mysqli_query($mysqli, $query);
    header("location: konto.php");
  }
  if(isset($_POST["delete-account"])) {
    $query = "SELECT id FROM albumy WHERE id_uzytkownika=" . $_SESSION["id"] . ";";
    $result = mysqli_query($mysqli, $query);
    $albumy = array();
    while($temp = $result->fetch_assoc()) {
      if(null !== reset($temp)){
        $albumy[] = $temp["id"];
      }
    }
    for($i = 0; $i < count($albumy); $i++) {
      array_map('unlink', glob("photo/" . $albumy[$i] ."/*"));
      rmdir("photo/" . $albumy[$i]);

      $query = "SELECT id FROM zdjecia WHERE id_albumu=" . $albumy[$i] . ";";
      $result = mysqli_query($mysqli, $query);
      $zdjecia = array();
      while($temp = $result->fetch_assoc()) {
        if(null !== reset($temp)){
          $zdjecia[] = $temp["id"];
        }
      }
      for($i = 0; $i < count($zdjecia); $i++) {
        $query = "DELETE FROM zdjecia_oceny WHERE id_zdjecia=" . $zdjecia[$i] . ";";
        mysqli_query($mysqli, $query);

        $query = "DELETE FROM zdjecia_komentarze WHERE id_zdjecia=" . $zdjecia[$i] . ";";
        mysqli_query($mysqli, $query);
      }
      $query = "DELETE FROM zdjecia WHERE id_albumu=" . $albumy[$i] . ";";
      mysqli_query($mysqli, $query);

      $query = "DELETE FROM albumy WHERE id=" . $albumy[$i] . ";";
      mysqli_query($mysqli, $query);
    }

    $query = "DELETE FROM zdjecia_oceny WHERE id_uzytkownika=" . $_SESSION["id"] . ";";
    mysqli_query($mysqli, $query);

    $query = "DELETE FROM zdjecia_komentarze WHERE id_uzytkownika=" . $_SESSION["id"] . ";";
    mysqli_query($mysqli, $query);

    $query = "DELETE FROM uzytkownicy WHERE id=" . $_SESSION["id"] . ";";
    mysqli_query($mysqli, $query);

    header("location: wyloguj.php");
  }
?>
<html>
  <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <script src="javascript/init.js" defer></script>
    <script src="javascript/formcheck.js" defer></script>
  </head>
  <body>
    <?php include "./include/header.php" ?>
    <br>
    <form method="get">
      <div class="container">
        <button name="dane" type="submit" class="btn <?php echo $_SESSION["konto_button"] == "dane" ? "btn-primary" : "btn-secondary"; ?>">Moje dane</button>
        <button name="albumy" type="submit" class="btn <?php echo $_SESSION["konto_button"] == "albumy" ? "btn-primary" : "btn-secondary"; ?>">Moje albumy</button>
        <button name="zdjecia" type="submit" class="btn <?php echo $_SESSION["konto_button"] == "zdjecia" ? "btn-primary" : "btn-secondary"; ?>">Moje zdjęcia</button>
        <button name="konto" type="submit" class="btn <?php echo $_SESSION["konto_button"] == "konto" ? "btn-primary" : "btn-secondary"; ?>">Usuń konto</button>
      </div>
    </form>
      <?php
        $not_clicked_buttons = !isset($_GET["dane"]) && !isset($_GET["albumy"]) && !isset($_GET["zdjecia"]) && !isset($_GET["konto"]);
        if(isset($_GET["album"]) && $not_clicked_buttons) {
          $query = "SELECT tytul FROM albumy WHERE id=" . $_GET["album"] . ";";
          $result = mysqli_query($mysqli, $query);
          $result = $result->fetch_assoc();
          print("<h3 class='mb-3 text-center'>" . $result["tytul"] . "</h3>");
          print("<div class='container-sm'>");
          print("<form class='form' method='post' action='konto.php?album=" . $_GET["album"] . "'>");
          print("<h4 class='mb-3'>Zmień nazwę</h4>");
          print("<input type='hidden' name='album-id' value='" . $_GET["album"] . "'>");
          print("<div class='form-floating mb-3 input-short'><input type='text' name='title' id='title' class='form-control' required><label for='title'>Nazwa albumu</label></div>");
          print("<div class='form-floating mb-3 input-short'><button type='submit' class='btn btn-primary'>Potwierdź</button></div>");
          print("</form>");
          print("<form class='form' method='post' action='konto.php?album=" . $_GET["album"] . "'>");
          print("<h4 class='mb-3'>Usuń album</h4>");
          print("<div class='form-floating mb-3 input-short'><button name='delete-album' type='submit' class='btn btn-danger'>Usuń</button></div>");
          print("</form>");
          print("</div>");
        } elseif(isset($_GET["zdjecie"]) && $not_clicked_buttons) {
          $query = "SELECT opis, id_albumu FROM zdjecia WHERE id=" . $_GET["zdjecie"] . ";";
          $result = mysqli_query($mysqli, $query);
          $result = $result->fetch_assoc();
          print("<div class='img-full-size-div'>");
          print("<img class='img-full-size' src='photo/" . $result["id_albumu"] . "/" . $_GET["zdjecie"] . "'>");
          print("</div>");
          print("<p class='lead mt-3 text-center'>" . $result["opis"] . "</p>");
          print("<div class='container-sm'>");
          print("<form class='form' method='post' action='konto.php?zdjecie=" . $_GET["zdjecie"] . "'>");
          print("<h4 class='mb-3'>Zmień opis</h4>");
          print("<input type='hidden' name='zdjecie-id' value='" . $_GET["zdjecie"] . "'>");
          print("<div class='form-floating mb-3 input-short'><input type='text' name='desc' id='desc' class='form-control' required><label for='title'>Opis zdjęcia</label></div>");
          print("<div class='form-floating mb-3 input-short'><button type='submit' class='btn btn-primary'>Potwierdź</button></div>");
          print("</form>");
          print("<form class='form' method='post' action='konto.php?zdjecie=" . $_GET["zdjecie"] . "'>");
          print("<h4 class='mb-3'>Usuń zdjęcie</h4>");
          print("<div class='form-floating mb-3 input-short'><button name='delete-photo' type='submit' class='btn btn-danger'>Usuń</button></div>");
          print("</form>");
          print("</div>");
        } elseif($_SESSION["konto_button"] == "dane" || $not_clicked_buttons) {
          print("<div class='container-sm'>");
          print("<form id='change-data-form' class='form' method='post' onsubmit='return checkChangeAccountData()'>");
          print("<h4 class='mb-3'>Zmień dane</h4>");
          print("<div class='form-floating mb-3 input-short'><input type='password' name='password-old' id='password-old' class='form-control' required><label for='password-old'>Stare hasło</label></div>");
          print("<div class='form-floating mb-3 input-short'><input type='password' name='password-new' id='password-new' class='form-control'><label for='password-new'>Nowe hasło</label></div>");
          print("<div class='form-floating mb-3 input-short'><input type='email' class='form-control' name='email' id='email'><label for='email'>Nowy e-mail</label></div>");
          print("<div class='mb-3 warning-form-div'></div>");
          print("<div class='form-floating mb-3 input-short d-flex justify-content-center'><button type='submit' class='btn btn-primary'>Potwierdź</button></div>");
          print("</form>");
          print("</div>");
        } elseif($_SESSION["konto_button"] == "albumy") {
          $query = "SELECT id, tytul FROM albumy WHERE id_uzytkownika=" . $_SESSION["id"] . ";";
          $result = mysqli_query($mysqli, $query);
          $albumy = array();
          while($temp = $result->fetch_assoc()) {
            if(null !== reset($temp)){
              $albumy[] = $temp;
            }
          }
          print("<div class='container-sm'>");
          print("<ul class='list-group'>");
          for($i = 0; $i < count($albumy); $i++) {
            print("<li class='list-group-item list-group-item-action'><a href='konto.php?album=" . $albumy[$i]["id"] . "'>" . $albumy[$i]["tytul"] . "</a></li>");
          }
          print("</div>");
        } elseif($_SESSION["konto_button"] == "zdjecia") {
          $query = "SELECT id, tytul FROM albumy WHERE id_uzytkownika=" . $_SESSION["id"] . ";";
          $result = mysqli_query($mysqli, $query);
          $albumy = array();
          while($temp = $result->fetch_assoc()) {
            if(null !== reset($temp)){
              $albumy[] = $temp;
            }
          }
          $zdjecia = array();
          for($i = 0; $i < count($albumy); $i++) {
            $query = "SELECT id AS zdjecie FROM zdjecia WHERE id_albumu=" . $albumy[$i]["id"] . ";";
            $result = mysqli_query($mysqli, $query);
            while($temp = $result->fetch_assoc()) {
              if(null !== reset($temp)){
                $temp["album"] = $albumy[$i]["id"];
                $zdjecia[] = $temp;
              }
            }
          }
          print('<div class="container">');
          for($i = 0; $i < count($zdjecia); $i++) {
            print("<div><a href='konto.php?zdjecie=" . $zdjecia[$i]["zdjecie"] . "'><img src='photo/" . $zdjecia[$i]["album"] . "/" . $zdjecia[$i]["zdjecie"] . "'  alt='thumbnail' width='180' height='180'></a></div>");
          }
          print('</div>');
        } elseif($_SESSION["konto_button"] == "konto") {
          print("<div class='container-sm'>");
          print("<form class='form' method='post'>");
          print("<h4 class='mb-3'>Usuń konto</h4>");
          print("<div class='form-floating mb-3 input-short'><button name='delete-account' type='submit' class='btn btn-danger'>Usuń</button></div>");
          print("</form>");
          print("</div>");
        }
      ?>
    <?php include "./include/footer.php" ?>
  </body>
</html>
