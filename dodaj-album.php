<?php
  if(!isset($_SESSION)) {
      session_start();
  }
  if($_SESSION["loggedin"] == false) {
    header("location: logrej.php");
  }
  require("include/database_connect.php");
  if(isset($_POST["title"])) {
    $query = "INSERT INTO albumy (tytul, id_uzytkownika) VALUES ('" . $_POST["title"] . "', " . $_SESSION["id"] . ");";
    mysqli_query($mysqli, $query);
    mkdir("photo\\" . mysqli_insert_id($mysqli));
    header("location: dodaj-foto.php");
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
      <form id="album-form" action="dodaj-album.php" method="post" class="form" onsubmit="return checkAlbumSubmit()">
        <h4>Załóż album</h4>
        <div class="form-floating mb-3 input-short">
            <input type="text" name="title" id="floatTitle" placeholder="Tytuł albumu" class="form-control" value="" required>
            <label for="floatTitle">Tytuł albumu</label>
        </div>
        <div class="mb-3 warning-form-div">
        </div>
        <div class="mb-3 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Dodaj</button>
        </div>
      </form>
    </div>
    <?php include "./include/footer.php" ?>
  </body>
</html>
