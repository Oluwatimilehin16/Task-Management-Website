<?php
session_start();
require_once '../config/db.php';

// if already logged in, go to tasks
if (isset($_SESSION['user_id'])) {
    header('Location: ../tasks/read.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            header('Location: ../tasks/read.php');
            exit;
        } else {
            $error = 'Incorrect email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — Lifesaver</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&family=Caveat:wght@600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --cream: #faf8f3;
    --warm-white: #f5f0e8;
    --black: #111111;
    --soft-black: #2a2a2a;
    --accent: #d4502a;
    --yellow: #f5d842;
    --mid-gray: #9a9a9a;
    --light-border: #e2ddd5;
    --error: #c0392b;
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background-color: var(--warm-white);
    color: var(--black);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  nav {
    padding: 0 5%;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--cream);
    border-bottom: 1px solid var(--light-border);
  }

  .nav-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 900;
    color: var(--black);
    text-decoration: none;
  }

  .nav-logo span { color: var(--accent); }

  .nav-link {
    font-size: 0.88rem;
    color: var(--soft-black);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
  }

  .nav-link:hover { color: var(--accent); }

  .page {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: calc(100vh - 64px);
  }

  .left-panel {
    background: var(--black);
    color: var(--cream);
    padding: 4rem 5%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }

  .left-panel::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 220px;
    height: 220px;
    border: 40px solid rgba(245, 216, 66, 0.08);
    border-radius: 50%;
  }

  .left-panel::after {
    content: '';
    position: absolute;
    bottom: -40px;
    left: -40px;
    width: 160px;
    height: 160px;
    border: 30px solid rgba(212, 80, 42, 0.1);
    border-radius: 50%;
  }

  .panel-tag {
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--yellow);
    margin-bottom: 1rem;
  }

  .panel-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 3vw, 2.8rem);
    font-weight: 900;
    line-height: 1.1;
    letter-spacing: -1px;
    margin-bottom: 1.2rem;
  }

  .panel-sub {
    font-size: 0.95rem;
    font-weight: 300;
    line-height: 1.8;
    color: rgba(250,248,243,0.6);
    margin-bottom: 2.5rem;
  }

  .panel-points { display: flex; flex-direction: column; gap: 1rem; }

  .panel-point {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 0.9rem;
    font-weight: 300;
    color: rgba(250,248,243,0.8);
  }

  .panel-point::before {
    content: '';
    width: 8px;
    height: 8px;
    background: var(--yellow);
    border-radius: 50%;
    flex-shrink: 0;
  }

  .mini-sticky {
    position: absolute;
    bottom: 60px;
    right: 40px;
    background: #fca5a5;
    padding: 0.8rem 1rem;
    width: 150px;
    transform: rotate(-3deg);
    box-shadow: 3px 4px 12px rgba(0,0,0,0.3);
    font-family: 'Caveat', cursive;
    font-size: 0.95rem;
    color: var(--black);
    line-height: 1.5;
  }

  .right-panel {
    background: var(--cream);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 5%;
  }

  .form-box {
    width: 100%;
    max-width: 400px;
    animation: fadeUp 0.5s ease forwards;
  }

  .form-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 900;
    letter-spacing: -0.5px;
    margin-bottom: 0.4rem;
  }

  .form-sub {
    font-size: 0.9rem;
    color: var(--mid-gray);
    font-weight: 300;
    margin-bottom: 2rem;
  }

  .form-sub a { color: var(--accent); text-decoration: none; font-weight: 500; }
  .form-sub a:hover { text-decoration: underline; }

  .alert-error {
    padding: 0.85rem 1rem;
    font-size: 0.88rem;
    margin-bottom: 1.2rem;
    border-left: 3px solid var(--error);
    background: #fdf0ef;
    color: var(--error);
  }

  .form-group { margin-bottom: 1.2rem; }

  label {
    display: block;
    font-size: 0.82rem;
    font-weight: 500;
    letter-spacing: 0.3px;
    margin-bottom: 0.45rem;
    color: var(--soft-black);
  }

  input {
    width: 100%;
    padding: 0.78rem 1rem;
    border: 1.5px solid var(--light-border);
    background: var(--warm-white);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    color: var(--black);
    outline: none;
    transition: border-color 0.2s;
  }

  input:focus { border-color: var(--black); }
  input::placeholder { color: var(--mid-gray); }

  .btn-submit {
    width: 100%;
    padding: 0.9rem;
    background: var(--black);
    color: var(--cream);
    border: 2px solid var(--black);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    margin-top: 0.5rem;
    transition: background 0.2s, color 0.2s;
  }

  .btn-submit:hover { background: var(--accent); border-color: var(--accent); }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 780px) {
    .page { grid-template-columns: 1fr; }
    .left-panel { display: none; }
    .right-panel { padding: 3rem 6%; align-items: flex-start; }
  }
</style>
</head>
<body>

<nav>
  <a class="nav-logo" href="../index.php"><span>L</span>ifesaver</a>
  <a class="nav-link" href="register.php">No account? Sign up free</a>
</nav>

<div class="page">
  <div class="left-panel">
    <p class="panel-tag">Welcome Back</p>
    <h2 class="panel-title">Good to have<br>you back.</h2>
    <p class="panel-sub">Your tasks are right where you left them. Sign in and keep the momentum going.</p>
    <div class="panel-points">
      <div class="panel-point">All your tasks still saved</div>
      <div class="panel-point">Pick up exactly where you stopped</div>
      <div class="panel-point">Secure login every time</div>
    </div>
    <div class="mini-sticky">Welcome back! 👋 Tasks await.</div>
  </div>

  <div class="right-panel">
    <div class="form-box">
      <h1 class="form-title">Sign in</h1>
      <p class="form-sub">Don't have an account? <a href="register.php">Sign up free</a></p>

      <?php if ($error): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="john@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Your password" required>
        </div>

        <button type="submit" class="btn-submit">Sign In</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>