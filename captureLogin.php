<?php
session_start();

require 'database.php';

$_SESSION['attempteduser'] = $_POST['username'];

if (!empty($_POST['username']) && !empty($_POST['password'])) {
  $records = $conn->prepare('SELECT id, username, password FROM Users WHERE username = :username');
  $records->bindParam(':username', $_POST['username']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $_SESSION['loginMessage'] = '';

  if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
    //set game session variables move to game landing page
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['username'] = $results['username'];

    //enter game
    header("Location:setup.php");
  } else {
    $_SESSION['loginMessage'] = 'Sorry, those credentials do not match';
  }
} else {
    $_SESSION['loginMessage'] = 'Please enter a username and password';
}
header('Location:login.php');

?>