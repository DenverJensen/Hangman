<?php
session_start();

require 'hangedman.php';
require 'database.php';

$_SESSION['message'] = "";

//letter used 
$_SESSION['total_life_dashes'] = implode(" ", $_SESSION['letterUsed']);

//letter used 
$_SESSION['words_to_guess_dashes'] = implode(" ", $_SESSION['wordToGuessArray']);

//if user is not logged in, push to login page
if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['username'] === '') {
    header('Location:logout.php');
}

// check if game is already over
if (!$_SESSION['gameOver']) {

    // when user submit new letter
    $_SESSION['input'] = strtoupper($_POST['letterInput']);
    if (isset($_POST['letterInput']) && !empty($_POST['letterInput']) && $_POST['letterInput'] !== "") {

        if (!in_array($_SESSION['input'], $_SESSION['letterUsed']) && !in_array($_SESSION['input'], $_SESSION['wordToGuessArray'])) {
            $_SESSION['inguesses'] = !in_array($_POST['letterInput'], $_SESSION['letterUsed']);
            // find the position of the letter in the word
            $value = strtoupper($_POST["letterInput"]);
            $offset = 0;
            $allpos = array();
            while (($pos = strpos($_SESSION['wordToGuess'], $value, $offset)) !== FALSE) {
                $offset   = $pos + 1;
                $allpos[] = $pos;
            }
            $position = strpos($_SESSION['wordToGuess'], $value);

            // check if the word is alphabet
            if (!preg_match("/^[a-zA-Z]*$/", $value)) {

                $_SESSION['message'] = "Only letters allowed";

                //check if the there is only one letter 
            } elseif (strlen($value) > 1) {

                $_SESSION['message'] = "Only one letter allowed at a time";
            } elseif (count($allpos) < 1) {
                //if the letter is not found then add that letter to used letter array and update the remaining life
                $_SESSION['letterUsedCount']++;

                //total_life_dashes
                for ($i = 0; $i < $_SESSION['totalLife']; $i++) {
                    if ($_SESSION['letterUsed'][$i] == "_") {
                        $_SESSION['letterUsed'][$i] = $value;
                        break;
                    }
                }
                $_SESSION['total_life_dashes'] = implode(" ", $_SESSION['letterUsed']);

                $_SESSION['message'] = "Letter not found";

                //check if the all the 6 life are used then game over
                if ($_SESSION['letterUsedCount'] >= $_SESSION['totalLife']) {
                    $_SESSION['gameOver'] = true;
                    $_SESSION['letterUsed'] = array();
                    $_SESSION['message'] = "You Lose! \n Your word was " . $_SESSION['wordToGuess'];
                }
            } else {

                //if the letter found and any position in the word
                //add letter to the position where it is found
                for ($i = 0; $i < count($allpos); $i++) {
                    $posit = $allpos[$i];
                    $_SESSION['wordToGuessArray'][$posit] = $value;
                    $_SESSION['letterTrueGuess']++;
                }
                $_SESSION['words_to_guess_dashes'] = implode(" ", $_SESSION['wordToGuessArray']);
                $_SESSION['message'] = "Letter matched";


                //check if the all letter are guessed
                if ($_SESSION['letterTrueGuess'] >= $_SESSION['wordToGuessLetterCount']) {
                    $_SESSION['gameOver'] = true;
                    $_SESSION['letterUsed'] = array();
                    $_SESSION['message'] = "You Won";
                    //enter win into db
                    $sql = "INSERT INTO Scores (userid, strikes, word, word_length ) VALUES (:userid, :strikes, :word, :word_length)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':userid', $_SESSION['user_id']);
                    $stmt->bindParam(':strikes', $_SESSION['letterUsedCount']);
                    $stmt->bindParam(':word', $_SESSION['wordToGuess']);
                    $stmt->bindParam(':word_length', $_SESSION['wordToGuessLetterCount']);
                    if ($stmt->execute()) {
                        $_SESSION['message'] = 'You won! Score entered';
                    } else {
                        $_SESSION['message'] = 'You won! Score entry error';
                    }
                }
            }
        } else {
            $_SESSION['message'] = 'letter already guessed';
        }
    } else {
        $_SESSION['message'] = 'Enter a guess';
    }
}
header('Location:hangman.php');

?>
<html>

<body>
    <div id="result"><?php echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>' ?></div>

</body>

</html>