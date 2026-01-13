<?php
/**
 * DATABASE CONNECTION FILE
 * 
 * This file creates ONE connection to MySQL that will be shared
 * across all other PHP files using include 'db.php';
 * 
 * FLOW: Every PHP file includes this first → Connection is established → 
 *       Other files can use $conn to talk to database
 */

// Database credentials
$host = 'localhost';      // Where MySQL server is (usually localhost)
$dbname = 'simple_crud';  // Which database to use
$username = 'root';       // MySQL username
$password = '';           // MySQL password (empty for XAMPP default)

// Create new MySQL connection object
// mysqli = MySQL Improved (the modern way to connect)
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection failed
if ($conn->connect_error) {
    // die() = stop everything and show error
    die("Connection failed: " . $conn->connect_error);
}

// If we get here, connection succeeded
// $conn is now available to execute queries
// This file does NOT close the connection - that happens in the files that include it

// Optional: Start session for login functionality
// Uncomment this line when you add login features
// session_start();
?>