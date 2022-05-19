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
    //set game session variables
    $_SESSION['user_id'] = $results['id'];
    $words = array(
      "robust",
      "polar", 
      "measly", 
      "movie",
      "thirsty"
    );
  
    $random_word = array_rand($words, 1);
    $letter_count = strlen($words[$random_word]);
    $letter_dashes = "";
    for($i=0; $i < $letter_count; $i++){
      $letter_dashes .= "_ ";
    }
  
    $total_life = "_ _ _ _ _ _";
  
    $_SESSION['wordToGuess'] = $words[$random_word];
    $_SESSION['wordToGuessArray'] = array();
    $_SESSION['wordToGuessLetterCount'] = $letter_count;
    for($i=0; $i< $letter_count; $i++){
      $_SESSION['wordToGuessArray'][] = "_";
    }
    $_SESSION['totalLife'] = 6;
    $_SESSION['letterUsedCount'] = 0;
    $_SESSION['letterTrueGuess'] = 0;
    $_SESSION['letterUsed'] = array();
    $_SESSION['letterUsed'][0] = "_";
    $_SESSION['letterUsed'][1] = "_";
    $_SESSION['letterUsed'][2] = "_";
    $_SESSION['letterUsed'][3] = "_";
    $_SESSION['letterUsed'][4] = "_";
    $_SESSION['letterUsed'][5] = "_";
  
    $_SESSION['gameOver'] = false;
    header("Location:hangman.php");
  } else {
    $message = 'Sorry, those credentials do not match';
  }
} else {
  $message = 'Please enter a username and password';
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
</body>

</html>