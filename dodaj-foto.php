<?php
  if(!isset($_SESSION)) {
      session_start();
  }
  if($_SESSION["loggedin"] == false) {
    header("location: logrej.php");
  }
  require("include/database_connect.php");
  if(isset($_FILES["filename"])) {
    $opis = $_POST["textarea"];
    $opis = addslashes($opis);
    $query = "INSERT INTO zdjecia (opis, id_albumu, zaakceptowane) VALUES ('" . $opis . "', " . $_GET["album"] . ", " . "0" . ")";
    mysqli_query($mysqli, $query);
    $query = "SELECT LAST_INSERT_ID();";
    $result = mysqli_query($mysqli, $query);
    $last_insert_id = $result->fetch_assoc();
    $last_insert_id = reset($last_insert_id);

    $image_info = getimagesize($_FILES["filename"]["tmp_name"]);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    if($image_width > $image_height) {
      $calc_big = $image_width / 1200;
      $calc_small = $image_width / 180;
    } else {
      $calc_big = $image_height / 1200;
      $calc_small = $image_width / 180;
    }

    $image_big_width = intval($image_width / $calc_big);
    $image_big_height = intval($image_height / $calc_big);
    $image_small_width = intval($image_width / $calc_small);
    $image_small_height = intval($image_height / $calc_small);

    $big = imagecreatetruecolor($image_big_width, $image_big_height);
    $small = imagecreatetruecolor($image_small_width, $image_small_height);

    $path_parts = pathinfo($_FILES["filename"]["name"]);
    if($path_parts['extension'] == "jpeg") {
      $source_big = imagecreatefromjpeg($_FILES["filename"]["tmp_name"]);
      $source_small = imagecreatefromjpeg($_FILES["filename"]["tmp_name"]);
    } elseif($path_parts['extension'] == "jpg") {
      $source_big = imagecreatefromstring(file_get_contents($_FILES["filename"]["tmp_name"]));
      $source_small = imagecreatefromstring(file_get_contents($_FILES["filename"]["tmp_name"]));
    } elseif($path_parts['extension'] == "png") {
      $source_big = imagecreatefrompng($_FILES["filename"]["tmp_name"]);
      $source_small = imagecreatefrompng($_FILES["filename"]["tmp_name"]);
    } elseif($path_parts['extension'] == "gif") {
      $source_big = imagecreatefromgif($_FILES["filename"]["tmp_name"]);
      $source_small = imagecreatefromgif($_FILES["filename"]["tmp_name"]);
    }
    imagecopyresized($big, $source_big, 0, 0, 0, 0, $image_big_width, $image_big_height, $image_width, $image_height);
    imagecopyresized($small, $source_small, 0, 0, 0, 0, $image_small_width, $image_small_height, $image_width, $image_height);

    imagejpeg($big, 'photo/' . $_GET["album"] . '/' . $last_insert_id);
    imagejpeg($small, 'photo/' . $_GET["album"] . '/' . $last_insert_id . '-min');
  }
?>
<html>
  <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <script src="javascript/formcheck.js" defer></script>
  </head>
  <body>
    <?php include "./include/header.php" ?>
    <br>
    <div class="container-sm">
      <?php
        $query = "SELECT id, tytul, data FROM albumy WHERE id_uzytkownika=" . $_SESSION["id"] . " ORDER BY data DESC;";
        $result = mysqli_query($mysqli, $query);
        $data = array();
        while($temp = $result->fetch_assoc()) {
          if(null !== reset($temp)){
            $data[] = $temp;
          }
        }
        for($i = 0; $i < count($data); $i++) {
          $query = "SELECT COUNT(id) from zdjecia WHERE id_albumu=" . $data[$i]["id"] . ";";
          $result = mysqli_query($mysqli, $query);
          $result = $result->fetch_assoc();
          $data[$i]["foto-count"] = $result["COUNT(id)"];
        }
        if(count($data) == 0) {
          print("<h3 class='text-center'><a href='dodaj-album.php'>Załóż album</a></h3>");
        } elseif (!isset($_GET["album"]) && count($data) > 1) {
          print("<ul class='list-group'>");
          for($i = 0; $i < count($data); $i++) {
            print("<li class='list-group-item list-group-item-action'><a href='dodaj-foto.php?album=" . $data[$i]["id"] . "'>" . $data[$i]["tytul"] . "</a> " . $data[$i]["data"] . " " . $data[$i]["foto-count"] . " zdjęć </li>");
          }
          print("</ul>");
        } else {
          if(isset($_GET["album"])) {
            $album = $_GET["album"];
          } else {
            $album = $data[0]["id"];
          }
          print('<form enctype="multipart/form-data" id="image-form" action="dodaj-foto.php?album=' . $album .'" method="post" class="form" onsubmit="return checkImageSubmit()">');
          print('<h3>Dodaj zdjęcie</h3>');
          print('<div class="mb-3"><input class="form-control" type="file" id="filename" name="filename"></div>');
          print('<div class="form-floating mb-3 input-short"><textarea class="form-control" name="textarea" id="textarea"></textarea><label for="textarea">Opis zdjęcia</label></div>');
          print('<div class="mb-3 warning-form-div"></div>');
          print('<div class="mb-3"><button type="submit" name="submit" class="btn btn-primary btn-lg">Dodaj</button></div>');
          print('</form>');

          $query = "SELECT id FROM zdjecia WHERE id_albumu=" . $album . ";";
          $result = mysqli_query($mysqli, $query);
          $data = array();
          while($temp = $result->fetch_assoc()) {
            if(null !== reset($temp)){
              $data[] = $temp;
            }
          }
          print('<div class="container">');
          for($i = 0; $i < count($data); $i++) {
            print("<div><img src='photo/" . $album . "/" . $data[$i]["id"] . "'  alt='thumbnail' width='180' height='180'></div>");
          }
          print('</div>');
        }
      ?>
    </div>
    <?php include "./include/footer.php" ?>
  </body>
</html>
