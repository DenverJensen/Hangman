<?php

session_start();

if (isset($_SESSION['user_id'])) {
  header('Location:hangman.php');
}
require 'database.php';

if (!empty($_POST['username']) && !empty($_POST['password'])) {
  $records = $conn->prepare('SELECT id, username, password FROM Users WHERE username = :username');
  $records->bindParam(':username', $_POST['username']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $message = '';

  if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
    //set game session variables move to game landing page
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['username'] = $results['username'];

    //enter game
    header("Location:setup.php");
  } else {
    $message = 'Sorry, those credentials do not match';
  }
} else {
  $message = 'Please enter a correct username and password';
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php require 'partials/header.php' ?>

  <?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
  <?php endif; ?>

  <h1>Login</h1>
  <span>or <a href="signup.php">SignUp</a></span>

  <form action="login.php" method="POST">
    <input name="username" type="text" placeholder="Enter your username">
    <input name="password" type="password" placeholder="Enter your Password">
    <input type="submit" value="Submit">
  </form>
  <div class="col-md-12" id="result"><?php echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>' ?></div>

</body>

</html>