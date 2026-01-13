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
    $name = trim($_POST['name']);       // trim() removes extra spaces
    $email = trim($_POST['email']);
    $age = trim($_POST['age']);
    $password = trim($_POST['password']);  // Get password from form
    
    // VALIDATION: Check if data is acceptable
    
    // Check if any field is empty
    if (empty($name) || empty($email) || empty($age) || empty($password)) {
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
    // Validate password length (minimum 6 characters)
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    }
    // If all validation passes
    else {
        
        // PASSWORD HASHING: Secure way to store passwords
        // NEVER store plain text passwords in database
        // password_hash() uses bcrypt algorithm (very secure)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Example: "mypass123" becomes "$2y$10$abcd1234..." (60 characters)
        
        // PREPARED STATEMENT: Safe way to insert user data
        // Why? Prevents SQL injection attacks
        
        // Step 2a: Prepare SQL with placeholders (?)
        // Added password field to INSERT query
        $stmt = $conn->prepare("INSERT INTO users (name, email, age, password) VALUES (?, ?, ?, ?)");
        
        // Step 2b: Bind actual values to placeholders
        // "ssis" = string, string, integer, string (data types of our 4 values)
        // This tells MySQL what type each value is
        $stmt->bind_param("ssis", $name, $email, $age, $hashed_password);
        
        // Step 2c: Execute the prepared statement
        // MySQL will safely insert the data with hashed password
        if ($stmt->execute()) {
            $success = "User added successfully!";
            // Clear form by resetting variables
            $name = $email = $age = $password = '';
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
        Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>" required>
        <br><small>(Minimum 6 characters - will be encrypted)</small>
    </p>
    <p>
        <input type="submit" value="Add User">
    </p>
</form>

<?php
// Close database connection
$conn->close();
?>