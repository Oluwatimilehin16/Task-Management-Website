# Lifesaver — Task Management Platform

A simple, full-stack task management web application built with PHP, HTML, CSS, and JavaScript. Users can register, log in, and manage their personal tasks with priorities and deadlines.

---

## Tools & Technologies

| Technology | Purpose |
|---|---|
| PHP | Backend logic, routing, session management |
| MySQL | Database (managed via XAMPP) |
| HTML | Page structure and markup |
| CSS | Styling and responsive design |
| JavaScript | UI interactions, animations, mobile menu |
| XAMPP | Local development server (Apache + MySQL) |

---

## How to Run the Project

### Prerequisites
- XAMPP installed on your machine
- A web browser

### Steps

1. **Start XAMPP**
   - Open the XAMPP Control Panel
   - Start **Apache** and **MySQL**

2. **Clone or copy the project**
   - Place the project folder inside `C:/xampp/htdocs/`
   - Example: `C:/xampp/htdocs/lifesaver/`

3. **Set up the database**
   - Open your browser and go to `http://localhost/phpmyadmin`
   - Create a new database called `lifesaver`
   - Select the database and click the **SQL** tab
   - Run the following queries:

```sql
CREATE TABLE users (
    id         INT(11) AUTO_INCREMENT PRIMARY KEY,
    fullname   VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id          INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id     INT(11) NOT NULL,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    due_date    DATE NULL,
    priority    ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status      ENUM('pending', 'completed') DEFAULT 'pending',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

4. **Configure the database connection**
   - Open `config/db.php`
   - Make sure the credentials match your XAMPP setup:

```php
$host = 'localhost';
$db   = 'task_management';
$user = 'root';
$pass = ''; // XAMPP default has no password
```

5. **Open the project in your browser**
   - Go to `http://localhost/lifesaver/`

---

## Project Structure

```
/TASK MANAGEMENT ASSESMENT
  /assets
    style.css           — Shared stylesheet for all dashboard pages
  /auth
    login.php           — Login page
    register.php        — Registration page
    logout.php          — Destroys session and redirects to login
  /config
    db.php              — PDO database connection
  /tasks
    dashboard.php       — Main dashboard shown after login
    create.php          — Create a new task
    read.php            — View and filter all tasks
    update.php          — Edit an existing task
    delete.php          — Deletes a task and redirects
  index.php             — Landing page
  README.md             — This file
```

---

## Application Flow

### 1. Landing Page
The first thing a visitor sees is `index.php` — a public-facing landing page that explains what Lifesaver does. It includes a navigation bar with Sign In and Sign Up buttons, a hero section, a features section, how it works, an about section, and an FAQ. No login is required to view this page.

### 2. Registration
A new user clicks **Sign Up** and is taken to `auth/register.php`. They fill in their full name, email, password, and confirm password. The system checks that all fields are filled, that the passwords match, that the password is at least 6 characters, and that the email is not already registered. The password is hashed using PHP's `password_hash()` with bcrypt before being saved to the database. The raw password is never stored.

### 3. Login
The user goes to `auth/login.php`, enters their email and password. The system fetches the user record by email and uses `password_verify()` to compare the input against the stored hash. If valid, a session is started and the user's ID and name are stored in `$_SESSION`. They are then redirected to the dashboard.

### 4. Session Guard
Every task page begins with:
```php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
```
This means unauthenticated users cannot access any task pages — they are always redirected to login.

### 5. Dashboard
`tasks/dashboard.php` is the home screen after login. It shows four stat cards (total tasks, completed, pending, overdue), an animated progress bar showing percentage of tasks completed, and a list of the most recent tasks ordered by priority and due date. If any tasks are past their due date, an overdue warning banner appears at the top.

### 6. Creating a Task
The user clicks **New Task** and is taken to `tasks/create.php`. They fill in a title, an optional description, an optional due date, a status (pending or completed), and a priority level (High, Medium, Low) selected via a visual card selector. On submit the data is inserted into the `tasks` table with the logged-in user's ID attached.

### 7. Viewing All Tasks
`tasks/read.php` shows all tasks in a table with filter tabs for All, Pending, and Completed. Tasks are sorted by priority (high first) then by due date. Overdue tasks are highlighted with a red left border and a warning icon. Tasks due within 3 days show an orange clock indicator.

### 8. Editing a Task
Clicking **Edit** on any task opens `tasks/update.php?id=X`. The form is pre-filled with the task's current data. The system verifies the task belongs to the logged-in user before loading or saving. The user can change any field including marking it as completed. A delete button is also available on this page.

### 9. Deleting a Task
`tasks/delete.php` handles deletion. It receives the task ID via the URL, confirms the task belongs to the logged-in user, deletes it, and redirects back to `read.php` with a success message. No UI is shown — it is a pure logic file.

### 10. Logout
Clicking **Log Out** hits `auth/logout.php` which calls `session_destroy()` and redirects the user to the login page.

---

## Security Measures

- Passwords are hashed with `password_hash()` using bcrypt — never stored in plain text
- All database queries use PDO prepared statements — no raw SQL string injection possible
- Every task query filters by the logged-in user's session ID — users cannot access or modify each other's data
- Session check at the top of every protected page — direct URL access is blocked

---

## Assumptions Made

- One user account per email address
- Tasks belong to a single user and cannot be shared
- No password reset functionality was required for this scope
- The confirm password field is used for frontend validation only — it is not stored in the database
- XAMPP default credentials are used (root user, no password) — this would be changed in a production environment
- Priority defaults to Medium if not explicitly selected

---

## AI Assistance

Part of the frontend was built with the assistance of **Claude AI** (by Anthropic). Design prompts and feature descriptions were provided, and Claude generated HTML/CSS code that was reviewed, adjusted, and integrated into the project. All backend logic, database design, and application flow were written and understood by the developer.

---

## Author

Built as a technical assessment project.