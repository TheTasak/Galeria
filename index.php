<?php
  if(!isset($_SESSION)) {
      session_start();
  }
  require("include/database_connect.php");

  $query = "SELECT * FROM albumy;";
  $result = mysqli_query($mysqli, $query);
  $data = array();
  while($temp = $result->fetch_assoc()) {
    if(null !== reset($temp)){
      $data[] = $temp;
    }
  }
  if(isset($_GET["sort"])) {
	  $_SESSION["sort"] = $_GET["sort"];
  } elseif(!isset($_SESSION["sort"])) {
	  $_SESSION["sort"] = "default";
  }
  if(count($data) > 20) {
    $current_page_size = 20;
  } else {
    $current_page_size = count($data);
  }
  if(isset($_GET["page"])) {
    $current_page = $_GET["page"];
  } else {
    $current_page = 0;
  }

  for($i = $current_page*$current_page_size; $i < ($current_page+1)*$current_page_size; $i++) {
    $query = "SELECT login FROM uzytkownicy WHERE id=" . $data[$i]["id_uzytkownika"];
    $result = mysqli_query($mysqli, $query);
    $temp = $result->fetch_assoc();
    $data[$i]["uzytkownik"] = $temp["login"];

    $query = "SELECT id FROM zdjecia WHERE id_albumu=" . $data[$i]["id"] . " AND zaakceptowane=1";
    $result = mysqli_query($mysqli, $query);
    $files = array();
    while($temp = $result->fetch_assoc()) {
      if(null !== reset($temp)){
        $files[] = $temp;
      }
    }
    $data[$i]["files"] = $files;
  }
?>
<html>
  <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <script src="javascript/init.js" defer></script>
  </head>
  <body>
    <?php include "./include/header.php" ?>
    <p></p>
    <div class="content">
      <div class="d-flex justify-content-center gap-3 mb-3">
        <a href="index.php?sort=date">Sortuj data dodania</a>
        <a href="index.php?sort=name">Sortuj login</a>
      </div>
      <div class="container">
        <?php
          if(isset($_SESSION["sort"])) {
            if($_SESSION["sort"] == "name") {
              usort($data, function($a, $b) {return strcmp($a["uzytkownik"], $b["uzytkownik"]);});
            } elseif($_SESSION["sort"] == "date") {
              usort($data, function($a, $b) {return $a["data"] > $b["data"];});
            }
          } else {
            usort($data, function($a, $b) {return strcmp($a["tytul"], $b["tytul"]);});
          }
          if(count($data) > 20) {
            $current_page_size = 20;
          } else {
            $current_page_size = count($data);
          }
          if(isset($_GET["page"])) {
            $current_page = $_GET["page"];
          } else {
            $current_page = 0;
          }
          for($i = $current_page*20; $i < $current_page_size; $i++) {
            if(count($data[$i]["files"]) > 0) {
              $file = $data[$i]["files"][0]["id"];
              print("<div><a href='album.php?album=" . $data[$i]["id"] . "'><img src='photo/" . $data[$i]["id"] . "/" . $file . "-min'  alt='thumbnail' width='180' height='180' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . $data[$i]["tytul"] . "\n" . $data[$i]["uzytkownik"] . "\n" . $data[$i]["data"] . "'></a></div>");
            }
          }
        ?>
      </div>
      <?php
        print("<div class='d-flex justify-content-center gap-3 mb-3'>");
        for($i = 0; $i < count($data); $i += 20) {
          print("<div><a href='index.php?page=" . intval($i/20) . "'>" . intval(ceil(($i+1)/20)) . "</a></div>");
        }
        print("</div>");
      ?>
    </div>
    <?php include "./include/footer.php" ?>
  </body>
</html>
