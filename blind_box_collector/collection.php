<?php
include "auth.php";
include "config.php";

$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM characters ORDER BY id ASC";
$all_characters = mysqli_query($conn, $sql);
$collected_count = 0;
$total_draws = 0;
$count_sql = "SELECT * FROM collection WHERE user_id = $user_id";
$count_res = mysqli_query($conn, $count_sql);

while ($c_row = mysqli_fetch_assoc($count_res)) {
    if ($c_row['quantity'] > 0) {
        $collected_count++;
    }
    $total_draws += $c_row['quantity'];
}


$total_sql = "SELECT * FROM characters";
$total_res = mysqli_query($conn, $total_sql);
$total_characters = mysqli_num_rows($total_res);
$progress = 0;
if ($total_characters > 0) {
    $progress = round(($collected_count / $total_characters) * 100);
}


$has_secret = false;
$secret_sql = "SELECT * FROM collection 
               JOIN characters ON collection.character_id = characters.id 
               WHERE collection.user_id = $user_id AND characters.rarity = 'Secret'";
$secret_res = mysqli_query($conn, $secret_sql);

if (mysqli_num_rows($secret_res) > 0) {
    $has_secret = true;
}

$page_title = "My Collection";
$active_page = "collection";
include "header.php";
?>

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
        <span><?php echo $progress; ?>% complete &bull; <?php echo $total_draws; ?> total draws</span>
    </div>
    <div class="collection-progress">
        <i style="width: <?php echo $progress; ?>%"></i>
    </div>
</section>


<section class="badge-row">
    <div class="achievement <?php if ($total_draws >= 1) { echo 'unlocked'; } ?>">
        <span>&#9733;</span>
        <div>
            <strong>First Reveal</strong>
            <small>Open your first box</small>
        </div>
    </div>
    
    <div class="achievement <?php if ($total_draws >= 10) { echo 'unlocked'; } ?>">
        <span>&#9635;</span>
        <div>
            <strong>Box Fan</strong>
            <small>Open 10 boxes</small>
        </div>
    </div>
    
    <div class="achievement <?php if ($has_secret) { echo 'unlocked'; } ?>">
        <span>&#10024;</span>
        <div>
            <strong>Secret Hunter</strong>
            <small>Find a Secret</small>
        </div>
    </div>
</section>


<section class="collection-grid">
    <?php 
    while ($character = mysqli_fetch_assoc($all_characters)) { 
        $char_id = $character['id'];
        $user_char_sql = "SELECT * FROM collection WHERE user_id = $user_id AND character_id = $char_id";
        $user_char_res = mysqli_query($conn, $user_char_sql);
        $user_char = mysqli_fetch_assoc($user_char_res);
        
        $quantity = 0;
        if ($user_char) {
            $quantity = $user_char['quantity'];
        }
    ?>
        <article class="collection-card <?php if ($quantity > 0) { echo 'owned'; } else { echo 'locked'; } ?>">
            <div class="card-image">
                <span>
                    <?php 
                    if ($quantity > 0) {
                        echo strtoupper(substr($character['name'], 0, 1));
                    } else {
                        echo '?';
                    }
                    ?>
                </span>
                
                <em class="rarity-badge <?php echo strtolower($character['rarity']); ?>">
                    <?php echo $character['rarity']; ?>
                </em>
                
                <?php if ($quantity > 1) { ?>
                    <b class="quantity-badge">x<?php echo $quantity; ?></b>
                <?php } ?>
            </div>
            
            <div class="card-info">
                <small><?php echo htmlspecialchars($character['series_name']); ?> SERIES</small>
                
                <h3>
                    <?php 
                    if ($quantity > 0) {
                        echo htmlspecialchars($character['name']);
                    } else {
                        echo 'Not Collected';
                    }
                    ?>
                </h3>
                
                <p>
                    <?php 
                    if ($quantity > 0) {
                        echo htmlspecialchars($character['description']);
                    } else {
                        echo 'Keep opening boxes to reveal this character.';
                    }
                    ?>
                </p>
            </div>
        </article>
    <?php } ?>
</section>

<?php include "footer.php"; ?>