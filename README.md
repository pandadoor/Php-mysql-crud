# Pure PHP + MySQL CRUD Tutorial - Complete Setup Guide

A minimal, beginner-friendly CRUD (Create, Read, Update, Delete) application using pure PHP and MySQL. **No frameworks, no styling, just core fundamentals.**

---

## 📋 Table of Contents
1. [Prerequisites](#prerequisites)
2. [Installation Steps](#installation-steps)
3. [Database Setup](#database-setup)
4. [File Setup](#file-setup)
5. [Running the Application](#running-the-application)
6. [Testing Each Feature](#testing-each-feature)
7. [Troubleshooting](#troubleshooting)
8. [Understanding the Code](#understanding-the-code)

---

## 🔧 Prerequisites

Before starting, you need:

### 1. **XAMPP** (or similar local server)
- Download from: https://www.apachefriends.org/
- Includes: Apache (web server), MySQL (database), PHP
- **OR** use WAMP, MAMP, or LAMP depending on your OS

### 2. **Text Editor**
- VS Code, Notepad++, Sublime Text, or any code editor

### 3. **Web Browser**
- Chrome, Firefox, Edge, etc.

---

## 📥 Installation Steps

### Step 1: Install XAMPP

1. Download XAMPP from https://www.apachefriends.org/
2. Run the installer
3. Install to default location (usually `C:\xampp` on Windows)
4. Complete installation

### Step 2: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache** (web server)
3. Click **Start** next to **MySQL** (database)
4. Both should show green "Running" status

```
✅ Apache  - Running on Port 80
✅ MySQL   - Running on Port 3306
```

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
  age INT NOT NULL
);
```

4. Click **"Go"** button
5. You should see: "1 table has been created"

### Step 4: Verify Table Structure

1. Click **`simple_crud`** database in left sidebar
2. Click **`users`** table
3. Click **"Structure"** tab
4. You should see 4 columns:
   - `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
   - `name` (VARCHAR 100)
   - `email` (VARCHAR 100)
   - `age` (INT)

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

Inside the `simple_crud` folder, create these 5 files:

```
htdocs/
└── simple_crud/
    ├── db.php          ← Database connection
    ├── index.php       ← List all users
    ├── create.php      ← Add new user
    ├── update.php      ← Edit user
    └── delete.php      ← Delete user
```

### Step 4: Copy Code to Files

Copy the code from each artifact into its corresponding file:

1. **db.php** - Copy database connection code
2. **index.php** - Copy user list code
3. **create.php** - Copy add user code
4. **update.php** - Copy edit user code
5. **delete.php** - Copy delete user code

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
2. Go to: **`http://localhost/simple_crud/index.php`**
3. You should see the "User List" page

```
Expected URL: http://localhost/simple_crud/index.php

URL Breakdown:
- http://localhost  → Your local web server (Apache)
- /simple_crud      → Your project folder name
- /index.php        → Main page file
```

### Step 3: First View

You should see:
- **Heading:** "User List"
- **Link:** "Add New User"
- **Table:** Empty (showing "No users found")

---

## ✅ Testing Each Feature

### Test 1: Create a User

1. Click **"Add New User"** link
2. Fill in the form:
   ```
   Name:  John Doe
   Email: john@example.com
   Age:   25
   ```
3. Click **"Add User"** button
4. You should see: **"Success: User added successfully!"**
5. Click **"Back to User List"**

**What happened:**
```
Browser → POST to create.php → Validation → INSERT INTO users → Success message
```

### Test 2: View User List

1. On `index.php`, you should now see John Doe in the table
2. Table shows: ID, Name, Email, Age, and Actions (Edit | Delete)

**What happened:**
```
index.php → SELECT * FROM users → Loop through results → Display in table
```

### Test 3: Edit a User

1. Click **"Edit"** link next to John Doe
2. Change age from `25` to `30`
3. Click **"Update User"**
4. You should see: **"Success: User updated successfully!"**
5. Click **"Back to User List"**
6. Verify age is now `30`

**What happened:**
```
Click Edit → GET id from URL → SELECT user data → Show in form
Submit form → POST data → Validation → UPDATE users → Success message
```

### Test 4: Delete a User

1. Click **"Delete"** link next to John Doe
2. You should see: **"User deleted successfully"**
3. Click **"Back to User List"**
4. Table should be empty again (No users found)

**What happened:**
```
Click Delete → GET id from URL → DELETE FROM users WHERE id = ? → Confirmation
```

### Test 5: Validation

Try these to test validation:

**Empty Fields:**
1. Go to "Add New User"
2. Leave Name empty, fill email and age
3. Click submit
4. Should see: **"All fields are required"**

**Invalid Email:**
1. Enter: `Name: Test, Email: notanemail, Age: 20`
2. Click submit
3. Should see: **"Invalid email format"**

**Invalid Age:**
1. Enter: `Name: Test, Email: test@test.com, Age: 200`
2. Click submit
3. Should see: **"Age must be between 1 and 150"**

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
│index.php │ ←────→ │create.php│  (Links between pages)
└──────────┘        └──────────┘
    ↓                     ↓
┌──────────┐        ┌──────────┐
│update.php│ ←────→ │delete.php│
└──────────┘        └──────────┘
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

## 📖 Next Steps

Once you understand this basic CRUD:

1. **Add Password Field:**
   - Use `password_hash()` to encrypt passwords
   - Use `password_verify()` to check login

2. **Add User Login:**
   - Create login page
   - Use sessions to track logged-in users

3. **Add Search Feature:**
   - Search by name or email
   - Use LIKE in SQL queries

4. **Add Pagination:**
   - Show 10 users per page
   - Use LIMIT and OFFSET in SQL

5. **Add CSS:**
   - Make it look nice
   - Use Bootstrap or write custom CSS

6. **Learn Object-Oriented PHP:**
   - Classes and objects
   - Better code organization

7. **Learn PHP Frameworks:**
   - Laravel (most popular)
   - CodeIgniter (beginner-friendly)

---

## 📝 Summary

**What You Built:**
- Database: `simple_crud` with `users` table
- 5 PHP files: connection, list, create, edit, delete
- Full CRUD functionality with validation and security

**What You Learned:**
- Database connections with mysqli
- Prepared statements for security
- Form handling (GET and POST)
- Data validation
- Basic PHP and MySQL integration

**Key Takeaway:**
This is the **foundation** of web development. Every complex website still uses these same concepts, just with more features and better organization.

---

## 🆘 Getting Help

If you encounter issues:

1. **Check error messages** - They tell you what's wrong
2. **Read comments in code** - Each line is explained
3. **Verify XAMPP services** - Both Apache and MySQL must be running
4. **Check file locations** - Files must be in `htdocs/simple_crud/`
5. **Test database connection** - Go to phpMyAdmin to verify setup

---

**You've successfully set up a pure PHP + MySQL CRUD application! 🎉**