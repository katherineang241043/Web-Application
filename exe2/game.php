<?php
session_start();

if (!isset($_SESSION['UID'])) {
    die("Please fill out the form first. <a href='index.php'>Go Back</a>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Selection</title>
  <style>
    body {
      display: flex;
      flex-direction: column;
      height: 100vh;
      margin: 0;
    }

    .game-container {
      margin: 20px 0px 0px 40px;
    }

    .game-logout {
      margin: 40px 0px 0px 40px;
    }

    h1 {
      font-size: 35px;
      margin-top: 20px;
      margin-bottom: 20px;
      font-weight: bold;
    }

    .play-btn {
      font-size: 15px;
      color: purple;
      background-color: #efefef;
      border: 1px solid #767676;
      border-radius: 2px;
      padding: 3px 8px;
      cursor: pointer;
      text-decoration: underline;
    }

    .play-btn:hover {
      background-color: #e5e5e5;
    }
  </style>
</head>
<body>

  <div class="game-container">
    <h1>Game 1</h1>
    <a href="game1.php">
      <button type="button" class="play-btn">Play Game 1</button>
    </a>

    <h1>Game 2</h1>
    <a href="game2.php">
      <button type="button" class="play-btn">Play Game 2</button>
    </a>

    <h1>Game 3</h1>
    <a href="game3.php">
      <button type="button" class="play-btn">Play Game 3</button>
    </a>
  </div>

  <div class="game-logout">
    <a href="login.php"><button type="button" class="play-btn">Logout</button></a>
  </div>

</body>
</html>