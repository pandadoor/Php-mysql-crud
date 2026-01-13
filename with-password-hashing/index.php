<?php
/**
 * INDEX.PHP - Main page that lists all users
 * 
 * FLOW:
 * 1. Check if user is logged in (session exists)
 * 2. Include db.php → Get $conn (database connection)
 * 3. Query database → Get all users
 * 4. Loop through results → Display each user
 * 5. Close connection
 */

// Start session to check if user is logged in
session_start();

// Check if user is logged in
// If not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// STEP 1: Get database connection
include 'db.php';  // This brings in $conn from db.php

// STEP 2: Query to get all users from the users table
$sql = "SELECT * FROM users";  // * means "get all columns"

// Execute query and store result set
// $conn->query() sends SQL to database and returns results
$result = $conn->query($sql);
?>

<!-- Minimal HTML - No styling -->
<h1>User List</h1>

<p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! | <a href="logout.php">Logout</a></p>

<p><a href="create.php">Add New User</a></p>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Age</th>
        <th>Actions</th>
    </tr>
    
    <?php
    // STEP 3: Check if we got any results
    // num_rows = number of rows returned by query
    if ($result->num_rows > 0) {
        
        // Loop through each row (user) in the result
        // fetch_assoc() gets next row as associative array (key => value)
        while ($row = $result->fetch_assoc()) {
            
            // $row is now an array: ['id' => 1, 'name' => 'John', 'email' => 'john@email.com', 'age' => 25]
            
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";  // Access array by column name
            
            // htmlspecialchars() converts special characters to safe HTML entities
            // Prevents XSS: if name contains <script>, it becomes &lt;script&gt;
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['age']) . "</td>";
            
            // Action links with ID passed in URL
            // update.php?id=5 → update.php can read $_GET['id'] to get 5
            echo "<td>";
            echo "<a href='update.php?id=" . $row['id'] . "'>Edit</a> | ";
            echo "<a href='delete.php?id=" . $row['id'] . "'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        
    } else {
        // No users in database
        echo "<tr><td colspan='5'>No users found</td></tr>";
    }
    ?>
</table>

<?php
// STEP 4: Close database connection
// Always close when done to free up resources
$conn->close();
?>