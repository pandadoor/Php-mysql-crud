<?php
/**
 * LOGIN.PHP - User authentication page
 * 
 * FLOW:
 * 1. Show login form
 * 2. When submitted, check email and password
 * 3. Use password_verify() to check hashed password
 * 4. If correct, start session and redirect to index.php
 * 5. If wrong, show error message
 */

// Start session FIRST before any output
// Sessions allow us to track logged-in users across pages
session_start();

// Include database connection
include 'db.php';

$error = '';

// Check if user is already logged in
// If they are, redirect them to index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Basic validation
    if (empty($email) || empty($password)) {
        $error = "Email and password are required";
    }
    else {
        
        // Query to find user by email
        // We use prepared statement for security
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        // Check if user exists
        if ($result->num_rows > 0) {
            
            // Get user data as array
            $user = $result->fetch_assoc();
            
            // PASSWORD VERIFICATION
            // password_verify() checks if plain password matches hashed password
            // $password = what user typed (e.g., "mypass123")
            // $user['password'] = hashed version from database (e.g., "$2y$10$abc...")
            
            if (password_verify($password, $user['password'])) {
                
                // Password is correct!
                // Store user information in session
                // $_SESSION is a special array that persists across pages
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Redirect to main page
                header("Location: index.php");
                exit();
                
            } else {
                // Password is wrong
                $error = "Invalid email or password";
            }
            
        } else {
            // No user found with this email
            $error = "Invalid email or password";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<h1>Login</h1>

<!-- Show error message if exists -->
<?php if ($error): ?>
    <p style="color: red;"><strong>Error:</strong> <?php echo $error; ?></p>
<?php endif; ?>

<!--
LOGIN FORM
When submitted, sends POST request to same file (login.php)
-->
<form method="POST" action="login.php">
    <p>
        Email: <input type="email" name="email" required>
    </p>
    <p>
        Password: <input type="password" name="password" required>
    </p>
    <p>
        <input type="submit" value="Login">
    </p>
</form>

<p>Don't have an account? <a href="create.php">Create one here</a></p>

<!--
PASSWORD HASHING EXPLAINED:

1. When user registers (create.php):
   - User types: "mypassword123"
   - password_hash() converts to: "$2y$10$abc123xyz..." (60 characters)
   - This hash is stored in database

2. When user logs in (login.php):
   - User types: "mypassword123"
   - password_verify() checks if it matches the stored hash
   - Returns true/false

3. Why this is secure:
   - Original password is NEVER stored
   - Hash cannot be reversed to get original password
   - Each hash is unique (even same password creates different hashes)
   - Bcrypt algorithm is very slow = hard to crack

4. Example:
   password_hash("hello", PASSWORD_DEFAULT) could produce:
   "$2y$10$abcdefghijklmnopqrstuv1234567890ABCDEFGHIJKLMNOPQR"
   
   password_verify("hello", "$2y$10$abc...") returns: true
   password_verify("wrong", "$2y$10$abc...") returns: false
-->