<?php
if (!isset($_SESSION['admin'])) {
    print("Login Failed.");
    header("Location: index.php?msg=Login failed...");
}
?>