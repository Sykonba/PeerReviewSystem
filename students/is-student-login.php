<?php
if (!isset($_SESSION['student'])) {
    header("Location: index.php?msg=Login failed...");
    print("Login Failed.");
    
}
?>