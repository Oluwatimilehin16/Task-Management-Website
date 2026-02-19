<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lifesaver — Your Trusted Task Management Platform</title>
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
  }

  html { scroll-behavior: smooth; }

  body {
    font-family: 'DM Sans', sans-serif;
    background-color: var(--cream);
    color: var(--black);
    overflow-x: hidden;
  }

  /* ── NAV ── */
  nav {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 100;
    padding: 0 5%;
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(250, 248, 243, 0.92);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--light-border);
    transition: box-shadow 0.3s;
  }

  nav.scrolled { box-shadow: 0 2px 20px rgba(0,0,0,0.07); }

  .nav-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.35rem;
    font-weight: 900;
    color: var(--black);
    text-decoration: none;
    letter-spacing: -0.5px;
  }

  .nav-logo span { color: var(--accent); }

  .nav-links {
    display: flex;
    align-items: center;
    gap: 2.2rem;
    list-style: none;
  }

  .nav-links a {
    text-decoration: none;
    color: var(--soft-black);
    font-size: 0.9rem;
    font-weight: 500;
    letter-spacing: 0.2px;
    position: relative;
    transition: color 0.2s;
  }

  .nav-links a::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--accent);
    transition: width 0.25s;
  }

  .nav-links a:hover { color: var(--accent); }
  .nav-links a:hover::after { width: 100%; }

  .nav-actions { display: flex; gap: 0.8rem; align-items: center; }

  .btn-ghost {
    padding: 0.5rem 1.2rem;
    border: 1.5px solid var(--black);
    background: transparent;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem;
    font-weight: 500;
    cursor: pointer;
    color: var(--black);
    transition: background 0.2s, color 0.2s;
    text-decoration: none;
  }

  .btn-ghost:hover { background: var(--black); color: var(--cream); }

  .btn-fill {
    padding: 0.5rem 1.3rem;
    background: var(--black);
    color: var(--cream);
    border: 1.5px solid var(--black);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    text-decoration: none;
  }

  .btn-fill:hover { background: var(--accent); border-color: var(--accent); }

  /* ── HAMBURGER ── */
  .hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    background: none;
    border: none;
    padding: 4px;
  }

  .hamburger span {
    display: block;
    width: 24px;
    height: 2px;
    background: var(--black);
    transition: transform 0.3s, opacity 0.3s;
  }

  .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
  .hamburger.open span:nth-child(2) { opacity: 0; }
  .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

  .mobile-menu {
    display: none;
    position: fixed;
    top: 68px;
    left: 0;
    width: 100%;
    background: var(--cream);
    border-bottom: 1px solid var(--light-border);
    padding: 1.5rem 5%;
    z-index: 99;
    flex-direction: column;
    gap: 1.2rem;
    animation: slideDown 0.25s ease;
  }

  .mobile-menu.open { display: flex; }
  .mobile-menu a { text-decoration: none; color: var(--black); font-size: 1rem; font-weight: 500; }
  .mobile-menu .mob-actions { display: flex; gap: 0.8rem; margin-top: 0.5rem; }

  @keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── HERO ── */
  .hero {
    min-height: 100vh;
    padding: 120px 5% 80px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 4rem;
    position: relative;
  }

  .hero-label {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 1.2rem;
    opacity: 0;
    animation: fadeUp 0.6s 0.2s forwards;
  }

  .hero-label::before {
    content: '';
    display: inline-block;
    width: 28px;
    height: 2px;
    background: var(--accent);
  }

  .hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.8rem, 5.5vw, 5rem);
    font-weight: 900;
    line-height: 1.07;
    letter-spacing: -2px;
    color: var(--black);
    opacity: 0;
    animation: fadeUp 0.6s 0.35s forwards;
  }

  .hero h1 .underline-word {
    position: relative;
    display: inline-block;
  }

  .hero h1 .underline-word::after {
    content: '';
    position: absolute;
    bottom: 4px;
    left: 0;
    width: 100%;
    height: 6px;
    background: var(--yellow);
    z-index: -1;
    transform: scaleX(0);
    transform-origin: left;
    animation: scaleIn 0.5s 0.9s forwards;
  }

  .hero-sub {
    margin-top: 1.4rem;
    font-size: 1.05rem;
    color: var(--soft-black);
    line-height: 1.75;
    max-width: 480px;
    font-weight: 300;
    opacity: 0;
    animation: fadeUp 0.6s 0.5s forwards;
  }

  .hero-cta {
    margin-top: 2.2rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    opacity: 0;
    animation: fadeUp 0.6s 0.65s forwards;
  }

  .btn-primary {
    padding: 0.85rem 2rem;
    background: var(--black);
    color: var(--cream);
    border: 2px solid var(--black);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, color 0.2s, transform 0.2s;
    display: inline-block;
  }

  .btn-primary:hover { background: var(--accent); border-color: var(--accent); transform: translateY(-2px); }

  .btn-secondary {
    padding: 0.85rem 2rem;
    background: transparent;
    color: var(--black);
    border: 2px solid var(--black);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, color 0.2s, transform 0.2s;
    display: inline-block;
  }

  .btn-secondary:hover { background: var(--black); color: var(--cream); transform: translateY(-2px); }

  /* sticky note cluster */
  .hero-visual {
    position: relative;
    height: 480px;
    opacity: 0;
    animation: fadeIn 0.8s 0.7s forwards;
  }

  .sticky {
    position: absolute;
    padding: 1.2rem 1.4rem;
    width: 185px;
    box-shadow: 4px 6px 18px rgba(0,0,0,0.13);
    font-family: 'Caveat', cursive;
    font-size: 1.05rem;
    line-height: 1.5;
    transition: transform 0.3s;
  }

  .sticky:hover { transform: scale(1.04) rotate(0deg) !important; z-index: 10; }

  .sticky-lines {
    margin-top: 0.6rem;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .sticky-line {
    height: 1.5px;
    background: rgba(0,0,0,0.12);
    border-radius: 2px;
  }

  .sticky-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
    margin-top: 0.4rem;
    color: var(--soft-black);
  }

  .sticky-check .box {
    width: 14px;
    height: 14px;
    border: 1.5px solid currentColor;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .sticky-check .box.done::after {
    content: '✓';
    font-size: 10px;
  }

  .s1 { background: #fef08a; top: 20px;  left: 30px;  transform: rotate(-4deg); }
  .s2 { background: #fca5a5; top: 60px;  left: 200px; transform: rotate(3deg); }
  .s3 { background: #86efac; top: 200px; left: 60px;  transform: rotate(-2deg); }
  .s4 { background: #93c5fd; top: 230px; left: 240px; transform: rotate(5deg); }
  .s5 { background: #fef08a; top: 360px; left: 140px; transform: rotate(-3deg); width: 160px; }

  .stripe-bar {
    position: absolute;
    bottom: 30px;
    right: -20px;
    width: 120px;
    height: 16px;
    background: repeating-linear-gradient(
      45deg,
      var(--black) 0px,
      var(--black) 4px,
      transparent 4px,
      transparent 10px
    );
    opacity: 0.25;
  }

  .dot-grid {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 80px;
    height: 80px;
    background-image: radial-gradient(circle, rgba(0,0,0,0.2) 1px, transparent 1px);
    background-size: 10px 10px;
  }

  /* ── STATS ── */
  .stats {
    background: var(--black);
    color: var(--cream);
    padding: 3.5rem 5%;
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 2rem;
  }

  .stat-item { text-align: center; }

  .stat-number {
    font-family: 'Playfair Display', serif;
    font-size: 2.8rem;
    font-weight: 900;
    color: var(--yellow);
    line-height: 1;
  }

  .stat-label {
    margin-top: 0.4rem;
    font-size: 0.85rem;
    font-weight: 300;
    letter-spacing: 0.5px;
    color: rgba(250,248,243,0.6);
    text-transform: uppercase;
  }

  /* ── SECTION SHARED ── */
  section { padding: 90px 5%; }

  .section-tag {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 0.8rem;
  }

  .section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 3.5vw, 3rem);
    font-weight: 900;
    letter-spacing: -1px;
    line-height: 1.1;
    color: var(--black);
    max-width: 560px;
  }

  .section-sub {
    margin-top: 1rem;
    font-size: 1rem;
    color: var(--mid-gray);
    font-weight: 300;
    line-height: 1.8;
    max-width: 520px;
  }

  /* ── FEATURES ── */
  .features { background: var(--warm-white); }

  .features-header { margin-bottom: 3.5rem; }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }

  .feature-card {
    background: var(--cream);
    padding: 2rem;
    border: 1px solid var(--light-border);
    transition: transform 0.25s, box-shadow 0.25s;
    position: relative;
    overflow: hidden;
  }

  .feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 0;
    background: var(--accent);
    transition: height 0.3s;
  }

  .feature-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.09); }
  .feature-card:hover::before { height: 100%; }

  .feature-icon {
    font-size: 1.6rem;
    margin-bottom: 1rem;
  }

  .feature-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--black);
    margin-bottom: 0.7rem;
  }

  .feature-desc {
    font-size: 0.9rem;
    color: var(--mid-gray);
    line-height: 1.75;
    font-weight: 300;
  }

  /* ── HOW IT WORKS ── */
  .how { background: var(--cream); }

  .steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    margin-top: 3rem;
    position: relative;
  }

  .steps::before {
    content: '';
    position: absolute;
    top: 28px;
    left: 10%;
    width: 80%;
    height: 1px;
    background: var(--light-border);
    z-index: 0;
  }

  .step { text-align: center; position: relative; z-index: 1; }

  .step-num {
    width: 56px;
    height: 56px;
    border: 2px solid var(--black);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.2rem;
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 900;
    background: var(--cream);
    transition: background 0.25s, color 0.25s;
  }

  .step:hover .step-num { background: var(--black); color: var(--cream); }

  .step-title {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
  }

  .step-desc { font-size: 0.88rem; color: var(--mid-gray); line-height: 1.7; font-weight: 300; }

  /* ── ABOUT ── */
  .about {
    background: var(--black);
    color: var(--cream);
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5rem;
    align-items: center;
  }

  .about .section-title { color: var(--cream); }
  .about .section-sub { color: rgba(250,248,243,0.6); }

  .about-image-placeholder {
    background: #1e1e1e;
    border: 1px solid #333;
    height: 380px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    color: #555;
    letter-spacing: 1px;
    font-family: 'DM Sans', sans-serif;
  }

  .about-points { margin-top: 2rem; display: flex; flex-direction: column; gap: 1rem; }

  .about-point {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    font-size: 0.95rem;
    font-weight: 300;
    color: rgba(250,248,243,0.8);
    line-height: 1.6;
  }

  .point-dot {
    width: 8px;
    height: 8px;
    background: var(--yellow);
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 7px;
  }

  /* ── FAQ ── */
  .faq { background: var(--warm-white); }
  .faq-header { margin-bottom: 3rem; }

  .faq-list { max-width: 720px; display: flex; flex-direction: column; gap: 0; }

  .faq-item {
    border-bottom: 1px solid var(--light-border);
    overflow: hidden;
  }

  .faq-q {
    width: 100%;
    background: none;
    border: none;
    text-align: left;
    padding: 1.4rem 0;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 500;
    color: var(--black);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
  }

  .faq-q .arrow {
    width: 22px;
    height: 22px;
    border: 1.5px solid var(--black);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.8rem;
    transition: transform 0.3s, background 0.3s, color 0.3s;
  }

  .faq-item.open .faq-q .arrow { transform: rotate(45deg); background: var(--black); color: var(--cream); }

  .faq-a {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.35s ease, padding 0.35s ease;
    font-size: 0.93rem;
    color: var(--mid-gray);
    line-height: 1.8;
    font-weight: 300;
  }

  .faq-item.open .faq-a { max-height: 200px; padding-bottom: 1.2rem; }

  /* ── CTA BANNER ── */
  .cta-banner {
    background: var(--accent);
    padding: 70px 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
  }

  .cta-banner h2 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3vw, 2.6rem);
    font-weight: 900;
    color: var(--cream);
    letter-spacing: -1px;
    max-width: 500px;
  }

  .btn-white {
    padding: 0.9rem 2.2rem;
    background: var(--cream);
    color: var(--accent);
    border: 2px solid var(--cream);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.2s, color 0.2s, transform 0.2s;
    display: inline-block;
  }

  .btn-white:hover { background: var(--black); color: var(--cream); border-color: var(--black); transform: translateY(-2px); }

  /* ── FOOTER ── */
  footer {
    background: var(--black);
    color: rgba(250,248,243,0.5);
    padding: 3rem 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    border-top: 1px solid #222;
  }

  .footer-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 900;
    color: var(--cream);
    text-decoration: none;
  }

  .footer-logo span { color: var(--accent); }

  .footer-links { display: flex; gap: 1.8rem; flex-wrap: wrap; }

  .footer-links a {
    text-decoration: none;
    color: rgba(250,248,243,0.5);
    font-size: 0.85rem;
    transition: color 0.2s;
  }

  .footer-links a:hover { color: var(--cream); }

  .footer-copy { font-size: 0.8rem; }

  /* ── ANIMATIONS ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
  }

  @keyframes scaleIn {
    from { transform: scaleX(0); }
    to   { transform: scaleX(1); }
  }

  .reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }

  .reveal.visible { opacity: 1; transform: translateY(0); }

  /* ── RESPONSIVE ── */
  @media (max-width: 900px) {
    .hero { grid-template-columns: 1fr; padding-top: 100px; }
    .hero-visual { height: 320px; margin: 0 auto; }
    .features-grid { grid-template-columns: 1fr 1fr; }
    .steps { grid-template-columns: 1fr 1fr; }
    .steps::before { display: none; }
    .about { grid-template-columns: 1fr; }
    .about-image-placeholder { height: 260px; }
    .nav-links, .nav-actions { display: none; }
    .hamburger { display: flex; }
  }

  @media (max-width: 580px) {
    .features-grid { grid-template-columns: 1fr; }
    .steps { grid-template-columns: 1fr 1fr; }
    .stats { justify-content: center; }
    .cta-banner { flex-direction: column; text-align: center; }
    footer { flex-direction: column; text-align: center; }
    .hero { gap: 2.5rem; }
    .hero-visual { width: 100%; height: 300px; }
    .sticky { transform: none !important; }
  }
</style>
</head>
<body>

<!-- NAV -->
<nav id="navbar">
  <a class="nav-logo" href="#"><span>L</span>ifesaver</a>
  <ul class="nav-links">
    <li><a href="#home">Home</a></li>
    <li><a href="#features">Features</a></li>
    <li><a href="#about">About Us</a></li>
    <li><a href="#faq">FAQ</a></li>
  </ul>
  <div class="nav-actions">
    <a href="auth/login.php" class="btn-ghost">Sign In</a>
    <a href="auth/register.php" class="btn-fill">Sign Up</a>
  </div>
  <button class="hamburger" id="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu" id="mobileMenu">
  <a href="#home">Home</a>
  <a href="#features">Features</a>
  <a href="#about">About Us</a>
  <a href="#faq">FAQ</a>
  <div class="mob-actions">
    <a href="auth/login.php" class="btn-ghost">Sign In</a>
    <a href="auth/register.php" class="btn-fill">Sign Up</a>
  </div>
</div>

<!-- HERO -->
<section class="hero" id="home">
  <div class="hero-left">
    <p class="hero-label">Task Management, Reimagined</p>
    <h1>Stay on top.<br>Every <span class="underline-word">task.</span><br>Every day.</h1>
    <p class="hero-sub">Lifesaver helps you capture, organise, and complete your work without the chaos. Simple by design, powerful by nature.</p>
    <div class="hero-cta">
      <a href="auth/register.php" class="btn-primary">Get Started Free</a>
      <a href="#features" class="btn-secondary">See How It Works</a>
    </div>
  </div>

  <div class="hero-visual">
    <div class="dot-grid"></div>
    <div class="sticky s1">
      <strong>Design Review</strong>
      <div class="sticky-lines">
        <div class="sticky-line"></div>
        <div class="sticky-line"></div>
      </div>
      <div class="sticky-check"><div class="box done"></div> Wireframes</div>
      <div class="sticky-check"><div class="box"></div> Prototype</div>
    </div>
    <div class="sticky s2">
      <strong>Team Standup</strong>
      <div class="sticky-check"><div class="box done"></div> Agenda ready</div>
      <div class="sticky-check"><div class="box"></div> Notes doc</div>
    </div>
    <div class="sticky s3">
      <strong>Sprint Tasks</strong>
      <div class="sticky-lines">
        <div class="sticky-line"></div>
        <div class="sticky-line"></div>
        <div class="sticky-line"></div>
      </div>
      <div class="sticky-check"><div class="box done"></div> Auth done</div>
    </div>
    <div class="sticky s4">
      <strong>Deadline: Friday</strong>
      <div class="sticky-lines">
        <div class="sticky-line"></div>
        <div class="sticky-line"></div>
      </div>
      <div class="sticky-check"><div class="box"></div> Final report</div>
    </div>
    <div class="sticky s5">
      <strong>Quick Notes</strong>
      <div class="sticky-lines">
        <div class="sticky-line"></div>
        <div class="sticky-line"></div>
        <div class="sticky-line"></div>
      </div>
    </div>
    <div class="stripe-bar"></div>
  </div>
</section>

<!-- STATS -->
<div class="stats">
  <div class="stat-item reveal">
    <div class="stat-number">12k+</div>
    <div class="stat-label">Active Users</div>
  </div>
  <div class="stat-item reveal">
    <div class="stat-number">98%</div>
    <div class="stat-label">Uptime</div>
  </div>
  <div class="stat-item reveal">
    <div class="stat-number">340k</div>
    <div class="stat-label">Tasks Completed</div>
  </div>
  <div class="stat-item reveal">
    <div class="stat-number">4.9★</div>
    <div class="stat-label">User Rating</div>
  </div>
</div>

<!-- FEATURES -->
<section class="features" id="features">
  <div class="features-header reveal">
    <span class="section-tag">What We Offer</span>
    <h2 class="section-title">Everything you need. Nothing you don't.</h2>
    <p class="section-sub">We built Lifesaver around one goal — helping you get things done without friction.</p>
  </div>
  <div class="features-grid">
    <div class="feature-card reveal">
      <div class="feature-icon">⏱️</div>
      <div class="feature-title">Stop Wasting Time Planning</div>
      <p class="feature-desc">Most people spend more time organising their work than actually doing it. Lifesaver cuts that overhead so you're acting, not shuffling sticky notes around.</p>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">🧠</div>
      <div class="feature-title">Clear Your Head Instantly</div>
      <p class="feature-desc">Carrying tasks in your head is exhausting. Dump everything into Lifesaver in seconds and free up your mental energy for the work that actually matters.</p>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">🎯</div>
      <div class="feature-title">Always Know What's Next</div>
      <p class="feature-desc">No more staring at a screen wondering where to start. Your tasks are laid out and prioritised so you always have a clear next move the moment you log in.</p>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">🔄</div>
      <div class="feature-title">Pick Up Where You Left Off</div>
      <p class="feature-desc">Life interrupts. Lifesaver remembers. Whether you switch devices or come back after a week, your tasks are exactly where you left them — no catching up required.</p>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">✅</div>
      <div class="feature-title">Feel the Progress</div>
      <p class="feature-desc">There's something powerful about marking a task done. Lifesaver makes that feeling a constant — watch your list shrink and your momentum build through the day.</p>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">🚫</div>
      <div class="feature-title">Stop Letting Things Slip</div>
      <p class="feature-desc">Forgotten deadlines and dropped balls cost you. Lifesaver keeps everything visible so nothing gets lost in an email thread, a notebook, or the back of your mind.</p>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how" id="how">
  <div class="reveal">
    <span class="section-tag">How It Works</span>
    <h2 class="section-title">Up and running in four steps.</h2>
  </div>
  <div class="steps">
    <div class="step reveal">
      <div class="step-num">01</div>
      <div class="step-title">Create Account</div>
      <p class="step-desc">Sign up in under a minute. Just your name, email, and password.</p>
    </div>
    <div class="step reveal">
      <div class="step-num">02</div>
      <div class="step-title">Log In Securely</div>
      <p class="step-desc">Your session is protected. Every login is verified on our end.</p>
    </div>
    <div class="step reveal">
      <div class="step-num">03</div>
      <div class="step-title">Add Your Tasks</div>
      <p class="step-desc">Start adding tasks instantly. Title, description, status — keep it simple.</p>
    </div>
    <div class="step reveal">
      <div class="step-num">04</div>
      <div class="step-title">Get Things Done</div>
      <p class="step-desc">Track progress, mark complete, and clear your board one task at a time.</p>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section class="about" id="about">
  <div>
    <span class="section-tag" style="color: var(--yellow);">About Us</span>
    <h2 class="section-title reveal">Built for people who want to get things done.</h2>
    <p class="section-sub reveal">Lifesaver was born out of frustration with overcomplicated tools. We believe task management should feel natural — not like work itself.</p>
    <div class="about-points reveal">
      <div class="about-point"><div class="point-dot"></div><span>Built with simplicity as a core principle, not an afterthought.</span></div>
      <div class="about-point"><div class="point-dot"></div><span>Your data stays yours. No selling, no sharing, no nonsense.</span></div>
      <div class="about-point"><div class="point-dot"></div><span>Continuously improving based on how real people actually work.</span></div>
    </div>
  </div>
  <div class="about-image-placeholder reveal">
    about-team.jpg
  </div>
</section>

<!-- FAQ -->
<section class="faq" id="faq">
  <div class="faq-header reveal">
    <span class="section-tag">FAQ</span>
    <h2 class="section-title">Questions we get a lot.</h2>
  </div>
  <div class="faq-list">
    <div class="faq-item reveal">
      <button class="faq-q">Is Lifesaver free to use? <span class="arrow">+</span></button>
      <div class="faq-a">Yes, Lifesaver is completely free. Create an account and start managing your tasks right away with no hidden fees.</div>
    </div>
    <div class="faq-item reveal">
      <button class="faq-q">Is my data secure? <span class="arrow">+</span></button>
      <div class="faq-a">Absolutely. Your password is encrypted using bcrypt hashing and your tasks are only ever accessible through your authenticated session.</div>
    </div>
    <div class="faq-item reveal">
      <button class="faq-q">Can I access my tasks from multiple devices? <span class="arrow">+</span></button>
      <div class="faq-a">Yes. As long as you log in with your account, your tasks are available from any device with a browser.</div>
    </div>
    <div class="faq-item reveal">
      <button class="faq-q">Can I delete my account? <span class="arrow">+</span></button>
      <div class="faq-a">Yes. You can request account deletion and all your data will be permanently removed from our system.</div>
    </div>
    <div class="faq-item reveal">
      <button class="faq-q">Do I need to install anything? <span class="arrow">+</span></button>
      <div class="faq-a">Not at all. Lifesaver runs entirely in your browser. No downloads, no installs, no complications.</div>
    </div>
  </div>
</section>

<!-- CTA BANNER -->
<div class="cta-banner">
  <h2 class="reveal">Ready to stop forgetting things?</h2>
  <a href="auth/register.php" class="btn-white reveal">Start for Free</a>
</div>

<!-- FOOTER -->
<footer>
  <a href="#" class="footer-logo"><span>L</span>ifesaver</a>
  <div class="footer-links">
    <a href="#home">Home</a>
    <a href="#features">Features</a>
    <a href="#about">About</a>
    <a href="#faq">FAQ</a>
    <a href="auth/login.php">Sign In</a>
  </div>
  <p class="footer-copy">&copy; 2025 Lifesaver. All rights reserved.</p>
</footer>

<script>
  // nav scroll shadow
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  });

  // hamburger
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');
  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('open');
    mobileMenu.classList.toggle('open');
  });

  // close mobile menu on link click
  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      hamburger.classList.remove('open');
      mobileMenu.classList.remove('open');
    });
  });

  // scroll reveal
  const reveals = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => entry.target.classList.add('visible'), i * 80);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
  reveals.forEach(el => observer.observe(el));

  // FAQ accordion
  document.querySelectorAll('.faq-q').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.parentElement;
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
      if (!isOpen) item.classList.add('open');
    });
  });
</script>
</body>
</html>