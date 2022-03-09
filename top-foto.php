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
    <script src="javascript/init.js" defer></script>
  </head>
  <body>
    <?php include "./include/header.php" ?>
    <br>
    <h3 class='text-center'>Najlepiej oceniane zdjęcia</h3>
    <br>
      <?php
        $query = "SELECT zdjecia.id_albumu, zdjecia.id, AVG(zdjecia_oceny.ocena) FROM zdjecia_oceny INNER JOIN zdjecia WHERE zdjecia.id=zdjecia_oceny.id_zdjecia GROUP BY zdjecia_oceny.id_zdjecia ORDER BY AVG(zdjecia_oceny.ocena) DESC LIMIT 20;";
        $result = mysqli_query($mysqli, $query);
        $fotos = array();
        while($temp = $result->fetch_assoc()) {
          if(null !== reset($temp)){
            $fotos[] = $temp;
          }
        }
        if(count($fotos) < 20) {
          $ilosc = count($fotos);
        } else {
          $ilosc = 20;
        }
        print('<div class="container">');
        for($i = 0; $i < $ilosc; $i++) {
          $query = "SELECT id FROM zdjecia WHERE id_albumu=" . $fotos[$i]["id_albumu"] . ";";
          $result = mysqli_query($mysqli, $query);
          $list = array();
          while($temp = $result->fetch_assoc()) {
            if(null !== reset($temp)){
              $list[] = $temp["id"];
            }
          }
          $query = "SELECT albumy.tytul, uzytkownicy.login FROM albumy INNER JOIN uzytkownicy WHERE uzytkownicy.id=albumy.id_uzytkownika AND albumy.id=" . $fotos[$i]["id_albumu"] . ";";
          $result = mysqli_query($mysqli, $query);
          $result = $result->fetch_assoc();
          $user = $result["login"];
          $title = $result["tytul"];
          $position = array_search($fotos[$i]["id"], $list);
          print("<div><a href='foto.php?album=" . $fotos[$i]["id_albumu"] . "&foto=" . $position . "'><img src='photo/" . $fotos[$i]["id_albumu"] . "/" . $fotos[$i]["id"] . "-min'  alt='thumbnail' width='180' height='180' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . $title . "\n" . $user . "\n" . intval($fotos[$i]["AVG(zdjecia_oceny.ocena)"]) . "'></a></div>");
        }
        print("</div>");
      ?>
    <?php include "./include/footer.php" ?>
  </body>
</html>
