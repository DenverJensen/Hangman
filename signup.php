<?php

require 'database.php';
session_start();

$message = '';
$results = [];

if (!empty($_POST['username']) && !empty($_POST['password'])) {
  //check for existing user
  $records = $conn->prepare('SELECT id, username, password FROM Users WHERE username = :username');
  $records->bindParam(':username', $_POST['username']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  if (empty($results['username'])) {
    print_r($results);
    //if user doesnt exist then insert
    $sql = "INSERT INTO Users (username, password) VALUES (:username, :password)";
    if ($_POST['password'] === $_POST['confirm_password']) {
      $uppercase = preg_match('@[A-Z]@', $_POST['password']);
      $lowercase = preg_match('@[a-z]@', $_POST['password']);
      $number    = preg_match('@[0-9]@', $_POST['password']);
      $specialChars = preg_match('@[^\w]@', $_POST['password']);
      // Validate password strength
      if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8) {
        $message = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
      } else {
        $sql = "INSERT INTO Users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
          $message = 'Successfully created new user';
          //todo: autologin feature to get user ID from DB and set session variables for game
          $records = $conn->prepare('SELECT id, username, password FROM Users WHERE username = :username');
          $records->bindParam(':username', $_POST['username']);
          $records->execute();
          $results = $records->fetch(PDO::FETCH_ASSOC);
          $_SESSION['username'] = $results['username'];
          $_SESSION['user_id'] = $results['id'];
          header('Location:setup.php');
        } else {
          $message = 'Sorry there must have been an issue creating your account';
        }
      }
    } else {
      $message = 'Passwords do not match';
    }
  } else {
    $message = 'User already exists, click log in or choose a new username';
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