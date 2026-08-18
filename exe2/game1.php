<?php
session_start();
if (!isset($_SESSION['UID'])) die("Please fill out the form first. <a href='index.php'>Go Back</a>");

$conn = new mysqli("localhost", "game", "game123", "game");
$uid = $_SESSION['UID'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['g1_val'])) {
    $selected_val = intval($_POST['g1_val']);
    $stmt = $conn->prepare("UPDATE users SET G1 = ?, G1_count = G1_count + 1 WHERE UID = ? AND G1_count < 2");
    $stmt->bind_param("is", $selected_val, $uid);
    $stmt->execute();
}


$stmt = $conn->prepare("SELECT G1_count FROM users WHERE UID = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$g1_count = ($row = $stmt->get_result()->fetch_assoc()) ? intval($row['G1_count']) : 0;


$message = "";
if ($g1_count >= 2) {
    $message = "<p style='color:red;'>Limit reached! You can only update Game 1 up to 2 times.</p>";
} elseif (isset($selected_val)) {
    $message = "<p style='color:green;'>$selected_val successfully! (Attempt $g1_count/2)</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game 1</title>
  <style>
    body {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Times New Roman, serif;
    }

    h1 {
      font-size: 35px;
      margin-bottom: 30px;
    }

    .btn-container {
      display: flex;
      gap: 10px;
    }

    .num-btn {
      font-size: 15px;
      padding: 6px 16px;
      background-color: #efefef;
      border: 1px solid #767676;
      border-radius: 4px;
      cursor: pointer;
    }

    .num-btn:disabled {
      cursor: not-allowed;
      opacity: 0.5;
    }
  </style>
</head>
<body>

  <h1>Game 1</h1>

  <?php if (!empty($message)) echo $message; ?>

  <form method="POST">
    <div class="btn-container">
      <?php for ($i = 0; $i <= 5; $i++): ?>
        <button 
          type="submit" 
          name="g1_val" 
          value="<?php echo $i; ?>" 
          class="num-btn"
          <?php if ($g1_count >= 2) echo 'disabled'; ?>
        >
          <?php echo $i; ?>
        </button>
      <?php endfor; ?>
    </div>
  </form>

  <p><a href="game.php">Back</a></p>

</body>
</html>