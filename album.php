<?php
  if(!isset($_SESSION)) {
      session_start();
  }
  require("include/database_connect.php");
?>
<html>
  <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <script src="javascript/formcheck.js" defer></script>
    <script src="javascript/init.js" defer></script>
  </head>
  <body>
    <?php include "./include/header.php" ?>
    <br>
      <?php
        if(isset($_GET["album"])) {
          $album = $_GET["album"];
          $query = "SELECT * FROM albumy WHERE id=" . $album .";";
          $result = mysqli_query($mysqli, $query);
          $album_info = $result->fetch_assoc();

          print('<h3 class="text-center">' . $album_info["tytul"] . '</h3>');
          print('<p class="text-center"><a href="index.php">Wróć do listy albumów</a></p>');
          print('<div class="container-sm">');

          $query = "SELECT * FROM zdjecia WHERE id_albumu=" . $album . " AND zaakceptowane=1 ORDER BY data DESC;";
          $result = mysqli_query($mysqli, $query);
          $data = array();
          while($temp = $result->fetch_assoc()) {
            if(null !== reset($temp)){
              $data[] = $temp;
            }
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
          print('<div class="container">');

          for($i = $current_page*20; $i < $current_page_size; $i++) {
            print("<div><a href='foto.php?album=" . $album . "&foto=" . $i . "'><img src='photo/" . $album . "/" . $data[$i]["id"] . "-min'  alt='thumbnail' width='180' height='180' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . $data[$i]["data"] . "'></a></div>");
          }
          print('</div>');

          if(count($data) > 15) {
            print('<p class="text-center"><a href="index.php">Wróć do listy albumów</a></p>');
          }

          print("<div class='d-flex justify-content-center gap-3 mb-3'>");
          for($i = 0; $i < count($data); $i += 20) {
            print("<div><a href='album.php?album=" . $album . "&page=" . intval($i/20) . "'>" . intval(ceil(($i+1)/20)) . "</a></div>");
          }
          print("</div>");
          print('</div>');
        }
      ?>
    <?php include "./include/footer.php" ?>
  </body>
</html>
