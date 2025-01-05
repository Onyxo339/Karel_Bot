<?php
session_start();

function ResetBoard() {
    $_SESSION['karel'] = ['x' => 0, 'y' => 0, 'direction' => 0];
    $_SESSION['board'] = array_fill(0, 10, array_fill(0, 10, ''));
}

function UpdatePosition() {
    $board = $_SESSION['board'];
    $karel = $_SESSION['karel'];

    foreach ($board as $y => $row) {
        foreach ($row as $x => $cell) {
            if ($cell === 'K') {
                $board[$y][$x] = '';
            }
        }
    }

    $board[$karel['y']][$karel['x']] = 'K';
    $_SESSION['board'] = $board;
}

function ChangeBlockColor() {
    $karel = $_SESSION['karel'];
    $_SESSION['board'][$karel['y']][$karel['x']] = 'blue';
}

function Move() {
    $karel = &$_SESSION['karel'];
    $boardSize = 10;

    switch ($karel['direction']) {
        case 0: if ($karel['x'] < $boardSize - 1) $karel['x']++; break;
        case 1: if ($karel['y'] < $boardSize - 1) $karel['y']++; break;
        case 2: if ($karel['x'] > 0) $karel['x']--; break;
        case 3: if ($karel['y'] > 0) $karel['y']--; break;
    }

    UpdatePosition();
}

function Commands($command) {
    $parts = explode(' ', trim($command));
    $action = strtoupper($parts[0]);
    $param = isset($parts[1]) ? intval($parts[1]) : 1;

    switch ($action) {
        case 'KROK':
            for ($i = 0; $i < $param; $i++) Move();
            break;
        case 'VPRAVOBOK':
            $_SESSION['karel']['direction'] = ($_SESSION['karel']['direction'] + $param) % 4;
            break;
        case 'POLOZ':
            ChangeBlockColor();
            break;
        case 'RESET':
            ResetBoard();
            break;
        case 'RESET KAREL':
            $_SESSION['karel'] = ['x' => 0, 'y' => 0, 'direction' => 0];
            UpdatePosition();
            break;
        default:
            echo "<p>Neznámý příkaz: $command</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commands = explode("\n", $_POST['commands']);
    foreach ($commands as $command) {
        if (trim($command) !== '') {
            Commands($command);
        }
    }
} elseif (!isset($_SESSION['board'])) {
    ResetBoard();
}

function RenderBoard() {
    $board = $_SESSION['board'];
    echo '<div id="game-board" style="display: grid; grid-template-columns: repeat(10, 30px); gap: 2px;">';
    foreach ($board as $y => $row) {
        foreach ($row as $x => $cell) {
            $color = ($cell === 'blue') ? 'background-color: blue;' : '';
            $content = ($cell === 'K') ? 'K' : '';
            echo "<div style='width: 30px; height: 30px; border: 1px solid black; text-align: center; $color'>$content</div>";
        }
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karel Robot</title>
</head>
<body>
    <h1>Karel Robot</h1>
    <?php RenderBoard(); ?>
    <form method="post">
        <textarea name="commands" id="commands" rows="5" cols="30" placeholder="Enter commands"></textarea><br>
        <button type="submit">Run</button>
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karel - PHP Verze</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            text-align: center;
            padding: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(10, 50px);
            grid-template-rows: repeat(10, 50px);
            gap: 2px;
            margin: 20px auto;
        }

        .cell {
            width: 50px;
            height: 50px;
            background-color: white;
            border: 1px solid;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .robot {
            background-color: red;
            color: white;
            font-weight: bold;
        }

        .controls {
            margin-top: 20px;
        }

        textarea {
            width: 80%;
            height: 100px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>