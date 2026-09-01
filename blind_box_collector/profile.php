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
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $favorite_series = $_POST["favorite_series"];
    $favorite_color = $_POST["favorite_color"];
    $collecting_purpose = $_POST["collecting_purpose"];

    if (empty($full_name) || empty($email) || empty($phone) || empty($favorite_series) || empty($collecting_purpose)) {
        $error_message = "Please complete all required fields.";
    } else if (!ctype_digit($phone)) {
        $error_message = "Phone number must contain numbers only.";
    } else {
        $safe_name = mysqli_real_escape_string($conn, $full_name);
        $safe_email = mysqli_real_escape_string($conn, $email);
        $safe_phone = mysqli_real_escape_string($conn, $phone);
        $safe_series = mysqli_real_escape_string($conn, $favorite_series);
        $safe_color = mysqli_real_escape_string($conn, $favorite_color);
        $safe_purpose = mysqli_real_escape_string($conn, $collecting_purpose);

        $update_query = "UPDATE users SET
                         full_name = '$safe_name',
                         email = '$safe_email',
                         phone = '$safe_phone',
                         favorite_series = '$safe_series',
                         favorite_color = '$safe_color',
                         collecting_purpose = '$safe_purpose'
                         WHERE id = '$user_id'";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION["full_name"] = $full_name;
            $_SESSION["email"] = $email;
            $success_message = "Profile saved successfully. Your recommendations are now personalized!";
        } else {
            $error_message = "Unable to save profile. The email may already be in use.";
        }
    }
}

$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Pop Mart Collector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="dashboard.php"><span class="brand-box">?</span><span>POP & REVEAL</span></a>

        <nav class="nav-links">
            <a href="dashboard.php"><span class="nav-icon">&#8962;</span><span class="nav-label">Home</span></a>
            <a href="open_box.php"><span class="nav-icon">?</span><span class="nav-label">Open</span></a>
            <a href="collection.php"><span class="nav-icon">&#9638;</span><span class="nav-label">Collection</span></a>
            <a class="active" href="profile.php"><span class="nav-icon">&#9786;</span><span class="nav-label">Profile</span></a>
            <a class="logout-link" href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><span class="nav-icon">&#8594;</span><span class="nav-label">Logout</span></a>
        </nav>
    </header>

    <main class="page-container">
        <section class="page-heading">
            <p class="eyebrow">CLIENT DATA COLLECTION</p>
            <h1>Tell us what you love</h1>
            <p>Your preferences help us create a more personal blind box experience.</p>
        </section>

        <?php if ($success_message != "") { ?>
            <div class="message success-message"><?php echo $success_message; ?></div>
        <?php } ?>

        <?php if ($error_message != "") { ?>
            <div class="message error-message"><?php echo $error_message; ?></div>
        <?php } ?>

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
                    <input id="phone" type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" inputmode="numeric" pattern="[0-9]+" title="Please enter numbers only." required>
                </div>

                <div class="form-group">
                    <label for="favorite_series">Favorite Pop Mart Series *</label>
                    <select id="favorite_series" name="favorite_series" required>
                        <option value="">Choose a series</option>
                        <option value="Labubu" <?php if ($user['favorite_series'] == 'Labubu') { echo 'selected'; } ?>>Labubu</option>
                        <option value="Dimoo" <?php if ($user['favorite_series'] == 'Dimoo') { echo 'selected'; } ?>>Dimoo</option>
                        <option value="Hirono" <?php if ($user['favorite_series'] == 'Hirono') { echo 'selected'; } ?>>Hirono</option>
                        <option value="Molly" <?php if ($user['favorite_series'] == 'Molly') { echo 'selected'; } ?>>Molly</option>
                        <option value="Skullpanda" <?php if ($user['favorite_series'] == 'Skullpanda') { echo 'selected'; } ?>>Skullpanda</option>
                        <option value="Crybaby" <?php if ($user['favorite_series'] == 'Crybaby') { echo 'selected'; } ?>>Crybaby</option>
                        <option value="Hacipupu" <?php if ($user['favorite_series'] == 'Hacipupu') { echo 'selected'; } ?>>Hacipupu</option>
                        <option value="Twinkle Twinkle" <?php if ($user['favorite_series'] == 'Twinkle Twinkle') { echo 'selected'; } ?>>Twinkle Twinkle</option>
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
                        <option value="Collection" <?php if ($user['collecting_purpose'] == 'Collection') { echo 'selected'; } ?>>Collection</option>
                        <option value="Decoration" <?php if ($user['collecting_purpose'] == 'Decoration') { echo 'selected'; } ?>>Decoration</option>
                        <option value="Gift" <?php if ($user['collecting_purpose'] == 'Gift') { echo 'selected'; } ?>>Gift</option>
                    </select>
                </div>

                <div class="form-actions-wide">
                    <button class="btn btn-primary" type="submit">Save My Preferences</button>
                </div>
            </form>
        </section>
    </main>

    <footer>Student Assignment - Pop Mart Blind Box Collector</footer>
</body>
</html>
<?php mysqli_close($conn); ?>
