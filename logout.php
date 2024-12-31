<?php
// Include the configuration file
include('config.php');

// Start the session
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.html");
exit();
?>
