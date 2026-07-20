<?php
session_start();
session_unset();
session_destroy();

header("Location: index.php?success=You have logged out successfully.");
exit();
?>
