<?php
/**
 * CREATE.PHP - Add new user to database
 * 
 * FLOW:
 * 1. Include database connection
 * 2. If form submitted (POST request):
 *    a. Get form data
 *    b. Validate data
 *    c. Insert into database using prepared statement
 * 3. Show form (with any error/success messages)
 */

// STEP 1: Get database connection
include 'db.php';

// Initialize message variables
$error = '';
$success = '';

// STEP 2: Check if form was submitted
// $_SERVER['REQUEST_METHOD'] tells us if this is GET or POST
// GET = user just opened the page
// POST = user submitted the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data from $_POST superglobal
    // $_POST is an array containing all form field values
    // $_POST['name'] → value from <input name="name">
    $name = trim($_POST['name']);    // trim() removes extra spaces
    $email = trim($_POST['email']);
    $age = trim($_POST['age']);
    
    // VALIDATION: Check if data is acceptable
    
    // Check if any field is empty
    if (empty($name) || empty($email) || empty($age)) {
        $error = "All fields are required";
    }
    // Validate email format using built-in PHP filter
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    }
    // Validate age is numeric and in reasonable range
    elseif (!is_numeric($age) || $age < 1 || $age > 150) {
        $error = "Age must be between 1 and 150";
    }
    // If all validation passes
    else {
        
        // PREPARED STATEMENT: Safe way to insert user data
        // Why? Prevents SQL injection attacks
        
        // Step 2a: Prepare SQL with placeholders (?)
        $stmt = $conn->prepare("INSERT INTO users (name, email, age) VALUES (?, ?, ?)");
        
        // Step 2b: Bind actual values to placeholders
        // "ssi" = string, string, integer (data types of our 3 values)
        // This tells MySQL what type each value is
        $stmt->bind_param("ssi", $name, $email, $age);
        
        // Step 2c: Execute the prepared statement
        // MySQL will safely insert the data
        if ($stmt->execute()) {
            $success = "User added successfully!";
            // Clear form by resetting variables
            $name = $email = $age = '';
        } else {
            $error = "Database error: " . $stmt->error;
        }
        
        // Close the prepared statement
        $stmt->close();
    }
}
?>

<!-- Minimal HTML form -->
<h1>Add New User</h1>

<p><a href="index.php">Back to User List</a></p>

<!-- Show error message if exists -->
<?php if ($error): ?>
    <p><strong>Error:</strong> <?php echo $error; ?></p>
<?php endif; ?>

<!-- Show success message if exists -->
<?php if ($success): ?>
    <p><strong>Success:</strong> <?php echo $success; ?></p>
<?php endif; ?>

<!-- 
FORM FLOW:
1. User fills in fields
2. Clicks submit button
3. Browser sends POST request to create.php
4. PHP code above runs (checks REQUEST_METHOD)
5. Data is validated and inserted
6. Page reloads showing success/error message
-->
<form method="POST" action="create.php">
    <p>
        Name: <input type="text" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
    </p>
    <p>
        Email: <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
    </p>
    <p>
        Age: <input type="number" name="age" value="<?php echo isset($age) ? htmlspecialchars($age) : ''; ?>" required>
    </p>
    <p>
        <input type="submit" value="Add User">
    </p>
</form>

<?php
// Close database connection
$conn->close();
?>