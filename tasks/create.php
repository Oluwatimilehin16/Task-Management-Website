<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id   = $_SESSION['user_id'];
$fullname  = $_SESSION['fullname'];
$firstname = explode(' ', $fullname)[0];
$error     = '';
$success   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status      = $_POST['status'];
    $priority    = $_POST['priority'];
    $due_date    = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

    if (empty($title)) {
        $error = 'Task title is required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, status, priority, due_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $description, $status, $priority, $due_date]);
        $success = 'Task created successfully!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Task — Lifesaver</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <div class="mob-topbar">
    <a class="mob-logo" href="#"><span>L</span>ifesaver</a>
    <button class="hamburger" id="hamburger">
      <span></span><span></span><span></span>
    </button>
  </div>

  <aside class="sidebar" id="sidebar">
    <a class="sidebar-logo" href="dashboard.php"><span>L</span>ifesaver</a>
    <p class="sidebar-label">Menu</p>
    <nav class="sidebar-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="create.php" class="active">New Task</a>
      <a href="read.php">All Tasks</a>
    </nav>
    <div class="sidebar-bottom">
      <div class="sidebar-user">
        <div class="user-avatar"><?= strtoupper(substr($firstname, 0, 1)) ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($fullname) ?></div>
          <div class="user-role">Member</div>
        </div>
      </div>
      <a href="../auth/logout.php" class="logout-btn">Log Out</a>
    </div>
  </aside>

  <main class="main">

    <div class="page-header">
      <div>
        <h1>New Task</h1>
        <p>Fill in the details, set a deadline, and go.</p>
      </div>
      <a href="read.php" class="back-link">← Back to tasks</a>
    </div>

    <div class="form-card">

      <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($success) ?> —
          <a href="read.php" style="color: inherit; font-weight: 500;">View all tasks</a>
          or add another below.
        </div>
      <?php endif; ?>

      <form method="POST" action="">

        <div class="form-group">
          <label for="title">Task Title <span class="label-hint">required</span></label>
          <input type="text" id="title" name="title" placeholder="e.g. Finish project report" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label for="description">Description <span class="label-hint">optional</span></label>
          <textarea id="description" name="description" placeholder="Any notes or context about this task..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-divider"><span>Schedule & Priority</span></div>

        <div class="form-row">
          <div class="form-group">
            <label for="due_date">Due Date <span class="label-hint">optional</span></label>
            <input type="date" id="due_date" name="due_date" value="<?= htmlspecialchars($_POST['due_date'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <div class="select-wrap">
              <select id="status" name="status">
                <option value="pending"   <?= (($_POST['status'] ?? '') === 'pending')   ? 'selected' : '' ?>>Pending</option>
                <option value="completed" <?= (($_POST['status'] ?? '') === 'completed') ? 'selected' : '' ?>>Completed</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>Priority Level</label>
          <div class="priority-options">
            <div class="priority-option high">
              <input type="radio" name="priority" id="p-high" value="high" <?= (($_POST['priority'] ?? '') === 'high') ? 'checked' : '' ?>>
              <label for="p-high">
                <span class="p-icon">🔴</span>
                <span class="p-label">High</span>
                <span class="p-desc">Urgent, do first</span>
              </label>
            </div>
            <div class="priority-option medium">
              <input type="radio" name="priority" id="p-medium" value="medium" <?= (($_POST['priority'] ?? 'medium') === 'medium') ? 'checked' : '' ?>>
              <label for="p-medium">
                <span class="p-icon">🟠</span>
                <span class="p-label">Medium</span>
                <span class="p-desc">Important, not urgent</span>
              </label>
            </div>
            <div class="priority-option low">
              <input type="radio" name="priority" id="p-low" value="low" <?= (($_POST['priority'] ?? '') === 'low') ? 'checked' : '' ?>>
              <label for="p-low">
                <span class="p-icon">🟢</span>
                <span class="p-label">Low</span>
                <span class="p-desc">When time allows</span>
              </label>
            </div>
          </div>
        </div>

        <div class="form-footer">
          <button type="submit" class="btn-submit">Save Task</button>
          <a href="read.php" class="btn-cancel">Cancel</a>
        </div>

      </form>
    </div>

  </main>

  <script>
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      sidebar.classList.toggle('open');
    });
  </script>

</body>
</html>