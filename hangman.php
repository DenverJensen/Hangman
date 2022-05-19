<?php
session_start();

$message = "";
$console = $_SESSION;

/*
$_SESSION['wordToGuess'] = $words[$random_word];
$_SESSION['wordToGuessArray'] = array();
$_SESSION['wordToGuessLetterCount'] = $letter_count;
$_SESSION['totalLife'] = 6;
$_SESSION['letterUsedCount'] = 0;
$_SESSION['letterUsed'] = array;
$_SESSION['gameOver'] = false;
*/



//letter used 
$total_life_dashes = implode(" ", $_SESSION['letterUsed']);

//letter used 
$words_to_guess_dashes = implode(" ", $_SESSION['wordToGuessArray']);

// when user submit new letter
if (isset($_POST['letterInput']) && !empty($_POST['letterInput']) && $_POST['letterInput'] != "") {

    // find the position of the letter in the word
    $value = $_POST["letterInput"];
    $position = strpos($_SESSION['wordToGuess'], $value);

    // check if the word is alphabet
    if (!preg_match("/^[a-zA-Z]*$/", $value)) {

        $message = "Only letters allowed";

        //check if the there is only one letter 
    } elseif (strlen($value) > 1) {

        $message = "Only one letter allowed at a time";
    } elseif ($position === false) {
        //if the letter is not found then add that letter to used letter array and update the remaining life
        $_SESSION['letterUsedCount']++;
        //$_SESSION['letterUsed'][] = $value;

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
        $_SESSION['wordToGuessArray'][$position] = $value;
        $words_to_guess_dashes = implode(" ", $_SESSION['wordToGuessArray']);
        $_SESSION['letterTrueGuess']++;
        $message = "Letter matched";


        //check if the all letter are guessed
        if ($_SESSION['letterTrueGuess'] >= $_SESSION['wordToGuessLetterCount']) {
            $_SESSION['gameOver'] = true;
            $_SESSION['letterUsed'] = array();
            $message = "You Won";
        }
    }
} else { 
    $message = "Error: Empty input";
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
                    <span><?php echo $_SESSION['username']; ?></span>
                    <?php
                    $hangman_photo = "hangman0.png";
                    if ($_SESSION['letterUsedCount'] == 0) {
                        $hangman_photo = "hangman0.png";
                    } elseif ($_SESSION['letterUsedCount'] == 1) {
                        $hangman_photo = "hangman1.png";
                    } elseif ($_SESSION['letterUsedCount'] == 2) {
                        $hangman_photo = "hangman2.png";
                    } elseif ($_SESSION['letterUsedCount'] == 3) {
                        $hangman_photo = "hangman3.png";
                    } elseif ($_SESSION['letterUsedCount'] == 4) {
                        $hangman_photo = "hangman4.png";
                    } elseif ($_SESSION['letterUsedCount'] == 5) {
                        $hangman_photo = "hangman5.png";
                    } elseif ($_SESSION['letterUsedCount'] == 6) {
                        $hangman_photo = "hangman6.png";
                    } else {
                        $hangman_photo = "hangman0.png";
                    }
                    ?>
                    <img class="d-block mx-auto mb-4" src="assets/images/<?php echo $hangman_photo; ?>" alt="" width="200">
                </div>
                <div class="remaining-guesses">
                    <span>Remaining Gueses</span>
                    <strong><?php echo $_SESSION['totalLife'] - $_SESSION['letterUsedCount']; ?></strong>
                </div>


            </div>
            <div>
                <h4>Game Board</h4>

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
                        <div>
                            <input type="submit" value="Submit">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                    <h4>Result</h4>
                    <div class="row">
                        <div class="col-md-12" id="result"><?php echo $message; ?></div>
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