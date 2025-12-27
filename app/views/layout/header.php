<?php
// Safe fallbacks to avoid warnings
$basePath = $basePath ?? '/sdstechmed/public';
$seo = $seo ?? [];
$settings = $settings ?? [];

// Active menu helper
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
$currentPath = str_replace($basePath, '', $currentPath);
$currentPath = rtrim($currentPath, '/') ?: '/';

function navActive(string $path, string $current): string {
  return $path === $current ? ' is-active' : '';
}

// Site name
$siteName = $settings['company_name'] ?? 'SDS Techmed';

// Best-guess canonical URL (local-safe)
$canonical = ($settings['base_url'] ?? '') ?: ($basePath . $currentPath);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= htmlspecialchars($seo['title'] ?? $siteName) ?></title>
  <meta name="description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">

  <!-- Optional: canonical -->
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">

  <!-- Open Graph -->
  <meta property="og:site_name" content="<?= htmlspecialchars($siteName) ?>">
  <meta property="og:title" content="<?= htmlspecialchars($seo['title'] ?? $siteName) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">
  <meta property="og:type" content="website">

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">

  <link rel="stylesheet" href="<?= $basePath ?>/assets/css/style.css">
</head>

<body>
<header>
  <div class="topbar">
    <span>Email: <?= htmlspecialchars($settings['email'] ?? '') ?></span>
    <span>Phone: <?= htmlspecialchars($settings['phone'] ?? '') ?></span>
  </div>

  <nav class="nav">
    <a class="nav__link<?= navActive('/', $currentPath) ?>" href="<?= $basePath ?>/">Home</a>
    <a class="nav__link<?= navActive('/products', $currentPath) ?>" href="<?= $basePath ?>/products">Products</a>
    <a class="nav__link<?= navActive('/about', $currentPath) ?>" href="<?= $basePath ?>/about">About</a>
    <a class="nav__link<?= navActive('/news', $currentPath) ?>" href="<?= $basePath ?>/news">News</a>
    <a class="nav__link<?= navActive('/contact', $currentPath) ?>" href="<?= $basePath ?>/contact">Contact</a>
    <a class="nav__link<?= navActive('/faq', $currentPath) ?>" href="<?= $basePath ?>/faq">FAQs</a>
    <a class="nav__link<?= navActive('/service-privacy', $currentPath) ?>" href="<?= $basePath ?>/service-privacy">Service & Privacy</a>

    <!-- CTA Button -->
    <a class="nav__cta" href="<?= $basePath ?>/contact#form">Contact Sales</a>
  </nav>
</header>

<main>
