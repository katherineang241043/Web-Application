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

$character_query = "SELECT * FROM characters ORDER BY id ASC";
$all_characters = mysqli_query($conn, $character_query);
$total_characters = mysqli_num_rows($all_characters);

$collected_count = 0;
$total_draws = 0;
$has_secret = false;

$collection_query = "SELECT * FROM collection WHERE user_id = '$user_id'";
$collection_result = mysqli_query($conn, $collection_query);

while ($collection_row = mysqli_fetch_assoc($collection_result)) {
    $collected_count++;
    $total_draws = $total_draws + $collection_row["quantity"];

    $saved_character_id = $collection_row["character_id"];
    $saved_character_query = "SELECT * FROM characters WHERE id = '$saved_character_id'";
    $saved_character_result = mysqli_query($conn, $saved_character_query);
    $saved_character = mysqli_fetch_assoc($saved_character_result);

    if ($saved_character["rarity"] == "Secret") {
        $has_secret = true;
    }
}

$progress = 0;

if ($total_characters > 0) {
    $progress = round(($collected_count / $total_characters) * 100);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Collection | Pop Mart Collector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="dashboard.php"><span class="brand-box">?</span><span>POP & REVEAL</span></a>

        <nav class="nav-links">
            <a href="dashboard.php"><span class="nav-icon">&#8962;</span><span class="nav-label">Home</span></a>
            <a href="open_box.php"><span class="nav-icon">?</span><span class="nav-label">Open</span></a>
            <a class="active" href="collection.php"><span class="nav-icon">&#9638;</span><span class="nav-label">Collection</span></a>
            <a href="profile.php"><span class="nav-icon">&#9786;</span><span class="nav-label">Profile</span></a>
            <a class="logout-link" href="logout.php"><span class="nav-icon">&#8594;</span><span class="nav-label">Logout</span></a>
        </nav>
    </header>

    <main class="page-container">
        <section class="page-heading collection-heading">
            <div>
                <p class="eyebrow">YOUR DISPLAY CABINET</p>
                <h1>My Collection</h1>
                <p>Every character you reveal is saved here. Duplicates increase the quantity.</p>
            </div>

            <a class="btn btn-primary" href="open_box.php">Open Another Box</a>
        </section>

        <section class="progress-panel panel">
            <div class="progress-copy">
                <strong><?php echo $collected_count; ?> / <?php echo $total_characters; ?> characters</strong>
                <span><?php echo $progress; ?>% complete • <?php echo $total_draws; ?> total draws</span>
            </div>

            <div class="collection-progress">
                <i style="width: <?php echo $progress; ?>%"></i>
            </div>
        </section>

        <section class="badge-row">
            <div class="achievement <?php if ($total_draws >= 1) { echo 'unlocked'; } ?>">
                <span>&#9733;</span>
                <div><strong>First Reveal</strong><small>Open your first box</small></div>
            </div>

            <div class="achievement <?php if ($total_draws >= 10) { echo 'unlocked'; } ?>">
                <span>&#9635;</span>
                <div><strong>Box Fan</strong><small>Open 10 boxes</small></div>
            </div>

            <div class="achievement <?php if ($has_secret == true) { echo 'unlocked'; } ?>">
                <span>&#10024;</span>
                <div><strong>Secret Hunter</strong><small>Find a Secret</small></div>
            </div>
        </section>

        <section class="collection-grid">
            <?php while ($character = mysqli_fetch_assoc($all_characters)) { ?>
                <?php
                $character_id = $character["id"];
                $user_character_query = "SELECT * FROM collection
                                         WHERE user_id = '$user_id' AND character_id = '$character_id'";
                $user_character_result = mysqli_query($conn, $user_character_query);
                $quantity = 0;

                if (mysqli_num_rows($user_character_result) > 0) {
                    $user_character = mysqli_fetch_assoc($user_character_result);
                    $quantity = $user_character["quantity"];
                }

                if ($quantity > 0) {
                    $collection_class = "owned";
                } else {
                    $collection_class = "locked";
                }

                $rarity_class = strtolower($character["rarity"]);
                ?>

                <article class="collection-card <?php echo $collection_class; ?>">
                    <div class="card-image" style="background-color: <?php echo htmlspecialchars($character['theme_color']); ?>;">
                        <?php if ($quantity > 0) { ?>
                            <img src="images/<?php echo htmlspecialchars($character['image_file']); ?>" alt="<?php echo htmlspecialchars($character['series_name'] . ' - ' . $character['name']); ?>">
                        <?php } else { ?>
                            <span>?</span>
                        <?php } ?>

                        <em class="rarity-badge <?php echo $rarity_class; ?>"><?php echo $character["rarity"]; ?></em>

                        <?php if ($quantity > 1) { ?>
                            <b class="quantity-badge">x<?php echo $quantity; ?></b>
                        <?php } ?>
                    </div>

                    <div class="card-info">
                        <small><?php echo htmlspecialchars($character["series_name"]); ?> SERIES</small>

                        <?php if ($quantity > 0) { ?>
                            <h3><?php echo htmlspecialchars($character["name"]); ?></h3>
                            <p><?php echo htmlspecialchars($character["description"]); ?></p>
                        <?php } else { ?>
                            <h3>Not Collected</h3>
                            <p>Keep opening boxes to reveal this character.</p>
                        <?php } ?>
                    </div>
                </article>
            <?php } ?>
        </section>
    </main>

    <footer>Student Assignment - Pop Mart Blind Box Collector</footer>
</body>
</html>
<?php mysqli_close($conn); ?>
