<?php
if (!isset($page_title)) {
    $page_title = "Blind Box Collector";
}
if (!isset($active_page)) {
    $active_page = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | Pop Mart Collector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="dashboard.php">
            <span class="brand-box">?</span>
            <span>POP & REVEAL</span>
        </a>

        <nav class="nav-links">
            <a class="<?php echo $active_page === 'home' ? 'active' : ''; ?>" href="dashboard.php">Home</a>
            <a class="<?php echo $active_page === 'draw' ? 'active' : ''; ?>" href="open_box.php">Open Box</a>
            <a class="<?php echo $active_page === 'collection' ? 'active' : ''; ?>" href="collection.php">My Collection</a>
            <a class="<?php echo $active_page === 'profile' ? 'active' : ''; ?>" href="profile.php">My Profile</a>
            <a class="logout-link" href="logout.php">Logout</a>
        </nav>
    </header>
    <main class="page-container">
