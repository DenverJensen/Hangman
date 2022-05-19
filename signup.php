<?php

require 'database.php';

$message = '';

if (!empty($_POST['username']) && !empty($_POST['password'])) {
  //todo: check for existing account first
  if ($_POST['password'] === $_POST['confirm_password']) {
  
    // todo: check for password strength and salt
    $sql = "INSERT INTO Users (username, password) VALUES (:username, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password);

    //todo: autologin feature to get user ID from DB and set session variables for game

    if ($stmt->execute()) {
      $message = 'Successfully created new user';
    } else {
      $message = 'Sorry there must have been an issue creating your account';
    }
  } else {
    $message = 'Passwords do not match';
  }
} else {
  $message = 'Please enter a username and password';
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>SignUp</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

  <?php require 'partials/header.php' ?>

  <?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
  <?php endif; ?>

  <h1>SignUp</h1>
  <span>or <a href="login.php">Login</a></span>

  <form action="signup.php" method="POST">
    <input name="username" type="text" placeholder="Enter your username">
    <input name="password" type="password" placeholder="Enter your Password">
    <input name="confirm_password" type="password" placeholder="Confirm Password">
    <input type="submit" value="Submit">
  </form>

</body>

</html>