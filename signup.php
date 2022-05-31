<?php

session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>SignUp</title>
</head>

<body>
  <?php require 'partials/header.php' ?>
  <h1>SignUp</h1>
  <span>or <a href="login.php">Login</a></span>
  <form action="captureSignup.php" method="POST">
    <input name="username" type="text" placeholder="Enter your username" value="<?php echo isset($_SESSION['attempteduser']) ? $_SESSION['attempteduser'] : '' ?>">
    <input name="password" type="password" placeholder="Enter your Password">
    <input name="confirm_password" type="password" placeholder="Confirm Password">
    <input type="submit" value="Submit">
  </form>
  <div class="login-message">
    <?php if (!empty($_SESSION['signup-message'])) : ?>
      <p> <?= $_SESSION['signup-message'] ?></p>
    <?php endif; ?>
  </div>
</body>

</html>