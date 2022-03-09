<?php
  if(!isset($_SESSION)) {
      session_start();
  }
  require("../include/database_connect.php");
  if($_SESSION["loggedin"] == false || $_SESSION["role"] == "uzytkownik") {
    header("location: ../index.php");
  }
  if(isset($_GET["powrot"])) {
    header("location: ../index.php");
  }
  if(isset($_GET["albumy"]) || !isset($_SESSION["button"])) {
    $_SESSION["button"] = "albumy";
  } elseif(isset($_GET["zdjecia"])) {
    $_SESSION["button"] = "zdjecia";
  } elseif(isset($_GET["komentarze"])) {
    $_SESSION["button"] = "komentarze";
  } elseif(isset($_GET["uzytkownicy"])) {
    $_SESSION["button"] = "uzytkownicy";
  }
  if(isset($_POST["delete-album"]) && $_SESSION["role"] == "administrator") {
    print("MysqlndUhPreparedStatement");
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
    header("location: index.php");
  }
  if(isset($_POST["title"]) && $_SESSION["role"] == "administrator") {
    $tytul = $_POST["title"];
    $tytul = addslashes($tytul);
    $query = "UPDATE albumy SET tytul='" . $tytul . "' WHERE id=" . $_GET["album"] . ";";
    mysqli_query($mysqli, $query);
  }
  if(isset($_POST["desc"])) {
    $opis = $_POST["desc"];
    $opis = addslashes($opis);
    $query = "UPDATE zdjecia SET opis='" . $opis . "' WHERE id=" . $_GET["zdjecie"] . ";";
    mysqli_query($mysqli, $query);
  }
  if(isset($_POST["accept-photo"])) {
    $query = "UPDATE zdjecia SET zaakceptowane=1 WHERE id=" . $_GET["zdjecie"] . ";";
    mysqli_query($mysqli, $query);
  }
  if(isset($_POST["delete-photo"])) {
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
    header("location: index.php");
  }
  if(isset($_GET["delete-comment"])) {
    $query = "DELETE FROM zdjecia_komentarze WHERE id=" . $_GET["delete-comment"] . ";";
    mysqli_query($mysqli, $query);
    header("location: index.php");
  }
  if(isset($_GET["accept-comment"])) {
    $query = "UPDATE zdjecia_komentarze SET zaakceptowany=1 WHERE id=" . $_GET["accept-comment"] . ";";
    mysqli_query($mysqli, $query);
    header("location: index.php");
  }
  if(isset($_GET["block-user"]) && $_SESSION["role"] == "administrator") {
    $query = "UPDATE uzytkownicy SET aktywny=0 WHERE id=" . $_GET["block-user"] . ";";
    mysqli_query($mysqli, $query);
    header("location: index.php");
  }
  if(isset($_GET["unblock-user"]) && $_SESSION["role"] == "administrator") {
    $query = "UPDATE uzytkownicy SET aktywny=1 WHERE id=" . $_GET["unblock-user"] . ";";
    mysqli_query($mysqli, $query);
    header("location: index.php");
  }
  if(isset($_GET["delete-user"]) && $_SESSION["role"] == "administrator") {
    $query = "SELECT id FROM albumy WHERE id_uzytkownika=" . $_GET["delete-user"] . ";";
    $result = mysqli_query($mysqli, $query);
    $albumy = array();
    while($temp = $result->fetch_assoc()) {
      if(null !== reset($temp)){
        $albumy[] = $temp["id"];
      }
    }
    for($i = 0; $i < count($albumy); $i++) {
      //#######################################################################
      //USUWANIE SCIEZKI ALBUMU
      array_map('unlink', glob("photo/" . $albumy[$i] ."/*"));
      rmdir("photo/" . $albumy[$i]);
      //#######################################################################

      $query = "SELECT id FROM zdjecia WHERE id_albumu=" . $albumy[$i] . ";";
      $result = mysqli_query($mysqli, $query);
      $zdjecia = array();
      while($temp = $result->fetch_assoc()) {
        if(null !== reset($temp)){
          $zdjecia[] = $temp["id"];
        }
      }
      for($j = 0; $j < count($zdjecia); $j++) {
        $query = "DELETE FROM zdjecia_oceny WHERE id_zdjecia=" . $zdjecia[$j] . ";";
        mysqli_query($mysqli, $query);

        $query = "DELETE FROM zdjecia_komentarze WHERE id_zdjecia=" . $zdjecia[$j] . ";";
        mysqli_query($mysqli, $query);
      }
      $query = "DELETE FROM zdjecia WHERE id_albumu=" . $albumy[$i] . ";";
      mysqli_query($mysqli, $query);

      $query = "DELETE FROM albumy WHERE id=" . $albumy[$i] . ";";
      mysqli_query($mysqli, $query);
    }

    $query = "DELETE FROM zdjecia_oceny WHERE id_uzytkownika=" . $_GET["delete-user"] . ";";
    mysqli_query($mysqli, $query);

    $query = "DELETE FROM zdjecia_komentarze WHERE id_uzytkownika=" . $_GET["delete-user"] . ";";
    mysqli_query($mysqli, $query);

    $query = "DELETE FROM uzytkownicy WHERE id=" . $_GET["delete-user"] . ";";
    mysqli_query($mysqli, $query);
  }
  if(isset($_POST["content"]) && $_SESSION["role"] == "administrator") {
    $content = $_POST["content"];
    $content = addslashes($content);
    $query = "UPDATE zdjecia_komentarze SET komentarz='" . $content ."' WHERE id=" . $_GET["edit-comment"] . ";";
    mysqli_query($mysqli, $query);
  }
  if(isset($_POST["privilege"]) && $_SESSION["role"] == "administrator") {
    $query = "UPDATE uzytkownicy SET uprawnienia='" . $_POST["privilege"] . "' WHERE id=" . $_GET["edit-user-privilege"] . ";";
    mysqli_query($mysqli, $query);
  }
?>
<html>
  <head>
    <script src="https://kit.fontawesome.com/9bf5a65653.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
  </head>
  <body>
    <?php include "../include/admin_header.php" ?>
    <br>
    <form method="get">
      <div class="container">
        <?php
          if($_SESSION["role"] == "administrator") {
            echo '<button name="albumy" type="submit" class="btn ' . ($_SESSION["button"] == "albumy" ? "btn-primary" : "btn-secondary") . '">Albumy</button>';
          }
        ?>
        <button name="zdjecia" type="submit" class="btn <?php echo $_SESSION["button"] == "zdjecia" ? "btn-primary" : "btn-secondary"; ?>">Zdjęcia</button>
        <button name="komentarze" type="submit" class="btn <?php echo $_SESSION["button"] == "komentarze" ? "btn-primary" : "btn-secondary"; ?>">Komentarze</button>
        <?php
          if($_SESSION["role"] == "administrator") {
            echo '<button name="uzytkownicy" type="submit" class="btn ' . ($_SESSION["button"] == "uzytkownicy" ? "btn-primary" : "btn-secondary") . '">Użytkownicy</button>';
          }
        ?>
        <button name="powrot" type="submit" class="btn btn-secondary">Powrót</button>
      </div>
    </form>
    <?php
      $not_clicked_buttons = !isset($_GET["albumy"]) && !isset($_GET["zdjecia"]) && !isset($_GET["komentarze"]) && !isset($_GET["uzytkownicy"]);
      if(isset($_GET["album"]) && $not_clicked_buttons) {
        $query = "SELECT tytul FROM albumy WHERE id=" . $_GET["album"] . ";";
        $result = mysqli_query($mysqli, $query);
        $result = $result->fetch_assoc();
        print("<h3 class='mb-3 text-center'>" . $result["tytul"] . "</h3>");
        print("<div class='container-sm'>");
        print("<form class='form' method='post' action='index.php?album=" . $_GET["album"] . "'>");
        print("<h4 class='mb-3'>Zmień nazwę</h4>");
        print("<input type='hidden' name='album-id' value='" . $_GET["album"] . "'>");
        print("<div class='form-floating mb-3 input-short'><input type='text' name='title' id='title' class='form-control' required><label for='title'>Nazwa albumu</label></div>");
        print("<div class='form-floating mb-3 input-short'><button type='submit' class='btn btn-primary'>Potwierdź</button></div>");
        print("</form>");
        print("<form class='form' method='post' action='index.php?album=" . $_GET["album"] . "'>");
        print("<h4 class='mb-3'>Usuń album</h4>");
        print("<div class='form-floating mb-3 input-short'><button name='delete-album' type='submit' class='btn btn-danger'>Usuń</button></div>");
        print("</form>");
        print("</div>");
      } elseif(isset($_GET["zdjecie"]) && $not_clicked_buttons) {
        $query = "SELECT opis, id_albumu, zaakceptowane FROM zdjecia WHERE id=" . $_GET["zdjecie"] . ";";
        $result = mysqli_query($mysqli, $query);
        $result = $result->fetch_assoc();
        print("<div class='img-full-size-div'>");
        print("<img class='img-full-size' src='//photo/" . $result["id_albumu"] . "/" . $_GET["zdjecie"] . "'>");
        print("</div>");
        print("<p class='lead mt-3 text-center'>" . $result["opis"] . "</p>");
        print("<div class='container-sm'>");
        print("<form class='form' method='post' action='index.php?zdjecie=" . $_GET["zdjecie"] . "'>");
        print("<h4 class='mb-3'>Zmień opis</h4>");
        print("<input type='hidden' name='zdjecie-id' value='" . $_GET["zdjecie"] . "'>");
        print("<div class='form-floating mb-3 input-short'><input type='text' name='desc' id='desc' class='form-control' required><label for='title'>Opis zdjęcia</label></div>");
        print("<div class='form-floating mb-3 input-short'><button type='submit' class='btn btn-primary'>Potwierdź</button></div>");
        print("</form>");
        if($result["zaakceptowane"] == "0") {
          print("<form class='form' method='post' action='index.php?zdjecie=" . $_GET["zdjecie"] . "'>");
          print("<h4 class='mb-3'>Zaakceptuj zdjęcie</h4>");
          print("<div class='form-floating mb-3 input-short'><button name='accept-photo' type='submit' class='btn btn-primary'>Zaakceptuj</button></div>");
          print("</form>");
        }
        print("<form class='form' method='post' action='index.php?zdjecie=" . $_GET["zdjecie"] . "'>");
        print("<h4 class='mb-3'>Usuń zdjęcie</h4>");
        print("<div class='form-floating mb-3 input-short'><button name='delete-photo' type='submit' class='btn btn-danger'>Usuń</button></div>");
        print("</form>");
        print("</div><br>");
      } elseif(isset($_GET["edit-user-privilege"]) && $not_clicked_buttons) {
        $query = "SELECT login FROM uzytkownicy WHERE id=" . $_GET["edit-user-privilege"] . ";";
        $result = mysqli_query($mysqli, $query);
        $result = $result->fetch_assoc();
        print("<h4 class='mb-3 text-center'>" . $result["login"] . "</h4>");
        print("<div class='container-sm'>");
        print("<form class='form' method='post' action='index.php?edit-user-privilege=" . $_GET["edit-user-privilege"] . "'>");
        print("<h4 class='mb-3'>Zmień typ użytkownika</h4>");
        print("<div class='form mb-3 input-short'><select name='privilege'><option value='uzytkownik'>Użytkownik</option><option value='moderator'>Moderator</option><option value='administrator'>Administrator</option></select></div>");
        print("<div class='form mb-3 input-short'><button type='submit' class='btn btn-primary'>Potwierdź</button></div>");
        print("</div>");
      } elseif(isset($_GET["album_foto_list"]) && $not_clicked_buttons) {
        if($_GET["album_foto_list"] == "-1") {
          $query = "SELECT id, id_albumu FROM zdjecia WHERE zaakceptowane=0";
          $result = mysqli_query($mysqli, $query);
          $zdjecia = array();
          while($temp = $result->fetch_assoc()) {
            if(null !== reset($temp)){
              $zdjecia[] = $temp;
            }
          }
          print('<div class="container">');
          for($i = 0; $i < count($zdjecia); $i++) {
            print("<div><a href='index.php?zdjecie=" . $zdjecia[$i]["id"] . "'><img src='/galeria/photo/" . $zdjecia[$i]["id_albumu"] . "/" . $zdjecia[$i]["id"] . "'  alt='thumbnail' width='180' height='180'></a></div>");
          }
          print('</div>');
        } else {
          $query = "SELECT id FROM zdjecia WHERE id_albumu=" . $_GET["album_foto_list"] . ";";
          $result = mysqli_query($mysqli, $query);
          $zdjecia = array();
          while($temp = $result->fetch_assoc()) {
            if(null !== reset($temp)){
              $zdjecia[] = $temp;
            }
          }
          $query = "SELECT tytul FROM albumy WHERE id=" . $_GET["album_foto_list"] . ";";
          $result = mysqli_query($mysqli, $query);
          $result = $result->fetch_assoc();
          print("<h3 class='mb-3 text-center'>" . $result["tytul"] . "</h3>");
          print('<div class="container">');
          for($i = 0; $i < count($zdjecia); $i++) {
            print("<div><a href='index.php?zdjecie=" . $zdjecia[$i]["id"] . "'><img src='/galeria/photo/" . $_GET["album_foto_list"] . "/" . $zdjecia[$i]["id"] . "'  alt='thumbnail' width='180' height='180'></a></div>");
          }
          print('</div>');
        }
      } elseif(isset($_GET["edit-comment"]) && $not_clicked_buttons) {
        $query = "SELECT komentarz FROM zdjecia_komentarze WHERE id=" . $_GET["edit-comment"] . ";";
        $result = mysqli_query($mysqli, $query);
        $result = $result->fetch_assoc();
        print("<p class='lead text-center'>" . $result["komentarz"] . "</p>");
        print("<div class='container-sm'>");
        print("<form class='form' method='post' action='index.php?edit-comment=" . $_GET["edit-comment"] . "'>");
        print("<h4 class='mb-3'>Zmień treść</h4>");
        print("<div class='form-floating mb-3 input-short'><input type='text' name='content' id='content' class='form-control' required><label for='title'>Treść komentarza</label></div>");
        print("<div class='form-floating mb-3 input-short'><button type='submit' class='btn btn-primary'>Potwierdź</button></div>");
        print("</form>");
        print("</div>");
      } elseif($_SESSION["button"] == "albumy" || ($not_clicked_buttons && !isset($_SESSION["button"]))) {
        $query = "SELECT albumy.id, albumy.tytul, albumy.data, uzytkownicy.login FROM albumy INNER JOIN uzytkownicy ON uzytkownicy.id=albumy.id_uzytkownika;";
        $result = mysqli_query($mysqli, $query);
        $albumy = array();
        while($temp = $result->fetch_assoc()) {
          if(null !== reset($temp)){
            $albumy[] = $temp;
          }
        }
        for($i = 0; $i < count($albumy); $i++) {
          $query = "SELECT COUNT(CASE zdjecia.zaakceptowane WHEN 0 THEN 1 ELSE NULL END) AS niezaakceptowane, COUNT(zdjecia.id) AS zdjecia FROM albumy LEFT JOIN zdjecia ON albumy.id=zdjecia.id_albumu WHERE albumy.id=" . $albumy[$i]["id"] . ";";
          $result = mysqli_query($mysqli, $query);
          $result = $result->fetch_assoc();
          $albumy[$i]["zdjecia"] = $result["zdjecia"];
          $albumy[$i]["niezaakceptowane"] = $result["niezaakceptowane"];
        }
        usort($albumy, function($a, $b) {return $a["niezaakceptowane"] < $b["niezaakceptowane"];});
        if(count($albumy) > 30) {
          $current_page_size = 30;
        } else {
          $current_page_size = count($albumy);
        }
        if(isset($_GET["page"])) {
          $current_page = $_GET["page"];
        } else {
          $current_page = 0;
        }
        print("<div class='container-sm'>");
        print("<ul class='list-group'>");
        for($i = $current_page*$current_page_size; $i < ($current_page+1)*$current_page_size; $i++) {
          print("<li class='list-group-item list-group-item-action'><a href='index.php?album=" . $albumy[$i]["id"] . "'><strong>" . $albumy[$i]["tytul"] . "</strong></a> " . $albumy[$i]["login"] . " " . $albumy[$i]["data"] . " <a href='index.php?album_foto_list=" . $albumy[$i]["id"] ."'><strong>" . $albumy[$i]["niezaakceptowane"] . "/" . $albumy[$i]["zdjecia"] . " niezaakceptowane</strong></a></li>");
        }
        print("</div>");
        print("<div class='d-flex justify-content-center gap-3 mb-3'>");
        for($i = 0; $i < count($albumy); $i += 30) {
          print("<div><a href='index.php?page=" . intval($i/30) . "'>" . intval(ceil(($i+1)/30)) . "</a></div>");
        }
        print("</div>");
      } elseif($_SESSION["button"] == "zdjecia") {
        $query = "SELECT albumy.id, albumy.tytul, albumy.data FROM albumy INNER JOIN zdjecia ON albumy.id=zdjecia.id_albumu GROUP BY zdjecia.id_albumu;";
        $result = mysqli_query($mysqli, $query);
        $albumy = array();
        while($temp = $result->fetch_assoc()) {
          if(null !== reset($temp)){
            $albumy[] = $temp;
          }
        }
        print("<div class='container-sm'>");
        print("<ul class='list-group'>");
        print("<li class='list-group-item list-group-item-action'><strong><a href='index.php?album_foto_list=-1'>Wszystkie niezaakceptowane zdjęcia</a></strong></li>");
        for($i = 0; $i < count($albumy); $i++) {
          print("<li class='list-group-item list-group-item-action'><a href='index.php?album_foto_list=" . $albumy[$i]["id"] . "'><strong>" . $albumy[$i]["tytul"] . "</strong></a> " . " " . $albumy[$i]["data"] . "</li>");
        }
        print("</div>");
      } elseif($_SESSION["button"] == "komentarze") {
        if(!isset($_GET["filtr"]) || $_GET["filtr"] == "all") {
          $query = "SELECT zk.id, zk.data, zk.komentarz, zk.zaakceptowany, uzytkownicy.login FROM zdjecia_komentarze AS zk INNER JOIN uzytkownicy ON uzytkownicy.id=zk.id_uzytkownika;";
        } else {
          $query = "SELECT zk.id, zk.data, zk.komentarz, zk.zaakceptowany, uzytkownicy.login FROM zdjecia_komentarze AS zk INNER JOIN uzytkownicy ON uzytkownicy.id=zk.id_uzytkownika WHERE zk.zaakceptowany=0;";
        }
        $result = mysqli_query($mysqli, $query);
        $komentarze = array();
        while($temp = $result->fetch_assoc()) {
          if(null !== reset($temp)){
            $komentarze[] = $temp;
          }
        }
        print("<div class='container-sm d-flex justify-content-center gap-3'>");
        print("<a href='index.php?filtr=accept'>Do zaakceptowania</a><a href='index.php?filtr=all'>Wszystkie</a>");
        print("</div>");
        print("<div class='container-sm'>");
        print("<ul class='list-group'>");
        for($i = 0; $i < count($komentarze); $i++) {
          print("<li class='list-group-item list-group-item-action'><strong>" . $komentarze[$i]["login"] . "</strong> " . $komentarze[$i]["komentarz"]);
          if($_SESSION["role"] == "administrator") {
            print(" <a href='index.php?edit-comment=" . $komentarze[$i]["id"] . "'><i class='fas fa-edit'></i></a>");
          }
          if($komentarze[$i]["zaakceptowany"] == 0) {
            print(" <a href='index.php?accept-comment=" . $komentarze[$i]["id"] . "'><i class='fas fa-check'></i></a>");
          }
          print(" <a href='index.php?delete-comment=" . $komentarze[$i]["id"] . "'><i class='fas fa-trash-alt'></i></a></li>");
        }
        print("</div>");
      } elseif($_SESSION["button"] == "uzytkownicy") {
        if(!isset($_GET["filtr"]) || $_GET["filtr"] == "all") {
          $query = "SELECT id, login, uprawnienia, aktywny FROM uzytkownicy WHERE NOT id=" . $_SESSION["id"] . ";";
        } else {
          $query = "SELECT id, login, uprawnienia, aktywny FROM uzytkownicy WHERE NOT id=" . $_SESSION["id"] . " AND uprawnienia='" . $_GET["filtr"] . "';";
        }
        $result = mysqli_query($mysqli, $query);
        $uzytkownicy = array();
        while($temp = $result->fetch_assoc()) {
          if(null !== reset($temp)){
            $uzytkownicy[] = $temp;
          }
        }
        print("<div class='container-sm d-flex justify-content-center gap-3'>");
        print("<a href='index.php?filtr=uzytkownik'>Użytkownicy</a><a href='index.php?filtr=moderator'>Moderatorzy</a><a href='index.php?filtr=administrator'>Administratorzy</a><a href='index.php?filtr=all'>Wszyscy</a>");
        print("</div>");
        print("<div class='container-sm'>");
        print("<ul class='list-group'>");
        for($i = 0; $i < count($uzytkownicy); $i++) {
          if($uzytkownicy[$i]["aktywny"] == 0) {
            print("<li class='list-group-item list-group-item-action'><strong>" . $uzytkownicy[$i]["login"] . "</strong> " . $uzytkownicy[$i]["uprawnienia"] . " <a href='index.php?edit-user-privilege=" . $uzytkownicy[$i]["id"] . "'><i class='fas fa-edit'></i></a> <a href='index.php?unblock-user=" . $uzytkownicy[$i]["id"] . "'><i class='fas fa-unlock'></i></a> <a href='index.php?delete-user=" . $uzytkownicy[$i]["id"] . "'><i class='fas fa-trash-alt'></i></a></li>");
          } else {
            print("<li class='list-group-item list-group-item-action'><strong>" . $uzytkownicy[$i]["login"] . "</strong> " . $uzytkownicy[$i]["uprawnienia"] . " <a href='index.php?edit-user-privilege=" . $uzytkownicy[$i]["id"] . "'><i class='fas fa-edit'></i></a> <a href='index.php?block-user=" . $uzytkownicy[$i]["id"] . "'><i class='fas fa-lock'></i></a> <a href='index.php?delete-user=" . $uzytkownicy[$i]["id"] . "'><i class='fas fa-trash-alt'></i></a></li>");
          }
        }
        print("</div>");
      }
    ?>
    <?php include "../include/footer.php" ?>
  </body>
</html>
