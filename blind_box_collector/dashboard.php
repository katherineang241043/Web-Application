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

$session_email = mysqli_real_escape_string($conn, $_SESSION["email"]);
$user_query = "SELECT * FROM users WHERE email = '$session_email'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user["id"];

$collected_count = 0;
$total_draws = 0;
$collection_query = "SELECT * FROM collection WHERE user_id = '$user_id'";
$collection_result = mysqli_query($conn, $collection_query);

while ($collection_row = mysqli_fetch_assoc($collection_result)) {
    $collected_count++;
    $total_draws = $total_draws + $collection_row["quantity"];
}

$character_query = "SELECT * FROM characters";
$character_result = mysqli_query($conn, $character_query);
$total_characters = mysqli_num_rows($character_result);

$favorite = $user["favorite_series"];
$favorite_display = $favorite;

if ($favorite == "") {
    $favorite_display = "Not Set";
    $recommendation = "Complete your profile to receive a personalized series recommendation.";
} else if ($favorite == "Labubu") {
    $recommendation = "HIRONO - My Deepest Secret: another expressive character with a strong personality.";
} else if ($favorite == "Dimoo") {
    $recommendation = "TWINKLE TWINKLE - Waiting in Snow: a dreamy style with soft colours.";
} else if ($favorite == "Hirono") {
    $recommendation = "SKULLPANDA - A Dawn Duet: a stylish design with emotional storytelling.";
} else if ($favorite == "Molly") {
    $recommendation = "HACIPUPU - Make Me Blush: a sweet and colourful character.";
} else if ($favorite == "Skullpanda") {
    $recommendation = "CRYBABY - Shall We Dance: a bold design with an expressive mood.";
} else if ($favorite == "Crybaby") {
    $recommendation = "LABUBU - Dive into Love: playful, mischievous, and full of surprises.";
} else if ($favorite == "Hacipupu") {
    $recommendation = "MOLLY - My Sweet Trouble: a cute style with a playful personality.";
} else if ($favorite == "Twinkle Twinkle") {
    $recommendation = "DIMOO - Whispers of Love: a gentle style filled with imagination.";
} else {
    $recommendation = "LABUBU - Dive into Love: playful, mischievous, and full of surprises.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Home | Pop Mart Collector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="dashboard.php">
            <span class="brand-box">?</span>
            <span>POP & REVEAL</span>
        </a>

        <nav class="nav-links">
            <a class="active" href="dashboard.php"><span class="nav-icon">&#8962;</span><span class="nav-label">Home</span></a>
            <a href="open_box.php"><span class="nav-icon">?</span><span class="nav-label">Open</span></a>
            <a href="collection.php"><span class="nav-icon">&#9638;</span><span class="nav-label">Collection</span></a>
            <a href="profile.php"><span class="nav-icon">&#9786;</span><span class="nav-label">Profile</span></a>
            <a class="logout-link" href="logout.php"><span class="nav-icon">&#8594;</span><span class="nav-label">Logout</span></a>
        </nav>
    </header>

    <main class="page-container">
        <section class="hero">
            <div class="hero-content">
                <p class="eyebrow">YOUR COLLECTOR SPACE</p>
                <h1>Welcome, <?php echo htmlspecialchars($user["full_name"]); ?>!</h1>
                <p>Ready for another surprise? Every box could be your first Secret character.</p>

                <div class="hero-actions">
                    <a class="btn btn-primary" href="open_box.php">Open a Blind Box</a>
                    <a class="btn btn-outline" href="collection.php">View Collection</a>
                </div>
            </div>

            <div class="hero-box">
                <span>?</span>
                <small>WHAT'S INSIDE?</small>
            </div>
        </section>

        <?php if ($favorite == "") { ?>
            <div class="message warning-message">Your collector profile is incomplete. <a href="profile.php">Add your preferences now</a>.</div>
        <?php } ?>

        <section class="stats-grid">
            <article class="stat-card coral">
                <span class="stat-icon">&#9733;</span>
                <div>
                    <strong><?php echo $collected_count; ?> / <?php echo $total_characters; ?></strong>
                    <p>Characters Collected</p>
                </div>
            </article>

            <article class="stat-card yellow">
                <span class="stat-icon">&#9635;</span>
                <div>
                    <strong><?php echo $total_draws; ?></strong>
                    <p>Total Boxes Opened</p>
                </div>
            </article>

            <article class="stat-card blue">
                <span class="stat-icon">&#9829;</span>
                <div>
                    <strong><?php echo htmlspecialchars($favorite_display); ?></strong>
                    <p>Favourite Series</p>
                </div>
            </article>
        </section>

        <section class="content-grid">
            <article class="panel recommendation-panel">
                <p class="eyebrow">PERSONALIZED FOR YOU</p>

                <?php if ($favorite == "") { ?>
                    <h2>Your next favourite series...</h2>
                <?php } else { ?>
                    <h2>Because you like <?php echo htmlspecialchars($favorite); ?>...</h2>
                <?php } ?>

                <p><?php echo htmlspecialchars($recommendation); ?></p>
                <a href="open_box.php">Try your luck &rarr;</a>
            </article>

            <article class="panel rarity-panel">
                <p class="eyebrow">DROP RATES</p>
                <h2>Every box is a surprise</h2>

                <div class="rarity-row"><span>Common</span><strong>98.75%</strong></div>
                <div class="rate-bar"><i class="common-width"></i></div>

                <div class="rarity-row"><span>Secret (1 / 80)</span><strong>1.25%</strong></div>
                <div class="rate-bar secret-rate"><i class="secret-width"></i></div>
            </article>
        </section>
    </main>

    <footer>Student Assignment - Pop Mart Blind Box Collector</footer>
</body>
</html>
<?php mysqli_close($conn); ?>
