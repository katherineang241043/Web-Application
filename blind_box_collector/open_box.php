<?php
include "auth.php";
include "config.php";

$user_id = (int) $_SESSION["user_id"];
$drawn_character = null;
$is_duplicate = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $chance = rand(1, 100);

    if ($chance <= 70) {
        $rarity = "Common";
    } elseif ($chance <= 95) {
        $rarity = "Rare";
    } else {
        $rarity = "Secret";
    }

    $result = mysqli_query($conn, "SELECT * FROM characters WHERE rarity='$rarity' ORDER BY RAND() LIMIT 1");
    $drawn_character = mysqli_fetch_assoc($result);
    $character_id = (int) $drawn_character["id"];

    $check = mysqli_query($conn, "SELECT * FROM collection WHERE user_id=$user_id AND character_id=$character_id");

    if (mysqli_num_rows($check) > 0) {
        $is_duplicate = true;
        mysqli_query($conn, "UPDATE collection SET quantity=quantity+1, last_drawn_at=NOW() WHERE user_id=$user_id AND character_id=$character_id");
    } else {
        mysqli_query($conn, "INSERT INTO collection (user_id, character_id, quantity) VALUES ($user_id, $character_id, 1)");
    }
}

$page_title = "Open Blind Box";
$active_page = "draw";
include "header.php";
?>

<section class="draw-page <?php echo $drawn_character && $drawn_character['rarity'] === 'Secret' ? 'secret-background' : ''; ?>">
    <div class="draw-copy">
        <p class="eyebrow">THE MAIN EVENT</p>
        <h1>Choose curiosity.<br>Reveal a surprise.</h1>
        <p>One click randomly selects a Common, Rare, or Secret character and saves it to your collection.</p>
        <div class="chance-pills">
            <span>Common 70%</span><span>Rare 25%</span><span>Secret 5%</span>
        </div>
    </div>

    <div class="draw-stage">
        <?php if ($drawn_character === null) { ?>
            <div class="blind-box" id="blindBox">
                <span class="box-question">?</span>
                <span class="box-label">MYSTERY BOX</span>
            </div>
            <form method="POST" action="open_box.php" id="drawForm">
                <button class="btn btn-primary draw-button" type="submit" id="openButton">Open Blind Box</button>
            </form>
            <p class="tiny-note">A new result is generated every time you open a box.</p>
        <?php } else { ?>
            <div class="result-card <?php echo strtolower($drawn_character['rarity']); ?> reveal-animation">
                <span class="rarity-badge"><?php echo $drawn_character["rarity"]; ?></span>
                <div class="character-circle" style="background-color: <?php echo htmlspecialchars($drawn_character['theme_color']); ?>;">
                    <?php echo strtoupper(substr($drawn_character["name"], 0, 1)); ?>
                </div>
                <p class="series-name"><?php echo htmlspecialchars($drawn_character["series_name"]); ?> SERIES</p>
                <h2><?php echo htmlspecialchars($drawn_character["name"]); ?></h2>
                <p><?php echo htmlspecialchars($drawn_character["description"]); ?></p>

                <?php if ($is_duplicate) { ?>
                    <div class="duplicate-message">Duplicate! Your quantity has increased by 1.</div>
                <?php } else { ?>
                    <div class="new-message">New character added to your collection!</div>
                <?php } ?>

                <?php if ($drawn_character["rarity"] === "Secret") { ?>
                    <div class="secret-message">&#10024; AMAZING! YOU FOUND A SECRET! &#10024;</div>
                <?php } ?>
            </div>
            <div class="result-actions">
                <form method="POST" action="open_box.php"><button class="btn btn-primary" type="submit">Open Another</button></form>
                <a class="btn btn-outline" href="collection.php">View Collection</a>
            </div>
        <?php } ?>
    </div>
</section>

<?php include "footer.php"; ?>
