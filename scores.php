<?php
session_start();
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        table,
        th,
        td {
            border: 1px solid;
            padding: 15px;
            text-align: center;
        }

        table {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        th {
            background-color: #6666FF;
        }

        .center {
            margin: auto !important;
            width: 50% !important;
            padding: 10px !important;
            text-align: center !important;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.striped tr:even').addClass('alt');
        });
    </script>
    <title>High Scores</title>
</head>

<body>
    <?php require 'partials/header.php' ?>

    <table>
        <thead>
            <div class="title center">
                <h3>
                    High Scores Table
                </h3>
            </div>
            <tr>
                <th>UserID</td>
                <th>Strikes</td>
                <th>Word</td>
            </tr>
        </thead>
        <tbody>
            <?php
            require 'database.php';

            $records = $conn->prepare('SELECT U.username, S.strikes, S.word FROM Scores AS S JOIN Users AS U ON U.id = S.userid WHERE S.word_length = :wlength order by S.strikes asc LIMIT 10');
            $records->bindParam(':wlength', $_SESSION['wordToGuessLetterCount']);
            $records->execute();
            $rows = $records->fetchall(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                echo "
            <tr>
                <td>" . $row['username'] . "</td>
                <td>" . $row["strikes"] . "</td>
                <td>" . $row["word"] . "</td>
            </tr>
            ";
            }
            ?>
        </tbody>
    </table>
    <div class="center">
        <a href="setup.php" class='center'>Play Again?</a>
    </div>

</body>

</html>