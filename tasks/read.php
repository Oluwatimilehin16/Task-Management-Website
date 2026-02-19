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

$filter = isset($_GET['status']) && in_array($_GET['status'], ['pending', 'completed']) ? $_GET['status'] : 'all';

if ($filter === 'all') {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY FIELD(priority,'high','medium','low'), due_date ASC, created_at DESC");
    $stmt->execute([$user_id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND status = ? ORDER BY FIELD(priority,'high','medium','low'), due_date ASC, created_at DESC");
    $stmt->execute([$user_id, $filter]);
}

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
$stmt->execute([$user_id]);
$count_all = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$count_pending = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'completed'");
$stmt->execute([$user_id]);
$count_completed = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'pending' AND due_date < CURDATE()");
$stmt->execute([$user_id]);
$count_overdue = $stmt->fetchColumn();

$deleted = isset($_GET['deleted']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Tasks — Lifesaver</title>
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
      <a href="dashboard.php"> Dashboard</a>
      <a href="create.php"> New Task</a>
      <a href="read.php" class="active">All Tasks</a>
    </nav>
    <div class="sidebar-bottom">
      <div class="sidebar-user">
        <div class="user-avatar"><?= strtoupper(substr($firstname, 0, 1)) ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($fullname) ?></div>
          <div class="user-role">Member</div>
        </div>
      </div>
      <a href="../auth/logout.php" class="logout-btn"> Log Out</a>
    </div>
  </aside>

  <main class="main">

    <div class="page-header">
      <div>
        <h1>All Tasks</h1>
        <p>Manage and track everything in one place.</p>
      </div>
      <a href="create.php" class="btn-create">+ New Task</a>
    </div>

    <?php if ($deleted): ?>
      <div class="toast" id="toast">Task deleted successfully.</div>
    <?php endif; ?>

    <?php if ($count_overdue > 0): ?>
      <div class="overdue-banner">
        <span>⚠ You have <?= $count_overdue ?> overdue <?= $count_overdue === 1 ? 'task' : 'tasks' ?>. Take care of <?= $count_overdue === 1 ? 'it' : 'them' ?> first.</span>
      </div>
    <?php endif; ?>

    <!-- FILTER TABS -->
    <div class="filter-tabs">
      <a href="read.php" class="<?= $filter === 'all' ? 'active' : '' ?>">
        All <span class="tab-count"><?= $count_all ?></span>
      </a>
      <a href="read.php?status=pending" class="<?= $filter === 'pending' ? 'active' : '' ?>">
        Pending <span class="tab-count"><?= $count_pending ?></span>
      </a>
      <a href="read.php?status=completed" class="<?= $filter === 'completed' ? 'active' : '' ?>">
        Completed <span class="tab-count"><?= $count_completed ?></span>
      </a>
    </div>

    <?php if (empty($tasks)): ?>
      <div class="empty-state">
        <div class="empty-icon">📋</div>
        <h3>No tasks here</h3>
        <p><?= $filter === 'all' ? "You haven't created any tasks yet." : "No $filter tasks found." ?></p>
        <?php if ($filter === 'all'): ?>
          <a href="create.php" class="btn-empty">+ Create your first task</a>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <table class="task-table">
        <thead>
          <tr>
            <th>Task</th>
            <th>Priority</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tasks as $task):
            $is_overdue = $task['due_date'] && $task['status'] !== 'completed' && strtotime($task['due_date']) < strtotime('today');
            $is_soon    = $task['due_date'] && $task['status'] !== 'completed' && !$is_overdue && strtotime($task['due_date']) <= strtotime('+3 days');
          ?>
            <tr class="<?= $is_overdue ? 'overdue-row' : '' ?>">
              <td>
                <div class="task-title-cell <?= $task['status'] === 'completed' ? 'done' : '' ?>">
                  <?= htmlspecialchars($task['title']) ?>
                </div>
              </td>
              <td>
                <span class="badge badge-<?= $task['priority'] ?>"><?= $task['priority'] ?></span>
              </td>
              <td class="due-cell">
                <?php if ($task['due_date']): ?>
                  <span class="<?= $is_overdue ? 'due-overdue' : ($is_soon ? 'due-soon' : 'due-normal') ?>">
                    <?= $is_overdue ? '⚠ ' : ($is_soon ? '⏰ ' : '') ?>
                    <?= date('M j, Y', strtotime($task['due_date'])) ?>
                  </span>
                <?php else: ?>
                  <span class="due-none">—</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge badge-<?= $task['status'] ?>"><?= $task['status'] ?></span>
              </td>
              <td class="date-cell">
                <?= date('M j, Y', strtotime($task['created_at'])) ?>
              </td>
              <td class="actions-cell">
                <a href="update.php?id=<?= $task['id'] ?>" class="edit">Edit</a>
                <a href="delete.php?id=<?= $task['id'] ?>" class="del" onclick="return confirm('Delete this task?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

  </main>

  <script>
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      sidebar.classList.toggle('open');
    });

    const toast = document.getElementById('toast');
    if (toast) setTimeout(() => toast.style.opacity = '0', 3000);
  </script>

</body>
</html>