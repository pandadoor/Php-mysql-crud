# Pure PHP + MySQL CRUD Tutorial - Complete Setup Guide

A minimal, beginner-friendly CRUD (Create, Read, Update, Delete) application using pure PHP and MySQL. **No frameworks, no styling, just core fundamentals.**

---

## 🗄️ Database Setup

### Step 1: Access phpMyAdmin

1. Open your web browser
2. Go to: `http://localhost/phpmyadmin`
3. You should see the phpMyAdmin dashboard

### Step 2: Create Database

1. Click **"New"** in the left sidebar (or click **"Databases"** tab)
2. Enter database name: `simple_crud`
3. Choose collation: `utf8mb4_general_ci` (default is fine)
4. Click **"Create"**

```sql
-- What happens behind the scenes:
CREATE DATABASE simple_crud;
```

### Step 3: Create Users Table

1. Click on **`simple_crud`** database in the left sidebar
2. Click the **"SQL"** tab at the top
3. Copy and paste this SQL code:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  age INT NOT NULL,
  password VARCHAR(255) NOT NULL
);
```

4. Click **"Go"** button
5. You should see: "1 table has been created"

**Important:** The `password` field is VARCHAR(255) because hashed passwords are 60 characters, but we use 255 to be safe for future password algorithms.

### Step 4: Verify Table Structure

1. Click **`simple_crud`** database in left sidebar
2. Click **`users`** table
3. Click **"Structure"** tab
4. You should see 5 columns:
   - `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
   - `name` (VARCHAR 100)
   - `email` (VARCHAR 100)
   - `age` (INT)
   - `password` (VARCHAR 255) ← **For storing hashed passwords**

---

## 📁 File Setup

### Step 1: Locate htdocs Folder

The `htdocs` folder is where your PHP files must be placed:

- **Windows XAMPP:** `C:\xampp\htdocs\`
- **Mac XAMPP:** `/Applications/XAMPP/htdocs/`
- **Linux XAMPP:** `/opt/lampp/htdocs/`

### Step 2: Create Project Folder

1. Open the `htdocs` folder
2. Create a new folder named: `simple_crud`
3. Your path should be: `C:\xampp\htdocs\simple_crud\` (Windows example)

### Step 3: Create PHP Files

Inside the `simple_crud` folder, create these 7 files:

```
htdocs/
└── simple_crud/
    ├── db.php          ← Database connection
    ├── login.php       ← User login (authentication)
    ├── logout.php      ← End user session
    ├── index.php       ← List all users (protected)
    ├── create.php      ← Add new user (register)
    ├── update.php      ← Edit user (protected)
    └── delete.php      ← Delete user (protected)
```
### Step 5: Verify Database Credentials

Open `db.php` and verify these settings match your setup:

```php
$host = 'localhost';      // Usually 'localhost'
$dbname = 'simple_crud';  // Database name we created
$username = 'root';       // Default XAMPP username
$password = '';           // Default XAMPP password (empty)
```

**Note:** Default XAMPP has no password. If you changed it, update here.

---

## 🚀 Running the Application

### Step 1: Ensure Services Are Running

1. Open **XAMPP Control Panel**
2. Verify both **Apache** and **MySQL** show green "Running" status
3. If not, click **Start** for each

### Step 2: Access the Application

1. Open your web browser
2. Go to: **`http://localhost/simple_crud/login.php`**
3. You should see the "Login" page

```
Expected URL: http://localhost/simple_crud/login.php

URL Breakdown:
- http://localhost  → Your local web server (Apache)
- /simple_crud      → Your project folder name
- /login.php        → Login page (entry point)
```

---

## 🐛 Troubleshooting

### Problem 1: "Connection failed" Error

**Error Message:**
```
Connection failed: Access denied for user 'root'@'localhost'
```

**Solution:**
1. Open `db.php`
2. Check credentials match your MySQL setup:
   ```php
   $username = 'root';     // Check username
   $password = '';         // Check password (usually empty for XAMPP)
   ```
3. Verify MySQL is running in XAMPP Control Panel

---

### Problem 2: Blank White Page

**Possible Causes:**
- PHP error that's hidden
- Apache not running

**Solution:**
1. Check XAMPP Control Panel - Apache must be green "Running"
2. Enable error display by adding to top of `db.php`:
   ```php
   <?php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```
3. Refresh browser to see actual error

---

### Problem 3: "Unknown database 'simple_crud'"

**Error Message:**
```
Connection failed: Unknown database 'simple_crud'
```

**Solution:**
1. Go to: `http://localhost/phpmyadmin`
2. Check if `simple_crud` database exists in left sidebar
3. If not, create it again (see Database Setup section)
4. Verify database name in `db.php` matches exactly

---

### Problem 4: "Table 'simple_crud.users' doesn't exist"

**Solution:**
1. Go to phpMyAdmin
2. Click `simple_crud` database
3. Check if `users` table exists
4. If not, run the CREATE TABLE SQL again (see Database Setup)

---

### Problem 5: Page Not Found (404 Error)

**Error Message:**
```
Not Found
The requested URL was not found on this server.
```

**Solution:**
1. Check file location: Must be in `htdocs/simple_crud/`
2. Check file name: Must be `index.php` (not `Index.php` or `index.PHP`)
3. Check URL: Must be `http://localhost/simple_crud/index.php`
4. Try: `http://localhost/` to verify Apache is working

---

### Problem 6: Changes Not Showing

**Solution:**
1. **Hard refresh** browser: `Ctrl + F5` (Windows) or `Cmd + Shift + R` (Mac)
2. Clear browser cache
3. Check if you saved the file after editing

---

## 📚 Understanding the Code

### File Relationships

```
        ┌─────────────┐
        │   db.php    │  ← Database connection (included by all)
        └─────────────┘
               ↓
    ┌──────────┴──────────┐
    ↓                     ↓
┌──────────┐        ┌──────────┐
│login.php │ ──────→│index.php │  (Login required to access)
└──────────┘        └──────────┘
    ↑                     ↓
    │              ┌──────┴──────┐
┌──────────┐       ↓             ↓
│logout.php│   ┌──────────┐ ┌──────────┐
└──────────┘   │create.php│ │update.php│
               └──────────┘ └──────────┘
                     ↓             ↓
               ┌──────────┐ ┌──────────┐
               │Password  │ │delete.php│
               │ Hashing  │ └──────────┘
               └──────────┘
```

### Data Flow

**1. Connection Flow:**
```
Any PHP file
    ↓
include 'db.php'
    ↓
$conn object created
    ↓
Ready to execute queries
```

**2. Query Flow:**
```
Prepare SQL with placeholders (?)
    ↓
Bind actual values to placeholders
    ↓
Execute query
    ↓
Get results / Check success
    ↓
Close statement
```

**3. Page Navigation Flow:**
```
index.php (list)
    ↓ Click "Add New User"
create.php (form) → Submit → Insert to DB → Back to index.php
    
index.php (list)
    ↓ Click "Edit"
update.php?id=5 (form) → Submit → Update DB → Back to index.php

index.php (list)
    ↓ Click "Delete"
delete.php?id=5 → Delete from DB → Show message
```

---

## 🔐 Security Features Explained

### 1. Prepared Statements (SQL Injection Prevention)

**Bad (Vulnerable):**
```php
$sql = "SELECT * FROM users WHERE id = " . $_GET['id'];
// Hacker can inject: ?id=1 OR 1=1
```

**Good (Protected):**
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
// MySQL treats ? as a value, not SQL code
```

### 2. htmlspecialchars() (XSS Prevention)

**Bad (Vulnerable):**
```php
echo $row['name'];  
// If name = "<script>alert('hack')</script>", it executes
```

**Good (Protected):**
```php
echo htmlspecialchars($row['name']);
// Converts < to &lt; so it displays as text, not code
```

### 3. Input Validation

**Checks before saving:**
```php
// Check if empty
if (empty($name)) { ... }

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { ... }

// Validate age range
if (!is_numeric($age) || $age < 1 || $age > 150) { ... }
```

### 4. Type Casting

**Ensures correct data types:**
```php
$id = intval($_GET['id']);  // Forces integer, prevents string injection
```
---
