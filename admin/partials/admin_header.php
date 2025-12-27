<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'Admin') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Arial;margin:0;background:#0b1220;color:#fff}
    a{color:inherit;text-decoration:none}
    .wrap{max-width:1100px;margin:0 auto;padding:18px}
    .top{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.1);padding:14px 18px;background:rgba(255,255,255,.03)}
    .nav a{margin-right:10px;padding:8px 10px;border-radius:10px;background:rgba(255,255,255,.06)}
    .nav a:hover{background:rgba(255,255,255,.1)}
    .card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:14px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid rgba(255,255,255,.08);text-align:left;vertical-align:top}
    input,textarea,select{width:100%;padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.04);color:#fff}
    label{display:block;margin:10px 0 6px;color:rgba(255,255,255,.75)}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .btn{display:inline-block;padding:10px 12px;border-radius:10px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.06);color:#fff;font-weight:700;cursor:pointer}
    .btn:hover{background:rgba(255,255,255,.1)}
    .btn-primary{background:rgba(255,255,255,.92);color:#0b1220}
    .muted{color:rgba(255,255,255,.65)}
    .thumb{width:90px;height:60px;object-fit:cover;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06)}
    .actions{display:flex;gap:8px;flex-wrap:wrap}
    .danger{border-color:rgba(239,68,68,.35)}
    .msg{padding:10px 12px;border-radius:12px;margin-bottom:12px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04)}
  </style>
</head>
<body>
  <div class="top">
    <div><strong>Admin</strong> <span class="muted">/ SDS Techmed</span></div>
    <div class="nav">
      <a href="/sdstechmed/admin/dashboard.php">Dashboard</a>
      <a href="/sdstechmed/admin/categories.php">Categories</a>
      <a href="/sdstechmed/admin/products.php">Products</a>
      <a href="/sdstechmed/admin/news.php">News</a>
      <a href="/sdstechmed/admin/pages.php">Pages</a>
      <a href="/sdstechmed/admin/inquiries.php">Inquiries</a>
      <a href="/sdstechmed/admin/settings.php">Settings</a>
      <a href="/sdstechmed/admin/logout.php">Logout</a>
    </div>
  </div>
  <div class="wrap">
