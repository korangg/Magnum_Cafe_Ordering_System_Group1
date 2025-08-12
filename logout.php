<?php
session_start();
session_unset();
session_destroy();

// Redirect to guest page or homepage
header("Location: index.html");  // or use guesthome.php if that’s your guest page
exit();
?>
