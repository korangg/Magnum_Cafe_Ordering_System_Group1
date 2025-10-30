<?php
session_start();
session_unset();
session_destroy();

// Redirect to guest page or homepage
echo "<script>
        localStorage.removeItem('cart');
        window.location.href = 'index.php'; 
      </script>";
exit();
?>
