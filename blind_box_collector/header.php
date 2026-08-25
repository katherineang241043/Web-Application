<?php

if (!isset($page_title)) {
    $page_title = "Blind Box Collector";
}
if (!isset($active_page)) {
    $active_page = "";
}


$home_active = "";
$draw_active = "";
$collection_active = "";
$profile_active = "";


if ($active_page == 'home') {
    $home_active = "active";
} else if ($active_page == 'draw') {
    $draw_active = "active";
} else if ($active_page == 'collection') {
    $collection_active = "active";
} else if ($active_page == 'profile') {
    $profile_active = "active";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | Pop Mart Collector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="topbar">
        <a class="brand" href="dashboard.php">
            <span class="brand-box">?</span>
            <span>POP &amp; REVEAL</span>
        </a>

        <nav class="nav-links">
            <a class="<?php echo $home_active; ?>" href="dashboard.php">
                <span class="nav-icon">&#8962;</span>
                <span class="nav-label">Home</span>
            </a>
            
            <a class="<?php echo $draw_active; ?>" href="open_box.php">
                <span class="nav-icon">?</span>
                <span class="nav-label">Open</span>
            </a>
            
            <a class="<?php echo $collection_active; ?>" href="collection.php">
                <span class="nav-icon">&#9638;</span>
                <span class="nav-label">Collection</span>
            </a>
            
            <a class="<?php echo $profile_active; ?>" href="profile.php">
                <span class="nav-icon">&#9786;</span>
                <span class="nav-label">Profile</span>
            </a>
            
            <a class="logout-link" href="logout.php">
                <span class="nav-icon">&#8594;</span>
                <span class="nav-label">Logout</span>
            </a>
        </nav>
    </header>

    <main class="page-container">