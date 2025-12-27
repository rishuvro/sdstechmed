<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// If already logged in, go dashboard
if (!empty($_SESSION['admin_logged_in']) || !empty($_SESSION['admin_id'])) {
  header("Location: /sdstechmed/admin/dashboard.php");
  exit;
}

$error = '';
$emailValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = (string)($_POST['password'] ?? '');
  $emailValue = $email;

  $stmt = $pdo->prepare("SELECT * FROM admins WHERE email=? LIMIT 1");
  $stmt->execute([$email]);
  $admin = $stmt->fetch();

  if ($admin && password_verify($pass, $admin['password_hash'])) {
    // Session
    $_SESSION['admin_logged_in'] = 1;               // for fallback index.php
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_name'] = (string)$admin['name'];

    // Optional: session regeneration for security
    session_regenerate_id(true);

    header("Location: /sdstechmed/admin/dashboard.php");
    exit;
  }

  $error = "Invalid email or password";
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | SDS Techmed</title>
  <style>
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      background:#0b1220;
      color:#fff;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:18px;
    }
    .shell{
      width:min(980px, 100%);
      display:grid;
      grid-template-columns:1.2fr .8fr;
      gap:16px;
    }
    .left{
      border-radius:22px;
      padding:22px;
      border:1px solid rgba(255,255,255,.1);
      background:linear-gradient(135deg,
        rgba(59,130,246,.28),
        rgba(20,184,166,.18),
        rgba(255,122,24,.12)
      );
    }
    .brand{
      font-weight:900;
      letter-spacing:.2px;
      font-size:20px;
      margin-bottom:8px;
    }
    .tag{
      color:rgba(255,255,255,.78);
      margin:0 0 14px;
      max-width:46ch;
    }
    .points{
      display:grid;
      gap:10px;
      margin-top:14px;
    }
    .point{
      border:1px solid rgba(255,255,255,.12);
      background:rgba(255,255,255,.06);
      border-radius:16px;
      padding:12px;
      color:rgba(255,255,255,.8);
      font-size:14px;
    }

    .card{
      border-radius:22px;
      border:1px solid rgba(255,255,255,.1);
      background:rgba(255,255,255,.03);
      padding:20px;
      backdrop-filter: blur(10px);
    }
    h2{margin:0 0 10px;font-size:22px}
    .muted{color:rgba(255,255,255,.7);font-size:13px;margin-bottom:14px}
    label{display:block;margin:10px 0 6px;color:rgba(255,255,255,.78);font-weight:700}
    input{
      width:100%;
      padding:11px 12px;
      border-radius:12px;
      border:1px solid rgba(255,255,255,.14);
      background:rgba(255,255,255,.04);
      color:#fff;
      outline:none;
    }
    input:focus{border-color:rgba(255,255,255,.28)}
    input::placeholder{color:rgba(255,255,255,.45)}
    .btn{
      width:100%;
      margin-top:14px;
      padding:11px 12px;
      border-radius:12px;
      border:1px solid rgba(255,255,255,.15);
      background:rgba(255,255,255,.92);
      color:#0b1220;
      font-weight:900;
      cursor:pointer;
    }
    .btn:hover{transform:translateY(-1px)}
    .alert{
      border:1px solid rgba(239,68,68,.35);
      background:rgba(239,68,68,.08);
      color:#ffd7d7;
      border-radius:14px;
      padding:10px 12px;
      margin:10px 0 0;
      font-size:14px;
    }
    .foot{
      margin-top:12px;
      color:rgba(255,255,255,.55);
      font-size:12px;
      text-align:center;
    }
    @media (max-width:900px){
      .shell{grid-template-columns:1fr}
    }
  </style>
</head>
<body>
  <div class="shell">
    <div class="left">
      <div class="brand">SDS Techmed Admin</div>
      <p class="tag">Sign in to manage categories, products, news, pages, and website settings.</p>

      <div class="points">
        <div class="point"><strong>Products</strong><br>Upload devices, specs, images, featured items.</div>
        <div class="point"><strong>Categories</strong><br>Homepage tiles + SEO H1.</div>
        <div class="point"><strong>Inquiries</strong><br>View contact messages from customers instantly.</div>
      </div>
    </div>

    <div class="card">
      <h2>Admin Login</h2>
      <div class="muted">Enter your admin credentials to continue.</div>

      <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="off">
        <label>Email</label>
        <input name="email" type="email" required placeholder="admin@sdstechmed.com"
               value="<?= htmlspecialchars($emailValue) ?>">

        <label>Password</label>
        <input name="password" type="password" required placeholder="••••••••">

        <button class="btn" type="submit">Sign In</button>
      </form>

      <div class="foot">© <?= date('Y') ?> SDS Techmed</div>
    </div>
  </div>
</body>
</html>
