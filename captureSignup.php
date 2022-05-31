<?php

require 'database.php';
session_start();

$_SESSION['signup-message'] = '';
$_SESSION['attempteduser'] = $_POST['username'];

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
                $_SESSION['signup-message'] = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
            } else {
                $sql = "INSERT INTO Users (username, password) VALUES (:username, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $_POST['username']);
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $stmt->bindParam(':password', $password);

                if ($stmt->execute()) {
                    $_SESSION['signup-message'] = 'Successfully created new user';
                    //todo: autologin feature to get user ID from DB and set session variables for game
                    $records = $conn->prepare('SELECT id, username, password FROM Users WHERE username = :username');
                    $records->bindParam(':username', $_POST['username']);
                    $records->execute();
                    $results = $records->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['username'] = $results['username'];
                    $_SESSION['user_id'] = $results['id'];
                    header('Location:setup.php');
                } else {
                    $_SESSION['signup-message'] = 'Sorry there must have been an issue creating your account';
                }
            }
        } else {
            $_SESSION['signup-message'] = 'Passwords do not match';
        }
    } else {
        $_SESSION['signup-message'] = 'User already exists, click log in or choose a new username';
    }
} else {
    $_SESSION['signup-message'] = 'Please enter a username and password';
}

header('Location:signup.php');

?>