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

$siteName = $settings['company_name'] ?? 'SDS Techmed';

// canonical url (local-safe)
$canonical = ($settings['base_url'] ?? '') ?: ($basePath . $currentPath);

// header values from settings
$topbarEnabled = !empty($settings['topbar_enabled']);
$tagline = trim((string)($settings['header_tagline'] ?? '')) ?: 'Laser • IPL • Body Shaping';
$ctaText = trim((string)($settings['header_cta_text'] ?? '')) ?: 'Contact Sales';
$ctaUrl  = trim((string)($settings['header_cta_url'] ?? '')) ?: ($basePath . '/contact#form');
$headerStyle = ($settings['header_style'] ?? 'v1') === 'v2' ? 'v2' : 'v1';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= htmlspecialchars($seo['title'] ?? $siteName) ?></title>
  <meta name="description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">

  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">

  <meta property="og:site_name" content="<?= htmlspecialchars($siteName) ?>">
  <meta property="og:title" content="<?= htmlspecialchars($seo['title'] ?? $siteName) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">
  <meta property="og:type" content="website">

  <meta name="twitter:card" content="summary_large_image">

  <link rel="stylesheet" href="<?= $basePath ?>/assets/css/style.css">

  <!-- Optional libs you already use -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css">
</head>

<body>

<header class="site-header site-header--<?= htmlspecialchars($headerStyle) ?>">
  <?php if ($topbarEnabled): ?>
    <div class="topbar topbar--new">
      <div class="topbar__left">
        <span class="topbar__chip"><?= htmlspecialchars($tagline) ?></span>
      </div>

      <div class="topbar__right">
        <?php if (!empty($settings['email'])): ?>
          <a class="topbar__item" href="mailto:<?= htmlspecialchars($settings['email']) ?>">✉ <?= htmlspecialchars($settings['email']) ?></a>
        <?php endif; ?>
        <?php if (!empty($settings['phone'])): ?>
          <a class="topbar__item" href="tel:<?= htmlspecialchars($settings['phone']) ?>">☎ <?= htmlspecialchars($settings['phone']) ?></a>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="navwrap">
    <a class="brand" href="<?= $basePath ?>/">
      <?php if (!empty($settings['header_logo'])): ?>
        <img class="brand__logoImg"
             src="<?= $basePath ?>/uploads/brand/<?= htmlspecialchars($settings['header_logo']) ?>"
             alt="<?= htmlspecialchars($siteName) ?>">
      <?php else: ?>
        <div class="brand__logoBox">SDS</div>
      <?php endif; ?>

      <div class="brand__text">
        <div class="brand__name"><?= htmlspecialchars($siteName) ?></div>
        <div class="brand__tag"><?= htmlspecialchars($tagline) ?></div>
      </div>
    </a>

    <button class="navtoggle" type="button" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <nav class="nav nav--new" id="siteNav">
      <a class="nav__link<?= navActive('/', $currentPath) ?>" href="<?= $basePath ?>/">Home</a>
      <a class="nav__link<?= navActive('/products', $currentPath) ?>" href="<?= $basePath ?>/products">Products</a>
      <a class="nav__link<?= navActive('/about', $currentPath) ?>" href="<?= $basePath ?>/about">About</a>
      <a class="nav__link<?= navActive('/news', $currentPath) ?>" href="<?= $basePath ?>/news">News</a>
      <a class="nav__link<?= navActive('/contact', $currentPath) ?>" href="<?= $basePath ?>/contact">Contact</a>
      <a class="nav__link<?= navActive('/faq', $currentPath) ?>" href="<?= $basePath ?>/faq">FAQs</a>
      <a class="nav__link<?= navActive('/service-privacy', $currentPath) ?>" href="<?= $basePath ?>/service-privacy">Service & Privacy</a>

      <a class="nav__cta" href="<?= htmlspecialchars($ctaUrl) ?>"><?= htmlspecialchars($ctaText) ?></a>
    </nav>
  </div>
</header>

<script>
(() => {
  const btn = document.querySelector('.navtoggle');
  const nav = document.getElementById('siteNav');
  if (!btn || !nav) return;

  btn.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    btn.classList.toggle('is-open', open);
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  // close on link click (mobile)
  nav.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      if (!nav.classList.contains('is-open')) return;
      nav.classList.remove('is-open');
      btn.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
    });
  });
})();
</script>

<main>
