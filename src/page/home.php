<?php
session_start();

// Check if the user is logged in, if
// not then redirect them to the login page
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}
?>


<a href="../include_page/logout.php" type="submit">LOGOUT</a>
<h2>Welcome to online sale</h2>
