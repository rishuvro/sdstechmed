<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($settings['company_name'] ?? 'SDS Techmed') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/sdstechmed/public/assets/css/style.css">
</head>
<body>
<header>
  <div class="topbar">
    <span>Email: <?= htmlspecialchars($settings['email'] ?? '') ?></span>
    <span>Phone: <?= htmlspecialchars($settings['phone'] ?? '') ?></span>
  </div>
  <nav>
    <a href="/sdstechmed/public/">Home</a>
    <a href="/sdstechmed/public/products">Products</a>
    <a href="/sdstechmed/public/about">About</a>
    <a href="/sdstechmed/public/news">News</a>
    <a href="/sdstechmed/public/contact">Contact</a>
    <a href="/sdstechmed/public/service-privacy">Service & Privacy</a>
    <a href="/sdstechmed/public/faq">FAQs</a>
  </nav>
</header>
<main>
