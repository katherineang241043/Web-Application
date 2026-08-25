<?php
include "auth.php";
include "config.php";

$user_id = (int) $_SESSION["user_id"];
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $favorite_series = $_POST["favorite_series"];
    $favorite_color = trim($_POST["favorite_color"]);
    $collecting_purpose = $_POST["collecting_purpose"];

    if ($full_name === "" || $email === "" || $phone === "" || $favorite_series === "" || $collecting_purpose === "") {
        $error_message = "Please complete all required fields.";
    } else {
        $safe_name = mysqli_real_escape_string($conn, $full_name);
        $safe_email = mysqli_real_escape_string($conn, $email);
        $safe_phone = mysqli_real_escape_string($conn, $phone);
        $safe_series = mysqli_real_escape_string($conn, $favorite_series);
        $safe_color = mysqli_real_escape_string($conn, $favorite_color);
        $safe_purpose = mysqli_real_escape_string($conn, $collecting_purpose);

        $sql = "UPDATE users SET full_name='$safe_name', email='$safe_email', phone='$safe_phone',
                favorite_series='$safe_series', favorite_color='$safe_color', collecting_purpose='$safe_purpose'
                WHERE id=$user_id";

        if (mysqli_query($conn, $sql)) {
            $_SESSION["full_name"] = $full_name;
            $_SESSION["email"] = $email;
            $success_message = "Profile saved successfully. Your recommendations are now personalized!";
        } else {
            $error_message = "Unable to save profile. The email may already be in use.";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($result);
$series_list = array("Labubu", "Dimoo", "Hirono", "Molly", "Skullpanda", "Crybaby");
$purpose_list = array("Collection", "Decoration", "Gift");

$page_title = "My Profile";
$active_page = "profile";
include "header.php";
?>

<section class="page-heading">
    <p class="eyebrow">CLIENT DATA COLLECTION</p>
    <h1>Tell us what you love</h1>
    <p>Your preferences help us create a more personal blind box experience.</p>
</section>

<?php if ($success_message !== "") { ?><div class="message success-message"><?php echo $success_message; ?></div><?php } ?>
<?php if ($error_message !== "") { ?><div class="message error-message"><?php echo $error_message; ?></div><?php } ?>

<section class="panel profile-panel">
    <form method="POST" action="profile.php" class="two-column-form">
        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input id="full_name" type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address *</label>
            <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input id="phone" type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <div class="form-group">
            <label for="favorite_series">Favorite Pop Mart Series *</label>
            <select id="favorite_series" name="favorite_series" required>
                <option value="">Choose a series</option>
                <?php foreach ($series_list as $series) { ?>
                    <option value="<?php echo $series; ?>" <?php echo $user['favorite_series'] === $series ? 'selected' : ''; ?>><?php echo $series; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="favorite_color">Favorite Color (Optional)</label>
            <input id="favorite_color" type="text" name="favorite_color" value="<?php echo htmlspecialchars($user['favorite_color']); ?>" placeholder="Example: Pink">
        </div>
        <div class="form-group">
            <label for="collecting_purpose">Collecting Purpose *</label>
            <select id="collecting_purpose" name="collecting_purpose" required>
                <option value="">Choose a purpose</option>
                <?php foreach ($purpose_list as $purpose) { ?>
                    <option value="<?php echo $purpose; ?>" <?php echo $user['collecting_purpose'] === $purpose ? 'selected' : ''; ?>><?php echo $purpose; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-actions-wide">
            <button class="btn btn-primary" type="submit">Save My Preferences</button>
        </div>
    </form>
</section>

<?php include "footer.php"; ?>
