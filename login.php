<?php

session_start();

if (isset($_SESSION['user_id'])) {
  header('Location:setup.php');
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Login</title>
</head>

<body class='login'>
  <?php require 'partials/header.php' ?>
  <h1>Login</h1>
  <span>or <a href="signup.php">SignUp</a></span>
  <form action="captureLogin.php" method="POST">
    <input name="username" type="text" placeholder="Enter your username" value="<?php echo isset($_SESSION['attempteduser']) ? $_SESSION['attempteduser'] : '' ?>">
    <input name="password" type="password" placeholder="Enter your Password">
    <input type="submit" value="Submit">
  </form>
  <div class="login-message">
    <?php if (($_SESSION['loginMessage']) !== "") : ?>
      <p> <?= $_SESSION['loginMessage'] ?></p>
    <?php endif; ?>
  </div>
</body>

</html>