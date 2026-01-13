<?php
/**
 * DELETE.PHP - Remove user from database
 * 
 * FLOW:
 * 1. User clicks "Delete" on index.php
 * 2. Browser goes to delete.php?id=5
 * 3. This file receives id from URL
 * 4. Delete user from database
 * 5. Show confirmation message
 */

// Get database connection
include 'db.php';

$message = '';  // Will hold success or error message

// Check if id exists in URL
// When user clicks delete link: delete.php?id=5
// $_GET['id'] reads the id value from URL
if (isset($_GET['id'])) {
    
    // Convert to integer for security
    // If someone tries delete.php?id=hack, intval() makes it 0
    $id = intval($_GET['id']);
    
    // DELETE using prepared statement
    // Prepared statements work for DELETE just like INSERT/UPDATE
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);  // "i" = integer parameter
    
    // Execute the delete
    if ($stmt->execute()) {
        
        // Check if any row was actually deleted
        // affected_rows tells us how many rows were changed
        if ($stmt->affected_rows > 0) {
            $message = "User deleted successfully";
        } else {
            // affected_rows = 0 means no user had that id
            $message = "No user found with that ID";
        }
        
    } else {
        $message = "Error deleting user: " . $stmt->error;
    }
    
    $stmt->close();
    
} else {
    // No id in URL (user came directly to delete.php)
    $message = "No user ID specified";
}

// Close connection
$conn->close();
?>

<h1>Delete User</h1>

<!-- Display result -->
<p><?php echo $message; ?></p>

<p><a href="index.php">Back to User List</a></p>

<!--
COMPLETE FLOW SUMMARY:

1. USER VISITS index.php
   → db.php included → $conn created
   → SELECT query executed → All users fetched
   → Loop displays each user with Edit/Delete links

2. USER CLICKS "Add New User"
   → Goes to create.php
   → Shows empty form
   → User fills form and submits
   → POST processed → Validation → INSERT query → Success message

3. USER CLICKS "Edit" on a user
   → Goes to update.php?id=5
   → GET processed → SELECT query fetches user #5
   → Form shows with current data
   → User changes fields and submits
   → POST processed → Validation → UPDATE query → Success message

4. USER CLICKS "Delete" on a user
   → Goes to delete.php?id=5
   → GET processed → DELETE query removes user #5
   → Confirmation message shown

KEY CONCEPTS:

- include 'db.php' → Shares one database connection across all files
- $_GET → Reads data from URL (delete.php?id=5)
- $_POST → Reads data from form submission
- prepare() → Creates safe SQL query with placeholders
- bind_param() → Fills in placeholders safely
- execute() → Runs the query
- htmlspecialchars() → Makes output safe for HTML
- intval() → Converts to integer for security
-->