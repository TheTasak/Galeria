<?php
  session_start();
?>
<html>
  <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="javascript/formcheck.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
  </head>
  <body>
    <?php include "./include/header.php" ?>
    <div class="container-sm">
      <p>Pomyślnie zarejestrowano użytkownika: <?php echo $_SESSION["username"]; ?></p>
      <a href="index.php">Przejdź do strony głównej</a>
    </div>
    <?php include "./include/footer.php" ?>
  </body>
</html>
