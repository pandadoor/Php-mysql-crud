<?php
/**
 * LOGOUT.PHP - Destroy user session and redirect to login
 * 
 * FLOW:
 * 1. Start session (to access session data)
 * 2. Clear all session variables
 * 3. Destroy the session
 * 4. Redirect to login page
 */

// Start session to access $_SESSION
session_start();

// Unset all session variables
// This clears everything stored in $_SESSION array
$_SESSION = array();

// Destroy the session completely
// This removes the session file from server
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();

/*
WHAT HAPPENS WHEN YOU LOGOUT:

Before logout:
$_SESSION = [
    'user_id' => 5,
    'user_name' => 'John Doe',
    'user_email' => 'john@example.com'
]

After logout:
$_SESSION = []  (empty)
Session destroyed = user is no longer logged in
*/
?>