<?php
 session_start();
 require 'database.php';

 $records = $conn->prepare('SELECT upper(word) as word FROM Words ORDER BY RAND() LIMIT 1;');
 $records->execute();
 $results = $records->fetch(PDO::FETCH_ASSOC);
 $_SESSION['ranword']=$results;

  $random_word = $results['word'];
  $letter_count = strlen($random_word);
  $letter_dashes = "";
  for($i=0; $i < $letter_count; $i++){
    $letter_dashes .= "_ ";
  }

  $total_life = "_ _ _ _ _ _";

  $_SESSION['wordToGuess'] = $random_word;
  $_SESSION['wordToGuessArray'] = array();
  $_SESSION['wordToGuessLetterCount'] = $letter_count;
  for($i=0; $i< $letter_count; $i++){
    $_SESSION['wordToGuessArray'][] = "_";
  }

  $_SESSION['totalLife'] = 12;

  $_SESSION['letterUsed'] = array();
  for($i=0; $i< $_SESSION['totalLife']; $i++){
    $_SESSION['letterUsed'][] = "_";
  }

  // $_SESSION['letterUsed'][0] = "_";
  // $_SESSION['letterUsed'][1] = "_";
  // $_SESSION['letterUsed'][2] = "_";
  // $_SESSION['letterUsed'][3] = "_";
  // $_SESSION['letterUsed'][4] = "_";
  // $_SESSION['letterUsed'][5] = "_";
  
  $_SESSION['letterUsedCount'] = 0;
  $_SESSION['letterTrueGuess'] = 0;
  $_SESSION['gameOver'] = false;

  header("Location:hangman.php");

?>