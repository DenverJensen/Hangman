<?php
session_start();
require 'hangedman.php';
require 'database.php';

$message = "";
$console = $_SESSION;

//letter used 
$total_life_dashes = implode(" ", $_SESSION['letterUsed']);

//letter used 
$words_to_guess_dashes = implode(" ", $_SESSION['wordToGuessArray']);

// check if game is already over
if (!$_SESSION['gameOver']) {

    // when user submit new letter
    $_SESSION['input'] = $_POST['letterInput'];
    if (isset($_POST['letterInput']) && !empty($_POST['letterInput']) && $_POST['letterInput'] != "") {

        // find the position of the letter in the word
        $value = strtoupper($_POST["letterInput"]);
        $offset = 0;
        $allpos = array();
        while (($pos = strpos($_SESSION['wordToGuess'], $value, $offset)) !== FALSE) {
            $offset   = $pos + 1;
            $allpos[] = $pos;
        }
        $position = strpos($_SESSION['wordToGuess'], $value);
        $_SESSION['positions'] = $allpos;

        // check if the word is alphabet
        if (!preg_match("/^[a-zA-Z]*$/", $value)) {

            $message = "Only letters allowed";

            //check if the there is only one letter 
        } elseif (strlen($value) > 1) {

            $message = "Only one letter allowed at a time";
        } elseif (count($allpos) < 1) {
            //if the letter is not found then add that letter to used letter array and update the remaining life
            $_SESSION['letterUsedCount']++;

            //$total_life_dashes
            for ($i = 0; $i < 6; $i++) {
                if ($_SESSION['letterUsed'][$i] == "_") {
                    $_SESSION['letterUsed'][$i] = $value;
                    break;
                }
            }
            $total_life_dashes = implode(" ", $_SESSION['letterUsed']);

            $message = "Letter not found";

            //check if the all the 6 life are used then game over
            if ($_SESSION['letterUsedCount'] >= 6) {
                $_SESSION['gameOver'] = true;
                $_SESSION['letterUsed'] = array();
                $message = "You Lose";
            }
        } else {

            //if the letter found and any position in the word
            //add letter to the position where it is found
            for ($i = 0; $i < count($allpos); $i++) {
                $posit = $allpos[$i];
                $_SESSION['wordToGuessArray'][$posit] = $value;
                $_SESSION['letterTrueGuess']++;
            }
            $words_to_guess_dashes = implode(" ", $_SESSION['wordToGuessArray']);
            $message = "Letter matched";


            //check if the all letter are guessed
            if ($_SESSION['letterTrueGuess'] >= $_SESSION['wordToGuessLetterCount']) {
                $_SESSION['gameOver'] = true;
                $_SESSION['letterUsed'] = array();
                $message = "You Won";
                //enter win into db
                $sql = "INSERT INTO Scores (userid, strikes, word, word_length ) VALUES (:userid, :strikes, :word, :word_length)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userid', $_SESSION['user_id']);
                $stmt->bindParam(':strikes', $_SESSION['letterUsedCount']);
                $stmt->bindParam(':word', $_SESSION['wordToGuess']);
                $stmt->bindParam(':word_length', $_SESSION['wordToGuessLetterCount']);
                if ($stmt->execute()) {
                    $message = 'You won! Score entered';
                } else {
                    $message = 'You won! Score entry error';
                }
            }
        }
    } else {
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hangman</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body class="bg-light">

    <div class="bodyh">

        <?php require 'partials/header.php' ?>


        <div class="game-container">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Game Progress/Output</span>
                </h4>
                <div id="hangmanPhoto">
                    <?php
                    $hangman_photo = $hang[$_SESSION['letterUsedCount']];

                    ?>
                    <?php echo $hangman_photo; ?>
                </div>
                <div class="remaining-guesses">
                    <strong>Remaining Guesses: <?php echo $_SESSION['totalLife'] - $_SESSION['letterUsedCount']; ?></strong>
                </div>


            </div>
            <div>
                <form id="gameData" action="" method="post">
                    <div class="row">
                        <div>
                            <label>Word to guess (Total Letters: <?php echo $_SESSION['wordToGuessLetterCount']  ?>)</label>
                            <div class="list-group-item" id="wordToGuess"><?php echo $words_to_guess_dashes; ?></div>
                        </div>
                        <div>
                            <label>Letters used in guesses (Total: 6)</label>
                            <div class="list-group-item" id="letterUsed"><?php echo $total_life_dashes; ?></div>
                        </div>
                        <div class="guess">
                            <label>Enter your guess</label>
                            <input class='guess' type="text" id="letterInput" name="letterInput" value="">
                        </div>
                        <div class="col-md-12" id="result"><?php echo $message; ?></div>
                        <div>
                            <?php if (!$_SESSION['gameOver']) : ?>
                                <input type="submit" value="Submit">
                            <?php endif; ?>
                            <?php if ($_SESSION['gameOver']) : ?>
                                <div class="play-again">

                                    <a href="setup.php">Play Again?</a>
                                </div>
                                <div class="scores">
                                    <a href="scores.php">View high scores</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                    </div>
                    <h4>Result</h4>
                    <div class="row">
                        <div class="col-md-12" id="result"><?php echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>' ?></div>
                    </div>

            </div>
        </div>


        </form>

        <form id="gameData">
            <input type="hidden" name="pname" id="pname" value="<?php echo $_SESSION["name"]; ?>">
            <input type="hidden" name="wordToGuess" id="wordToGuess" value="<?php echo $_SESSION["wordToGuess"]; ?>">
            <input type="hidden" name="letterUsedCount" id="letterUsedCount" value="<?php echo $_SESSION["letterUsedCount"]; ?>">
            <input type="hidden" name="letterTrueGuess" id="letterTrueGuess" value="<?php echo $_SESSION["letterTrueGuess"]; ?>">
        </form>
    </div>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/script.js" charset="utf-8"></script>
</body>

</html>