<?php
require __DIR__ . '/../app/helpers/auth.php';
adminRequireLogin();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Dashboard</title></head>
<body>
  <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></h1>
  <ul>
    <li><a href="/sdstechmed/admin/categories.php">Manage Categories</a></li>
    <li><a href="/sdstechmed/admin/products.php">Manage Products</a></li>
    <li><a href="/sdstechmed/admin/news.php">Manage News</a></li>
    <li><a href="/sdstechmed/admin/pages.php">Manage Pages</a></li>
    <li><a href="/sdstechmed/admin/settings.php">Site Settings</a></li>
    <li><a href="/sdstechmed/admin/logout.php">Logout</a></li>
  </ul>
</body></html>
