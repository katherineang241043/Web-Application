<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php?warning=Please login first.");
    exit();
}

$servername = "localhost";
$username = "popmart_collector";
$password = "pop123";
$dbname = "popmart_collector";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_set_charset($conn, "utf8mb4");

$user_id = $_SESSION["user_id"];
$has_result = false;
$is_duplicate = false;
$drawn_character = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chance = rand(1, 80);

    if ($chance == 1) {
        $rarity = "Secret";
        $character_id = 9;
    } else {
        $rarity = "Common";
        $character_id = rand(1, 8);
    }

    $character_query = "SELECT * FROM characters WHERE id = '$character_id'";
    $character_result = mysqli_query($conn, $character_query);

    if (mysqli_num_rows($character_result) > 0) {
        $drawn_character = mysqli_fetch_assoc($character_result);
        $has_result = true;

        $check_query = "SELECT * FROM collection WHERE user_id = '$user_id' AND character_id = '$character_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $is_duplicate = true;
            $update_query = "UPDATE collection SET quantity = quantity + 1, last_drawn_at = NOW()
                             WHERE user_id = '$user_id' AND character_id = '$character_id'";
            mysqli_query($conn, $update_query);
        } else {
            $insert_query = "INSERT INTO collection (user_id, character_id, quantity)
                             VALUES ('$user_id', '$character_id', 1)";
            mysqli_query($conn, $insert_query);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Blind Box | Pop Mart Collector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="dashboard.php"><span class="brand-box">?</span><span>POP &amp; REVEAL</span></a>

        <nav class="nav-links">
            <a href="dashboard.php"><span class="nav-icon">&#8962;</span><span class="nav-label">Home</span></a>
            <a class="active" href="open_box.php"><span class="nav-icon">?</span><span class="nav-label">Open</span></a>
            <a href="collection.php"><span class="nav-icon">&#9638;</span><span class="nav-label">Collection</span></a>
            <a href="profile.php"><span class="nav-icon">&#9786;</span><span class="nav-label">Profile</span></a>
            <a class="logout-link" href="logout.php"><span class="nav-icon">&#8594;</span><span class="nav-label">Logout</span></a>
        </nav>
    </header>

    <main class="page-container">
        <section class="draw-page">
            <div class="draw-copy">
                <p class="eyebrow">THE MAIN EVENT</p>
                <h1>Choose curiosity.<br>Reveal a surprise.</h1>
                <p>One click randomly selects a Common or Secret character and saves it to your collection.</p>

                <div class="chance-pills">
                    <span>Common 79 / 80</span>
                    <span>Secret 1 / 80</span>
                </div>
            </div>

            <div class="draw-stage">
                <?php if ($has_result == false) { ?>
                    <div class="blind-box">
                        <span class="box-question">?</span>
                        <span class="box-label">MYSTERY BOX</span>
                    </div>

                    <form method="POST" action="open_box.php">
                        <button class="btn btn-primary draw-button" type="submit">Open Blind Box</button>
                    </form>

                    <p class="tiny-note">A new result is generated every time you open a box.</p>
                <?php } else { ?>
                    <?php
                    $rarity_class = strtolower($drawn_character["rarity"]);
                    ?>

                    <div class="result-card <?php echo $rarity_class; ?> reveal-animation">
                        <span class="rarity-badge"><?php echo $drawn_character["rarity"]; ?></span>

                        <div class="character-circle" style="background-color: <?php echo htmlspecialchars($drawn_character['theme_color']); ?>;">
                            <?php echo strtoupper(substr($drawn_character["name"], 0, 1)); ?>
                        </div>

                        <p class="series-name"><?php echo htmlspecialchars($drawn_character["series_name"]); ?> SERIES</p>
                        <h2><?php echo htmlspecialchars($drawn_character["name"]); ?></h2>
                        <p><?php echo htmlspecialchars($drawn_character["description"]); ?></p>

                        <?php if ($is_duplicate == true) { ?>
                            <div class="duplicate-message">Duplicate! Your quantity has increased by 1.</div>
                        <?php } else { ?>
                            <div class="new-message">New character added to your collection!</div>
                        <?php } ?>

                        <?php if ($drawn_character["rarity"] == "Secret") { ?>
                            <div class="secret-message">&#10024; AMAZING! YOU FOUND A SECRET! &#10024;</div>
                        <?php } ?>
                    </div>

                    <div class="result-actions">
                        <form method="POST" action="open_box.php">
                            <button class="btn btn-primary" type="submit">Open Another</button>
                        </form>

                        <a class="btn btn-outline" href="collection.php">View Collection</a>
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>

    <footer>Student Assignment - Pop Mart Blind Box Collector</footer>
</body>
</html>
<?php mysqli_close($conn); ?>
