<?php
// logout.php
session_start();
session_unset();
session_destroy();

// Redirect to index.php (which redirects to dashboard or login)
header("Location: index.php");
exit();
?>
