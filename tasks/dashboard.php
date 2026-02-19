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

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
$stmt->execute([$user_id]);
$total = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'completed'");
$stmt->execute([$user_id]);
$completed = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$pending = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'pending' AND due_date < CURDATE()");
$stmt->execute([$user_id]);
$overdue = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY FIELD(priority,'high','medium','low'), due_date ASC, created_at DESC LIMIT 6");
$stmt->execute([$user_id]);
$recent_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$percent = $total > 0 ? round(($completed / $total) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Lifesaver</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <!-- MOBILE TOP BAR -->
  <div class="mob-topbar">
    <a class="mob-logo" href="#"><span>L</span>ifesaver</a>
    <button class="hamburger" id="hamburger">
      <span></span><span></span><span></span>
    </button>
  </div>

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <a class="sidebar-logo" href="dashboard.php"><span>L</span>ifesaver</a>
    <p class="sidebar-label">Menu</p>
    <nav class="sidebar-nav">
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="create.php">New Task</a>
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

  <!-- MAIN -->
  <main class="main">

    <div class="page-header">
      <div>
        <h1>Good day, <?= htmlspecialchars($firstname) ?> 👋</h1>
        <p>Here's what's going on with your tasks today.</p>
      </div>
      <a href="create.php" class="btn-create">+ New Task</a>
    </div>

    <?php if ($overdue > 0): ?>
      <div class="overdue-banner">
        <span>⚠ You have <?= $overdue ?> overdue <?= $overdue === 1 ? 'task' : 'tasks' ?>. Don't let them pile up.</span>
        <a href="read.php?status=pending">View pending →</a>
      </div>
    <?php endif; ?>

    <!-- STAT CARDS -->
    <div class="stats-grid">
      <div class="stat-card total">
        <div class="stat-label">Total Tasks</div>
        <div class="stat-number"><?= $total ?></div>
        <div class="stat-sub">All time</div>
      </div>
      <div class="stat-card done">
        <div class="stat-label">Completed</div>
        <div class="stat-number"><?= $completed ?></div>
        <div class="stat-sub">Tasks finished</div>
      </div>
      <div class="stat-card pending-c">
        <div class="stat-label">Pending</div>
        <div class="stat-number"><?= $pending ?></div>
        <div class="stat-sub">Still to do</div>
      </div>
      <div class="stat-card overdue-c">
        <div class="stat-label">Overdue</div>
        <div class="stat-number"><?= $overdue ?></div>
        <div class="stat-sub">Past due date</div>
      </div>
    </div>

    <!-- PROGRESS -->
    <div class="progress-section">
      <div class="progress-header">
        <span class="progress-title">Overall Progress</span>
        <span class="progress-pct"><?= $percent ?>%</span>
      </div>
      <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
      </div>
    </div>

    <!-- RECENT TASKS -->
    <div class="section-header">
      <h2 class="section-title">Recent Tasks</h2>
      <a href="read.php" class="view-all">View all →</a>
    </div>

    <?php if (empty($recent_tasks)): ?>
      <div class="empty-state">
        <div class="empty-icon">📋</div>
        <h3>No tasks yet</h3>
        <p>You haven't created any tasks yet. Start now.</p>
        <a href="create.php" class="btn-empty">+ Create your first task</a>
      </div>
    <?php else: ?>
      <div class="task-list">
        <?php foreach ($recent_tasks as $task):
          $is_overdue = $task['due_date'] && $task['status'] !== 'completed' && strtotime($task['due_date']) < strtotime('today');
          $is_soon    = $task['due_date'] && $task['status'] !== 'completed' && !$is_overdue && strtotime($task['due_date']) <= strtotime('+3 days');
        ?>
          <div class="task-item priority-<?= $task['priority'] ?>">
            <div class="task-left">
              <div class="task-info">
                <div class="task-title <?= $task['status'] === 'completed' ? 'done' : '' ?>">
                  <?= htmlspecialchars($task['title']) ?>
                </div>
                <div class="task-meta-row">
                  <span class="badge badge-<?= $task['priority'] ?>"><?= $task['priority'] ?></span>
                  <?php if ($task['due_date']): ?>
                    <span class="task-due <?= $is_overdue ? 'due-overdue' : ($is_soon ? 'due-soon' : 'due-normal') ?>">
                      <?= $is_overdue ? '⚠ Overdue' : ($is_soon ? '⏰ Due soon' : 'Due') ?>
                      <?= date('M j', strtotime($task['due_date'])) ?>
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="task-right">
              <span class="badge badge-<?= $task['status'] ?>"><?= $task['status'] ?></span>
              <div class="task-actions">
                <a href="update.php?id=<?= $task['id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $task['id'] ?>" class="del" onclick="return confirm('Delete this task?')">Delete</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </main>

  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.getElementById('progressFill').style.width = '<?= $percent ?>%';
      }, 300);
    });

    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      sidebar.classList.toggle('open');
    });
  </script>

</body>
</html>