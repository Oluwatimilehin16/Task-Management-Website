<?php
session_start();
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if (empty($fullname) || empty($email) || empty($password) || empty($cpassword)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $cpassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = 'An account with that email already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$fullname, $email, $hashed]);
            $success = 'Account created! You can now sign in.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up — Lifesaver</title>
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
    --success: #27ae60;
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background-color: var(--warm-white);
    color: var(--black);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* NAV */
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

  /* PAGE LAYOUT */
  .page {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: calc(100vh - 64px);
  }

  /* LEFT PANEL */
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

  /* sticky decoration */
  .mini-sticky {
    position: absolute;
    bottom: 60px;
    right: 40px;
    background: var(--yellow);
    padding: 0.8rem 1rem;
    width: 140px;
    transform: rotate(3deg);
    box-shadow: 3px 4px 12px rgba(0,0,0,0.3);
    font-family: 'Caveat', cursive;
    font-size: 0.95rem;
    color: var(--black);
    line-height: 1.5;
  }

  /* RIGHT PANEL - FORM */
  .right-panel {
    background: var(--cream);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 5%;
  }

  .form-box {
    width: 100%;
    max-width: 420px;
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

  .alert {
    padding: 0.85rem 1rem;
    font-size: 0.88rem;
    margin-bottom: 1.2rem;
    border-left: 3px solid;
  }

  .alert-error { background: #fdf0ef; color: var(--error); border-color: var(--error); }
  .alert-success { background: #eafaf1; color: var(--success); border-color: var(--success); }

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

  .divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 1.5rem 0;
    color: var(--mid-gray);
    font-size: 0.82rem;
  }

  .divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--light-border);
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* RESPONSIVE */
  @media (max-width: 780px) {
    .page { grid-template-columns: 1fr; }
    .left-panel { display: none; }
    .right-panel { padding: 3rem 6%; align-items: flex-start; padding-top: 3rem; }
  }
</style>
</head>
<body>

<nav>
  <a class="nav-logo" href="../index.php"><span>L</span>ifesaver</a>
  <a class="nav-link" href="login.php">Already have an account? Sign in</a>
</nav>

<div class="page">
  <div class="left-panel">
    <p class="panel-tag">Join Lifesaver</p>
    <h2 class="panel-title">Your tasks.<br>Under control.</h2>
    <p class="panel-sub">Create a free account and start getting things done without the chaos.</p>
    <div class="panel-points">
      <div class="panel-point">Free to use, no credit card needed</div>
      <div class="panel-point">Your data is private and secure</div>
      <div class="panel-point">Access from any device, anytime</div>
    </div>
    <div class="mini-sticky">Don't forget to sign up! 📌</div>
  </div>

  <div class="right-panel">
    <div class="form-box">
      <h1 class="form-title">Create account</h1>
      <p class="form-sub">Already have one? <a href="login.php">Sign in here</a></p>

      <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" name="fullname" placeholder="John Doe" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="john@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
        </div>

        <div class="form-group">
          <label for="cpassword">Confirm Password</label>
          <input type="password" id="cpassword" name="cpassword" placeholder="Repeat your password" required>
        </div>

        <button type="submit" class="btn-submit">Create My Account</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>