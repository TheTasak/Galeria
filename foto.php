<?php
  if(!isset($_SESSION)) {
      session_start();
  }
  require("include/database_connect.php");
  if(isset($_GET["album"]) && isset($_GET["foto"])) {
    $query = "SELECT * FROM zdjecia WHERE id_albumu=" . $_GET["album"] ." AND zaakceptowane=1 ORDER BY data DESC;";
    $result = mysqli_query($mysqli, $query);
    $album_fotos = array();
    while($temp = $result->fetch_assoc()) {
      if(null !== reset($temp)){
        $album_fotos[] = $temp;
      }
    }
    $current_foto = $album_fotos[intval($_GET["foto"])];
    if(isset($_POST["comment"])){
      $comment = addslashes($_POST["comment"]);
      $query = "INSERT INTO zdjecia_komentarze (id_zdjecia, id_uzytkownika, komentarz, zaakceptowany) VALUES (" . $current_foto["id"] . ", " . $_SESSION["id"] . ", '" . $comment . "', 0);";
      mysqli_query($mysqli, $query);
    }
    if(isset($_POST["grade-value"])) {
      $value = $_POST["grade-value"];
      $query = "SELECT * FROM zdjecia_oceny WHERE id_zdjecia=" . $current_foto["id"] . " AND id_uzytkownika=" . $_SESSION["id"] . ";";
      $result = mysqli_query($mysqli, $query);
      if(!$result->fetch_assoc()) {
        $query = "INSERT INTO zdjecia_oceny (id_zdjecia, id_uzytkownika, ocena) VALUES (" . $current_foto["id"] . ", " . $_SESSION["id"] . ", " . $_POST["grade-value"] . ");";
        mysqli_query($mysqli, $query);
      }
    }
  }
?>
<html>
  <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://kit.fontawesome.com/9bf5a65653.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <script src="javascript/formcheck.js" defer></script>
    <script src="javascript/stargrade.js" defer></script>
    <script src="javascript/init.js" defer></script>
  </head>
  <body>
    <?php include "./include/header.php" ?>
    <br>
      <?php
        if(isset($_GET["album"]) && isset($_GET["foto"])) {
          $query = "SELECT albumy.tytul, uzytkownicy.login FROM albumy INNER JOIN uzytkownicy WHERE albumy.id=" . $_GET["album"] ." AND albumy.id_uzytkownika=uzytkownicy.id;";
          $result = mysqli_query($mysqli, $query);
          $album_info = $result->fetch_assoc();


          print("<h3 class='text-center'>" . $album_info["tytul"] . "</h3>");
          print("<h5 class='text-center text-muted'>" . $current_foto["opis"] . "</h5>");
          print("<h5 class='text-center'>" . $current_foto["data"] . " " . $album_info["login"] ."</h5>" );
          print("<p class='text-center'><a href='album.php?album=" . $_GET["album"] . "'>Wróć do albumu</a></p>");

          print("<div class='img-full-size-div'>");
          if($_GET["foto"] > 0) {
            print("<a href='foto.php?album=" . $_GET["album"] . "&foto=" . (intval($_GET["foto"])-1) . "'><i class='fas fa-arrow-circle-left'></i></a>");
          }
          print("<img class='img-full-size' src='photo/" . $_GET["album"] . "/" . $current_foto["id"] . "'>");
          if($_GET["foto"] < count($album_fotos)-1) {
            print("<a href='foto.php?album=" . $_GET["album"] . "&foto=" . (intval($_GET["foto"])+1) . "'><i class='fas fa-arrow-circle-right'></i></a>");
          }
          print("</div>");
          //######################################################
          // OCENY
          $query = "SELECT AVG(ocena), COUNT(ocena) FROM zdjecia_oceny WHERE id_zdjecia=" . $current_foto["id"] . ";";
          $result = mysqli_query($mysqli, $query);
          $ocena = $result->fetch_assoc();
          if(null !== reset($ocena)) {
            print("<br>");
            print("<h4 class='text-center mt-5'>Ocena zdjęcia</h4><div class='container mb-2'>");
            for($i = 0; $i < intval($ocena["AVG(ocena)"]); $i++) {
              print("<i class='fas fa-star'></i>");
            }
            if(intval($ocena["AVG(ocena)"]) < round($ocena["AVG(ocena)"], 2)){
              print("<i class='fas fa-star-half-alt'></i>");
            } elseif(intval($ocena["AVG(ocena)"]) != 10) {
              print("<i class='far fa-star'></i>");
            }
            for($i = intval($ocena["AVG(ocena)"])+1; $i < 10; $i++) {
              print("<i class='far fa-star'></i>");
            }
            print("</div>");
            print("<p class='lead text-center'>Średnia: " . round($ocena["AVG(ocena)"], 2) . " - " . intval($ocena["COUNT(ocena)"]) ." oceniających</p>");
          } else {
            print("<h4 class='text-center mt-5'>Ocena zdjęcia</h4><div class='container mb-2'>");
            for($i = 0; $i < 10; $i++) {
              print("<i class='far fa-star'></i>");
            }
            print("</div>");
          }
          if($_SESSION["loggedin"]) {
            $query = "SELECT * FROM zdjecia_oceny WHERE id_zdjecia=" . $current_foto["id"] . " AND id_uzytkownika=" . $_SESSION["id"] . ";";
            $result = mysqli_query($mysqli, $query);
            $ocena = $result->fetch_assoc();
            if(isset($ocena)) {
              $ocena = intval($ocena["ocena"]);
              print("<h4 class='text-center mb-2'>Twoja ocena</h4><div class='container mb-3'>");
              for($i = 0; $i < $ocena; $i++) {
                print("<i class='fas fa-star'></i>");
              }
              for($i = $ocena; $i < 10; $i++) {
                print("<i class='far fa-star'></i>");
              }
              print("</div>");
            } else {
              print("<form id='grade-form' action='foto.php?album=" . $_GET["album"] . "&foto=" . $_GET["foto"] . "' method='post'>");
              print("<h4 class='text-center mb-2'>Twoja ocena</h4><div class='container mb-3'>");
              for($i = 0; $i < 10; $i++) {
                print("<i class='far fa-star' id='star-" . $i . "' onmouseover='hoverStars(this)' onclick='sendGrade(this)'></i>");
              }
              print("</div>");
              print("<input name='grade-value' id='grade-value' type='hidden' value=''>");
              print("</form>");
            }
          }
          //######################################################
          // KOMENTARZE
          $query = "SELECT uzytkownicy.login, zdjecia_komentarze.data, zdjecia_komentarze.komentarz FROM zdjecia_komentarze INNER JOIN uzytkownicy WHERE zdjecia_komentarze.id_zdjecia=" . $current_foto["id"] . " AND zdjecia_komentarze.id_uzytkownika=uzytkownicy.id AND zdjecia_komentarze.zaakceptowany=1 ORDER BY zdjecia_komentarze.data DESC;";
          $result = mysqli_query($mysqli, $query);
          $komentarze = array();
          while($temp = $result->fetch_assoc()) {
            if(null !== reset($temp)){
              $komentarze[] = $temp;
            }
          }
          print("<h4 class='text-center mb-2'>Komentarze</h4>");
          if(count($komentarze) == 0) {
            print("<h4 class='lead text-center'>Brak komentarzy. Bądź pierwszy i dodaj swój!</h4><br>");
          } else {
            for($i = 0; $i < count($komentarze); $i++) {
              print("<div class='container comment mb-3 flex-column'>");
              print("<span class='mt-3'><strong>" . $komentarze[$i]["login"] . " " . date("d.m.Y H:i" , strtotime($komentarze[$i]["data"])) . "</strong></span>");
              print("<span class='mb-3'>" . $komentarze[$i]["komentarz"] . "</span>");
              print("</div>");
            }
            print("<br>");
          }
          if($_SESSION["loggedin"]) {
            print('<div class="container-sm">');
            print('<form id="comment-form" action="foto.php?album=' . $_GET["album"] . '&foto=' . $_GET["foto"] . '" method="post" class="form" onsubmit="return checkCommentSubmit()">');
            print('<h3>Dodaj komentarz</h3>');
            print('<div class="form-floating mb-3 input-short">');
            print('<input type="text" name="comment" id="floatComment" placeholder="Treść komentarza" class="form-control" value="" required>');
            print('<label for="floatComment">Treść komentarza</label>');
            print('</div>');
            print('<div class="mb-3 warning-form-div"></div>');
            print('<div class="mb-3 d-flex justify-content-center">');
            print('<button type="submit" class="btn btn-primary btn-lg">Dodaj</button>');
            print('</div>');
            print('</form>');
            print('</div>');
            print("<br>");
          } else {
            print("<p class='lead text-center'>Aby dodawać komentarze <a href='logrej.php'>zaloguj się tutaj</a></p>");
            print("<br>");
          }
        }
      ?>
    <?php include "./include/footer.php" ?>
  </body>
</html>
