<?php
$server = "sql104.epizy.com";
$username = "epiz_31705905";
$password = "wHyNh7spZrIZD";
$database = "epiz_31705905_denver";

//establish PDO connection to infinity free
try {
  $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
} catch (PDOException $e) {
  die('Connection Failed: ' . $e->getMessage());
}

?>
