<?php
/**
 * UPDATE.PHP - Edit existing user
 * 
 * FLOW:
 * 1. User clicks "Edit" on index.php → Comes here with ?id=5 in URL
 * 2. GET request: Fetch user data from database → Show in form
 * 3. POST request: User submits changes → Update database
 */

// Get database connection
include 'db.php';

$error = '';
$success = '';
$user = null;  // Will hold user data

// STEP 1: Check if ID was passed in URL (GET request)
// When user clicks "Edit" on index.php, URL is update.php?id=5
// $_GET['id'] retrieves the id from URL
if (isset($_GET['id'])) {
    
    // Convert to integer for security
    // intval() ensures we get a number, prevents SQL injection
    $id = intval($_GET['id']);
    
    // Fetch user data using prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);  // "i" = integer
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows > 0) {
        // fetch_assoc() converts row to array
        $user = $result->fetch_assoc();
        // Now $user = ['id' => 5, 'name' => 'John', 'email' => 'john@email.com', 'age' => 25]
    } else {
        $error = "User not found";
    }
    
    $stmt->close();
}

// STEP 2: Check if form was submitted (POST request)
// This happens when user clicks "Update User" button
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get data from form
    // Unlike GET (visible in URL), POST data comes from form submission
    $id = intval($_POST['id']);      // Hidden field in form
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $age = trim($_POST['age']);
    
    // VALIDATION
    if (empty($name) || empty($email) || empty($age)) {
        $error = "All fields are required";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    }
    elseif (!is_numeric($age) || $age < 1 || $age > 150) {
        $error = "Age must be between 1 and 150";
    }
    else {
        
        // UPDATE query using prepared statement
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, age = ? WHERE id = ?");
        
        // Bind 4 parameters: 3 strings/integers for SET, 1 integer for WHERE
        $stmt->bind_param("ssii", $name, $email, $age, $id);
        
        if ($stmt->execute()) {
            $success = "User updated successfully!";
            
            // Update $user array to show new values in form
            $user['name'] = $name;
            $user['email'] = $email;
            $user['age'] = $age;
        } else {
            $error = "Database error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}
?>

<h1>Edit User</h1>

<p><a href="index.php">Back to User List</a></p>

<!-- Show messages -->
<?php if ($error): ?>
    <p><strong>Error:</strong> <?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p><strong>Success:</strong> <?php echo $success; ?></p>
<?php endif; ?>

<!-- Only show form if user was found -->
<?php if ($user): ?>
    <!--
    FORM FLOW:
    1. Form is pre-filled with current user data from $user array
    2. User changes values
    3. Form submits to same file (update.php) via POST
    4. PHP code above processes POST, updates database
    5. Page reloads with success message
    -->
    <form method="POST" action="update.php">
        
        <!-- Hidden field: user can't see it, but it's sent with form -->
        <!-- This tells us WHICH user to update -->
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        
        <p>
            Name: <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </p>
        <p>
            Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </p>
        <p>
            Age: <input type="number" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" required>
        </p>
        <p>
            <input type="submit" value="Update User">
        </p>
    </form>
<?php endif; ?>

<?php
$conn->close();
?>