<?php
declare(strict_types=1);
require __DIR__ . '/../app/config/db.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM admins WHERE email=?");
  $stmt->execute([$email]);
  $admin = $stmt->fetch();

  if ($admin && password_verify($pass, $admin['password_hash'])) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    header("Location: /sdstechmed/admin/dashboard.php");
    exit;
  }
  $error = "Invalid credentials";
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Login</title></head>
<body>
  <h2>Admin Login</h2>
  <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <form method="post">
    <input name="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
</body></html>
