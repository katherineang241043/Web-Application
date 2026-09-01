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
$draw_was_free = false;
$error_message = "";
$drawn_character = array();

// count only this user's draws for today's date.
$today = date("Y-m-d");
$today_query = "SELECT * FROM draw_history WHERE user_id = '$user_id' AND draw_date = '$today'";
$today_result = mysqli_query($conn, $today_query);
$today_draws = mysqli_num_rows($today_result);
$draws_left = 4 - $today_draws;

if ($draws_left < 0) {
    $draws_left = 0;
}

// Show the last completed draw after redirecting back to this page.
// Refreshing this GET page only shows the same result and does not draw again.
if (isset($_GET["result"]) && isset($_SESSION["last_draw_result"])) {
    $drawn_character = $_SESSION["last_draw_result"];
    $is_duplicate = $_SESSION["last_draw_duplicate"];
    $draw_was_free = $_SESSION["last_draw_free"];
    $has_result = true;
}

// A draw runs only when the user clicks a button named draw_button.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["draw_button"])) {
    // user cannot make a fifth draw on the same day.
    if ($today_draws >= 4) {
        $error_message = "You have used all 4 draws for today. Come back tomorrow!";
    } else {
        $chance = rand(1, 80);

        if ($chance == 1) {
            $character_id = 9;
        } else {
            $character_id = rand(1, 8);
        }

        $character_query = "SELECT * FROM characters WHERE id = '$character_id'";
        $character_result = mysqli_query($conn, $character_query);

        if (mysqli_num_rows($character_result) > 0) {
            $drawn_character = mysqli_fetch_assoc($character_result);

            // first draw each day is the Daily Free Draw.
            $is_free = 0;

            if ($today_draws == 0) {
                $is_free = 1;
                $draw_was_free = true;
            }

            $history_query = "INSERT INTO draw_history (user_id, character_id, draw_date, is_free)
                              VALUES ('$user_id', '$character_id', '$today', '$is_free')";

            if (mysqli_query($conn, $history_query)) {
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

                $today_draws = $today_draws + 1;
                $draws_left = 4 - $today_draws;

                // Save the result, then redirect to prevent F5 from repeating POST.
                $_SESSION["last_draw_result"] = $drawn_character;
                $_SESSION["last_draw_duplicate"] = $is_duplicate;
                $_SESSION["last_draw_free"] = $draw_was_free;

                header("Location: open_box.php?result=1");
                exit();
            } else {
                $error_message = "Unable to save this draw. Please try again.";
            }
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
            <a class="logout-link" href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><span class="nav-icon">&#8594;</span><span class="nav-label">Logout</span></a>
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

                <div class="daily-draw-panel">
                    <p class="daily-title">🎁 DAILY FREE DRAW</p>
                    <strong>1 Free Draw Every Day</strong>

                    <div class="draw-counter-row">
                        <span>Today's Draws</span>
                        <b><?php echo $today_draws; ?> / 4</b>
                    </div>

                    <div class="draw-counter-row">
                        <span>Daily Free Draw</span>

                        <?php if ($today_draws == 0) { ?>
                            <b class="free-available">Available</b>
                        <?php } else { ?>
                            <b class="free-used">Used</b>
                        <?php } ?>
                    </div>

                    <?php if ($draws_left == 1) { ?>
                        <p class="draws-left">1 draw left today</p>
                    <?php } else { ?>
                        <p class="draws-left"><?php echo $draws_left; ?> draws left today</p>
                    <?php } ?>
                </div>
            </div>

            <div class="draw-stage">
                <?php if ($error_message != "") { ?>
                    <div class="message warning-message"><?php echo $error_message; ?></div>
                <?php } ?>

                <?php if ($has_result == false) { ?>
                    <div class="blind-box">
                        <span class="box-question">?</span>
                        <span class="box-label">MYSTERY BOX</span>
                    </div>

                    <?php if ($draws_left > 0) { ?>
                        <form method="POST" action="open_box.php">
                            <button class="btn btn-primary draw-button" type="submit" name="draw_button" value="1">
                                <?php if ($today_draws == 0) { ?>Draw Now - Free<?php } else { ?>Open Blind Box<?php } ?>
                            </button>
                        </form>
                    <?php } else { ?>
                        <button class="btn draw-button disabled-button" type="button" disabled>No Draws Left Today</button>
                        <div class="message warning-message">You have used all 4 draws for today. Come back tomorrow!</div>
                    <?php } ?>

                    <p class="tiny-note">Your first draw is free. You can open up to 4 boxes each day.</p>
                <?php } else { ?>
                    <?php
                    $rarity_class = strtolower($drawn_character["rarity"]);
                    ?>

                    <?php if ($drawn_character["rarity"] == "Secret") { ?>
                        <p class="result-heading">✨ SECRET!</p>
                    <?php } else { ?>
                        <p class="result-heading">🎉 Congratulations!</p>
                    <?php } ?>

                    <div class="result-card <?php echo $rarity_class; ?> reveal-animation">
                        <span class="rarity-badge"><?php echo $drawn_character["rarity"]; ?></span>

                        <img class="result-image" src="images/<?php echo htmlspecialchars($drawn_character['image_file']); ?>" alt="<?php echo htmlspecialchars($drawn_character['series_name'] . ' - ' . $drawn_character['name']); ?>">

                        <p class="series-name"><?php echo htmlspecialchars($drawn_character["series_name"]); ?> SERIES</p>
                        <h2><?php echo htmlspecialchars($drawn_character["name"]); ?></h2>
                        <p><?php echo htmlspecialchars($drawn_character["description"]); ?></p>

                        <?php if ($draw_was_free == true) { ?>
                            <div class="free-draw-message">🎁 Daily Free Draw Used</div>
                        <?php } ?>

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
                        <?php if ($draws_left > 0) { ?>
                            <form method="POST" action="open_box.php">
                                <button class="btn btn-primary" type="submit" name="draw_button" value="1">Open Another (<?php echo $draws_left; ?> left)</button>
                            </form>
                        <?php } else { ?>
                            <button class="btn disabled-button" type="button" disabled>Daily Limit Reached</button>
                        <?php } ?>

                        <a class="btn btn-outline" href="collection.php">View Collection</a>
                    </div>

                    <?php if ($draws_left == 0) { ?>
                        <div class="message warning-message result-limit-message">You have used all 4 draws for today. Come back tomorrow!</div>
                    <?php } ?>
                <?php } ?>
            </div>
        </section>
    </main>

    <footer>Student Assignment - Pop Mart Blind Box Collector</footer>
</body>
</html>
<?php mysqli_close($conn); ?>
