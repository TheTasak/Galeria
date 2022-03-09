<html>
  <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="javascript/formcheck.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
  </head>
  <body>
    <?php include "./include/header.php" ?>
    <div style="height: 90%;" class="container-sm d-flex flex-column justify-content-center mt-3">
    <div class="container-sm">
      <form action="logowanie.php" method="post" onsubmit="return checkLoginFormSubmit()" id="login-form">
        <h3 class="mb-3">Zaloguj się</h3>
        <div class="form-floating mb-3 input-short">
            <input type="text" name="username" id="floatLoginUsername" placeholder="Login" class="form-control" value="" required>
            <label for="floatLoginUsername">Nazwa użytkownika</label>
        </div>
        <div class="form-floating mb-3 input-short">
            <input type="password" name="password" id="floatLoginPassword" placeholder="Hasło" class="form-control" required>
            <label for="floatLoginPassword">Hasło</label>
        </div>
        <div class="mb-3 warning-form-div">
        </div>
        <div class="mb-3 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary btn-lg">Zaloguj</button>
        </div>
      </form>
    </div>
    <div class="container-sm">
      <form action="rejestracja.php" method="post" onsubmit="return checkRegisterFormSubmit()" id="register-form">
        <h3 class="mb-3">Zarejestruj się</h3>
        <div class="form-floating mb-3 input-short">
            <input type="text" name="username" id="floatUsername" placeholder="Login" class="form-control" value="" required>
            <label for="floatUsername">Nazwa użytkownika</label>
        </div>
        <div class="form-floating mb-3 input-short">
            <input type="password" name="password" id="floatPassword" placeholder="Hasło" class="form-control" required>
            <label for="floatPassword">Hasło</label>
        </div>
        <div class="form-floating mb-3 input-short">
            <input type="password" name="confirm-password" id="floatConfirmPassword" placeholder="Potwierdź hasło" class="form-control" required>
            <label for="floatConfirmPassword">Potwierdź hasło</label>
        </div>
        <div class="form-floating mb-3 input-short">
            <input type="email" name="email" id="floatEmail" placeholder="Email" class="form-control" required>
            <label for="floatEmail">Email</label>
        </div>
        <div class="mb-3 warning-form-div">
        </div>
        <div class="mb-3 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary btn-lg">Zarejestruj</button>
        </div>
      </form>
    </div>
    </div>
    <?php include "./include/footer.php" ?>
  </body>
</html>
