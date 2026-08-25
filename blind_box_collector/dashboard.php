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


$session_email = $_SESSION["email"];
$user_result = $conn->query("SELECT * FROM users WHERE email = '$session_email'");
$user = $user_result->fetch_assoc();

$user_id = $user["id"];
$collection_result = $conn->query("SELECT COUNT(*) AS total_types, COALESCE(SUM(quantity), 0) AS total_draws FROM collection WHERE user_id = '$user_id'");
$collection_stats = $collection_result->fetch_assoc();

$character_result = $conn->query("SELECT COUNT(*) AS total FROM characters");
$character_stats = $character_result->fetch_assoc();

$recommendations = array(
    "Labubu" => "Hirono: another expressive character with a strong personality.",
    "Dimoo" => "Pucky: a dreamy series with soft colours and fantasy themes.",
    "Hirono" => "Skullpanda: a stylish series with emotional storytelling.",
    "Molly" => "Dimoo: a colourful world filled with imagination.",
    "Skullpanda" => "Crybaby: bold designs with expressive moods.",
    "Crybaby" => "Labubu: playful, mischievous, and full of surprises."
);

$favorite = isset($user["favorite_series"]) ? $user["favorite_series"] : "";
$recommendation = isset($recommendations[$favorite]) ? $recommendations[$favorite] : "Complete your profile to receive a personalized series recommendation.";

$page_title = "Collector Home";
$active_page = "home";

include "header.php";
?>

<section class="hero">
    <div>
        <p class="eyebrow">YOUR COLLECTOR SPACE</p>
        <h1>Welcome, <?php echo htmlspecialchars(isset($user["username"]) ? $user["username"] : $user["full_name"]); ?>!</h1>
        <p>Ready for another surprise? Every box could be your first Secret character.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="open_box.php">Open a Blind Box</a>
            <a class="btn btn-outline" href="collection.php">View Collection</a>
        </div>
    </div>
    <div class="hero-box"><span>?</span><small>WHAT'S INSIDE?</small></div>
</section>

<?php if ($favorite === "") { ?>
    <div class="message warning-message">Your collector profile is incomplete. <a href="profile.php">Add your preferences now</a>.</div>
<?php } ?>

<section class="stats-grid">
    <article class="stat-card coral">
        <span class="stat-icon">&#9733;</span>
        <div><strong><?php echo $collection_stats["total_types"]; ?> / <?php echo $character_stats["total"]; ?></strong><p>Characters Collected</p></div>
    </article>
    <article class="stat-card yellow">
        <span class="stat-icon">&#9635;</span>
        <div><strong><?php echo $collection_stats["total_draws"]; ?></strong><p>Total Boxes Opened</p></div>
    </article>
    <article class="stat-card blue">
        <span class="stat-icon">&#9829;</span>
        <div><strong><?php echo $favorite !== "" ? htmlspecialchars($favorite) : "Not Set"; ?></strong><p>Favourite Series</p></div>
    </article>
</section>

<section class="content-grid">
    <article class="panel recommendation-panel">
        <p class="eyebrow">PERSONALIZED FOR YOU</p>
        <h2>Because you like <?php echo $favorite !== "" ? htmlspecialchars($favorite) : "blind boxes"; ?>...</h2>
        <p><?php echo htmlspecialchars($recommendation); ?></p>
        <a href="open_box.php">Try your luck &rarr;</a>
    </article>

    <article class="panel rarity-panel">
        <p class="eyebrow">DROP RATES</p>
        <h2>Every box is a surprise</h2>
        <div class="rarity-row"><span>Common</span><strong>70%</strong></div>
        <div class="rate-bar"><i style="width:70%"></i></div>
        <div class="rarity-row"><span>Rare</span><strong>25%</strong></div>
        <div class="rate-bar rare-rate"><i style="width:25%"></i></div>
        <div class="rarity-row"><span>Secret</span><strong>5%</strong></div>
        <div class="rate-bar secret-rate"><i style="width:5%"></i></div>
    </article>
</section>

<?php 
$conn->close();
include "footer.php"; 
?>